<?php

// header('Content-Type: application/json');
// error_reporting(0); // or better: error_reporting(E_ALL); ini_set('display_errors', 0);
// ob_start(); // buffer output so no whitespace causes errors

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

use function PHPSTORM_META\type;

include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 3) {
  header("Location: ../");
  exit;
}


$facLgnId = str_replace($defaultLoginExtension, "", $_SESSION[$_session_login_name] ?? "");



$btnfldbtnfld = ["marksEntryCa1", "marksEntryCa2", "marksEntryCa3", "marksEntryInternal", "marksEntryLab"];

$fieldNameForMarksEntry = "";
$maxMarksForMarksEntry = 0;

if (isset($_POST[$btnfldbtnfld[0]])) {
  $fieldNameForMarksEntry = "CA1";
  $selectFromResultTableField = $_resultSemesterCa1;
  $isCa1 = $_POST["isCa1"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 1) ? 50 : (($isUG && $isType == 3) ? 50 : ((!$isUG && $isType == 1) ? 50 : ((!$isUG && $isType == 3) ? 50 : 0)));

  $_SESSION["MarksEnteredField"] = $_resultSemesterCa1;

  // $s = SelectStudentOfSelectedCurse();
  // $selectCourseStudent = mysqli_query($conn, $s);
  // CreateQueryForGetStudenbtPush();
  $_SESSION["courseOpenBtn"] = 1;
  $_SESSION["courseOpenBtn1"] = 1;
  // exit;
}

if (isset($_POST[$btnfldbtnfld[1]])) {
  $fieldNameForMarksEntry = "CA2";
  $selectFromResultTableField = $_resultSemesterCa2;
  $isCa2 = $_POST["isCa2"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 1) ? 50 : (($isUG && $isType == 3) ? 50 : ((!$isUG && $isType == 1) ? 50 : ((!$isUG && $isType == 3) ? 50 : 0)));

  $_SESSION["MarksEnteredField"] = $_resultSemesterCa2;

  // $s = SelectStudentOfSelectedCurse();
  // $selectCourseStudent = mysqli_query($conn, $s);
  // CreateQueryForGetStudenbtPush();
  $_SESSION["courseOpenBtn"] = 2;
  $_SESSION["courseOpenBtn1"] = 2;

}

if (isset($_POST[$btnfldbtnfld[2]])) {
  $fieldNameForMarksEntry = "CA3";
  $selectFromResultTableField = $_resultSemesterCa3;
  $isCa3 = $_POST["isCa3"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 1) ? 50 : (($isUG && $isType == 3) ? 50 :  0);

  $_SESSION["MarksEnteredField"] = $_resultSemesterInternalMarks;

  // $s = SelectStudentOfSelectedCurse();
  // $selectCourseStudent = mysqli_query($conn, $s);
  // CreateQueryForGetStudenbtPush();
  $_SESSION["courseOpenBtn"] = 3;
  $_SESSION["courseOpenBtn1"] = 3;
}

if (isset($_POST[$btnfldbtnfld[3]])) {
  $fieldNameForMarksEntry = "Internal";
  $selectFromResultTableField = $_resultSemesterInternalMarks;
  $isInternal = $_POST["isInternal"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 1) ? 25 : (($isUG && $isType == 2) ? 10 : ((!$isUG && $isType == 1) ? 20 : ((!$isUG && $isType == 2) ? 10 : 0)));

  $_SESSION["MarksEnteredField"] = $_resultSemesterCa3;

  // $s = SelectStudentOfSelectedCurse();
  // $selectCourseStudent = mysqli_query($conn, $s);
  // CreateQueryForGetStudenbtPush();
  $_SESSION["courseOpenBtn"] = 4;
  $_SESSION["courseOpenBtn1"] = 4;
}

