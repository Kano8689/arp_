<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 1) {
  header("Location: ../");
  exit;
}

include_once("../DB/pagination.php");   // ✅ added pagination functions
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;




// ✅ get page & limit
list($currentPage, $currentLimit) = getPaginationParams();
$redirectUrl = "add_student.php?page=$currentPage&limit=$currentLimit";

// === FILTER INPUTS ===
$filterProgram = isset($_GET['filterProgram']) ? mysqli_real_escape_string($conn, $_GET['filterProgram']) : '';
$filterEnNo = isset($_GET['filterEnNo']) ? mysqli_real_escape_string($conn, $_GET['filterEnNo']) : '';
$filterName = isset($_GET['filterName_student']) ? mysqli_real_escape_string($conn, $_GET['filterName_student']) : '';
$filterAdYear = isset($_GET['filterAdYear']) ? mysqli_real_escape_string($conn, $_GET['filterAdYear']) : '';

$whereClause = [];

// Apply filters
if ($filterProgram !== '') {
  $prgId = GetProgramNameId($filterProgram, false);
  $whereClause[] = "$_studentProgram = '$prgId'";
}
if ($filterEnNo !== '') {
  $whereClause[] = "$_studentCode LIKE '%$filterEnNo%'";
}
if ($filterName !== '') {
  $whereClause[] = "$_studentName LIKE '%$filterName%'";
}
if ($filterAdYear !== '') {
  $whereClause[] = "$_studentAdmitYear = '$filterAdYear'";
}

$whereSQL = '';
if (!empty($whereClause)) {
  $whereSQL = " WHERE " . implode(" AND ", $whereClause);
}


// Build filter query for persistent URLs
$filterQuery = '';
if ($filterProgram !== '')
  $filterQuery .= '&filterProgram=' . urlencode($filterProgram);
if ($filterEnNo !== '')
  $filterQuery .= '&filterEnNo=' . urlencode($filterEnNo);
if ($filterName !== '')
  $filterQuery .= '&filterName_student=' . urlencode($filterName);
if ($filterAdYear !== '')
  $filterQuery .= '&filterAdYear=' . urlencode($filterAdYear);




// Delete Student
if (isset($_GET['del_id'])) {
  $del_id = $_GET['del_id'];

  $select = "SELECT $_studentCode FROM $_studentTable WHERE $_studentId=$del_id";
  $result = mysqli_query($conn, $select);
  $row = mysqli_fetch_assoc($result);
  $studentCode = $row[$_studentCode];

  $deleteStudent = "DELETE FROM $_studentTable WHERE $_studentId = $del_id";
  mysqli_query($conn, $deleteStudent);


  $un = $studentCode . $defaultLoginExtension;
  // $loginDelete = "DELETE FROM $_loginTable WHERE $_loginUsername = '$un'";
  // mysqli_query($conn, $loginDelete);
  LoginTableDelete($conn, $_loginTable, $_loginUsername, $un);

  header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
  exit;
}

$currentPage = 0;
$currentLimit = 0;

// Update Student
if (isset($_POST['editStudent'])) {
  $studentId = $_POST['edt_id'];
  $studentEnrlNo = $_POST['editEnNo'];
  $studentName = $_POST['editName'];
  $studentPrgNo = $_POST['editPrgName'];
  $studentAdtYr = $_POST['editAdYr'];

  $studentPrgNo = GetProgramNameId($studentPrgNo, false);

  $where = "$_studentCode='$studentEnrlNo' AND $_studentName = '$studentName' AND $_studentProgram = '$studentPrgNo' AND $_studentAdmitYear = '$studentAdtYr'";
  if (isUniqueOrNot($conn, $_studentTable, $where)) {
    $UpdateStudent = "UPDATE $_studentTable  SET $_studentCode = '$studentEnrlNo', $_studentName = '$studentName', $_studentProgram = '$studentPrgNo', $_studentAdmitYear = '$studentAdtYr' WHERE $_studentId = '$studentId'";

    mysqli_query($conn, $UpdateStudent);

    $page = isset($_POST['page']) ? intval($_POST['page']) : $currentPage;
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : $currentLimit;

    $filterQuery = '';
    if (!empty($_POST['filterProgram']))
      $filterQuery .= '&filterProgram=' . urlencode($_POST['filterProgram']);
    if (!empty($_POST['filterEnNo']))
      $filterQuery .= '&filterEnNo=' . urlencode($_POST['filterEnNo']);
    if (!empty($_POST['filterName_student']))
      $filterQuery .= '&filterName_student=' . urlencode($_POST['filterName_student']);
    if (!empty($_POST['filterAdYear']))
      $filterQuery .= '&filterAdYear=' . urlencode($_POST['filterAdYear']);

    header("Location: add_student.php?page=$page&limit=$limit$filterQuery"); // ✅ use POST filters
    exit;
  } else {
    $uniqError = "Record is not upated due to student enrollment number already exists..";
  }
}

