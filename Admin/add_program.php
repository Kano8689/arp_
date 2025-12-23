<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 1) {
  header("Location: ../");
  exit;
}

require '../vendor/autoload.php';
include_once("../DB/pagination.php");   // ✅ added pagination functions

use PhpOffice\PhpSpreadsheet\IOFactory;

// ✅ get page & limit
list($currentPage, $currentLimit) = getPaginationParams();
$redirectUrl = "add_program.php?page=$currentPage&limit=$currentLimit";


// ✅ Filter inputs
$filterName = isset($_GET['filterName']) ? mysqli_real_escape_string($conn, $_GET['filterName']) : '';
$filterSem = isset($_GET['filterSem']) && $_GET['filterSem'] !== '' ? intval($_GET['filterSem']) : '';
$filterDep = isset($_GET['filterDep']) ? mysqli_real_escape_string($conn, $_GET['filterDep']) : '';
$filterGrad = isset($_GET['filterGrad']) ? mysqli_real_escape_string($conn, $_GET['filterGrad']) : '';

// ✅ Build WHERE clause
$whereClause = [];
if ($filterName !== '') {
  $whereClause[] = "$_programNameField LIKE '%$filterName%'";
}
if ($filterSem !== '') {
  $whereClause[] = "$_programSemField = $filterSem";
}
if ($filterDep !== '') {
  $deptId = GetDepartmentNameId($filterDep, false);
  $whereClause[] = "$_programDeptField = '$deptId'";
}
if ($filterGrad !== '') {
  $filterGrad = ($filterGrad == 'UG' ? '1' : ($filterGrad == 'PG' ? '2' : '0'));
  $whereClause[] = "$_graduationTypeField = '$filterGrad'";
}

$whereSQL = '';
if (!empty($whereClause)) {
  $whereSQL = " WHERE " . implode(" AND ", $whereClause);
}

// ✅ Build filter query for URL persistence (pagination, edit, delete)
$filterQuery = '';
if ($filterName !== '')
  $filterQuery .= '&filterName=' . urlencode($filterName);
if ($filterSem !== '')
  $filterQuery .= '&filterSem=' . urlencode($filterSem);
if ($filterDep !== '')
  $filterQuery .= '&filterDep=' . urlencode($filterDep);
if ($filterGrad !== '')
  $filterQuery .= '&filterGrad=' . urlencode($filterGrad);


// Delete Program
if (isset($_GET['del_id'])) {
  $del_id = $_GET['del_id'];
  // Check if any student exists with this program
  $checkSql = "SELECT COUNT(*) AS cnt FROM $_studentTable WHERE $_studentProgram = $del_id";
  $checkRes = mysqli_query($conn, $checkSql);
  $count = mysqli_fetch_assoc($checkRes)['cnt'];

  if ($count > 0) {
    echo "<script>alert('Cannot delete program. It is assigned to $count student(s).');</script>";
  } else {
    $deleteProgram = "DELETE FROM $_programTableName WHERE $_programId = $del_id";
    mysqli_query($conn, $deleteProgram);
  }
  header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
  exit;
}

// Update Program
if (isset($_POST['updateProgram'])) {
  $edt_id = $_POST['edt_id'];
  $programName = $_POST['programName'];
  $programSem = $_POST['programSem'];
  $programDept = $_POST['programDept'];
  $graduationType = $_POST['graduationType'];
  $programDept = GetDepartmentNameId($programDept, false);
  // echo $qqq;
  // exit;

  $graduationType = $graduationType == "UG" ? 1 : ($graduationType == "PG" ? 2 : 0);

  $where = "$_programNameField = '$programName' AND $_programSemField = '$programSem' AND $_graduationTypeField = '$_graduationType' AND $_programDeptField = '$programDept'";
  if (isUniqueOrNot($conn, $_programTableName, $where)) {
    $updateQuery = "UPDATE $_programTableName
                    SET $_programNameField='$programName',$_graduationTypeField='$graduationType', $_programSemField='$programSem', $_programDeptField='$programDept'
                    WHERE $_programId=$edt_id";
    mysqli_query($conn, $updateQuery);

    $page = isset($_POST['page']) ? intval($_POST['page']) : $currentPage;
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : $currentLimit;

    header("Location: add_program.php?page=$page&limit=$limit$filterQuery"); // ✅ use POST filters
    exit;
  } else {
    $uniqError = "Record is not updated due to already exists..";
  }
}