if (isset($_POST[$btnfldbtnfld[4]])) {
  $fieldNameForMarksEntry = "Lab";
  $selectFromResultTableField = $_resultSemesterPracticalMarks;
  $isLab = $_POST["isLab"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 2) ? 100 : (($isUG && $isType == 3) ? 30 : ((!$isUG && $isType == 2) ? 100 : ((!$isUG && $isType == 3) ? 30 : 0)));

  $_SESSION["MarksEnteredField"] = $_resultSemesterPracticalMarks;

  // $s = SelectStudentOfSelectedCurse();
  // $selectCourseStudent = mysqli_query($conn, $s);
  // CreateQueryForGetStudenbtPush();
  $_SESSION["courseOpenBtn"] = 5;
  $_SESSION["courseOpenBtn1"] = 5;
}

function SelectStudentOfSelectedCurse()
{
  global $conn;
  global $_mappingTable, $_mapId, $_facId, $_slotYear, $_semesterType, $_crseId;
  global $freezePushPermissionTable, $freezePushPermissionFacId, $freezePushPermissionCourseId;
  global $_resultSemesterResultYear, $_resultSemesterResultSemType, $_resultSemesterStdCrseId;
  global $_facultyId;

  $selectCourseId =  $_POST['hiddenMappingId'];
  $facId = GetFacultyDetailCellData($_facultyId);

  $mapTableSingleRowSelect = mysqli_query($conn, "SELECT * FROM $_mappingTable WHERE $_mapId='$selectCourseId'");
  $mapTableSingleRowRes = mysqli_fetch_assoc($mapTableSingleRowSelect);

  $facId = $mapTableSingleRowRes[$_facId];
  // echo "$academicYear<br>";
  $academicYear = $mapTableSingleRowRes[$_slotYear];
  // echo "$academicYear<br>";
  // exit;
  $semNo = $mapTableSingleRowRes[$_semesterType];
  $selectCourseId = $mapTableSingleRowRes[$_crseId];

  $stuResId = $selectCourseId;
  $_SESSION['course'] = $stuResId;

  $sql = "SELECT * FROM $_mappingTable WHERE $_facId='$facId' AND $_slotYear='$academicYear' AND $_semesterType='$semNo' AND $_crseId='$selectCourseId'";

  return $sql;
}
$isUnFreeze = TRUE;

if (isset($_POST['freeze_me'])) {
  $fac = GetFacultyDetailCellData($_facultyId);
  $crse = $_SESSION['course'];
  $examName = $_SESSION["courseOpenBtn1"] ?? 0;
  // echo "$examName<br>";
  $examName = $examName == 1 ? "$freezePushPermissionFreezeCa1" : ($examName == 2 ? "$freezePushPermissionFreezeCa2" : ($examName == 3 ? "$freezePushPermissionFreezeCa3" : ($examName == 4 ? "$freezePushPermissionFreezeLab" : ($examName == 5 ? "$freezePushPermissionFreezeInternal" : ""))));
  
  // echo "$examName";
  // exit;

  $status = 0;
  $sql = "UPDATE $freezePushPermissionTable SET $examName = '0' WHERE $freezePushPermissionFacId='$fac' AND $freezePushPermissionCourseId='$crse'";
  // echo "$sql";
  // exit;
  mysqli_query($conn, $sql);
  header("location: faculty_marks.php");
  // echo "$sql";
  // exit;
}

