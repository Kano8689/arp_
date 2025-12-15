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
  $selectFromResultTableField = $_SESSION["MarksEnteredField"] = $_resultCa1;

  $isCa1 = $_POST["isCa1"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 1) ? 50 : (($isUG && $isType == 3) ? 50 : ((!$isUG && $isType == 1) ? 50 : ((!$isUG && $isType == 3) ? 50 : 0)));

  $_SESSION["courseOpenBtn"] = 1;
  $_SESSION["courseOpenBtn1"] = 1;
  // exit;
}

if (isset($_POST[$btnfldbtnfld[1]])) {
  $fieldNameForMarksEntry = "CA2";
  $selectFromResultTableField = $_SESSION["MarksEnteredField"] = $_resultCa2;

  $isCa2 = $_POST["isCa2"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 1) ? 50 : (($isUG && $isType == 3) ? 50 : ((!$isUG && $isType == 1) ? 50 : ((!$isUG && $isType == 3) ? 50 : 0)));

  $_SESSION["courseOpenBtn"] = 2;
  $_SESSION["courseOpenBtn1"] = 2;
}

if (isset($_POST[$btnfldbtnfld[2]])) {
  $fieldNameForMarksEntry = "CA3";
  $selectFromResultTableField = $_SESSION["MarksEnteredField"] = $_resultCa3;

  $isCa3 = $_POST["isCa3"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 1) ? 50 : (($isUG && $isType == 3) ? 50 :  0);

  $_SESSION["courseOpenBtn"] = 3;
  $_SESSION["courseOpenBtn1"] = 3;
}

if (isset($_POST[$btnfldbtnfld[3]])) {
  $fieldNameForMarksEntry = "Internal";
  $selectFromResultTableField = $_SESSION["MarksEnteredField"] = $_resultInternalMarks;

  $isInternal = $_POST["isInternal"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 1) ? 25 : (($isUG && $isType == 2) ? 10 : ((!$isUG && $isType == 1) ? 20 : ((!$isUG && $isType == 2) ? 10 : 0)));

  $_SESSION["courseOpenBtn"] = 4;
  $_SESSION["courseOpenBtn1"] = 4;
}

if (isset($_POST[$btnfldbtnfld[4]])) {
  $fieldNameForMarksEntry = "Lab";
  $selectFromResultTableField = $_SESSION["MarksEnteredField"] = $_resultLabMarks;

  $isLab = $_POST["isLab"];
  $isUG = $_POST["isUG"];
  $isType = $_POST["isType"];
  $maxMarksForMarksEntry = ($isUG && $isType == 2) ? 100 : (($isUG && $isType == 3) ? 30 : ((!$isUG && $isType == 2) ? 100 : ((!$isUG && $isType == 3) ? 30 : 0)));

  $_SESSION["courseOpenBtn"] = 5;
  $_SESSION["courseOpenBtn1"] = 5;
}

function SelectStudentOfSelectedCurse()
{
  global $conn;
  global $_mappingFacultyTable, $_mappingFacultyTblId, $_mappingFacultyId, $_mappingFacultyId, $_mappingFacultySemesterType, $_mappingFacultyCourseId;
  global $_freezePushPermissionTable, $_freezePushPermissionFacId, $_freezePushPermissionCourseId;
  global $_resultLabMarks, $_resultResultSemType, $_resultStdCrseId;
  global $_facultyId;

  $selectCourseId =  $_POST['hiddenMappingId'];
  $facId = GetFacultyDetailCellData($_facultyId);

  $mapTableSingleRowSelect = mysqli_query($conn, "SELECT * FROM $_mappingFacultyTable WHERE $_mappingFacultyTblId='$selectCourseId'");
  $mapTableSingleRowRes = mysqli_fetch_assoc($mapTableSingleRowSelect);

  $facId = $mapTableSingleRowRes[$_mappingFacultyId];
  // echo "$academicYear<br>";
  $academicYear = $mapTableSingleRowRes[$_mappingFacultyId];
  // echo "$academicYear<br>";
  // exit;
  $semNo = $mapTableSingleRowRes[$_mappingFacultySemesterType];
  $selectCourseId = $mapTableSingleRowRes[$_mappingFacultyCourseId];

  $stuResId = $selectCourseId;
  $_SESSION['course'] = $stuResId;

  $sql = "SELECT * FROM $_mappingFacultyTable WHERE $_mappingFacultyId='$facId' AND $_mappingFacultyId='$academicYear' AND $_mappingFacultySemesterType='$semNo' AND $_mappingFacultyCourseId='$selectCourseId'";

  return $sql;
}
$isUnFreeze = TRUE;