// Single Record Insertion
if (isset($_POST['addProgram'])) {
  $programName = $_POST['programName'];
  $programSem = $_POST['programSem'];
  $programDept = $_POST['programDept'];
  $graduationType = $_POST['graduationType'];
  $programDept = GetDepartmentNameId($programDept, false);

  $graduationType = $graduationType == "UG" ? 1 : ($graduationType == "PG" ? 2 : 0);

  $where = "$_programNameField = '$programName' AND $_programSemField = '$programSem' AND $_graduationTypeField = '$graduationType' AND $_programDeptField = '$programDept'";
  if (isUniqueOrNot($conn, $_programTableName, $where)) {
    $insertProgram = "INSERT INTO $_programTableName ($_programNameField, $_programSemField, $_graduationTypeField, $_programDeptField) VALUES ('$programName', '$programSem', '$graduationType', '$programDept')";
    // echo "$insertProgram";
    // exit;
    mysqli_query($conn, $insertProgram);
    header("Location: $redirectUrl$filterQuery");   // ✅ redirect with pagination
    exit;
  } else {
    $uniqError = "Record is not insetred due to already exists..";
  }
}


if (isset($_POST['addFile'])) {
  // session_start(); // Start session at the top
  $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
  $fields = [$_programNameField, $_programSemField, $_programDeptField, $_graduationTypeField];

  // Summary Counters
  $total = 0;
  $inserted = 0;
  $updated = 0;
  $failed = 0;

  // 🔧 Insert or Update helper
  function insertOrUpdateProgram($conn, $table, $fields, $values, $uniqueField)
  {
    // echo "";
    // exit;
    $programName = $values[0];
    $programSem = $values[1];
    $programDept = $values[2];
    $graduationType = $values[3];
    // print("<pre>");
    // print_r($fields);
    // echo "";
    // exit;

    // Check if record exists
    $checkSql = "SELECT * FROM $table WHERE $uniqueField = '$programName' LIMIT 1";
    $result = mysqli_query($conn, $checkSql);

    if ($result && mysqli_num_rows($result) > 0) {
      // Update
      $updateSql = "UPDATE $table
                          SET {$fields[1]}='$programSem', {$fields[3]}='$graduationType', {$fields[2]}='$programDept'
                          WHERE $uniqueField='$programName'";
      if (mysqli_query($conn, $updateSql)) {
        return 'update';
      }
    } else {
      // Insert
      $fieldList = implode(",", $fields);
      $valueList = "'" . implode("','", $values) . "'";
      $insertSql = "INSERT INTO $table ($fieldList) VALUES ($valueList)";
      if (mysqli_query($conn, $insertSql)) {
        return 'insert';
      }
    }
    return 'fail';
  }

  // -------- CSV / Excel / SQL Parser --------
  $rows = [];

  if ($ext === 'csv') {
    if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== false) {
      $rowIndex = 0;
      while (($data = fgetcsv($handle)) !== false) {
        $rowIndex++;
        if ($rowIndex == 1)
          continue; // skip header
        $rows[] = $data;
      }
      fclose($handle);
    }
  } elseif (in_array($ext, ['xlsx', 'xls'])) {
    // echo "done";
    $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
    $sheetData = $spreadsheet->getActiveSheet()->toArray();
    foreach ($sheetData as $index => $row) {
      if ($index == 0)
        continue; // skip header  
      $rows[] = $row;
    }
  } elseif ($ext === 'sql') {
    $sqlContent = file_get_contents($_FILES['file']['tmp_name']);
    if ($sqlContent) {
      $queries = explode(";", $sqlContent);
      foreach ($queries as $query) {
        $trimmed = trim($query);
        if ($trimmed) {
          if (mysqli_query($conn, $trimmed))
            $inserted++;
          else
            $failed++;
        }
      }
    }
  }

  // -------- Process Rows --------
  foreach ($rows as $row) {
    $total++;
    $programName = trim($row[1] ?? '');
    $programSem = trim($row[2] ?? '');
    $programDept = trim($row[3] ?? '');
    $graduationType = trim($row[4] ?? '');
    
    // Skip if any field is empty
    if ($programName === '' || $programSem === '' || $programDept === '' || $graduationType === '') {
      $failed++;
      continue;
    }
    
    // Convert department name to ID
    $programDeptId = GetDepartmentNameId($programDept, false);
    $graduationType = strtoupper(trim($graduationType, " ")) == "UG" ? 1 : (strtoupper(trim($graduationType, " ")) == "PG" ? 2 : 0);

    if (!$programDeptId) {
      // Department not found, mark as failed
      $failed++;
      continue;
    }

    // Escape strings for SQL
    $programName = mysqli_real_escape_string($conn, $programName);
    $programSem = mysqli_real_escape_string($conn, $programSem);
    $programDeptId = mysqli_real_escape_string($conn, $programDeptId);
    $graduationType = mysqli_real_escape_string($conn, $graduationType);

    // Insert or Update
    $result = insertOrUpdateProgram(
      $conn,
      $_programTableName,
      $fields,
      [$programName, $programSem, $programDeptId, $graduationType],
      $_programNameField
    );

    switch ($result) {
      case 'insert':
        $inserted++;
        break;
      case 'update':
        $updated++;
        break;
      case 'fail':
        $failed++;
        break;
    }
  }

  // ✅ Store summary in session
  $_SESSION['upload_summary'] = [
    'total' => $total,
    'inserted' => $inserted,
    'updated' => $updated,
    'failed' => $failed,
  ];

  $display = "flex";
  // $_SESSION['display'] = "flex";
  // Redirect after processing

  header("Location: $redirectUrl$filterQuery");
  exit;
}