if (isset($_POST['loadCourse'])) {
  $academicYear = $_POST['yearSelect'];
  $semSelect = $_POST['semSelect'];
  $semNo = $semSelect == "Fall" ? 1 : ($semSelect == "Summer" ? 2 : 0);
  $_SESSION['year'] = $academicYear;
  $_SESSION['sem'] = $semNo;

  // echo $academicYear;
  // exit;

  $facId = GetFacultyDetailCellData($_facultyId);

  $selectCourse = "SELECT * FROM $_mappingTable WHERE $_facId='$facId' AND $_slotYear='$academicYear' AND $_semesterType='$semNo'";

  // echo $selectCourse;
  // exit;
  $selectCourseRes = mysqli_query($conn, $selectCourse);

  $facultyCourseId = array();
  $facultyCourseMappingTableId = array();

  $TempCoursePrintingIdsUnique = array();

  while ($courseResData = mysqli_fetch_assoc($selectCourseRes)) {
    if (!in_array($courseResData[$_crseId], $facultyCourseId)) {
      $TempCoursePrintingIdsUnique[] = $courseResData[$_mapId];
    }

    $facultyCourseId[] = $courseResData[$_crseId];
    $facultyCourseMappingTableId[] = $courseResData[$_mapId];
  }
  $facultyCourseId = array_unique($facultyCourseId);
  if (count($facultyCourseId) > 0) {
    $ids = implode(',', $facultyCourseId);
  } else {
    $ids = 0;
  }
  $facultyCourse = mysqli_query($conn, "SELECT * FROM $_coursesTable WHERE FIND_IN_SET($_courseId, '$ids') > 0 ORDER BY FIND_IN_SET($_courseId, '$ids')");
}

$stuResWhere = "";
$stuResId = 0;
if (isset($_SESSION['courseOpenBtn'])) {
  unset($_SESSION['courseOpenBtn']);
  $selectCourseId =  $_POST['hiddenMappingId'];
  $facId = GetFacultyDetailCellData($_facultyId);

  $mapTableSingleRowSelect = mysqli_query($conn, "SELECT * FROM $_mappingTable WHERE $_mapId='$selectCourseId'");
  $mapTableSingleRowRes = mysqli_fetch_assoc($mapTableSingleRowSelect);

  $facId = $mapTableSingleRowRes[$_facId];
  // echo "$academicYear<br>";
  $academicYear = $mapTableSingleRowRes[$_slotYear];
  // echo "$academicYear<br>";
  // exit;
  $semNo = $mapTableSingleRowRes[$_semesterType];
  $selectCourseId = $mapTableSingleRowRes[$_crseId];

  $stuResId = $selectCourseId;
  $_SESSION['course'] = $stuResId;

  $sql = "SELECT * FROM $_mappingTable WHERE $_facId='$facId' AND $_slotYear='$academicYear' AND $_semesterType='$semNo' AND $_crseId='$selectCourseId'";
  $selectCourseStudent = mysqli_query($conn, $sql);

  $freezeCourse = "SELECT * FROM $freezePushPermissionTable WHERE $freezePushPermissionFacId='$facId' AND $freezePushPermissionCourseId='$selectCourseId'";
  $freezeCourseRes = mysqli_query($conn, $freezeCourse);
  $freezeCourseRes = mysqli_fetch_assoc($freezeCourseRes);
  $f = str_replace("_", "", $selectFromResultTableField);
  $f = str_replace("practicalmarks", "lab", $f);
  $f = str_replace("internalmarks", "internal", $f);
  $f = $f . "_freeze";
  $isUnFreeze = $freezeCourseRes[$f];

  $stuResWhere = "$_resultSemesterResultYear='$academicYear' AND $_resultSemesterResultSemType='$semNo' AND $_resultSemesterStdCrseId='$selectCourseId'";
}

function GetCourseTPC($field)
{
  global $conn, $_coursesTable, $stuResId;
  global $_courseId, $_courseOwnerField, $_courseCodeField, $_courseNameField, $_courseTypeField, $_courseTheoryMarksField, $_coursePracticalMarksField, $_courseCreditMarksField;

  $slct = "SELECT $field FROM $_coursesTable WHERE $_courseId = '$stuResId'";
  $res = mysqli_query($conn, $slct);
  $data = mysqli_fetch_assoc($res);
  return $data[$field] ?? null;
}

function GetProgramField($field, $id)
{
  global $conn, $programTableName;
  global $programId, $programNameField, $programSemField, $programDeptField, $graduationTypeField;

  $slct = "SELECT $field FROM $programTableName WHERE $programId = '$id'";
  $res = mysqli_query($conn, $slct);
  $data = mysqli_fetch_assoc($res);
  return $data[$field] ?? null;
}