if (isset($_POST['freeze_me'])) {
  $fac = GetFacultyDetailCellData($_facultyId);
  $crse = $_SESSION['course'];
  $examName = $_SESSION["courseOpenBtn1"] ?? 0;
  // echo "$examName<br>";
  $examName = $examName == 1 ? "$_freezePushPermissionFreezeCa1" : ($examName == 2 ? "$_freezePushPermissionFreezeCa2" : ($examName == 3 ? "$_freezePushPermissionFreezeCa3" : ($examName == 4 ? "$_freezePushPermissionFreezeLab" : ($examName == 5 ? "$_freezePushPermissionFreezeInternal" : ""))));

  // echo "$examName";
  // exit;

  $status = 0;
  $sql = "UPDATE $_freezePushPermissionTable SET $examName = '0' WHERE $_freezePushPermissionFacId='$fac' AND $_freezePushPermissionCourseId='$crse'";
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

  $selectCourse = "SELECT * FROM $_mappingFacultyTable WHERE $_mappingFacultyId='$facId' AND $_mappingFacultySemesterYear='$academicYear' AND $_mappingFacultySemesterType='$semNo'";

  // echo $selectCourse;
  // exit;
  $selectCourseRes = mysqli_query($conn, $selectCourse);

  $facultyCourseId = array();
  $facultyCourseMappingTableId = array();

  $TempCoursePrintingIdsUnique = array();

  while ($courseResData = mysqli_fetch_assoc($selectCourseRes)) {
    if (!in_array($courseResData[$_mappingFacultyCourseId], $facultyCourseId)) {
      $TempCoursePrintingIdsUnique[] = $courseResData[$_mappingFacultyTblId];
    }

    $facultyCourseId[] = $courseResData[$_mappingFacultyCourseId];
    $facultyCourseMappingTableId[] = $courseResData[$_mappingFacultyTblId];
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

  $mapTableSingleRowSelect = mysqli_query($conn, "SELECT * FROM $_mappingFacultyTable WHERE $_mappingFacultyTblId='$selectCourseId'");
  $mapTableSingleRowRes = mysqli_fetch_assoc($mapTableSingleRowSelect);

  $facId = $mapTableSingleRowRes[$_mappingFacultyId];
  // echo "$academicYear<br>";
  $academicYear = $mapTableSingleRowRes[$_mappingFacultySemesterYear];
  // echo "$academicYear<br>";
  // exit;
  $semNo = $mapTableSingleRowRes[$_mappingFacultySemesterType];
  $selectCourseId = $mapTableSingleRowRes[$_mappingFacultyCourseId];

  $stuResId = $selectCourseId;
  $_SESSION['course'] = $stuResId;

  $sql = "SELECT * FROM $_mappingStudentTable WHERE $_mappingFacultySemesterYear='$academicYear' AND $_mappingFacultySemesterType='$semNo' AND $_mappingFacultyCourseId='$selectCourseId'";
  // echo "$sql";
  // exit;
  $selectCourseStudent = mysqli_query($conn, $sql);

  $freezeCourse = "SELECT * FROM $_freezePushPermissionTable WHERE $_freezePushPermissionFacId='$facId' AND $_freezePushPermissionCourseId='$selectCourseId'";
  $freezeCourseRes = mysqli_query($conn, $freezeCourse);
  $freezeCourseRes = mysqli_fetch_assoc($freezeCourseRes);
  $f = str_replace("_", "", $selectFromResultTableField);
  $f = str_replace("practicalmarks", "lab", $f);
  $f = str_replace("internalmarks", "internal", $f);
  $f = $f . "_freeze";
  $isUnFreeze = $freezeCourseRes[$f];

  $stuResWhere = "$_resultResultYear='$academicYear' AND $_resultResultSemType='$semNo' AND $_resultStdCrseId='$selectCourseId'";
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
  global $conn, $_programTableName;
  global $_programId;

  $slct = "SELECT $field FROM $_programTableName WHERE $_programId = '$id'";
  $res = mysqli_query($conn, $slct);
  $data = mysqli_fetch_assoc($res);
  return $data[$field] ?? null;
}



function GetFacultyDetailCellData($field)
{
  global $conn, $_facultyTable;
  global $_facultyId, $_facultyCode, $_facultyName, $_facultyDepartment, $_facultyEmail, $_facultyJoinDate;
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
  global $_studentId;

  $sql = "SELECT * FROM $_studentTable WHERE $_studentId = '$id'";

  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$field] ?? null;
}


