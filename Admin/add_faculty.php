<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 1) {
  header("Location: ../");
  exit;
}

require '../vendor/autoload.php';
include_once("../DB/pagination.php");   // ✅ added pagination functions
use PhpOffice\PhpSpreadsheet\IOFactory;

// login_table fields variables



list($currentPage, $currentLimit) = getPaginationParams();
$redirectUrl = "add_faculty.php?page=$currentPage&limit=$currentLimit";

// === FILTER INPUTS ===
$filterDept = isset($_GET['filterDept_faculty']) ? mysqli_real_escape_string($conn, $_GET['filterDept_faculty']) : '';
$filterCode = isset($_GET['filterCode_faculty']) ? mysqli_real_escape_string($conn, $_GET['filterCode_faculty']) : '';
$filterName = isset($_GET['filterName_faculty']) ? mysqli_real_escape_string($conn, $_GET['filterName_faculty']) : '';
$filterEmail = isset($_GET['filterEmail_faculty']) ? mysqli_real_escape_string($conn, $_GET['filterEmail_faculty']) : '';

$whereClause = [];

// Apply filters
if ($filterDept !== '') {
  $deptId = GetDepartmentNameId($filterDept, false);
  $whereClause[] = "$_facultyDepartment = '$deptId'";
}
if ($filterCode !== '') {
  $whereClause[] = "$_facultyCode LIKE '%$filterCode%'";
}
if ($filterName !== '') {
  $whereClause[] = "$_facultyName LIKE '%$filterName%'";
}
if ($filterEmail !== '') {
  $whereClause[] = "$_facultyEmail LIKE '%$filterEmail%'";
}

$whereSQL = '';
if (!empty($whereClause)) {
  $whereSQL = " WHERE " . implode(" AND ", $whereClause);
}

// Build filter query for persistent URLs
$filterQuery = '';
if ($filterDept !== '')
  $filterQuery .= '&filterDept_faculty=' . urlencode($filterDept);
if ($filterCode !== '')
  $filterQuery .= '&filterCode_faculty=' . urlencode($filterCode);
if ($filterName !== '')
  $filterQuery .= '&filterName_faculty=' . urlencode($filterName);
if ($filterEmail !== '')
  $filterQuery .= '&filterEmail_faculty=' . urlencode($filterEmail);


// Delete Faculty
if (isset($_GET['del_id'])) {
  $del_id = $_GET['del_id'];

  $select = "SELECT $_facultyCode FROM $_facultyTable WHERE $_facultyId=$del_id";
  $result = mysqli_query($conn, $select);
  $row = mysqli_fetch_assoc($result);
  $facultyCode = $row[$_facultyCode];

  $deleteFaculty = "DELETE FROM $_facultyTable WHERE $_facultyId = $del_id";
  mysqli_query($conn, $deleteFaculty);

  $un = $facultyCode . $defaultLoginExtension;
  LoginTableDelete($conn, $_loginTable, $_loginUsername, $un);

  header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
  exit;
}

// Update Faculty
if (isset($_POST['editFaculty'])) {
  $facultyId = $_POST['edt_id'];
  $facultyCode = $_POST['f_id1'];
  $facultyName = $_POST['f_name1'];
  $facultyDept = $_POST['f_dept1'];
  $facultyEmail = $_POST['f_email1'];
  $facultyJoinDate = $_POST['f_joinDate1'];

  $facultyDept = GetDepartmentNameId($facultyDept, false);

  $where = "$_facultyName = '$facultyName' AND $_facultyEmail='$facultyEmail' AND $_facultyDepartment = '$facultyDept' AND $_facultyJoinDate = '$facultyJoinDate'";
  if (isUniqueOrNot($conn, $_facultyTable, $where)) {
    $UpdateFaculty = "UPDATE $_facultyTable  SET $_facultyCode = '$facultyCode', $_facultyName = '$facultyName', $_facultyDepartment = '$facultyDept', $_facultyEmail = '$facultyEmail', $_facultyJoinDate = '$facultyJoinDate' WHERE $_facultyId = '$facultyId'";
    mysqli_query($conn, $UpdateFaculty);

    $page = isset($_POST['page']) ? intval($_POST['page']) : $currentPage;
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : $currentLimit;

    header("Location: add_faculty.php?page=$page&limit=$limit$filterQuery"); // ✅ use POST filters
    exit;
  } else {
    $uniqError = "Record is not updated due to faculty enrollment number already exists..";
  }
}