function GetFacultyDetailCellData($field)
{
  global $conn, $_facultyTable;
  global $_facultyId, $_facultyCode, $_facultyName, $_facultyDepartment, $_facultyEmail, $_facultyJoinDate, $_facultyPassword;
  global $facLgnId;

  $sql = "SELECT * FROM $_facultyTable WHERE $_facultyCode = '$facLgnId'";
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$field] ?? null;
}

function GetDepartmentDetailCellData($id)
{
  global $conn, $_deptTable;
  global $_deptId, $_deptName;

  $sql = "SELECT * FROM $_deptTable WHERE $_deptId = '$id'";
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$_deptName] ?? null;
}

function GetCourseDetailCellData($field, $id)
{
  global $conn, $_coursesTable;
  global $_courseId, $_courseOwnerField, $_courseCodeField, $_courseNameField, $_courseTypeField, $_courseTheoryMarksField, $_coursePracticalMarksField, $_courseCreditMarksField;

  $sql = "SELECT * FROM $_coursesTable WHERE $_courseId = '$id'";

  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$field] ?? null;
}

function GetStudentDetailCellData($field, $id)
{
  global $conn, $_studentTable;
  global $_courseId;

  $sql = "SELECT * FROM $_studentTable WHERE $_courseId = '$id'";

  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$field] ?? null;
}


function GetStudentResult($stuId)
{
  global $conn, $_resultSemesterTable;
  global $_resultSemesterResultYear, $_resultSemesterResultSemType, $_resultSemesterStdCrseId, $selectCourseId, $_resultSemesterStdDtlId;
  global $stuResWhere;

  if ($stuResWhere != "")
    $stuResWhere = $stuResWhere;

  $condition = $stuResWhere . " AND $_resultSemesterStdDtlId='$stuId'";

  $sqlResult = "SELECT * FROM $_resultSemesterTable WHERE $condition";

  // echo "$stuResWhere<br>";
  // echo "$condition<br>";
  // echo "$sqlResult<br>";
  $resultData = mysqli_query($conn, $sqlResult);

  // print("<pre>");
  // echo "$sqlResult";
  // echo "<br>";
  // print_r($resultData);
  // $resultData = mysqli_fetch_all($resultData);
  // print_r($resultData);
  // echo "<br>";
  // exit;

  return $resultData;
}

include_once("../header.php");

?>

