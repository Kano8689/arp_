<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 2) {
  header("Location: ../");
  exit;
}
include_once("../header.php");
include_once("../DB/pagination.php");   // ✅ added pagination functions



$freezeEntityArray = [$_freezePushPermissionFreezeCa1, $_freezePushPermissionFreezeCa2, $_freezePushPermissionFreezeCa3, $_freezePushPermissionFreezeInternal, $_freezePushPermissionFreezeLab];

$pushEntityArray = [$_freezePushPermissionPushCa1, $_freezePushPermissionPushCa2, $_freezePushPermissionPushCa3, $_freezePushPermissionPushInternal, $_freezePushPermissionPushLab];

//Freeze/Unfreeze perticular course of perticular faculty
if (isset($_POST[$freezeEntityArray[0]])) {
  $field = $freezeEntityArray[0];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
if (isset($_POST[$freezeEntityArray[1]])) {
  $field = $freezeEntityArray[1];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
if (isset($_POST[$freezeEntityArray[2]])) {
  $field = $freezeEntityArray[2];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
if (isset($_POST[$freezeEntityArray[3]])) {
  $field = $freezeEntityArray[3];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
if (isset($_POST[$freezeEntityArray[4]])) {
  $field = $freezeEntityArray[4];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}

//Push/Unpush perticular course of perticular faculty
if (isset($_POST[$pushEntityArray[0]])) {
  $field = $pushEntityArray[0];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
if (isset($_POST[$pushEntityArray[1]])) {
  $field = $pushEntityArray[1];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
if (isset($_POST[$pushEntityArray[2]])) {
  $field = $pushEntityArray[2];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
if (isset($_POST[$pushEntityArray[3]])) {
  $field = $pushEntityArray[3];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
if (isset($_POST[$pushEntityArray[4]])) {
  $field = $pushEntityArray[4];
  $fppId = $_POST['ent_id'];
  $sql = "SELECT $field from $_freezePushPermissionTable WHERE $_freezePushPermissionId='$fppId'";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);

  $ca1 = $res[$field];
  $ca1 = $ca1 == 0 ? 1 : 0;

  $sql = "UPDATE $_freezePushPermissionTable SET $field='$ca1' WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}

//Freeze perticular course of perticular faculty for all exam
if (isset($_POST["Freeze"])) {
  $fppId = $_POST['ent_id'];
  $value = 0;
  $sql = "UPDATE $_freezePushPermissionTable SET 
    $_freezePushPermissionFreezeCa1='$value', 
    $_freezePushPermissionFreezeCa2='$value', 
    $_freezePushPermissionFreezeCa3='$value', 
    $_freezePushPermissionFreezeLab='$value',
    $_freezePushPermissionFreezeInternal='$value'
    WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
//Unfreeze perticular course of perticular faculty for all exam
if (isset($_POST["Unfreeze"])) {
  $fppId = $_POST['ent_id'];
  $value = 1;
  $sql = "UPDATE $_freezePushPermissionTable SET 
    $_freezePushPermissionFreezeCa1='$value', 
    $_freezePushPermissionFreezeCa2='$value', 
    $_freezePushPermissionFreezeCa3='$value', 
    $_freezePushPermissionFreezeLab='$value',
    $_freezePushPermissionFreezeInternal='$value'
    WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
//Push perticular course of perticular faculty for all exam
if (isset($_POST["Push"])) {
  $fppId = $_POST['ent_id'];
  $value = 1;
  $sql = "UPDATE $_freezePushPermissionTable SET 
    $_freezePushPermissionPushCa1='$value', 
    $_freezePushPermissionPushCa2='$value', 
    $_freezePushPermissionPushCa3='$value', 
    $_freezePushPermissionPushLab='$value',
    $_freezePushPermissionPushInternal='$value'
    WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}
//Unpush perticular course of perticular faculty for all exam
if (isset($_POST["Unpush"])) {
  $fppId = $_POST['ent_id'];
  $value = 0;
  $sql = "UPDATE $_freezePushPermissionTable SET 
    $_freezePushPermissionPushCa1='$value', 
    $_freezePushPermissionPushCa2='$value', 
    $_freezePushPermissionPushCa3='$value', 
    $_freezePushPermissionPushLab='$value',
    $_freezePushPermissionPushInternal='$value'
    WHERE $_freezePushPermissionId='$fppId'";
  mysqli_query($conn, $sql);
}

//Freeze all course of all faculty for all exam
if (isset($_POST["freeze_all"])) {
  $value = 0;
  $sql = "UPDATE $_freezePushPermissionTable SET 
    $_freezePushPermissionFreezeCa1='$value', 
    $_freezePushPermissionFreezeCa2='$value', 
    $_freezePushPermissionFreezeCa3='$value', 
    $_freezePushPermissionFreezeLab='$value',
    $_freezePushPermissionFreezeInternal='$value'";
  mysqli_query($conn, $sql);

  header("Location: faculty_permissions.php");  // ✅ redirect with page & limit

  exit;
}
//Unfreeze all course of all faculty for all exam
if (isset($_POST["unfreeze_all"])) {
  $value = 1;
  $sql = "UPDATE $_freezePushPermissionTable SET 
    $_freezePushPermissionFreezeCa1='$value', 
    $_freezePushPermissionFreezeCa2='$value', 
    $_freezePushPermissionFreezeCa3='$value', 
    $_freezePushPermissionFreezeLab='$value',
    $_freezePushPermissionFreezeInternal='$value'";
  mysqli_query($conn, $sql);

  header("Location: faculty_permissions.php");  // ✅ redirect with page & limit

  exit;
}
//Push all course of all faculty for all exam
if (isset($_POST["push_all"])) {
  $value = 1;
  $sql = "UPDATE $_freezePushPermissionTable SET 
    $_freezePushPermissionPushCa1='$value', 
    $_freezePushPermissionPushCa2='$value', 
    $_freezePushPermissionPushCa3='$value', 
    $_freezePushPermissionPushLab='$value',
    $_freezePushPermissionPushInternal='$value'";
  mysqli_query($conn, $sql);

  header("Location: faculty_permissions.php");  // ✅ redirect with page & limit

  exit;
}
//Unpush all course of all faculty for all exam
if (isset($_POST["unpush_all"])) {
  $value = 0;
  $sql = "UPDATE $_freezePushPermissionTable SET 
    $_freezePushPermissionPushCa1='$value', 
    $_freezePushPermissionPushCa2='$value', 
    $_freezePushPermissionPushCa3='$value', 
    $_freezePushPermissionPushLab='$value',
    $_freezePushPermissionPushInternal='$value'";
  mysqli_query($conn, $sql);

  header("Location: faculty_permissions.php");  // ✅ redirect with page & limit

  exit;
}


// ✅ get page & limit
list($currentPage, $currentLimit) = getPaginationParams();
$redirectUrl = "faculty_permissions.php?page=$currentPage&limit=$currentLimit";
// echo "$currentPage";
// exit;

// --- NEW: Get filtration input from GET ---
$filterfaculty = isset($_GET['filter_faculty_ceo']) ? mysqli_real_escape_string($conn, $_GET['filter_faculty_ceo']) : '';
$filtercoursecode = isset($_GET['filter_coursecode_ceo']) ? mysqli_real_escape_string($conn, $_GET['filter_coursecode_ceo']) : '';
$filtercoursename = isset($_GET['filter_coursename_ceo']) ? mysqli_real_escape_string($conn, $_GET['filter_coursename_ceo']) : '';

// Filter Faculty - get faculty ID from name and filter
if ($filterfaculty !== '') {
  $facultyIdQuery = "SELECT $_facultyId FROM $_facultyTable WHERE $_facultyName = '$filterfaculty' LIMIT 1";
  $resFac = mysqli_query($conn, $facultyIdQuery);
  $facRow = mysqli_fetch_assoc($resFac);
  if ($facRow) {
    $whereClauses[] = $_mappingFacultyId . " = '" . $facRow[$_facultyId] . "'";
  } else {
    $whereClauses[] = "0";
  }
}
// Filter Course - get course ID from name and filter
if ($filtercoursename !== '') {
  $courseIdQuery = "SELECT $_courseId FROM $_coursesTable WHERE $_courseNameField = '$filtercoursename' LIMIT 1";
  $resCourse = mysqli_query($conn, $courseIdQuery);
  $courseRow = mysqli_fetch_assoc($resCourse);
  if ($courseRow) {
    $whereClauses[] = $_mappingFacultyCourseId . " = '" . $courseRow[$_courseId] . "'";
  } else {
    $whereClauses[] = "0";
  }
}
// Filter Course - get course ID from name and filter
if ($filtercoursecode !== '') {
  $courseIdQuery = "SELECT $_courseId FROM $_coursesTable WHERE $_courseCodeField = '$filtercoursecode' LIMIT 1";
  $resCourse = mysqli_query($conn, $courseIdQuery);
  $courseRow = mysqli_fetch_assoc($resCourse);
  if ($courseRow) {
    $whereClauses[] = $_mappingFacultyCourseId . " = '" . $courseRow[$_courseId] . "'";
  } else {
    $whereClauses[] = "0";
  }
}

$whereSQL = '';
if (!empty($whereClauses)) {
  $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
}

$filterQuery = '';
foreach (['filter_faculty_ceo', 'filter_coursecode_ceo', 'filter_coursename_ceo'] as $param) {
  if (isset($_GET[$param]) && $_GET[$param] !== '') {
    $filterQuery .= '&' . $param . '=' . urlencode($_GET[$param]);
  }
}



function GetFacultyNameId($val, $isGetName = true)
{
  global $conn, $_facultyTable;
  global $_facultyId, $_facultyName;

  $field = $isGetName ? $_facultyId : $_facultyName;

  $res = mysqli_query($conn, "SELECT * FROM $_facultyTable WHERE $field = '$val'");
  $row = mysqli_fetch_assoc($res);

  if ($isGetName)
    return $row[$_facultyName] ?? null;
  else
    return $row[$_facultyId] ?? null;
}

function GetCourseDetails($fieldName, $fieldValue)
{
  global $conn, $_coursesTable;
  global $_courseId;

  $slct = "SELECT $fieldName FROM $_coursesTable WHERE $_courseId = '$fieldValue'";
  $res = mysqli_query($conn, $slct);
  $data = mysqli_fetch_assoc($res);
  return $data[$fieldName] ?? null;
}

$filter_faculty = mysqli_query($conn, "SELECT * FROM $_facultyTable");
$filter_course = mysqli_query($conn, "SELECT * FROM $_coursesTable");

// ✅ Force integer values
$currentPage = (int) $currentPage;
$currentLimit = (int) $currentLimit;

$offset = ($currentPage - 1) * $currentLimit;
// echo "$currentPage";
// exit;


// Capture sort field and direction from GET
$sort_field = isset($_GET['sort_field']) ? $_GET['sort_field'] : 'created_at'; // default sorting field
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';      // default sorting order

// Validate sort field and order to avoid SQL injection
$allowed_sort_fields = [
  '$_mappingFacultyId' => 'm.$_mappingFacultyId',
  '$_mappingFacultyCourseId' => 'm.$_mappingFacultyCourseId',
  '$_mappingFacultySemesterYear' => 'm.$_mappingFacultySemesterYear',
  '$_mappingFacultySemesterType' => 'm.$_mappingFacultySemesterType',
  'created_at' => 'm.created_at', // make sure to specify table alias here
  // add more columns as needed with aliases
];

if (!array_key_exists($sort_field, $allowed_sort_fields)) {
  $sort_field = 'created_at';
}

$sort_order = strtoupper($sort_order);
if ($sort_order !== 'ASC' && $sort_order !== 'DESC') {
  $sort_order = 'DESC';
}


// Build base URL for sorting links including current filters and pagination
$baseUrl = "faculty_permissions.php?page=$currentPage&limit=$currentLimit$filterQuery";
// echo "$currentPage";
// exit;

function sortLink($field, $label, $currentField, $currentOrder, $baseUrl)
{
  $nextOrder = 'ASC';
  $arrow = '';
  if ($field === $currentField) {
    if ($currentOrder === 'ASC') {
      $nextOrder = 'DESC';
      $arrow = ' ▲';
    } else {
      $nextOrder = 'ASC';
      $arrow = ' ▼';
    }
  }
  $url = $baseUrl . "&sort_field=$field&sort_order=$nextOrder";
  return '<a href="' . htmlspecialchars($url) . '" style="text-decoration:none;color:#ffffff">' . $label . $arrow . '</a>';
}

$sql = "
SELECT 
  m._id,
    m.$_mappingFacultyId, 
    m.$_mappingFacultyCourseId, 
    m.$_mappingFacultySemesterYear, 
    m.$_mappingFacultySemesterType,
    f.$_facultyName AS faculty_name, 
    c.$_courseCodeField AS course_code, 
    c.$_courseNameField AS course_name
FROM 
    $_mappingFacultyTable m
LEFT JOIN 
    $_facultyTable f ON m.$_mappingFacultyId = f.$_facultyId
LEFT JOIN 
    $_coursesTable c ON m.$_mappingFacultyCourseId = c.$_courseId
$whereSQL
GROUP BY 
    m.$_mappingFacultyId, m.$_mappingFacultyCourseId, m.$_mappingFacultySemesterYear, m.$_mappingFacultySemesterType
ORDER BY 
    " . $allowed_sort_fields[$sort_field] . " $sort_order
LIMIT 
    $offset, $currentLimit
";

$mappingRes = mysqli_query($conn, $sql);

if (!$mappingRes) {
  die("Query failed: " . mysqli_error($conn));
}

$resultArray = [];
while ($row = mysqli_fetch_assoc($mappingRes)) {
  // Store all fields needed, including _id
  $filteredRow = [
    '_id' => $row['_id'],
    '$_mappingFacultyId' => $row[$_mappingFacultyId],
    '$_mappingFacultyCourseId' => $row[$_mappingFacultyCourseId],
    '$_mappingFacultySemesterYear' => $row[$_mappingFacultySemesterYear],
    '$_mappingFacultySemesterType' => $row[$_mappingFacultySemesterType]
  ];
  $resultArray[] = $filteredRow;
}

// Make unique based on 4 fields (not _id)
$uniqueResults = [];
$uniqueKeys = [];
$RsltUniqIds = array();
// $RsltUniqIds = [];
foreach ($resultArray as $item) {
  $key = $item['_id'] . '|' . $item['$_mappingFacultyId'] . '|' . $item['$_mappingFacultyCourseId'] . '|' . $item['$_mappingFacultySemesterYear'] . '|' . $item['$_mappingFacultySemesterType'];
  if (!in_array($key, $uniqueKeys)) {
    $RsltUniqIds[] = $item['_id'];
    $uniqueKeys[] = $key;
    $uniqueResults[] = $item;
  }
}

if (!empty($RsltUniqIds)) {
  // If there is already a WHERE clause, append with AND
  if (stripos($whereSQL, 'WHERE') !== false) {
    $whereSQL .= " AND m._id IN (" . implode(',', $RsltUniqIds) . ")";
  } else {
    // First WHERE clause
    $whereSQL .= " WHERE m._id IN (" . implode(',', $RsltUniqIds) . ")";
  }
}

$sql = "
SELECT 
  m._id,
    m.$_mappingFacultyId, 
    m.$_mappingFacultyCourseId, 
    m.$_mappingFacultySemesterYear, 
    m.$_mappingFacultySemesterType,
    f.$_facultyName AS faculty_name, 
    c.$_courseCodeField AS course_code, 
    c.$_courseNameField AS course_name
FROM 
    $_mappingFacultyTable m
LEFT JOIN 
    $_facultyTable f ON m.$_mappingFacultyId = f.$_facultyId
LEFT JOIN 
    $_coursesTable c ON m.$_mappingFacultyCourseId = c.$_courseId
$whereSQL
GROUP BY 
    m.$_mappingFacultyId, m.$_mappingFacultyCourseId, m.$_mappingFacultySemesterYear, m.$_mappingFacultySemesterType
ORDER BY 
    " . $allowed_sort_fields[$sort_field] . " $sort_order
LIMIT 
    $offset, $currentLimit
";

$mappingRes = mysqli_query($conn, $sql);

// if (isset($_POST["permission"])) {
//   $facultyId = $_POST["faculty_id"];
//   $courseId = $_POST["$_mappingFacultyCourseId"];

//   $select_permission = mysqli_query($conn, "SELECT * FROM $_freezePushPermissionTable WHERE $_freezePushPermissionFacId = '$facultyId' AND $_freezePushPermissionCourseId = '$courseId'");
//   $permissionExists = mysqli_num_rows($select_permission) > 0;

//   while ($row = mysqli_fetch_assoc($select_permission)) {
//     $currentPermission = $row[$_freezePushPermission];
//   }

//   if ($permissionExists && $currentPermission == "0") {
//     // If permission exists, you might want to update it
//     $update_query = "UPDATE $_freezePushPermissionTable SET $_freezePushPermission = 1 WHERE $_freezePushPermissionFacId = '$facultyId' AND $_freezePushPermissionCourseId = '$courseId'";
//     $update_result = mysqli_query($conn, $update_query);
//   } else if ($permissionExists && $currentPermission == "1") {
//     // If permission exists, you might want to update it
//     $update_query = "UPDATE $_freezePushPermissionTable SET $_freezePushPermission = 0 WHERE $_freezePushPermissionFacId = '$facultyId' AND $_freezePushPermissionCourseId = '$courseId'";
//     $update_result = mysqli_query($conn, $update_query);
//   } else {
//     // If permission does not exist, insert a new record
//     $insert_query = "INSERT INTO $_freezePushPermissionTable ($_freezePushPermissionFacId, $_freezePushPermissionCourseId, $_freezePushPermission) 
//         VALUES ('$facultyId', '$courseId', 1)";
//     $insert_result = mysqli_query($conn, $insert_query);
//   }
//   header("Location: faculty_permissions.php");  // ✅ redirect with page & limit
//   exit;
// }

if (isset($_POST["push"])) {
  // $facultyId = $_POST["faculty_id"];
  // $courseId = $_POST["$_mappingFacultyCourseId"];

  // $select_push = mysqli_query($conn, "SELECT * FROM $_freezePushPermissionTable WHERE $_freezePushPermissionFacId = '$facultyId' AND $_freezePushPermissionCourseId = '$courseId'");
  // $pushExists = mysqli_num_rows($select_push) > 0;

  // while ($row = mysqli_fetch_assoc($select_push)) {
  //   $currentPush = $row[$_freezePushPermissionPush];
  // }

  // if ($pushExists && $currentPush == "0") {
  //   // If permission exists, you might want to update it
  //   $update_query = "UPDATE $_freezePushPermissionTable SET $_freezePushPermissionPush = 1 WHERE $_freezePushPermissionFacId = '$facultyId' AND $_freezePushPermissionCourseId = '$courseId'";
  //   $update_result = mysqli_query($conn, $update_query);
  // } else if ($pushExists && $currentPush == "1") {
  //   // If permission exists, you might want to update it
  //   $update_query = "UPDATE $_freezePushPermissionTable SET $_freezePushPermissionPush = 0 WHERE $_freezePushPermissionFacId = '$facultyId' AND $_freezePushPermissionCourseId = '$courseId'";
  //   $update_result = mysqli_query($conn, $update_query);
  // } else {
  //   // If permission does not exist, insert a new record
  //   $insert_query = "INSERT INTO $_freezePushPermissionTable ($_freezePushPermissionFacId, $_freezePushPermissionCourseId, $_freezePushPermissionPush) 
  //       VALUES ('$facultyId', '$courseId', 1)";
  //   $insert_result = mysqli_query($conn, $insert_query);
  // }

  // header("Location: faculty_permissions.php");  // ✅ redirect with page & limit

  // exit;
}



// include_once("../header.php");

?>


<!-- Main -->
<div class="container">
  <div class="card">
    <h3 style="color:#004a99">Freeze/Unfreeze Faculty Marks</h3>

    <div id="filterBox"
      style="margin-top:30px; background:#f8f8f8; padding:15px; border-radius:8px; margin-bottom:50px;">
      <form method="GET" action="faculty_permissions.php" style="padding: 0; margin: 0;">
        <div style="display:flex; flex-wrap: wrap; gap: 10px;">

          <select name="filter_faculty_ceo" style="flex:1; padding:10px;">
            <option value="">-- Select Faculty --</option>
            <?php mysqli_data_seek($filter_faculty, 0);
            while ($row = mysqli_fetch_assoc($filter_faculty)) {
              $selected = (isset($_GET['filter_faculty_ceo']) && $_GET['filter_faculty_ceo'] == $row[$_facultyName]) ? 'selected' : '';
              echo "<option value='{$row[$_facultyName]}' $selected>{$row[$_facultyName]}</option>";
            }
            ?>
          </select>

          <select name="filter_coursecode_ceo" style="flex:1; padding:10px;">
            <option value="">-- Select Course Code --</option>
            <?php mysqli_data_seek($filter_course, 0);
            while ($row = mysqli_fetch_assoc($filter_course)) {
              $selected = (isset($_GET['filter_coursecode_ceo']) && $_GET['filter_coursecode_ceo'] == $row[$_courseCodeField]) ? 'selected' : '';
              echo "<option value='{$row[$_courseCodeField]}' $selected>{$row[$_courseCodeField]}</option>";
            }
            ?>
          </select>

          <select name="filter_coursename_ceo" style="flex:1; padding:10px;">
            <option value="">-- Select Course Name --</option>
            <?php mysqli_data_seek($filter_course, 0);
            while ($row = mysqli_fetch_assoc($filter_course)) {
              $selected = (isset($_GET['filter_coursename_ceo']) && $_GET['filter_coursename_ceo'] == $row[$_courseNameField]) ? 'selected' : '';
              echo "<option value='{$row[$_courseNameField]}' $selected>{$row[$_courseNameField]}</option>";
            }
            ?>
          </select>
        </div>
        <div style="margin-top:10px;">
          <button class="btn" type="submit">Apply Filter</button>
          <button type="button" class="btn btn-light" onclick="resetFilter()">Cancel</button>
        </div>
      </form>
    </div>


    <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
      <form action="" method="post" style="display: flex; gap: 10px; margin: 0; padding: 0;">
        <button class="btn" type="submit" name="freeze_all">Freeze All</button>
        <button class="btn" type="submit" name="unfreeze_all">Unfreeze All</button>
        <button class="btn" type="submit" name="push_all">Push All</button>
        <button class="btn" type="submit" name="unpush_all">Unpush All</button>
      </form>
    </div>


    <div class="table-responsive" style="margin-top: 20px;">
      <table class="table">
        <thead>
          <tr>
            <th style="text-align: center;">#</th>
            <th style="text-align: center; cursor:pointer; white-space: nowrap; color: white;">
              <?= sortLink('faculty_name', 'Faculty Name', $sort_field, $sort_order, $baseUrl) ?>
            </th>
            <th style="text-align: center; cursor:pointer; white-space: nowrap; color: white;">
              <?= sortLink('course_code', 'Course Code', $sort_field, $sort_order, $baseUrl) ?>
            </th>
            <th style="text-align: center; cursor:pointer; white-space: nowrap; color: white;">
              <?= sortLink('course_name', 'Course Name', $sort_field, $sort_order, $baseUrl) ?>
            </th>
            <th style="text-align: center; color: white;">CA1</th>
            <th style="text-align: center; color: white;">CA2</th>
            <th style="text-align: center; color: white;">CA3</th>
            <th style="text-align: center; color: white;">Internal</th>
            <th style="text-align: center; color: white;">Lab</th>
            <th style="text-align: center; color: white;">Freeze/Unfreeze</th>
            <th style="text-align: center; color: white;">Push/Unpush</th>
          </tr>
        </thead>

        <tbody>
          <?php mysqli_data_seek($mappingRes, 0);
          $num = 1;

          while ($row = mysqli_fetch_assoc($mappingRes)) {
            # code...
            // $row = $uniqueResults[$i];
          ?>
            <tr>
              <td style="text-align: start;"><?php echo $num++; ?></td>
              <td style="text-align: start;"><?php echo GetFacultyNameId($row[$_mappingFacultyId]); ?></td>
              <td style="text-align: center;"><?php echo GetCourseDetails($_courseCodeField, $row[$_mappingFacultyCourseId]); ?>
              </td>
              <td style="text-align: center;"><?php echo GetCourseDetails($_courseNameField, $row[$_mappingFacultyCourseId]); ?>
              </td>
              <!-- <td style="text-align: center;"> -->
              <?php
              $select_permission_sql = "SELECT * FROM $_freezePushPermissionTable WHERE $_freezePushPermissionFacId = '$row[$_mappingFacultyId]' AND $_freezePushPermissionCourseId = '$row[$_mappingFacultyCourseId]'";
              $select_permission = mysqli_query($conn, $select_permission_sql);
              $permissionExists = mysqli_num_rows($select_permission) > 0;

              // echo "<br>$select_permission_sql = permissionExists: $permissionExists | ";
              // echo "<br>$select_permission_sql = permissionExists: $permissionExists | ";
              if ($permissionExists) {
                $permRow = mysqli_fetch_assoc($select_permission);
                mysqli_data_seek($select_permission, 0);
              ?>

                <form method="post">
                  <input type="hidden" name="ent_id" value="<?php echo $permRow[$_freezePushPermissionId]; ?>">

                  <?php while ($permRow = mysqli_fetch_assoc($select_permission)) {
                    $ctype = GetCourseDetails($_courseTypeField, $permRow[$_freezePushPermissionCourseId]);
                    $ccode = GetCourseDetails($_courseCodeField, $permRow[$_freezePushPermissionCourseId]);

                    $isUG = (int)$ccode[3] <= 4 ? 1 : 0;
                    $isCa1 = ($ctype == 1 || $ctype == 3);
                    $isCa2 = ($ctype == 1 || $ctype == 3);
                    $isCa3 = (($ctype == 1 || $ctype == 3) && ($isUG));
                    $isInternal = ($ctype == 1 || $ctype == 3);
                    $isLab = ($ctype == 2 || $ctype == 3);

                    // echo "$ccode<br>";
                    // echo "$isUG || $isCa1 || $isCa2 || $isCa3 || $isInternal || $isLab<br>";
                    for ($i = 0; $i < count($freezeEntityArray); $i++) {
                      $isFreeze = $permRow[$freezeEntityArray[$i]];
                      $isPush = $permRow[$pushEntityArray[$i]];


                      $isShowExam = $i == 0 ? $isCa1 : ($i == 1 ? $isCa2 : ($i == 2 ? $isCa3 : ($i == 3 ? $isInternal : ($i == 4 ? $isLab : FALSE))));
                  ?>
                      <td style="text-align: center;">
                        <?php if ($isShowExam) { ?>
                          <button class="btn btn-danger" type="submit" name="<?php echo $freezeEntityArray[$i]; ?>" style="width: 100px; height: 38px;"> <?php echo $isFreeze == "1" ? "Freeze" : "UnFreeze"; ?> </button>
                          <button class="btn btn-light" type="submit" name="<?php echo $pushEntityArray[$i]; ?>" style="width: 100px; height: 38px;"> <?php echo $isPush == "1" ? "Unpush" : "Push"; ?> </button>
                        <?php } else {
                          echo "-";
                        } ?>
                      </td>
                  <?php }
                  } ?>

                  <td style="text-align: center;">
                    <button class="btn btn-danger" type="submit" name="Freeze" style="width: 100px; height: 38px;">Freeze</button>
                    <button class="btn btn-danger" type="submit" name="Unfreeze" style="width: 100px; height: 38px;">Unfreeze</button>
                  </td>
                  <td style="text-align: center;">
                    <button class="btn btn-light" type="submit" name="Push" style="width: 100px; height: 38px;">Push</button>
                    <button class="btn btn-light" type="submit" name="Unpush" style="width: 100px; height: 38px;">Unpush</button>
                  </td>

                </form>

              <?php
              } else {
                echo "Active";
              }
              ?>

            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <!-- ✅ pagination UI -->
    <?php
    // echo "####$currentPage####<br>";
    $totalRecords = paginationUI3($conn, $_mappingFacultyTable, $currentPage, $currentLimit, $whereSQL, $filterQuery);
    // Show record count under table
    if ($totalRecords > 0) {
      // $startRecord = 1;
      // echo "currentPage = $currentPage = ".gettype($currentPage);
      // echo "<br>";
      // echo "currentLimit = $currentLimit = ".gettype($currentLimit);
      // exit;
      $startRecord = ($currentPage - 1) * $currentLimit + 1;
      $endRecord = min($startRecord + $currentLimit - 1, $totalRecords);
      echo "<div style='margin-top:10px; font-weight:bold;'>";
      echo "Showing $startRecord to $endRecord of $totalRecords records";
      echo "</div>";
    }
    ?>
  </div>
</div>

<script>
  function resetFilter() {
    window.location.href = "faculty_permissions.php";
  }
</script>

<?php
include_once("../footer.php");
?>