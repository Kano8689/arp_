<?php

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 4) {
  header("Location: ../");
  exit;
}

$stdLgnId = str_replace($defaultLoginExtension, "", $_SESSION[$_session_login_name] ?? "");


$graduationCkeckSql = "SELECT pt.$_graduationTypeField 
                       FROM $_programTableName pt
                       LEFT JOIN $_studentTable cd
                       ON cd.$_studentProgram = pt.$_programId
                       WHERE cd.$_studentCode = '$stdLgnId'";
$graduationCkeckSql = mysqli_query($conn, $graduationCkeckSql);
$graduationCkeckSql = mysqli_fetch_assoc($graduationCkeckSql);
$isUgStudent = $graduationCkeckSql[$_graduationTypeField] == 1;

$display = "none";
$yearSelect = "";
$semSelect = "";
if (isset($_POST['loadCourseResult'])) {
  $yearSelect = $_POST['yearSelect'];
  $semSelect = $_POST['semSelect'];
  $semSelect = strtolower($semSelect) == "fall" ? 1 : (strtolower($semSelect) == "summer" ? 2 : 0);


  $stuId = GetStudentDetailCellData($_studentId);
  $rsltAry = GetResultDetails("$_resultStdDtlId='$stuId' AND $_resultResultYear='$yearSelect' AND $_resultResultSemType='$semSelect'");


  if (mysqli_num_rows($rsltAry) > 0) {
    $display = "block";
  }
}

function GetCourseDetails($field, $idVal)
{
  global $conn, $_coursesTable;
  global $_courseId;

  $sql = "SELECT * FROM $_coursesTable WHERE $_courseId=$idVal";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_array($res);

  return $res[$field];
}

function GetResultDetailsRow($fld, $where)
{
  global $conn, $_resultTable, $_resultStdCrseId;

  $sql = "SELECT * FROM $_resultTable WHERE $where";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);
  $crsId = $res[$_resultStdCrseId];

  return $res[$fld] ?? null;
}

function GetResultDetails($where)
{
  global $conn, $_resultTable;

  $sql = "SELECT * FROM $_resultTable WHERE $where";
  $res = mysqli_query($conn, $sql);
  return $res;
}

function GetStudentDetailCellData($field)
{
  global $conn, $_studentTable;
  global $_studentId, $_studentCode, $_studentName, $_studentProgram, $_studentAdmitYear;
  global $stdLgnId;

  $sql = "SELECT * FROM $_studentTable WHERE $_studentCode = '$stdLgnId'";
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$field] ?? null;
}



include_once("../header.php");
?>


