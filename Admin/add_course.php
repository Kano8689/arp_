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
$redirectUrl = "add_course.php?page=$currentPage&limit=$currentLimit";


// ✅ Filter inputs
$filterOwner = isset($_GET['filterOwner']) ? mysqli_real_escape_string($conn, $_GET['filterOwner']) : '';
$filterCode = isset($_GET['filterCode']) ? mysqli_real_escape_string($conn, $_GET['filterCode']) : '';
$filterName = isset($_GET['filterName']) ? mysqli_real_escape_string($conn, $_GET['filterName']) : '';
$filterType = isset($_GET['filterType']) ? mysqli_real_escape_string($conn, $_GET['filterType']) : '';



// Base query
$selectCourseSQL = "SELECT c.*, o.$courseOwnerNameField AS course_owner_name
                    FROM $coursesTable c
                    JOIN $courseOwnerTable o ON c.$courseOwnerField = o.$courseOwnerIdField";

$whereClause = [];

// Filter by owner ID
if ($filterOwner !== '') {
  $whereClause[] = "o.$courseOwnerIdField = '$filterOwner'";
}

// Filter by code, name, type
if ($filterCode !== '') {
  $whereClause[] = "c.$courseCodeField LIKE '%$filterCode%'";
}
if ($filterName !== '') {
  $whereClause[] = "c.$courseNameField LIKE '%$filterName%'";
}
if ($filterType !== '') {
  $whereClause[] = "c.$courseTypeField = '$filterType'";
}

$whereSQL = '';
// Append WHERE if any filters
if (!empty($whereClause)) {
  $whereSQL = " WHERE " . implode(" AND ", $whereClause);
}


// ✅ Build filter query for URL persistence (pagination, edit, delete)
$filterQuery = '';
if ($filterOwner !== '')
  $filterQuery .= '&filterOwner=' . urlencode($filterOwner);
if ($filterCode !== '')
  $filterQuery .= '&filterCode=' . urlencode($filterCode);
if ($filterName !== '')
  $filterQuery .= '&filterName=' . urlencode($filterName);
if ($filterType !== '')
  $filterQuery .= '&filterType=' . urlencode($filterType);

// Delete Course
if (isset($_GET['del_id'])) {
  $del_id = $_GET['del_id'];
  $deleteProgram = "DELETE FROM $coursesTable WHERE $_id = $del_id";
  // exit;
  mysqli_query($conn, $deleteProgram);
  header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
  exit;
}

// Update Course
if (isset($_POST['editCourse'])) {
  $update_id = $_POST['edt_id'];
  $courseOwner = $_POST['cOwner'];
  $courseCode = $_POST['cCode'];
  $courseName = $_POST['cName'];
  $theoryMarks = $_POST['cTheory'] ?? 0;
  $practicalMarks = $_POST['cPractical'] ?? 0;
  $creditMarks = $_POST['cTEP'] ?? 0;

  $courseType = $_POST['cType'] == "Theory" ? 1 : ($_POST['cType'] == "Practical" ? 2 : ($_POST['cType'] == "Theory & Practical" ? 3 : 0));

    $where = "$courseOwnerField='$courseOwner' AND $courseCodeField='$courseCode' AND $courseNameField='$courseName' AND $courseTypeField='$courseType' AND $theoryMarksField = '$theoryMarks' AND $practicalMarksField='$practicalMarks' AND $creditMarksField = '$creditMarks'";
  if (isUniqueOrNot($conn, $coursesTable, $where)) {
    $updateCourse = "UPDATE $coursesTable SET $courseOwnerField = '$courseOwner', $courseCodeField = '$courseCode', $courseNameField = '$courseName', $courseTypeField = '$courseType', $theoryMarksField = '$theoryMarks', $practicalMarksField = '$practicalMarks', $creditMarksField = '$creditMarks' WHERE $_id = $update_id";


    mysqli_query($conn, $updateCourse);

    $page = isset($_POST['page']) ? intval($_POST['page']) : $currentPage;
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : $currentLimit;

    $filterQuery = '';
    if (!empty($_POST['filterOwner']))
      $filterQuery .= '&filterOwner=' . urlencode($_POST['filterOwner']);
    if (!empty($_POST['filterCode']))
      $filterQuery .= '&filterCode=' . urlencode($_POST['filterCode']);
    if (!empty($_POST['filterName']))
      $filterQuery .= '&filterName=' . urlencode($_POST['filterName']);
    if (!empty($_POST['filterType']))
      $filterQuery .= '&filterType=' . urlencode($_POST['filterType']);

    header("Location: add_course.php?page=$page&limit=$limit$filterQuery"); // ✅ use POST filters
    exit;
  } else {
    $uniqError = "Record is not updated due to already exists..";
    // echo "<br>";
    // echo "$uniqError";
    // echo "<br>";
    // echo "$where";
    // echo "<br>";
    // exit;
  }
}