include_once("../header.php");


list($currentPage, $currentLimit) = getPaginationParams();

// ✅ Force integer values
$currentPage = (int) $currentPage;
$currentLimit = (int) $currentLimit;

$offset = ($currentPage - 1) * $currentLimit;

// ✅ Final query with filter, order, and pagination
$selectPrograms = "SELECT * FROM $_programTableName $whereSQL ORDER BY created_at DESC LIMIT $offset, $currentLimit";
$response = mysqli_query($conn, $selectPrograms);

$selectPrograms1 = "SELECT * FROM $_programTableName";
$response1 = mysqli_query($conn, $selectPrograms1);
$totalRows = mysqli_num_rows($response1);


// Select Department Name
function GetDepartmentNameId($_value, $_isGetName = true)
{
  global $conn, $_departmentTable, $_departmentName, $_departmentId;
  $field = $_isGetName ? $_departmentId : $_departmentName;
  $select = "SELECT * FROM $_departmentTable WHERE $field = '$_value'";
  $res = mysqli_query($conn, $select);
  $row = mysqli_fetch_assoc($res);

  // echo "$select";
  // exit;

  if ($_isGetName)
    return $row[$_departmentName] ?? null;
  else
    return $row[$_departmentId] ?? null;
}


$deptRes = mysqli_query($conn, "SELECT * FROM $_departmentTable");

$display = "none";

?>