<div class="container">
  <div class="page-header">
    <h3>Marks</h3>
    <div class="breadcrumb-box">
      <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Faculty</span></a>
      <span class="sep">></span>
      <span class="crumb-link" style="pointer-events:none;opacity:.6">Marks</span>
    </div>
  </div>

  <div class="card">
    <div class="small-muted">Select Academic Year and Semester</div>

    <form method="POST">
      <select name="yearSelect" id="yearSelect">
        <option name="" value="">Academic Year</option>
        <?php $currentYear = date('Y');
        for ($i = 0; $i < 3; $i++) {
          $startYear = ($currentYear - $i);
          $acyr = $startYear;
        ?>
          <option value="<?php echo $acyr; ?>"><?php echo $acyr; ?></option>
        <?php } ?>
      </select>

      <select name="semSelect" id="semSelect">
        <option name="" value="">Semester</option>
        <option value="Fall">Fall</option>
        <option value="Summer">Summer</option>
      </select>

      <button class="btn" name="loadCourse">Load</button>
    </form>
  </div>

  <!-- Courses List -->
  <div id="courseWrap" style="display:none;" class="card">
    <h3 style="color:#004a99; margin-bottom: 10px;">Courses Assigned</h3>
    <div id="courseList"></div>
  </div>


  <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->

  <!-- Marks Entry -->
  <?php if (isset($facultyCourse)) {
    $num = 0;
    while ($data = mysqli_fetch_assoc($facultyCourse)) { ?>
      <div id="marksWrap" style="display:block;" class="course-card">
        <form method="POST">
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
              <strong><?php echo $data[$_courseCodeField]; ?></strong><br>
              <span class="small-muted"><?php echo $data[$_courseNameField]; ?></span>
            </div>
            <?php

            $ctype = (int)$data[$_courseTypeField];

            $isUG = (int)$data[$_courseCodeField][3] <= 4 ? 1 : 0;
            $isCa1 = ($ctype == 1 || $ctype == 3);
            $isCa2 = ($ctype == 1 || $ctype == 3);
            $isCa3 = (($ctype == 1 || $ctype == 3) && ($isUG));
            $isInternal = ($ctype == 1 || $ctype == 3);
            $isLab = ($ctype == 2 || $ctype == 3);

            $facFrz = $facId;
            $crsFrz = $data[$_courseId];
            $FreezeRecords = "SELECT * FROM $freezePushPermissionTable WHERE $freezePushPermissionFacId='$facFrz' AND $freezePushPermissionCourseId='$crsFrz' AND $freezePushPermissionAcademicYear='$academicYear' AND $freezePushPermissionAcademicSem='$semNo'";
            $frezzeRecordsData = mysqli_query($conn, $FreezeRecords);
            $frezzeRecordsData = mysqli_fetch_assoc($frezzeRecordsData);

            $isCa1Freze = $frezzeRecordsData[$freezePushPermissionFreezeCa1];
            $isCa2Freeze = $frezzeRecordsData[$freezePushPermissionFreezeCa2];
            $isCa3Freeze = $frezzeRecordsData[$freezePushPermissionFreezeCa3];
            $isLabFreeze = $frezzeRecordsData[$freezePushPermissionFreezeLab];
            $isInternalFreeze = $frezzeRecordsData[$freezePushPermissionFreezeInternal];
            ?>

            <input type="hidden" name="hiddenMappingId" value="<?php echo $TempCoursePrintingIdsUnique[$num++]; ?>" id="">
            <input type="hidden" name="isUG" value="<?php echo $isUG; ?>" />
            <input type="hidden" name="isType" value="<?php echo $ctype; ?>" />
            <?php if ($isCa1) { ?>
              <button <?= !$isCa1Freze ? 'disabled' : ''; ?> class="btn" name="<?php echo $btnfldbtnfld[0]; ?>" style="margin-left:16px;">CA1</button>
              <input type="hidden" name="isCa1" value="<?php echo $isCa1; ?>" />
            <?php } ?>

            <?php if ($isCa2) { ?>
              <button <?= !$isCa2Freeze ? 'disabled' : ''; ?> class="btn" name="<?php echo $btnfldbtnfld[1]; ?>" style="margin-left:16px;">CA2</button>
              <input type="hidden" name="isCa2" value="<?php echo $isCa2; ?>" />
            <?php } ?>

            <?php if ($isCa3) { ?>
              <button <?= !$isCa3Freeze ? 'disabled' : ''; ?> class="btn" name="<?php echo $btnfldbtnfld[2]; ?>" style="margin-left:16px;">CA3</button>
              <input type="hidden" name="isCa3" value="<?php echo $isCa3; ?>" />
            <?php } ?>

            <?php if ($isInternal) { ?>
              <button <?= !$isInternalFreeze ? 'disabled' : ''; ?> class="btn" name="<?php echo $btnfldbtnfld[3]; ?>" style="margin-left:16px;">Internal</button>
              <input type="hidden" name="isInternal" value="<?php echo $isInternal; ?>" />
            <?php } ?>

            <?php if ($isLab) { ?>
              <button <?= !$isLabFreeze ? 'disabled' : ''; ?> class="btn" name="<?php echo $btnfldbtnfld[4]; ?>" style="margin-left:16px;">Lab</button>
              <input type="hidden" name="isLab" value="<?php echo $isLab; ?>" />
            <?php } ?>
            <?php ?>
          </div>
        </form>
      </div>
  <?php }
  } ?>

  <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->


  <?php if (isset($selectCourseStudent)) : ?>
    <div id="marksWrap" style="display:block;" class="course-card">
      <div><?= GetCourseTPC($_courseCodeField) . " - " . GetCourseTPC($_courseNameField); ?></div>

      <!-- Form for bulk actions -->
      <form method="POST">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
          <button class="btn" <?= !$isUnFreeze ? 'disabled' : ''; ?> type="submit" name="freeze_me">Freeze Me</button>
          <button class="btn" <?= !$isUnFreeze ? 'disabled' : ''; ?> id="saveAllBtn" type="submit" name="">Save All</button>
        </div>

        <table class="table">
          <thead id="marksHeader">
            <tr>
              <?php
              mysqli_data_seek($selectCourseStudent, 0);
              $selectCourseStudentData = mysqli_fetch_assoc($selectCourseStudent);
              $proId = GetStudentDetailCellData($_studentProgram, $selectCourseStudentData[$_stuId]);

              $isUG = GetProgramField($graduationTypeField, $proId) == 1;
              $courseTPC = GetCourseTPC($_courseTypeField);
              $isTheory = $courseTPC == 1;
              $isPractical = $courseTPC == 2;
              $isBoth = $courseTPC == 3;
              $creditTotal = GetCourseTPC($_courseCreditMarksField);
              ?>

              <td style="text-align: center;">Enrollment No.</td>
              <td style="text-align: center;">Name</td>

              <td style="text-align: center;"><?php echo "$fieldNameForMarksEntry ($maxMarksForMarksEntry)"; ?></td>
              <td style="text-align: center;">Remarks</td>
              <td style="text-align: center;"></td>
            </tr>
          </thead>

          <tbody id="marksBody">
            <?php
            mysqli_data_seek($selectCourseStudent, 0);
            // mysqli_data_seek($resultQuery, 0);
            $RESULTDATAARRAY = [];

            // print("<pre>");
            // print_r($selectCourseStudent);
            // $selectCourseStudent = mysqli_fetch_all($selectCourseStudent);
            // print_r($selectCourseStudent);
            // echo "";
            // exit;
            // mysqli_data_seek($selectCourseStudent, 0);

            while ($selectCourseStudentData = mysqli_fetch_assoc($selectCourseStudent)) :
              $crs = $selectCourseStudentData[$_crseId]; // find course id from mapping table
              $yr = $selectCourseStudentData[$_slotYear]; // find year from mapping table
              $typ = $selectCourseStudentData[$_semesterType]; // find semester type from mapping table

              $resultQuery = GetStudentResult($selectCourseStudentData[$_stuId]); // get student data object from student id which is find from mapping table

              // print("<pre>");
              // print_r($resultQuery);
              // echo "";
              // exit;


              if ($resultQuery === false) {
                // Handle error: log it, show user message, or set default
                error_log("Query failed for student ID: " . $_stuId);
                $result = null; // or array('error' => 'No data found')
              } else {
                $result = mysqli_fetch_assoc($resultQuery);
              }

              $MAP = $selectCourseStudentData[$_mapId];
              $STDDTLID = $selectCourseStudentData[$_stuId];
              $STDCRSEID = $selectCourseStudentData[$_crseId];
              $RESULTID = $result[$_resultSemesterId] ?? 0;


              $ENNO = GetStudentDetailCellData($_studentCode, $STDDTLID);
              $NAME = GetStudentDetailCellData($_studentName, $STDDTLID);

              $obj = new ResultData();
              $obj->mapId = $MAP;
              $obj->stdDtlId = $STDDTLID;
              $obj->stdCrseId = $STDCRSEID;
              $obj->resultId = $RESULTID;


              $obj->enNo = $ENNO;
              $obj->name = $NAME;

              $MARKS = $result[$selectFromResultTableField] ?? 0;
              $REMARKS = $result[$_resultSemesterResultRemarks] ?? "";

              $RESULTDATAARRAY[] = $obj;
              $_SESSION['RESULTDATAARRAY'] = $RESULTDATAARRAY;
            ?>
              <tr>
                <!-- Individual form for each student's Save button -->
                <form method="POST" class="markEntryForm">
                  <input type="hidden" class="id" name="id" value="<?= $MAP; ?>">
                  <input type="hidden" class="stdDtlId" name="stdDtlId" value="<?= $STDDTLID; ?>">
                  <input type="hidden" class="stdCrseId" name="stdCrseId" value="<?= $STDCRSEID; ?>">
                  <input type="hidden" class="resultId" name="resultId" value="<?= $RESULTID; ?>">
                  <input type="hidden" class="semYear" name="semYear" value="<?= $yr; ?>">
                  <input type="hidden" class="semType" name="semType" value="<?= $typ; ?>">

                  <td style="text-align: center;" class="enNo" name="enNo"><?= htmlspecialchars($ENNO); ?></td>
                  <td style="text-align: start;" class="stdName" name="stdName"><?= htmlspecialchars($NAME); ?></td>

                  <td style="text-align: center;">
                    <input class="marks" <?= !$isUnFreeze ? 'disabled' : ''; ?> type="number" min="0" max="<?php echo $maxMarksForMarksEntry; ?>" name="marks" value="<?= $MARKS; ?>">
                  </td>

                  <td style="text-align: center;">
                    <input class="remarks" <?= !$isUnFreeze ? 'disabled' : ''; ?> type="text" name="remarks" style="width: 160px;" value="<?= htmlspecialchars($REMARKS); ?>">
                  </td>

                  <td style="text-align: center;">
                    <button <?= !$isUnFreeze ? 'disabled' : ''; ?> type="button" class="save btn" id="save_result" onclick="SaveResult(this.closest('tr'))">
                      <span class="button-text">Save</span>
                      <span class="spinner" style="display: none;">...</span>
                    </button>
                  </td>
                </form>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </form>
    </div>
  <?php endif; ?>



  <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->