// Single Record Insertion
if (isset($_POST['addCourse'])) {
  $courseOwner = $_POST['cOwner'];
  $courseCode = $_POST['cCode'];
  $courseName = $_POST['cName'];
  $theoryMarks = $_POST['cTheory'] ?? 0;
  $practicalMarks = $_POST['cPractical'] ?? 0;
  $creditMarks = $_POST['cTEP'] ?? 0;
  $courseType = $_POST['cType'] == "Theory" ? 1 : ($_POST['cType'] == "Practical" ? 2 : ($_POST['cType'] == "Theory & Practical" ? 3 : 0));

  $where = "$courseOwnerField='$courseOwner' AND $courseCodeField='$courseCode' AND $courseNameField='$courseName' AND $courseTypeField='$courseType' AND $theoryMarksField = '$theoryMarks' AND $practicalMarksField='$practicalMarks' AND $creditMarksField = '$creditMarks'";
  if (isUniqueOrNot($conn, $coursesTable, $where)) {
    $insertCourse = "INSERT INTO $coursesTable ($courseOwnerField, $courseCodeField, $courseNameField, $courseTypeField, $theoryMarksField, $practicalMarksField, $creditMarksField) VALUES ('$courseOwner', '$courseCode', '$courseName', '$courseType', '$theoryMarks', '$practicalMarks', '$creditMarks')";
    mysqli_query($conn, $insertCourse);
    header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
    exit;
  } else {
    $uniqError = "Record is not insetred due to already exists..";
    // echo "<br>";
    // echo "$uniqError";
    // echo "<br>";
    // echo "$where";
    // echo "<br>";
    // exit;
  }
}


// Multi Records File Insertion
if (isset($_POST['addFile'])) {
  $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
  $fields = [$courseOwnerField, $courseCodeField, $courseNameField, $courseTypeField, $theoryMarksField, $practicalMarksField, $creditMarksField];

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
  global $conn, $coursesTable;
  global $courseOwnerField, $courseCodeField, $courseNameField, $courseTypeField, $theoryMarksField, $practicalMarksField, $creditMarksField;
  global $courseOwnerTable, $courseOwnerNameField, $courseOwnerIdField;

  $fields = [$courseOwnerField, $courseCodeField, $courseNameField, $courseTypeField, $theoryMarksField, $practicalMarksField, $creditMarksField];

  $courseOwner = mysqli_real_escape_string($conn, $ary[1] ?? '');
  $courseCode = mysqli_real_escape_string($conn, $ary[2] ?? '');
  $courseName = mysqli_real_escape_string($conn, $ary[3] ?? '');
  $courseType = mysqli_real_escape_string($conn, $ary[4] ?? '');
  $theoryMarks = mysqli_real_escape_string($conn, $ary[5] ?? '');
  $practicalMarks = mysqli_real_escape_string($conn, $ary[6] ?? '');
  $creditMarks = mysqli_real_escape_string($conn, $ary[7] ?? '');

  $ct = ($courseType == "T") ? 1 : (($courseType == "P") ? 2 : (($courseType == "TEL") ? 3 : null));
  $courseType = $ct;



  $coi = returnCourseOwnerId($conn, $courseOwnerTable, $courseOwnerNameField, $courseOwner, $courseOwnerIdField);
  $data = [$coi, $courseCode, $courseName, $courseType, $theoryMarks, $practicalMarks, $creditMarks];
  $whereData = $courseCodeField . " = '" . $courseCode . "' and " . $courseNameField . " = '" . $courseName . "'";
  FieldStringSetter($conn, $coursesTable, $fields, $data, $whereData);
}