<!-- Main Content -->
<div class="container">

  <!-- Title + Breadcrumb -->
  <div class="page-header">
    <h3>Program</h3>
    <div class="breadcrumb-box">
      <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Admin</span></a>
      <span class="sep">›</span>
      <span class="crumb-link crumb-disabled">Program</span>
    </div>
  </div>

  <!-- Upload Summary Box -->
  <div id="uploadSummary" class="summary-box" style="display:<?php if (isset($display)) echo $display;
                                                              else echo "none"; ?>">
    <div><span class="label">Total:</span> <span id="sumTotal"><?php if (isset($total)) echo $total;
                                                                else echo 0; ?></span></div>
    <div style="color:green;"><span class="label">✅ Inserted:</span> <span id="sumInserted"><?php if (isset($inserted)) echo $inserted;
                                                                                            else echo 0; ?></span></div>
    <div style="color:orange;"><span class="label">🟡 Updated:</span> <span id="sumUpdated"><?php if (isset($updated)) echo $updated;
                                                                                            else echo 0; ?></span></div>
    <div style="color:red; display:flex; align-items:center; gap:5px;">
      <span class="label">❌ Failed:</span> <span id="sumFailed"><?php if (isset($failed)) echo $failed;
                                                                else echo 0; ?></span>
      <!-- <button class="btn btn-light" style="padding:2px 6px; font-size:12px;" onclick="viewFailedList()">View</button> -->
    </div>
  </div>

  <?php if (isset($uniqError)) { ?>
    <div class="card">
      <?php echo $uniqError; ?>
    </div>
  <?php } ?>


  <!-- Action Buttons -->
  <div class="card">
    <div class="actions-row">
      <button class="btn" type="button" onclick="toggleFilter()">🔍 Filter</button>
      <button class="btn" onclick="openModal('programModal')">+ Add Program</button>
      <button class="btn" onclick="openModal('csvModal')">+ Add CSV File</button>
    </div>

    <!-- Filter Box -->
    <div id="filterBox"
      style="display:<?php echo ($filterName || $filterSem || $filterDep || $filterGrad) ? 'block' : 'none'; ?>; margin-top:10px; background:#f8f8f8; padding:15px; border-radius:8px;">
      <form method="GET">
        <div style="display:flex; flex-wrap:wrap; gap:15px;">

          <div style="display:flex; flex-direction:column; flex:1; min-width:200px;">
            <label style="margin-bottom:5px; font-weight:bold;">Program Name :</label>
            <input type="text" name="filterName" value="<?php echo htmlspecialchars($filterName); ?>"
              placeholder="Program Name" tyle="padding:10px;">
          </div>

          <div style="display:flex; flex-direction:column; flex:1; min-width:200px;">
            <label style="margin-bottom:5px; font-weight:bold;">Semester :</label>
            <input type="number" name="filterSem" value="<?php echo htmlspecialchars($filterSem); ?>"
              placeholder="Semester" style="padding:10px;">
          </div>

          <div style="display:flex; flex-direction:column; flex:1; min-width:200px;">
            <label style="margin-bottom:5px; font-weight:bold;">Department :</label>
            <select name="filterDep" style="padding:10px;">
              <option value="">-- Select Department --</option>
              <?php
              // Generate options dynamically
              $deptQuery = "SELECT * FROM $_departmentTable";
              $deptOptions = mysqli_query($conn, $deptQuery);
              while ($dRow = mysqli_fetch_assoc($deptOptions)) {
                $deptName = $dRow[$_departmentName];
                $selected = ($filterDep == $deptName) ? 'selected' : '';
                echo "<option value='$deptName' $selected>$deptName</option>";
              }
              ?>
            </select>
          </div>

          <div style="display:flex; flex-direction:column; flex:1; min-width:200px;">
            <label style="margin-bottom:5px; font-weight:bold;">Graduation :</label>
            <select name="filterGrad" style="padding:10px;">
              <option value="">-- Select Graduation --</option>
              <option value="UG">UG</option>
              <option value="PG">PG</option>
            </select>
          </div>
        </div>

        <div style="margin-top:15px;">
          <button class="btn btn-primary" type="submit">Apply Filter</button>
          <button type="button" class="btn btn-light" onclick="resetFilter()">Cancel</button>
        </div>
      </form>
    </div>

    <!-- Responsive Table Wrapper -->
    <div class="table-responsive" style="margin-top: 20px;">
      <table class="table">
        <thead>
          <tr>
            <th style="text-align: center;">#</th>
            <th style="text-align: center;">Program Name</th>
            <th style="text-align: center;">Semester</th>
            <th style="text-align: center;">Graduation Type</th>
            <th style="text-align: center;">Department</th>
            <th style="text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody id="programTable">
          <?php
          if ($totalRows > 0) {
            // $num = $totalRows - $offset;
            $num = 1;
            while ($row = mysqli_fetch_assoc($response)) { ?>
              <tr style="cursor: pointer;" ondblclick="editProgram(
                            '<?php echo $row[$_programId]; ?>',
                            '<?php echo $row[$_programNameField]; ?>',
                            '<?php echo $row[$_programSemField]; ?>',
                            '<?php echo GetDepartmentNameId($row[$_programDeptField]); ?>',
                            '<?php echo ($row[$_graduationTypeField] == '1' ? 'UG' : ($row[$_graduationTypeField] == 2 ? 'PG' : 'None')); ?>',
                            '<?php echo $currentPage; ?>',
                            '<?php echo $currentLimit; ?>',
                            '<?php echo htmlspecialchars($filterName); ?>',
                            '<?php echo htmlspecialchars($filterSem); ?>',
                            '<?php echo htmlspecialchars($filterDep); ?>',
                            '<?php echo ($row[$_graduationTypeField] == '1' ? 'UG' : ($row[$_graduationTypeField] == 2 ? 'PG' : 'None')); ?>'
                        )">
                <td style="text-align: center; padding: 5px 0;"><?php echo $num++; ?></td>
                <td style="padding: 5px 0;"><?php echo $row[$_programNameField]; ?></td>
                <td style="text-align: center; padding: 5px 0;"><?php echo $row[$_programSemField]; ?></td>
                <td style="text-align: center; padding: 5px 0;"><?php echo ($row[$_graduationTypeField] == '1' ? 'UG' : ($row[$_graduationTypeField] == 2 ? 'PG' : 'None')); ?></td>
                <td style="text-align: center; padding: 5px 0;"><?php echo GetDepartmentNameId($row[$_programDeptField]); ?>
                </td>
                <td style="text-align: center; padding: 5px 0;">
                  <form method="POST">
                    <!-- Update button -->
                    <a onclick="editProgram(
                            '<?php echo $row[$_programId]; ?>',
                            '<?php echo $row[$_programNameField]; ?>',
                            '<?php echo $row[$_programSemField]; ?>',
                            '<?php echo GetDepartmentNameId($row[$_programDeptField]); ?>',
                            '<?php echo ($row[$_graduationTypeField] == '1' ? 'UG' : ($row[$_graduationTypeField] == 2 ? 'PG' : 'None')); ?>',
                            '<?php echo $currentPage; ?>',
                            '<?php echo $currentLimit; ?>',
                            '<?php echo htmlspecialchars($filterName); ?>',
                            '<?php echo htmlspecialchars($filterSem); ?>',
                            '<?php echo htmlspecialchars($filterDep); ?>',
                            '<?php echo ($row[$_graduationTypeField] == '1' ? 'UG' : ($row[$_graduationTypeField] == 2 ? 'PG' : 'None')); ?>'
                        )" class="btn btn-light" style="display:inline-block; margin-right:5px;">
                      ✏️ Edit
                    </a>
                    <!-- Delete button -->
                    <a href="add_program.php?del_id=<?php echo $row[$_programId]; ?>&page=<?php echo $currentPage; ?>&limit=<?php echo $currentLimit; ?><?php echo $filterQuery; ?>"
                      class="btn btn-danger" style="text-decoration: none;">
                      🗑 Delete
                    </a>
                  </form>
                </td>
              </tr>
            <?php }
          } else { ?>
            <tr>
              <td colspan="5" style="text-align:center; font-weight:bold; font-size:20px; color:#333; padding:100px;">
                No Any Record Found!
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- ✅ pagination UI -->
    <?php
    $totalRecords = paginationUI($conn, $_programTableName, $currentPage, $currentLimit, $whereSQL, $filterQuery);

    // Show record count under table
    if ($totalRecords > 0) {
      $startRecord = ($currentPage - 1) * $currentLimit + 1;
      $endRecord = min($startRecord + $currentLimit - 1, $totalRecords);
      echo "<div style='margin-top:10px; font-weight:bold;'>";
      echo "Showing $startRecord to $endRecord of $totalRecords records";
      echo "</div>";
    }
    ?>


  </div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="programModal">
  <div class="modal">
    <span class="close-btn" onclick="closeModal('programModal')">&times;</span>
    <h3 id="modalTitle">Add Program</h3>
    <hr>
    <form method="POST" id="programForm">
      <input type="hidden" name="page" value="<?php echo $currentPage; ?>">
      <input type="hidden" name="limit" value="<?php echo $currentLimit; ?>"><br>

      <label class="fw-bold">Program Name :</label>
      <input type="text" name="programName" id="courseName" placeholder="Enter Course Name" required>

      <label class="fw-bold">Semester :</label>
      <input type="number" name="programSem" id="programSem" placeholder="Enter Semester" required>

      <label class="fw-bold" for="graduationType">Graduation Type :</label>
      <select id="graduationType" name="graduationType" required>
        <option value="">-- Select Graduation Type --</option>
        <option value="UG">UG</option>
        <option value="PG">PG</option>
      </select>

      <label class="fw-bold" for="programDept">Department :</label>
      <select id="programDept" name="programDept" required>
        <option value="">-- Select Department --</option>
        <?php mysqli_data_seek($deptRes, 0);
        while ($row = mysqli_fetch_assoc($deptRes)) { ?>
          <option value="<?php echo $row[$_departmentName]; ?>"><?php echo $row[$_departmentName]; ?></option>
        <?php } ?>
      </select>
      <div class="modal-actions">
        <button class="btn" name="addProgram" id="saveBtn">Add Program</button>
        <button class="btn btn-light" onclick="closeModal('programModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editprogramModal">
  <div class="modal">
    <span class="close-btn" onclick="closeModal('editprogramModal')">&times;</span>
    <h3 id="modalTitle">Edit Program</h3>
    <hr>
    <form method="POST" id="editProgramForm" action="add_program.php">
      <input type="hidden" name="edt_id" id="edit_id">
      <input type="hidden" name="page" id="edit_page" value="<?php echo $currentPage; ?>">
      <input type="hidden" name="limit" id="edit_limit" value="<?php echo $currentLimit; ?>"><br>

      <!-- Add these hidden inputs -->
      <input type="hidden" name="filterName" id="edit_filterName">
      <input type="hidden" name="filterSem" id="edit_filterSem">
      <input type="hidden" name="filterDep" id="edit_filterDep">
      <input type="hidden" name="filterGrad" id="edit_filterGrad">

      <label class="fw-bold">Program Name :</label>
      <input type="text" name="programName" id="edit_programName" placeholder="Enter Course Name" required>

      <label class="fw-bold">Semester :</label>
      <input type="number" name="programSem" id="edit_programSem" placeholder="Enter Semester" required>

      <label class="fw-bold" for="graduationType">Graduation Type :</label>
      <select id="edit_graduationType" name="graduationType" required>
        <option value="">-- Select Graduation Type --</option>
        <option value="UG">UG</option>
        <option value="PG">PG</option>
      </select>

      <label class="fw-bold" for="edit_programDept">Department :</label>
      <select id="edit_programDept" name="programDept" required>
        <option value="">-- Select Department --</option>
        <?php mysqli_data_seek($deptRes, 0);
        while ($row = mysqli_fetch_assoc($deptRes)) { ?>
          <option value="<?php echo $row[$_departmentName]; ?>"><?php echo $row[$_departmentName  ]; ?></option>
        <?php } ?>
      </select>

      <div class="modal-actions">
        <button class="btn" name="updateProgram">Update Program</button>
        <button type="button" class="btn btn-light" onclick="closeModal('editprogramModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- CSV Modal -->
<div class="modal-overlay" id="csvModal">
  <div class="modal">
    <span class="close-btn" onclick="closeModal('csvModal')">&times;</span>
    <h3>Upload CSV File</h3>
    <form method="post" enctype="multipart/form-data">

      <input type="file" name="file" accept=".csv, .xlsx, .sql, .xls" id="csvFile">
      <div class="modal-actions">
        <button class="btn" name="addFile">Upload</button>
        <button class="btn btn-light" onclick="closeModal('csvModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  function toggleFilter() {
    var box = document.getElementById("filterBox");
    box.style.display = (box.style.display === "none" || box.style.display === "") ? "block" : "none";
  }

  function resetFilter() {
    window.location.href = "add_program.php";
  }
</script>
<?php include_once("../footer.php"); ?>