// Add Sinlge Record of Faculty
if (isset($_POST['addFaculty'])) {
  $facultyCode = $_POST['f_id'];
  $facultyName = $_POST['f_name'];
  $facultyDept = $_POST['f_dept'];
  $facultyEmail = $_POST['f_email'];
  $facultyJoinDate = $_POST['f_joinDate'];
  $facultyPassword = $_POST['f_password'];
  $facultyConfirmPassword = $_POST['f_cpassword'];

  if ($facultyPassword == $facultyConfirmPassword) {
    $encPass = Encrypt($facultyPassword);

    $facultyDept = GetDepartmentNameId($facultyDept, false);
    // echo $facultyDept;
    // exit;

    $where = "$_facultyCode='$facultyCode'";
    if (isUniqueOrNot($conn, $_facultyTable, $where)) {
      $AddFaculty = "INSERT INTO $_facultyTable ($_facultyCode,$_facultyName,$_facultyDepartment,$_facultyEmail,$_facultyJoinDate) VALUES ('$facultyCode','$facultyName','$facultyDept','$facultyEmail','$facultyJoinDate')";
      mysqli_query($conn, $AddFaculty);

      $un = $facultyCode . $defaultLoginExtension;
      LoginTableInsert($conn, $_loginTable, [$_loginUsername, $_loginPassword, $_loginUserType], [$un, $encPass, 3]);
      header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
      exit;
    } else {
      $uniqError = "Record is not insetred due to faculty enrollment number is already exists..";
      // echo "<br>";
      // echo "$uniqError";
      // echo "<br>";
      // echo "$where";
      // echo "<br>";
      // exit;
    }
  } else {
    $error = "Password and confirm PASSword doesnot match!";
  }
}

// Add Faculties Records File
if (isset($_POST['addFile'])) {
  $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

  switch ($ext) {
    case 'csv':
      $handle = fopen($_FILES['file']['tmp_name'], "r");

      while ($row = fgetcsv($handle)) {
        GetAndSaveDataFromFile($row);
      }

      break;

    case 'xlsx':
    case 'xls':
      $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
      $sheetData = $spreadsheet->getActiveSheet()->toArray();

      foreach ($sheetData as $index => $row) {
        GetAndSaveDataFromFile($row);
      }

      break;

    case 'sql':
      $sqlContent = file_get_contents($_FILES['file']['tmp_name']);
      if ($sqlContent) {
        $queries = explode(";", $sqlContent);
        foreach ($queries as $query) {
          $trimmedQuery = trim($query);
          if ($trimmedQuery) {
            mysqli_query($conn, $trimmedQuery);
          }
        }
      }
      break;
  }
  header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
  exit;
}


function GetAndSaveDataFromFile($ary)
{
  global $conn, $_facultyTable, $defaultLoginExtension;
  global $_facultyCode, $_facultyName, $_facultyDepartment, $_facultyEmail, $_facultyJoinDate;
  global $_loginTable, $_loginUsername, $_loginPassword, $_loginUserType;

  $fields = [$_facultyCode, $_facultyName, $_facultyDepartment, $_facultyEmail, $_facultyJoinDate];

  $facultyCode = mysqli_real_escape_string($conn, $ary[1] ?? '');
  $facultyName = mysqli_real_escape_string($conn, $ary[2] ?? '');
  $facultyDepartment = mysqli_real_escape_string($conn, $ary[3] ?? '');
  $facultyEmail = mysqli_real_escape_string($conn, $ary[4] ?? '');
  $facultyJoinDate = mysqli_real_escape_string($conn, $ary[5] ?? '');
  $pacultyPassowrd = mysqli_real_escape_string($conn, $ary[6] ?? '');

  if ($facultyJoinDate) {
    $facultyJoinDate = date("Y-m-d", strtotime($facultyJoinDate));
  }

  $facultyDepartment = GetDepartmentNameId($facultyDepartment, false);
  $encPass = Encrypt($pacultyPassowrd);

  $data = [$facultyCode, $facultyName, $facultyDepartment, $facultyEmail, $facultyJoinDate, $encPass];
  $whereData = $_facultyCode . " = '" . $facultyCode . "' and " . $_facultyName . " = '" . $facultyName . "'";

  $selectTemp = "SELECT * FROM $_facultyTable WHERE $_facultyCode='$facultyCode'";
  $resultTemp = mysqli_query($conn, $selectTemp);
  if (mysqli_num_rows($resultTemp) > 0) {
    $row = mysqli_fetch_array($resultTemp);
    $enNoFld = $row[$_facultyCode];

    $un = $facultyCode . $defaultLoginExtension;
    LoginTableUpdate($conn, $_loginTable, [$_loginUsername, $_loginPassword, $_loginUserType], [$un, $encPass, 3]);
    
  } else {
    $un = $facultyCode . $defaultLoginExtension;
    LoginTableInsert($conn, $_loginTable, [$_loginUsername, $_loginPassword, $_loginUserType], [$un, $encPass, 3]);
  }

  FieldStringSetter($conn, $_facultyTable, $fields, $data, $whereData);
}