function returnCourseOwnerName($conn, $table, $idField, $idValue, $nameField)
{
  $selectOwner = "SELECT * FROM $table WHERE $idField='$idValue'";
  $res = mysqli_query($conn, $selectOwner);
  $row = mysqli_fetch_assoc($res);
  return $row[$nameField] ?? null;
}
function returnCourseOwnerId($conn, $table, $nameField, $nameValue, $idField)
{
  $selectOwner = "SELECT * FROM $table WHERE $nameField='$nameValue'";
  $res = mysqli_query($conn, $selectOwner);
  $row = mysqli_fetch_assoc($res);
  // echo $selectOwner . "<br>";
  // echo $idField;
  // echo "<pre>";
  // print_r($row);

  // exit;
  return $row[$idField] ?? null;
}


function fetchCourseOwners($conn, $courseOwnerTable, $_id, $nameField, $id)
{
  $responseOwners = mysqli_query($conn, "SELECT * FROM $courseOwnerTable WHERE $_id = '$id'");

  // print("<pre>");
  // echo("$selectOwners ifcvdixnvjinvndn nnd n ");
  // echo($selectOwners);
  // exit;
  $row = mysqli_fetch_assoc($responseOwners);
  return $row[$nameField] ?? null;
}

// print("<pre>");
// echo(fetchCourseOwners($conn, $courseOwnerTable, $courseOwnerIdField, $courseOwnerNameField, 1));
// exit;


$responseOwners = mysqli_query($conn, "SELECT * FROM $courseOwnerTable");
include_once("../header.php");


list($currentPage, $currentLimit) = getPaginationParams();

// ✅ Force integer values
$currentPage = (int) $currentPage;
$currentLimit = (int) $currentLimit;

$offset = ($currentPage - 1) * $currentLimit;

// ✅ Final query with filter, order, and pagination
$selectCourseSQL .= "$whereSQL ORDER BY created_at DESC LIMIT $offset, $currentLimit";
$selectCourseSQL1 = "SELECT c.*, o.$courseOwnerNameField AS course_owner_name
                    FROM $coursesTable c
                    JOIN $courseOwnerTable o ON c.$courseOwnerField = o.$courseOwnerIdField $whereSQL ORDER BY created_at DESC";
$response = mysqli_query($conn, $selectCourseSQL);

$selectCourse1 = "SELECT * FROM $coursesTable";
$response1 = mysqli_query($conn, $selectCourse1);
$totalRows = mysqli_num_rows($response1);


?>

