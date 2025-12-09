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

function GetGrade($ttl)
{
  $grade = $ttl >= 90 ? "A" : ($ttl >= 80 ? "A2" : ($ttl >= 70 ? "B1" : ($ttl >= 60 ? "B2" : ($ttl >= 50 ? "C" : ($ttl >= 35 ? "D" : "FAIL")))));

  return $grade;
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
            <th style="text-align: center;">Grade</th>
          </tr>
        </thead>
        <tbody id="marksBody">  
          <?php if (isset($rsltAry)) {
            while ($resRow = mysqli_fetch_assoc($rsltAry)) {
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
                <td style="text-align: center;"><b><?php echo $crdt; ?></b></td>
                <td style="text-align: center;"><b><?php echo GetGrade($ttl); ?></b></td>
              </tr>
          <?php }
          } ?>
        </tbody>
      </table>
    </div>

  </div>

  <?php include_once("../footer.php"); ?>