// Select Department Name
function GetDepartmentNameId($_value, $_isGetName = true)
{
  global $conn, $_departmentTable, $_departmentName, $_departmentId;
  $field = $_isGetName ? $_departmentId : $_departmentName;
  $select = "SELECT * FROM $_departmentTable WHERE $field = '$_value'";
  $res = mysqli_query($conn, $select);
  $row = mysqli_fetch_assoc($res);

  // echo $select;
  // exit;

  if ($_isGetName)
    return $row[$_departmentName] ?? null;
  else
    return $row[$_departmentId] ?? null;
}

$deptRes = mysqli_query($conn, "SELECT * FROM $_departmentTable");

include_once("../header.php");

list($currentPage, $currentLimit) = getPaginationParams();

// Calculate offset
$offset = ($currentPage - 1) * $currentLimit;

// Fetch faculty for current page
$selectFaculties = "SELECT * FROM $_facultyTable $whereSQL ORDER BY $_facultyId DESC LIMIT $offset, $currentLimit";
$response = mysqli_query($conn, $selectFaculties);

$selectFaculties1 = "SELECT * FROM $_facultyTable";
$response1 = mysqli_query($conn, $selectFaculties1);
$totalRows = mysqli_num_rows($response1);


?>

<!-- Main Content -->
<div class="container">

  <!-- Title + Breadcrumb -->
  <div class="page-header">
    <h3>Faculty</h3>
    <div class="breadcrumb-box">
      <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Admin</span></a>
      <span class="sep">›</span>
      <span class="crumb-link crumb-disabled">Faculty</span>
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
      <button class="btn" onclick="open3Modal('facultyModal')">+ Add Faculty</button>
      <button class="btn" onclick="open3Modal('uploadModal')">+ Add File (csv/xlsx/sql)</button>
    </div>


    <div id="filterBox"
      style="display:<?php echo ($filterDept || $filterCode || $filterName || $filterEmail) ? 'block' : 'none'; ?>; margin-top:10px; background:#f8f8f8; padding:15px; border-radius:8px;">
      <form method="GET">
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
          <select name="filterDept_faculty" style="flex:1; padding:10px;">
            <option value="">-- Select Department --</option>
            <?php
            mysqli_data_seek($deptRes, 0);
            while ($row = mysqli_fetch_assoc($deptRes)) {
              $selected = ($filterDept == $row[$_departmentName]) ? 'selected' : '';
              echo "<option value='{$row[$_departmentName]}' $selected>{$row[$_departmentName]}</option>";
            }
            ?>
          </select>

          <input type="text" name="filterCode_faculty" value="<?php echo htmlspecialchars($filterCode); ?>"
            placeholder="Faculty ID" style="flex:1; padding:10px;">
          <input type="text" name="filterName_faculty" value="<?php echo htmlspecialchars($filterName); ?>"
            placeholder="Faculty Name" style="flex:1; padding:10px;">
          <input type="text" name="filterEmail_faculty" value="<?php echo htmlspecialchars($filterEmail); ?>"
            placeholder="Email" style="flex:1; padding:10px;">
        </div>
        <div style="margin-top:10px;">
          <button class="btn" type="submit">Apply Filter</button>
          <button type="button" class="btn btn-light" onclick="resetFilter()">Cancel</button>
        </div>
      </form>
    </div>

    <!-- Responsive Table Wrapper -->
    <div class="table-responsive" style="margin-top: 20px;">
      <table class="table" id="facultyList">
        <thead>
          <tr>
            <th style="text-align: center;">#</th>
            <th style="text-align: center;">Faculty ID</th>
            <th style="text-align: center;">Name</th>
            <th style="text-align: center;">Department</th>
            <th style="text-align: center;">Email</th>
            <th style="text-align: center;">Joining Date</th>
            <th style="text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody id="facultyTable">
          <?php
          // $num = $totalRows - $offset;
          $num = 1;
          while ($row = mysqli_fetch_assoc($response)) { ?>
            <tr ondblclick="editFaculty('<?php echo $row[$_facultyId]; ?>',
                                          '<?php echo $row[$_facultyCode]; ?>',
                                          '<?php echo $row[$_facultyName]; ?>',
                                          '<?php echo GetDepartmentNameId($row[$_facultyDepartment]); ?>',
                                          '<?php echo $row[$_facultyEmail]; ?>',
                                          '<?php echo $row[$_facultyJoinDate]; ?>',
                                          '<?php echo $currentPage; ?>',
                                          '<?php echo $currentLimit; ?>',
                                          '<?php echo htmlspecialchars($filterName); ?>',
                                          '<?php echo htmlspecialchars($filterCode); ?>',
                                          '<?php echo htmlspecialchars($filterDept); ?>',
                                          '<?php echo htmlspecialchars($filterEmail); ?>'
                                          )">
              <td style="text-align: center; padding: 5px 0;"><?php echo $num++ ?></td>
              <td style="text-align: center; padding: 5px 0;"><?php echo $row[$_facultyCode]; ?></td>
              <td style="text-align: center; padding: 5px 0;"><?php echo $row[$_facultyName]; ?></td>
              <td style="text-align: center; padding: 5px 0;">
                <?php echo GetDepartmentNameId($row[$_facultyDepartment]); ?>
              </td>
              <td style="text-align: center; padding: 5px 0;"><?php echo $row[$_facultyEmail]; ?></td>
              <td style="text-align: center; padding: 5px 0;">
                <?php echo date("d-m-Y", strtotime($row[$_facultyJoinDate])); ?>
              </td>
              <td style="text-align: center; padding: 5px 0;">
                <form method="POST">
                  <!-- Update button -->
                  <a onclick="editFaculty('<?php echo $row[$_facultyId]; ?>',
                                          '<?php echo $row[$_facultyCode]; ?>',
                                          '<?php echo $row[$_facultyName]; ?>',
                                          '<?php echo GetDepartmentNameId($row[$_facultyDepartment]); ?>',
                                          '<?php echo $row[$_facultyEmail]; ?>',
                                          '<?php echo $row[$_facultyJoinDate]; ?>',
                                          '<?php echo $currentPage; ?>',
                                          '<?php echo $currentLimit; ?>',
                                          '<?php echo htmlspecialchars($filterName); ?>',
                                          '<?php echo htmlspecialchars($filterCode); ?>',
                                          '<?php echo htmlspecialchars($filterDept); ?>',
                                          '<?php echo htmlspecialchars($filterEmail); ?>'
                                          )" class="btn btn-light" style="display:inline-block; margin-right:5px;">
                    ✏️ Edit
                  </a>

                  <!-- Delete button -->
                  <a href="add_faculty.php?del_id=<?php echo $row[$_facultyId]; ?>&page=<?php echo $currentPage; ?>&limit=<?php echo $currentLimit; ?><?php echo $filterQuery; ?>"
                    class="btn btn-danger" style="text-decoration: none;">
                    🗑 Delete
                  </a>
                </form>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php
    $totalRecords = paginationUI($conn, $_facultyTable, $currentPage, $currentLimit, $whereSQL, $filterQuery);
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
<div class="modal-overlay" id="facultyModal">
  <div class="modal">
    <span class="close-btn" onclick="close3Modal('facultyModal')">&times;</span>
    <h3 id="facultyModalTitle">Add Faculty</h3>
    <hr><br>
    <form method="POST">
      <label class="fw-bold">Faculty ID :</label>
      <input type="text" name="f_id" id="fId" placeholder="Faculty ID">

      <label class="fw-bold">Name :</label>
      <input type="text" name="f_name" id="fName" placeholder="Full Name">

      <label class="fw-bold" for="programDept">Department :</label>
      <select id="programDept" name="f_dept" required>
        <option value="">-- Select Department --</option>
        <?php mysqli_data_seek($deptRes, 0);
        while ($row = mysqli_fetch_assoc($deptRes)) { ?>
          <option value="<?php echo $row[$_departmentName]; ?>"><?php echo $row[$_departmentName]; ?></option>
        <?php } ?>
      </select>

      <label class="fw-bold">Email :</label>
      <input type="text" name="f_email" id="fEmail" placeholder="Email">

      <label class="fw-bold">Join Date :</label>
      <input type="date" name="f_joinDate" id="fJoinDate" placeholder="Join Date"><br></br>

      <label class="fw-bold">Password :</label>
      <input type="password" name="f_password" id="fPass" placeholder="Set Password">

      <label class="fw-bold">Confirm Password :</label>
      <input type="password" name="f_cpassword" id="fPass" placeholder="Confirm Password">

      <div class="modal-actions">
        <button class="btn" name="addFaculty">Add Faculty</button>
        <button class="btn btn-light" onclick="close3Modal('facultyModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editfacultyModal">
  <div class="modal">
    <span class="close-btn" onclick="close3Modal('editfacultyModal')">&times;</span>
    <h3 id="facultyModalTitle">Edit Faculty</h3>
    <hr><br>
    <form method="POST">
      <input type="hidden" name="edt_id" id="edit_id">
      <input type="hidden" name="page" id="edit_page" value="<?php echo $currentPage; ?>">
      <input type="hidden" name="limit" id="edit_limit" value="<?php echo $currentLimit; ?>"><br>

      <!-- Add these hidden inputs -->
      <input type="hidden" name="filterDept_faculty" id="edit_filterDept_faculty">
      <input type="hidden" name="filterCode_faculty" id="edit_filterCode_faculty">
      <input type="hidden" name="filterName_faculty" id="edit_filterName_faculty">
      <input type="hidden" name="filterEmail_faculty" id="edit_filterEmail_faculty">

      <label class="fw-bold">Faculty ID :</label>
      <input type="text" name="f_id1" id="fId1" placeholder="Faculty ID">

      <label class="fw-bold">Name :</label>
      <input type="text" name="f_name1" id="fName1" placeholder="Full Name">

      <label class="fw-bold" for="programDept">Department :</label>
      <select id="programDept1" name="f_dept1" required>
        <option value="">-- Select Department --</option>
        <?php mysqli_data_seek($deptRes, 0);
        while ($row = mysqli_fetch_assoc($deptRes)) { ?>
          <option value="<?php echo $row[$_departmentName]; ?>"><?php echo $row[$_departmentName]; ?></option>
        <?php } ?>
      </select>

      <label class="fw-bold">Email :</label>
      <input type="text" name="f_email1" id="fEmail1" placeholder="Email">

      <label class="fw-bold">Join Date :</label>
      <input type="date" name="f_joinDate1" id="fJoinDate1" placeholder="Join Date">

      <div class="modal-actions">
        <button class="btn" name="editFaculty">Edit Faculty</button>
        <button class="btn btn-light" onclick="close3Modal('editfacultyModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Upload Modal -->
<div class="modal-overlay" id="uploadModal">
  <div class="modal">
    <span class="close-btn" onclick="close3Modal('uploadModal')">&times;</span>
    <h3>Upload File</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="file" name="file" id="facultyFile" accept=".csv,.xlsx,.sql">
      <div class="modal-actions">
        <button class="btn" name="addFile">Upload</button>
        <button class="btn btn-light" onclick="close3Modal('uploadModal')">Cancel</button>
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
    window.location.href = "add_faculty.php";
  }
</script>

<?php include_once("../footer.php"); ?>