<!-- Main -->
<div class="container">

  <!-- Title + Breadcrumb -->
  <div class="page-header">
    <h3>Students Result</h3>
    <div class="breadcrumb-box">
      <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Students</span></a>
      <span class="sep">›</span>
      <span class="crumb-link crumb-disabled">Results</span>
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
          $academicYear = $startYear;
        ?>
          <option value="<?php echo $academicYear; ?>"><?php echo $academicYear; ?></option>
        <?php } ?>
      </select>

      <select name="semSelect" id="semSelect">
        <option name="" value="">Semester</option>
        <option value="Fall">Fall</option>
        <option value="Summer">Summer</option>
      </select>

      <button class="btn" name="loadCourseResult" onclick="">Load</button>
    </form>
  </div>

  <div class="card" style="display: <?php echo $display; ?>;">
    <div id="marksWrap" style="margin-top:18px; display:block;">
      <table class="table">
        <thead>
          <tr>
            <th style="text-align: center;">Course Code</th>
            <th style="text-align: center;">Course Name</th>
            <th style="text-align: center;">Credits</th>
            <th style="text-align: center;">CA1 (40)</th>
            <th style="text-align: center;">CA2 (40)</th>
            <?php if ($isUgStudent) { ?>
              <th style="text-align: center;">CA3 (40)</th>
            <?php } ?>
            <th style="text-align: center;">Practical (100)</th>
            <th style="text-align: center;">Internals (20)</th>
            <!-- <th style="text-align: center;">Total (100)</th> -->
          </tr>
        </thead>
        <tbody id="marksBody">
          <?php if (isset($rsltAry)) {
            while ($resRow = mysqli_fetch_assoc($rsltAry)) {
              $stu = $resRow[$_resultStdDtlId];
              $crse = $resRow[$_resultStdCrseId];

              $pushResultMap = "SELECT * FROM $_mappingStudentTable WHERE $_mappingStudentId='$stu' AND $_mappingStudentCourseId='$crse' AND $_mappingStudentSemesterYear='$yearSelect' AND $_mappingStudentSemesterType='$semSelect'";
              $response = mysqli_query($conn, $pushResultMap);
              $response = mysqli_fetch_assoc($response);
              $slt = $response[$_mappingStudentSlotId];

              $pushResultMap = "SELECT * FROM $_mappingFacultyTable WHERE $_mappingFacultySlotId='$slt' AND $_mappingFacultyCourseId='$crse' AND $_mappingFacultySemesterYear='$yearSelect' AND $_mappingFacultySemesterType='$semSelect'";
              $response = mysqli_query($conn, $pushResultMap);
              $response = mysqli_fetch_assoc($response);
              $fac = $response[$_mappingFacultyId] ?? 0;

              $pushResultMap = "SELECT * FROM $_freezePushPermissionTable WHERE $_freezePushPermissionFacId='$fac' AND $_freezePushPermissionCourseId='$crse'";

              $response = mysqli_query($conn, $pushResultMap);
              $response = mysqli_fetch_assoc($response);
              // $isUnPush = $response[$ceoPermissionPush];
              $isCa1 = $response[$_freezePushPermissionPushCa1] ?? 0;
              $isCa2 = $response[$_freezePushPermissionPushCa2] ?? 0;
              $isCa3 = $response[$_freezePushPermissionPushCa3] ?? 0;
              $isLab = $response[$_freezePushPermissionPushLab] ?? 0;
              $isInternal = $response[$_freezePushPermissionPushInternal] ?? 0;

              $rsltId = $resRow[$_resultId];
              $crCd = GetCourseDetails($_courseCodeField, $resRow[$_resultStdCrseId]);
              $crNm = GetCourseDetails($_courseNameField, $resRow[$_resultStdCrseId]);
              $crdt = GetCourseDetails($_courseCreditMarksField, $resRow[$_resultStdCrseId]);

              $ca1 = GetResultDetailsRow($_resultCa1, "$_resultId='$rsltId'");
              $ca2 = GetResultDetailsRow($_resultCa2, "$_resultId='$rsltId'");
              $ca3 = GetResultDetailsRow($_resultCa3, "$_resultId='$rsltId'");
              $prctl = GetResultDetailsRow($_resultLabMarks, "$_resultId='$rsltId'");
              $intrnl = GetResultDetailsRow($_resultInternalMarks, "$_resultId='$rsltId'");

              // $ttl = $ca1 + $ca2 + $ca3 + $prctl + $intrnl;
          ?>
              <tr>
                <td style="text-align: center;"><?php echo $crCd; ?></td>
                <td style="text-align: center;"><?php echo $crNm; ?></td>
                <td style="text-align: center;"><b><?php echo $crdt; ?></b></td>

                <?php if ($isCa1) { ?> <td style="text-align: center;"><?php echo $ca1; ?></td><?php } else { ?> <td style="text-align: center; color: rgba(203, 0, 0, 1);"><b>-</b></td> <?php } ?>
                <?php if ($isCa2) { ?> <td style="text-align: center;"><?php echo $ca2; ?></td><?php } else { ?> <td style="text-align: center; color: rgba(203, 0, 0, 1);"><b>-</b></td> <?php } ?>
                <?php if ($isUgStudent) { ?>
                  <?php if ($isCa3) { ?> <td style="text-align: center;"><?php echo $ca3; ?></td><?php } else { ?> <td style="text-align: center; color: rgba(203, 0, 0, 1);"><b>-</b></td> <?php } ?>
                <?php } ?>
                <?php if ($isLab) { ?> <td style="text-align: center;"><?php echo $prctl; ?></td><?php } else { ?> <td style="text-align: center; color: rgba(203, 0, 0, 1);"><b>-</b></td> <?php } ?>
                <?php if ($isInternal) { ?> <td style="text-align: center;"><?php echo $intrnl; ?></td><?php } else { ?> <td style="text-align: center; color: rgba(203, 0, 0, 1);"><b>-</b></td> <?php } ?>
                <!-- <td style="text-align: center;"><b><?php echo $ttl; ?></b></td> -->
              </tr>
          <?php }
          } ?>
        </tbody>
      </table>
    </div>

  </div>

  <?php include_once("../footer.php"); ?>