function GetStudentResult($stuId)
{
  global $conn, $_resultTable;
  global $_resultStdDtlId;
  global $stuResWhere;

  // if ($stuResWhere != "")
  //   $stuResWhere = $stuResWhere;

  $condition = $stuResWhere . " AND $_resultStdDtlId='$stuId'";

  $sqlResult = "SELECT * FROM $_resultTable WHERE $condition";

  $resultData = mysqli_query($conn, $sqlResult);
  // echo "$stuResWhere<br>";
  // echo "$condition<br>";
  // echo "$sqlResult<br>";

  // print("<pre>");
  // print_r($resultData);
  // echo "";
  // print("</pre>");
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
            $FreezeRecords = "SELECT * FROM $_freezePushPermissionTable WHERE $_freezePushPermissionFacId='$facFrz' AND $_freezePushPermissionCourseId='$crsFrz' AND $_freezePushPermissionAcademicYear='$academicYear' AND $_freezePushPermissionAcademicSem='$semNo'";
            $frezzeRecordsData = mysqli_query($conn, $FreezeRecords);
            $frezzeRecordsData = mysqli_fetch_assoc($frezzeRecordsData);

            $isCa1Freze = $frezzeRecordsData[$_freezePushPermissionFreezeCa1];
            $isCa2Freeze = $frezzeRecordsData[$_freezePushPermissionFreezeCa2];
            $isCa3Freeze = $frezzeRecordsData[$_freezePushPermissionFreezeCa3];
            $isLabFreeze = $frezzeRecordsData[$_freezePushPermissionFreezeLab];
            $isInternalFreeze = $frezzeRecordsData[$_freezePushPermissionFreezeInternal];
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
              <td style="text-align: center;">Enrollment No.</td>
              <td style="text-align: center;">Name</td>

              <td style="text-align: center;"><?php echo "$fieldNameForMarksEntry ($maxMarksForMarksEntry)"; ?></td>
              <td style="text-align: center;">Remarks</td>
              <td style="text-align: center;"></td>
            </tr>
            <?php
            mysqli_data_seek($selectCourseStudent, 0);
            if (mysqli_num_rows($selectCourseStudent) > 0) {
              $selectCourseStudentData = mysqli_fetch_assoc($selectCourseStudent);

              $proId = GetStudentDetailCellData($_studentProgram, $selectCourseStudentData[$_mappingStudentId]);

              $isUG = GetProgramField($_graduationTypeField, $proId) == 1;
              $courseTPC = GetCourseTPC($_courseTypeField);
              $isTheory = $courseTPC == 1;
              $isPractical = $courseTPC == 2;
              $isBoth = $courseTPC == 3;
              $creditTotal = GetCourseTPC($_courseCreditMarksField);
            ?>


            <?php } else { ?>
              <!-- <td style="text-align: center;">Students Not Found.</td> -->
            <?php } ?>
          </thead>

          <tbody id="marksBody">
            <?php
            mysqli_data_seek($selectCourseStudent, 0);

            if (mysqli_num_rows($selectCourseStudent) > 0) {

              while ($selectCourseStudentData = mysqli_fetch_assoc($selectCourseStudent)) :
                $crs = $selectCourseStudentData[$_mappingFacultyCourseId]; // find course id from mapping table
                $yr = $selectCourseStudentData[$_mappingFacultySemesterYear]; // find year from mapping table
                $typ = $selectCourseStudentData[$_mappingFacultySemesterType]; // find semester type from mapping table

                $resultQuery = GetStudentResult($selectCourseStudentData[$_mappingStudentId]); // get student data object from student id which is find from mapping table


                if ($resultQuery === false) {
                  error_log("Query failed for student ID: " . $_studentId);
                  $result = null;
                } else {
                  $result = mysqli_fetch_assoc($resultQuery);
                }

                $STDDTLID = $selectCourseStudentData[$_mappingStudentId];

                $STDCRSEID = $selectCourseStudentData[$_mappingFacultyCourseId];
                $RESULTID = $result[$_resultId] ?? 0;


                $ENNO = GetStudentDetailCellData($_studentCode, $STDDTLID);
                $NAME = GetStudentDetailCellData($_studentName, $STDDTLID);

                $MARKS = $result[$selectFromResultTableField] ?? 0;
                $REMARKS = $result[$_resultResultRemarks] ?? "";
            ?>
                <tr>
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
                </tr>
      </form>
      </tr>
    <?php endwhile; ?>
  <?php } else { ?>
    <tr>
      <td style="text-align: center;" colspan="4">Students Not Found.</td>
    </tr>
  <?php } ?>
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
    const data = {
      // id: trElem.querySelector('input[name="id"]')?.value || 0,
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
        window.location.reload(true); // deprecated, supported only in Firefox
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
          // id: row.querySelector('.id') ? row.querySelector('.id').value : '',
          stdDtlId: row.querySelector('.stdDtlId') ? row.querySelector('.stdDtlId').value : '',
          stdCrseId: row.querySelector('.stdCrseId') ? row.querySelector('.stdCrseId').value : '',
          resultId: row.querySelector('.resultId') ? row.querySelector('.resultId').value : '',
          semYear: row.querySelector('.semYear') ? row.querySelector('.semYear').value : '',
          semType: row.querySelector('.semType') ? row.querySelector('.semType').value : '',
          marks: row.querySelector('.marks') ? row.querySelector('.marks').value : '',
          remarks: row.querySelector('.remarks') ? row.querySelector('.remarks').value : ''
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