<?php

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 4) {
  header("Location: ../");
  exit;
}

$stdLgnId = str_replace($defaultLoginExtension, "", $_SESSION[$_session_login_name] ?? "");



$display = "none";
if (isset($_POST['loadCourseResult'])) {
  $yearSelect = $_POST['yearSelect'];
  $semSelect = $_POST['semSelect'];
  $semSelect = strtolower($semSelect) == "fall" ? 1 : (strtolower($semSelect) == "summer" ? 2 : 0);


  $stuId = GetStudentDetailCellData($_studentId);
  $rsltAry = GetResultDetails("$resultSemesterStdDtlId=$stuId AND $resultSemesterResultYear='$yearSelect' AND $resultSemesterResultSemType='$semSelect'");


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
  global $conn, $resultSemesterTable;

  $sql = "SELECT * FROM $resultSemesterTable WHERE $where";
  $res = mysqli_query($conn, $sql);
  $res = mysqli_fetch_assoc($res);
  return $res[$fld] ?? null;
}

function GetResultDetails($where)
{
  global $conn, $resultSemesterTable;

  $sql = "SELECT * FROM $resultSemesterTable WHERE $where";
  $res = mysqli_query($conn, $sql);
  return $res;
}

function GetStudentDetailCellData($field)
{
  global $conn, $_studentTable;
  global $_studentId, $_studentCode, $_studentName, $_studentProgram, $_studentAdmitYear, $_studentPassword;
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
            <th style="text-align: center;">CA3 (40)</th>
            <th style="text-align: center;">Practical (100)</th>
            <th style="text-align: center;">Internals (20)</th>
            <th style="text-align: center;">Total (100)</th>
          </tr>
        </thead>
        <tbody id="marksBody">
          <?php if (isset($rsltAry)) {
            while ($resRow = mysqli_fetch_assoc($rsltAry)) {
              $stu = $resRow[$resultSemesterStdDtlId];
              $crse = $resRow[$resultSemesterStdCrseId];
              // exit;
              // echo "$stu - $crse - ";
              
              $pushResultMap = "SELECT * FROM $_mappingTable WHERE $_stuId='$stu' AND $_crseId='$crse'";
              $response = mysqli_query($conn, $pushResultMap);
              $response = mysqli_fetch_assoc($response);
              $fac = $response[$_facId];
              
              $pushResultMap = "SELECT * FROM $ceoPermissionTable WHERE $ceoPermissionFacId='$fac' AND $ceoPermissionCourseId='$crse'";
              $response = mysqli_query($conn, $pushResultMap);
              $response = mysqli_fetch_assoc($response);
              $isUnPush = $response[$ceoPermissionPush];



              // $n = mysqli_num_rows($response);
              // echo "$isUnPush<br>";
              


              $rsltId = $resRow[$resultSemesterId];
              $crCd = GetCourseDetails($_courseCodeField, $resRow[$resultSemesterStdCrseId]);
              $crNm = GetCourseDetails($_courseNameField, $resRow[$resultSemesterStdCrseId]);
              $crdt = GetResultDetailsRow($resultSemesterTotalCredit, "$resultSemesterId='$rsltId'");

              $ca1 = GetResultDetailsRow($resultSemesterCa1, "$resultSemesterId='$rsltId'");
              $ca2 = GetResultDetailsRow($resultSemesterCa2, "$resultSemesterId='$rsltId'");
              $ca3 = GetResultDetailsRow($resultSemesterCa3, "$resultSemesterId='$rsltId'");
              $prctl = GetResultDetailsRow($resultSemesterPracticalMarks, "$resultSemesterId='$rsltId'");
              $intrnl = GetResultDetailsRow($resultSemesterInternalMarks, "$resultSemesterId='$rsltId'");

              $ttl = $ca1 + $ca2 + $ca3 + $prctl + $intrnl;
          ?>
              <tr>
                <td style="text-align: center;"><?php echo $crCd; ?></td>
                <td style="text-align: center;"><?php echo $crNm; ?></td>
                <?php if ($isUnPush) { ?>
                  <td style="text-align: center;"><b><?php echo $crdt; ?></b></td>
                  <td style="text-align: center;"><?php echo $ca1; ?></td>
                  <td style="text-align: center;"><?php echo $ca2; ?></td>
                  <td style="text-align: center;"><?php echo $ca3; ?></td>
                  <td style="text-align: center;"><?php echo $prctl; ?></td>
                  <td style="text-align: center;"><?php echo $intrnl; ?></td>
                  <td style="text-align: center;"><b><?php echo $ttl; ?></b></td>
                <?php } else { ?>
                  <td style="text-align: center; color: rgba(203, 0, 0, 1);" colspan="7"><b>Not Declared Yet</b></td>
                <?php } ?>
              </tr>
          <?php }
          } ?>
        </tbody>
      </table>
    </div>

  </div>

  <?php include_once("../footer.php"); ?>