// Add Sinlge Record of Student
if (isset($_POST['addStudent'])) {
  $studentEnrlNo = $_POST['addEnNo'];
  $studentName = $_POST['addName'];
  $studentPrgNo = $_POST['addPrgName'];
  $studentAdtYr = $_POST['addAdYr'];
  $studentPassword = $_POST['s_password'];
  $studentConfirmPassword = $_POST['s_cpassword'];


  if ($studentPassword == $studentConfirmPassword) {
    $encPass = Encrypt($studentPassword);
    $studentPrgNo = GetProgramNameId($studentPrgNo, false);


    $where = "$_studentCode='$studentEnrlNo'";
    if (isUniqueOrNot($conn, $_studentTable, $where)) {
      $AddStudent = "INSERT INTO $_studentTable ($_studentCode,$_studentName,$_studentProgram,$_studentAdmitYear) VALUES ('$studentEnrlNo','$studentName','$studentPrgNo','$studentAdtYr')";
      mysqli_query($conn, $AddStudent);
      // echo $AddStudent;
      // exit;
      $un = $studentEnrlNo . $defaultLoginExtension;
      LoginTableInsert($conn, $_loginTable, [$_loginUsername, $_loginPassword, $_loginUserType], [$un, $encPass, 4]);
      header("Location: $redirectUrl$filterQuery");
      exit;
    } else {
      $uniqError = "Record is not insetred due to student enrollment number already exists..";
    }
  } else {
    $error = "Password and confirm PASSword doesnot match!";
  }
}