</div>







<?php include_once("../footer.php"); ?>

<!-- SINGLE RECORD -->

<script>
  function SaveResult(trElem) {
    // const data = {
    //   id: trElem.querySelector('input[name="id"]')?.value || 0,
    //   stdDtlId: trElem.querySelector('input[name="stdDtlId"]')?.value || 0,
    //   stdCrseId: trElem.querySelector('input[name="stdCrseId"]')?.value || 0,
    //   resultId: trElem.querySelector('input[name="resultId"]')?.value || 0,

    //   ca1: trElem.querySelector('input[name="ca1"]')?.value || 0,
    //   ca2: trElem.querySelector('input[name="ca2"]')?.value || 0,
    //   ca3: trElem.querySelector('input[name="ca3"]')?.value || 0,
    //   practical: trElem.querySelector('input[name="practical"]')?.value || 0,
    //   internal: trElem.querySelector('input[name="internal"]')?.value || 0,
    //   ttlCredit: trElem.querySelector('input[name="ttlCredit"]')?.value || 0,
    //   remarks: trElem.querySelector('input[name="remarks"]')?.value || ''
    // };


    const data = {
      id: trElem.querySelector('input[name="id"]')?.value || 0,
      stdDtlId: trElem.querySelector('input[name="stdDtlId"]')?.value || 0,
      stdCrseId: trElem.querySelector('input[name="stdCrseId"]')?.value || 0,
      resultId: trElem.querySelector('input[name="resultId"]')?.value || 0,
      semYear: trElem.querySelector('input[name="semYear"]')?.value || 0,
      semType: trElem.querySelector('input[name="semType"]')?.value || 0,

      marks: trElem.querySelector('input[name="marks"]')?.value || 0,
      remarks: trElem.querySelector('input[name="remarks"]')?.value || ''
    };
    // console.log("Hello.........")

    console.log('Row Data:', data); // All field values for this row
    $msg = "";
    // Now send data to PHP via AJAX or fetch as needed
    fetch('single_record.php', {
        method: 'POST',
        body: JSON.stringify({
          students: data
        }),
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          students: data
        })
      })
      .then(response => response.json())
      .then(result => {
        if (result.redirect) {
          window.location.href = result.redirect; // redirect without refresh
        } else if (result.success) {
          $msg = ('Marks saved successfully.');
          window.location.reload(true); // deprecated, supported only in Firefox
        } else if (result.error) {
          $msg = ('Error: ' + result.error);
          window.location.reload(true); // deprecated, supported only in Firefox
        } else {
          $msg = ('Unknown error occurred.');
          window.location.reload(true); // deprecated, supported only in Firefox
        }
      })
      .catch(err => {
        $msg = ('Error submitting marks: ' + err.message);
        // console.log(result.redirect)
        window.location.reload(true); // deprecated, supported only in Firefox
        // console.log($msg);
      });
  };