<!-- Main Content -->
<div class="container">

  <!-- Title + Breadcrumb -->
  <div class="page-header">
    <h3>Course</h3>
    <div class="breadcrumb-box">
      <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Admin</span></a>
      <span class="sep">›</span>
      <span class="crumb-link crumb-disabled">Course</span>
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
      <button class="btn" onclick="open1Modal('courseModal')">+ Add Course</button>
      <button class="btn" onclick="open1Modal('uploadModal')">+ Add File (csv/xlsx/sql)</button>
    </div>


    <!-- Filter Box -->
    <div id="filterBox"
      style="display:<?php echo ($filterOwner || $filterCode || $filterName || $filterType) ? 'block' : 'none'; ?>; margin-top:10px; background:#f8f8f8; padding:15px; border-radius:8px;">
      <form method="GET">
        <div style="display:flex; flex-wrap:wrap; gap:10px;">

          <select name="filterOwner" style="flex:1; padding:10px;">
            <option value="">Select Owner</option>
            <?php
            mysqli_data_seek($responseOwners, 0);
            while ($row = mysqli_fetch_assoc($responseOwners)) {
              $selected = ($filterOwner == $row[$courseOwnerIdField]) ? 'selected' : '';
            ?>
              <option value="<?php echo $row[$courseOwnerIdField]; ?>" <?php echo $selected; ?>>
                <?php echo $row[$courseOwnerNameField]; ?>
              </option>
            <?php } ?>
          </select>

          <input type="text" name="filterCode" value="<?php echo htmlspecialchars($filterCode); ?>"
            placeholder="Course Code" style="flex:1; padding:10px;">
          <input type="text" name="filterName" value="<?php echo htmlspecialchars($filterName); ?>"
            placeholder="Course Name" style="flex:1; padding:10px;">
          <select name="filterType" style="flex:1; padding:10px;">
            <option value="">Select Course Type</option>
            <option value="1" <?php if ($filterType == "1")
                                echo "selected"; ?>>Theory</option>
            <option value="2" <?php if ($filterType == "2")
                                echo "selected"; ?>>Practical</option>
            <option value="3" <?php if ($filterType == "3")
                                echo "selected"; ?>>Theory & Practical</option>
          </select>

        </div>
        <div style="margin-top:10px;">
          <button class="btn" type="submit">Apply Filter</button>
          <button type="button" class="btn btn-light" onclick="resetFilter()">Cancel</button>
        </div>
      </form>
    </div>


    <!-- Responsive Table Wrapper -->
    <div class="table-responsive" style="margin-top: 20px;">
      <table class="table" id="courseList">
        <thead>
          <tr>
            <th style="text-align: center;">#</th>
            <th style="text-align: center;">Course Owner</th>
            <th style="text-align: center;">Course Code</th>
            <th style="text-align: center;">Course Name</th>
            <th style="text-align: center;">Course Type</th>
            <th style="text-align: center;">T</th>
            <th style="text-align: center;">P</th>
            <th style="text-align: center;">C</th>
            <th style="text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody id="courseTable">
          <?php
          if ($totalRows > 0) {
            // $num = $totalRows - $offset;
            $num = 1;
            while ($row = mysqli_fetch_assoc($response)) { ?>
              <tr style="cursor:pointer;"
                ondblclick="editCourse('<?php echo $row[$_id]; ?>', '<?php echo $row[$courseNameField]; ?>', '<?php echo $row[$courseCodeField]; ?>', '<?php echo $row[$courseOwnerField]; ?>', '<?php echo $row[$courseTypeField]; ?>', '<?php echo $row[$theoryMarksField]; ?>', '<?php echo $row[$practicalMarksField]; ?>', '<?php echo $row[$creditMarksField]; ?>', '<?php echo $currentPage; ?>', '<?php echo $currentLimit; ?>', '<?php echo htmlspecialchars($filterOwner); ?>','<?php echo htmlspecialchars($filterCode); ?>','<?php echo htmlspecialchars($filterName); ?>','<?php echo htmlspecialchars($filterType); ?>')">
                <td style="text-align: center; padding:5px 0px;"><?php echo $num++; ?></td>
                <td style="text-align: center; padding:5px 0px;">
                  <?php echo fetchCourseOwners($conn, $courseOwnerTable, $courseOwnerIdField, $courseOwnerNameField, $row[$courseOwnerField]); ?>
                </td>
                <td style="text-align: center; padding:5px 0px;"><?php echo $row[$courseCodeField]; ?></td>
                <td style="text-align: center; padding:5px 0px;"><?php echo $row[$courseNameField]; ?></td>
                <td style="text-align: center; padding:5px 0px;">
                  <?php
                  $type = ($row[$courseTypeField] == 1) ? "T" : (($row[$courseTypeField] == 2) ? "P" : (($row[$courseTypeField] == 3) ? "TEL" : "None"));
                  echo $type;
                  ?>
                </td>
                <td style="text-align: center; padding:5px 0px;"><?php echo $row[$theoryMarksField]; ?></td>
                <td style="text-align: center; padding:5px 0px;"><?php echo $row[$practicalMarksField]; ?></td>
                <td style="text-align: center; padding:5px 0px;"><?php echo $row[$creditMarksField]; ?></td>
                <td style="text-align: center; padding:5px 0px;">
                  <form method="POST">
                    <!-- Update button -->
                    <a onclick="editCourse('<?php echo $row[$_id]; ?>', '<?php echo $row[$courseNameField]; ?>', '<?php echo $row[$courseCodeField]; ?>', '<?php echo $row[$courseOwnerField]; ?>', '<?php echo $row[$courseTypeField]; ?>', '<?php echo $row[$theoryMarksField]; ?>', '<?php echo $row[$practicalMarksField]; ?>', '<?php echo $row[$creditMarksField]; ?>', '<?php echo $currentPage; ?>', '<?php echo $currentLimit; ?>', '<?php echo htmlspecialchars($filterOwner); ?>','<?php echo htmlspecialchars($filterCode); ?>','<?php echo htmlspecialchars($filterName); ?>','<?php echo htmlspecialchars($filterType); ?>')"
                      class="btn btn-light" style="display:inline-block; margin-right:5px;">
                      ✏️ Edit
                    </a>

                    <!-- Delete button -->
                    <a href="add_course.php?del_id=<?php echo $row[$_id]; ?>&page=<?php echo $currentPage; ?>&limit=<?php echo $currentLimit; ?> <?php echo $filterQuery; ?>"
                      style="text-decoration: none;" class="btn btn-danger">
                      🗑 Delete
                    </a>
                  </form>
                </td>
              </tr>
            <?php }
          } else { ?>
            <tr>
              <td colspan="9" style="text-align:center; font-weight:bold; font-size:20px; color:#333; padding:100px;">
                No Any Record Found!
              </td>
            </tr>
          <?php } ?>
        </tbody>

      </table>
    </div>

    <!-- ✅ pagination UI -->
    <?php
    $totalRecords = paginationUI2($conn, $selectCourseSQL1, $currentPage, $currentLimit, $filterQuery);



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
<div class="modal-overlay" id="courseModal">
  <div class="modal">
    <span class="close-btn" onclick="close1Modal('courseModal')">&times;</span>
    <h3 id="courseModalTitle">Add Course</h3>
    <hr><br>
    <form method="POST">
      <label class="fw-bold" for="cOwner">Course Owner :</label>
      <select id="cOwner" name="cOwner" required>
        <option value="">Select Course Owner</option>
        <?php mysqli_data_seek($responseOwners, 0);
        while ($row = mysqli_fetch_assoc($responseOwners)) { ?>
          <option value="<?php echo $row[$courseOwnerIdField] ?>"><?php echo $row[$courseOwnerNameField]; ?></option>
        <?php } ?>
      </select>

      <label class="fw-bold">Course Code :</label>
      <input type="text" id="cCode" name="cCode" placeholder="Course Code" required>

      <label class="fw-bold">Course Name :</label>
      <input type="text" id="cName" name="cName" placeholder="Course Name" required>

      <label class="fw-bold">Course Type :</label>
      <select id="cType" name="cType" onchange="handleCourseTypeChange()" required>
        <option value="">Select Course Type</option>
        <option value="Theory">Theory</option>
        <option value="Practical">Practical</option>
        <option value="Theory & Practical">Theory & Practical</option>
      </select>

      <div id="theoryFields" style="display:none;">
        <label class="fw-bold" for="cTheory">Theory Marks :</label>
        <input type="number" name="cTheory" id="cTheory" placeholder="Theory Marks">
      </div>

      <div id="practicalFields" style="display:none;">
        <label class="fw-bold" for="cPractical">Practical Marks :</label>
        <input type="number" name="cPractical" id="cPractical" placeholder="Practical Marks">
      </div>

      <div id="totalCreditField" style="display:none;">
        <label class="fw-bold" for="cTEP">Total Credit :</label>
        <input type="number" id="cTEP" name="cTEP" placeholder="Total Credit">
      </div>

      <div class="modal-actions">
        <button name="addCourse" class="btn">Add Course</button>
        <button class="btn btn-light" onclick="close1Modal('courseModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editcourseModal">
  <div class="modal">
    <span class="close-btn" onclick="close1Modal('editcourseModal')">&times;</span>
    <h3 id="courseModalTitle">Edit Course</h3>
    <hr><br>
    <form method="POST">
      <input type="hidden" name="edt_id" id="edit_id">
      <input type="hidden" name="page" id="edit_page" value="<?php echo $currentPage; ?>">
      <input type="hidden" name="limit" id="edit_limit" value="<?php echo $currentLimit; ?>"><br>

      <!-- Add these hidden inputs -->
      <input type="hidden" name="filterOwner" id="edit_filterOwner">
      <input type="hidden" name="filterName" id="edit_filterName">
      <input type="hidden" name="filterCode" id="edit_filterCode">
      <input type="hidden" name="filterType" id="edit_filterType">


      <label class="fw-bold" for="edit_cOwner">Owner Name:</label>
      <select id="edit_cOwner" name="cOwner" required>
        <option value="">Select Course Owner</option>
        <?php mysqli_data_seek($responseOwners, 0);
        while ($row = mysqli_fetch_assoc($responseOwners)) { ?>
          <option value="<?php echo $row[$courseOwnerIdField] ?>"><?php echo $row[$courseOwnerNameField]; ?></option>
        <?php } ?>
      </select>

      <label class="fw-bold">Course Code :</label>
      <input type="text" id="edit_cCode" name="cCode" placeholder="Course Code" required>

      <label class="fw-bold">Course Name :</label>
      <input type="text" id="edit_cName" name="cName" placeholder="Course Name" required>

      <label class="fw-bold" class="fw-bold">Course Type :</label>
      <select id="edit_cType1" name="cType" onchange="handleCourseTypeChange1()" required>
        <option value="">Select Course Type</option>
        <option value="Theory">Theory</option>
        <option value="Practical">Practical</option>
        <option value="Theory & Practical">Theory & Practical</option>
      </select>

      <div id="theoryFields1" style="display:none;">
        <label class="fw-bold" for="edit_cTheory">Theory Marks :</label>
        <input type="number" name="cTheory" id="edit_cTheory" placeholder="Theory Marks">
      </div>

      <div id="practicalFields1" style="display:none;">
        <label class="fw-bold" for="edit_cPractical">Practical Marks :</label>
        <input type="number" name="cPractical" id="edit_cPractical" placeholder="Practical Marks">
      </div>

      <div id="totalCreditField1" style="display:none;">
        <label class="fw-bold" for="edit_cTEP">Total Credit :</label>
        <input type="number" id="edit_cTEP" name="cTEP" placeholder="Total Credit">
      </div>

      <div class="modal-actions">
        <button name="editCourse" class="btn">Edit Course</button>
        <button class="btn btn-light" type="button" onclick="close1Modal('editcourseModal')">Cancel</button>
      </div>
    </form>
  </div>
</div>



<!-- CSV Modal -->
<div class="modal-overlay" id="uploadModal">
  <div class="modal">
    <span class="close-btn" onclick="close1Modal('uploadModal')">&times;</span>
    <h3>Upload File</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="file" name="file" id="courseFile" accept=".csv,.xlsx,.sql,.xls">
      <div class="modal-actions">
        <button class="btn" name="addFile">Upload</button>
        <button class="btn btn-light" onclick="close1Modal('uploadModal')">Cancel</button>
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
    window.location.href = "add_course.php";
  }
</script>

<?php include_once("../footer.php"); ?>