// Add Students Records File
if (isset($_POST['addFile'])) {
  $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

  switch ($ext) {
    case 'csv':
      $handle = fopen($_FILES['file']['tmp_name'], "r");
      $rowIndex = 0;
      while ($row = fgetcsv($handle)) {
        if ($rowIndex == 0)
          continue;
        $rowIndex++;
        GetAndSaveDataFromFile($row);
      }

      break;

    case 'xlsx':
    case 'xls':
      $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
      $sheetData = $spreadsheet->getActiveSheet()->toArray();

      foreach ($sheetData as $index => $row) {
        if ($index == 0)
          continue;
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
  global $conn, $_studentTable, $defaultLoginExtension;
  global $_studentCode, $_studentName, $_studentProgram, $_studentAdmitYear;
  global $_loginTable, $_loginUsername, $_loginPassword, $_loginUserType;

  $fields = [$_studentCode, $_studentName, $_studentProgram, $_studentAdmitYear];

  $studentEnrlNo = mysqli_real_escape_string($conn, $ary[1] ?? '');
  $studentName = mysqli_real_escape_string($conn, $ary[2] ?? '');
  $studentPrgNo = mysqli_real_escape_string($conn, $ary[3] ?? '');
  $studentAdtYr = mysqli_real_escape_string($conn, $ary[4] ?? '');
  $studentPassword = mysqli_real_escape_string($conn, $ary[5] ?? '');

  $studentPrgNo = GetProgramNameId($studentPrgNo, false);
  $encPass = Encrypt($studentPassword);

  $data = [$studentEnrlNo, $studentName, $studentPrgNo, $studentAdtYr, $encPass];
  $whereData = $_studentCode . " = '" . $studentEnrlNo . "' and " . $_studentName . " = '" . $studentName . "'";

  $selectTemp = "SELECT * FROM $_studentTable WHERE $_studentCode='$studentEnrlNo'";
  $resultTemp = mysqli_query($conn, $selectTemp);
  if (mysqli_num_rows($resultTemp) > 0) {
    $row = mysqli_fetch_array($resultTemp);
    $enNoFld = $row[$_studentCode];

    $un = $studentEnrlNo . $defaultLoginExtension;
    LoginTableUpdate($conn, $_loginTable, [$_loginUsername, $_loginPassword, $_loginUserType], [$un, $encPass, 3]);
    // exit;  
  } else {
    $un = $studentEnrlNo . $defaultLoginExtension;
    LoginTableInsert($conn, $_loginTable, [$_loginUsername, $_loginPassword, $_loginUserType], [$un, $encPass, 4]);
  }

  // print("<pre>");
  // print_r($fields);
  // print_r($data);
  // print_r($whereData);
  // echo "$_studentTable<br>";
  // exit;

  FieldStringSetter($conn, $_studentTable, $fields, $data, $whereData);
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

// Select Program Name
function GetProgramNameId($_value, $_isGetName = true)
{
  global $conn, $_programTableName;
  global $_programId, $_programNameField, $_programSemField, $_programDeptField;

  $field = $_isGetName ? $_programId : $_programNameField;
  $select = "SELECT * FROM $_programTableName WHERE $field = '$_value'";
  $res = mysqli_query($conn, $select);
  $row = mysqli_fetch_assoc($res);

  // echo $select;
  // exit;

  if ($_isGetName)
    return $row[$_programNameField] ?? null;
  else
    return $row[$_programId] ?? null;
}

$prgRes = mysqli_query($conn, "SELECT * FROM $_programTableName");


include_once("../header.php");

list($currentPage, $currentLimit) = getPaginationParams();

// ✅ Force integer values
$currentPage = (int) $currentPage;
$currentLimit = (int) $currentLimit;

$offset = ($currentPage - 1) * $currentLimit;

// Select Faculty
$selectStudents = "SELECT * FROM $_studentTable $whereSQL ORDER BY created_at DESC LIMIT $offset, $currentLimit";
$response = mysqli_query($conn, $selectStudents);

// Select Faculty
$selectStudents1 = "SELECT * FROM $_studentTable ";
$response1 = mysqli_query($conn, $selectStudents1);
$totalRows = mysqli_num_rows($response1);

?>

<!-- Main Content -->
<div class="container">

  <!-- Title + Breadcrumb -->
  <div class="page-header">
    <h3>Student</h3>
    <div class="breadcrumb-box">
      <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Admin</span></a>
      <span class="sep">›</span>
      <span class="crumb-link crumb-disabled">Student</span>
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
      <button class="btn" onclick="open3Modal('studentModal')">+ Add Student</button>
      <button class="btn" onclick="open3Modal('uploadModal')">+ Add File (csv/xlsx/sql)</button>
    </div>

    <div id="filterBox"
      style="display:<?php echo ($filterProgram || $filterEnNo || $filterName || $filterAdYear) ? 'block' : 'none'; ?>; margin-top:10px; background:#f8f8f8; padding:15px; border-radius:8px;">
      <form method="GET">
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
          <select name="filterProgram" style="flex:1; padding:10px;">
            <option value="">-- Select Program --</option>
            <?php mysqli_data_seek($prgRes, 0);
            while ($row = mysqli_fetch_assoc($prgRes)) {
              $selected = ($filterProgram == $row[$_programNameField]) ? 'selected' : '';
              echo "<option value='{$row[$_programNameField]}' $selected>{$row[$_programNameField]}</option>";
            }
            ?>
          </select>

          <input type="text" name="filterEnNo" value="<?php echo htmlspecialchars($filterEnNo); ?>"
            placeholder="Enrollment No" style="flex:1; padding:10px;">
          <input type="text" name="filterName_student" value="<?php echo htmlspecialchars($filterName); ?>"
            placeholder="Student Name" style="flex:1; padding:10px;">
          <input type="text" name="filterAdYear" value="<?php echo htmlspecialchars($filterAdYear); ?>"
            placeholder="Admission Year" style="flex:1; padding:10px;">
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
            <th style="text-align: center;">Enrollment Number</th>
            <th style="text-align: center;">Name</th>
            <th style="text-align: center;">Program Name</th>
            <th style="text-align: center;">Admission Year</th>
            <th style="text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody id="facultyTable">
          <?php
          // $num = $totalRows - $offset;
          $num = 1; $num = (($currentPage - 1) * $currentLimit + 1);
          while ($row = mysqli_fetch_assoc($response)) { ?>
            <tr ondblclick="editStudent('<?php echo $row[$_studentId]; ?>',
                                          '<?php echo $row[$_studentCode]; ?>',
                                          '<?php echo $row[$_studentName]; ?>',
                                          '<?php echo GetProgramNameId($row[$_studentProgram]); ?>',
                                          '<?php echo $row[$_studentAdmitYear]; ?>',
                                          '<?php echo $currentPage; ?>',
                                          '<?php echo $currentLimit; ?>',
                                          '<?php echo htmlspecialchars($filterProgram); ?>',
                                          '<?php echo htmlspecialchars($filterEnNo); ?>',
                                          '<?php echo htmlspecialchars($filterName); ?>',
                                          '<?php echo htmlspecialchars($filterAdYear); ?>')">
              <td style="text-align: center; padding: 5px 0;"><?php echo $num++ ?></td>
              <td style="text-align: center; padding: 5px 0;"><?php echo $row[$_studentCode]; ?></td>
              <td style="text-align: center; padding: 5px 0;"><?php echo $row[$_studentName]; ?></td>
              <td style="text-align: center; padding: 5px 0;"><?php echo GetProgramNameId($row[$_studentProgram]); ?></td>
              <td style="text-align: center; padding: 5px 0;"><?php echo $row[$_studentAdmitYear]; ?></td>
              <td style="text-align: center; padding: 5px 0;">
                <form method="POST">
                  <!-- Update button -->
                  <a onclick="editStudent('<?php echo $row[$_studentId]; ?>',
                                          '<?php echo $row[$_studentCode]; ?>',
                                          '<?php echo $row[$_studentName]; ?>',
                                          '<?php echo GetProgramNameId($row[$_studentProgram]); ?>',
                                          '<?php echo $row[$_studentAdmitYear]; ?>',
                                          '<?php echo $currentPage; ?>',
                                          '<?php echo $currentLimit; ?>',
                                          '<?php echo htmlspecialchars($filterProgram); ?>',
                                          '<?php echo htmlspecialchars($filterEnNo); ?>',
                                          '<?php echo htmlspecialchars($filterName); ?>',
                                          '<?php echo htmlspecialchars($filterAdYear); ?>')" class="btn btn-light"
                    style="display:inline-block; margin-right:5px;">
                    ✏️ Edit
                  </a>

                  <!-- Delete button -->
                  <a href="add_student.php?del_id=<?php echo $row[$_studentId]; ?>&page=<?php echo $currentPage; ?>&limit=<?php echo $currentLimit; ?><?php echo $filterQuery; ?>"
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

    <!-- ✅ pagination UI -->
    <?php
    $totalRecords = paginationUI($conn, $_studentTable, $currentPage, $currentLimit, $whereSQL, $filterQuery);

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
<div class="modal-overlay" id="studentModal">
  <div class="modal">
    <span class="close-btn" onclick="close2Modal('studentModal')">&times;</span>
    <h3 id="studentModalTitle">Add Student</h3>
    <hr><br>
    <form method="POST">
      <input type="hidden" name="edt_id" id="add_id">
      <label class="fw-bold">Enrollment No :</label>
      <input type="text" name="addEnNo" id="addEnNo" placeholder="Enrollment No">

      <label class="fw-bold">Name :</label>
      <input type="text" name="addName" id="addName" placeholder="Full Name">

      <label class="fw-bold" for="programDept">Program Name :</label>
      <select id="addPrgName" name="addPrgName" required>
        <option value="">-- Select Department --</option>
        <?php mysqli_data_seek($prgRes, 0);
        while ($row = mysqli_fetch_assoc($prgRes)) { ?>
          <option value="<?php echo $row[$_programNameField]; ?>"><?php echo $row[$_programNameField]; ?></option>
        <?php } ?>
      </select>

      <label class="fw-bold">Admission Year :</label>
      <input type="text" name="addAdYr" id="addAdYr" placeholder="eg; 20xx-20yy">

      <label class="fw-bold">Password :</label>
      <input type="password" name="s_password" id="fPass" placeholder="Set Password">

      <label class="fw-bold">Confirm Password :</label>
      <input type="password" name="s_cpassword" id="fPass" placeholder="Confirm Password">

      <div class="modal-actions">
        <button class="btn" name="addStudent">Add Student</button>
        <button class="btn btn-light" onclick="close2Modal('addStudentModal')">Cancel</button>
      </div>

    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editStudentModal">
  <div class="modal">
    <span class="close-btn" onclick="close2Modal('editStudentModal')">&times;</span>
    <h3 id="studentModalTitle">Edit Student</h3>
    <hr><br>
    <form method="POST">
      <input type="hidden" name="edt_id" id="edit_id">
      <input type="hidden" name="page" id="edit_page" value="<?php echo $currentPage; ?>">
      <input type="hidden" name="limit" id="edit_limit" value="<?php echo $currentLimit; ?>"><br>

      <!-- Add these hidden inputs -->
      <input type="hidden" name="filterProgram" id="edit_filterProgram">
      <input type="hidden" name="filterEnNo" id="edit_filterEnNo">
      <input type="hidden" name="filterName_student" id="edit_filterName_student">
      <input type="hidden" name="filterAdYear" id="edit_filterAdYear">

      <label class="fw-bold">Enrollment No :</label>
      <input type="text" name="editEnNo" id="editEnNo" placeholder="Enrollment No">

      <label class="fw-bold">Name :</label>
      <input type="text" name="editName" id="editName" placeholder="Full Name">

      <label class="fw-bold" for="programDept">Program Name :</label>
      <select id="editPrgName" name="editPrgName" required>
        <option value="">-- Select Department --</option>
        <?php mysqli_data_seek($prgRes, 0);
        while ($row = mysqli_fetch_assoc($prgRes)) { ?>
          <option value="<?php echo $row[$_programNameField]; ?>"><?php echo $row[$_programNameField]; ?></option>
        <?php } ?>
      </select>

      <label class="fw-bold">Admission Year :</label>
      <input type="text" name="editAdYr" id="editAdYr" placeholder="eg; 20xx-20yy">

      <div class="modal-actions">
        <button class="btn" name="editStudent">Edit Student</button>
        <button class="btn btn-light" onclick="close2Modal('editStudentModal')">Cancel</button>
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
    window.location.href = "add_student.php";
  }
</script>

<?php include_once("../footer.php"); ?>