</script>



<!-- SAVE ALL RECORDS -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('saveAllBtn').onclick = function(e) {
      e.preventDefault();

      let data = [];
      let rows = document.querySelectorAll('#marksBody tr');
      rows.forEach(function(row, idx) {
        let record = {
          id: row.querySelector('.id') ? row.querySelector('.id').value : '',
          stdDtlId: row.querySelector('.stdDtlId') ? row.querySelector('.stdDtlId').value : '',
          stdCrseId: row.querySelector('.stdCrseId') ? row.querySelector('.stdCrseId').value : '',
          resultId: row.querySelector('.resultId') ? row.querySelector('.resultId').value : '',
          semYear: row.querySelector('.semYear') ? row.querySelector('.semYear').value : '',
          semType: row.querySelector('.semType') ? row.querySelector('.semType').value : '',
          marks: row.querySelector('.marks') ? row.querySelector('.marks').value : '',
          remarks: row.querySelector('.remarks') ? row.querySelector('.remarks').value : ''

          // enNo: row.querySelector('.enNo') ? row.querySelector('.enNo').innerText : '',
          // stdName: row.querySelector('.stdName') ? row.querySelector('.stdName').innerText : '',
          // ca1: row.querySelector('.ca1') ? row.querySelector('.ca1').value : '',
          // ca2: row.querySelector('.ca2') ? row.querySelector('.ca2').value : '',
          // ca3: row.querySelector('.ca3') ? row.querySelector('.ca3').value : '',
          // practical: row.querySelector('.practical') ? row.querySelector('.practical').value : '',
          // internal: row.querySelector('.internal') ? row.querySelector('.internal').value : '',
          // ttlCredit: row.querySelector('.ttlCredit') ? row.querySelector('.ttlCredit').value : '',
        };

        data.push(record);
        console.log('Student #' + (idx + 1), record);
      });

      // Send data to PHP via AJAX:
      fetch('save_marks_ajax.php', {
          method: 'POST',
          body: JSON.stringify({
            students: data
          }),
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            students: data
          })
        })
        .then(response => response.json())
        .then(result => {
          if (result.redirect) {
            window.location.href = result.redirect; // redirect without refresh
          } else if (result.success) {
            $msg = ('Marks saved successfully.');
            window.location.reload(true); // deprecated, supported only in Firefox
          } else if (result.error) {
            $msg = ('Error: ' + result.error);
            window.location.reload(true); // deprecated, supported only in Firefox
          } else {
            $msg = ('Unknown error occurred.');
            window.location.reload(true); // deprecated, supported only in Firefox
          }
        })
        .catch(err => {
          $msg = ('Error submitting marks: ' + err.message);
          window.location.reload(true); // deprecated, supported only in Firefox
        });
    };
  });
</script>





<?php

class ResultData
{
  public $mapId;
  public $stdDtlId;
  public $stdCrseId;
  public $resultId;

  public $enNo;
  public $name;

  public $ca1;
  public $ca2;
  public $ca3;

  public $practical;
  public $internal;
  public $ttlCredit;
  public $remarks;
}
?>