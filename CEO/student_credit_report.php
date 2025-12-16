<?php

use Dom\Mysql;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 2) {
      header("Location: ../");
      exit;
}

$sem_count = 0;
$display = "none";

if (isset($_POST['fndCrdtRpt'])) {
      $enNo = $_POST['enNo2Fnd'];
      // echo "$enNo";
      $sql = "SELECT $_studentCode FROM $_studentTable WHERE $_studentCode='$enNo'";

      $sql = "SELECT st.$_studentCode, pt.$_programSemField
              FROM $_studentTable st
              LEFT JOIN $_programTableName pt
              ON st.$_studentProgram = pt.$_programId
              WHERE $_studentCode='$enNo'";
      $stuData = mysqli_query($conn, $sql);
      $stuData = mysqli_fetch_assoc($stuData);
      $stdLgnId = $stuData[$_studentCode] ?? "";
      $sem_count = $stuData[$_programSemField] ?? "";



      $stuId = GetStudentDetailCellData($_studentId);
      $SelectSemValue = "SELECT sv.*, st.$_studentId
                   FROM $_semesterValueTable sv 
                   LEFT JOIN $_studentTable st ON sv.$_semesterValueStudentId = st.$_studentId
                   ORDER BY sv.$_semesterValueSemNo ASC";
      $SelectSemData = mysqli_query($conn, $SelectSemValue);

      $display = "block";
}

function GetStudentDetailCellData($field)
{
      global $conn, $_studentTable;
      global $_studentCode;
      global $stdLgnId;

      $sql = "SELECT * FROM $_studentTable WHERE $_studentCode = '$stdLgnId'";
      $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
      // $row = mysqli_fetch_assoc($res);
      return $row[$field] ?? null;
}



function GetCourseDataFromSemYrTyp($stu, $yr, $typ)
{
      global $conn;
      global $_mappingStudentTable, $_mappingStudentId, $_mappingStudentSemesterYear, $_mappingStudentSemesterType, $_mappingStudentCourseId;
      global $_coursesTable, $_courseId;

      $typ = strtolower($typ);
      $typ = $typ == "fall" ? 1 : ($typ == "summer" ? 2 : 0);

      $sql = "SELECT ms.*, ct.*
                   FROM $_mappingStudentTable ms 
                   LEFT JOIN $_coursesTable ct ON ms.$_mappingStudentCourseId = ct.$_courseId
                   WHERE ms.$_mappingStudentId='$stu' AND ms.$_mappingStudentSemesterYear='$yr' AND ms.$_mappingStudentSemesterType='$typ'";

      $SelectCourseData = mysqli_query($conn, $sql);

      return $SelectCourseData;
}

function GetResultData($stu, $crse, $code, $yr, $typ, $crsetyp)
{
      global $conn;
      global $_resultTable, $_resultStdDtlId, $_resultStdCrseId, $_resultResultYear, $_resultResultSemType;
      global $_resultCa1, $_resultCa2, $_resultCa3, $_resultLabMarks, $_resultInternalMarks;
      global $_resultObtainedCredit, $_resultObtainedGrade;

      $typ = strtolower($typ);
      $typ = $typ == "fall" ? 1 : ($typ == "summer" ? 2 : 0);

      $sql = "SELECT $_resultObtainedCredit, $_resultObtainedGrade, $_resultCa1, $_resultCa2, $_resultCa3, $_resultLabMarks, $_resultInternalMarks FROM $_resultTable WHERE $_resultStdDtlId='$stu' AND $_resultStdCrseId='$crse' AND $_resultResultYear='$yr' AND $_resultResultSemType='$typ'";

      // echo "$sql";
      // exit;

      $MarksData = mysqli_query($conn, $sql);
      $MarksData = mysqli_fetch_assoc($MarksData);

      // return isAbleToPass($crsetyp, $code, $MarksData[$_resultCa1]??0, $MarksData[$_resultCa2]??0, $MarksData[$_resultCa3]??0, $MarksData[$_resultInternalMarks]??0,  $MarksData[$_resultLabMarks]??0);

      return [$MarksData[$_resultObtainedCredit] ?? "", $MarksData[$_resultObtainedGrade] ?? ""];
}

include_once("../header.php");
?>

<style>
      .header2 {
            background-color: #FFC000;
            color: #215C98;
      }
</style>

<!-- Main -->
<div class="container">

      <!-- Title + Breadcrumb -->
      <div class="page-header">
            <h3>Credit Report</h3>
            <div class="breadcrumb-box">
                  <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Students</span></a>
                  <span class="sep">›</span>
                  <span class="crumb-link crumb-disabled">Credit Report</span>
            </div>
      </div>


      <!-- Enter Enrollment Number -->
      <div>
            <div class="card" style="display: block;">
                  <form method="POST" style="width:550px; background:#fff; padding:20px; border-radius:6px; box-shadow:0 0 8px #ccc;">

                        <label class="fw-bold">Enrollment No :</label>
                        <input type="text" id="loginId" name="enNo2Fnd" value="" placeholder="Enrollment No" required style="width:100%; margin-bottom:15px;">

                        <div class="modal-actions" style="display:flex; gap:10px;">
                              <button name="fndCrdtRpt" class="btn" style="padding:10px 20px;" type="submit">Find Credit Report</button>
                        </div>

                  </form>
            </div>
      </div>


      <!-- Course Report Semester Wise -->
      <div style="display: <?php echo $display; ?>;">
            <?php
            $TotalRegistered = 0;
            $TotalObtained = 0;
            for ($i = 1; $i <= $sem_count; $i++) {
                  $semDataObj = mysqli_fetch_assoc($SelectSemData);
                  $semYrVlu  = str_replace("-", "/", $semDataObj[$_semesterValueSemYrTyp]);
                  $semYr  = substr($semYrVlu, 0, 4);
                  $semTyp  = substr($semYrVlu, 5, strlen($semYrVlu));


            ?>

                  <div class="card" style="display: block;">
                        <div id="marksWrap" style="margin-top:18px; display:block;">
                              <table class="table">
                                    <thead>
                                          <tr>
                                                <th style="text-align: center;" colspan="6">Semester <?php echo $i . " ($semYrVlu)"; ?> </th>
                                          </tr>
                                          <tr class="header2">
                                                <td style="text-align: center;">Course Code</td>
                                                <td style="text-align: center;">Coure Name</td>
                                                <td style="text-align: center;">Registered Credit</td>
                                                <td style="text-align: center;">Obtained Credit</td>
                                                <td style="text-align: center;">Result/Grade</td>
                                                <td style="text-align: center;">Registration Type</td>
                                          </tr>
                                    </thead>
                                    <tbody id="marksBody">
                                          <?php $SemCourse = GetCourseDataFromSemYrTyp($stuId, $semYr, $semTyp);
                                          if (mysqli_num_rows($SemCourse) > 0) {
                                                $totalRegistered = 0;
                                                $totalObtained = 0;
                                                while ($course = mysqli_fetch_assoc($SemCourse)) {

                                                      $totalRegistered += $course[$_courseCreditMarksField];
                                                      $TotalRegistered += $course[$_courseCreditMarksField];
                                                      // exit;


                                                      $returnResultData = GetResultData($stuId, $course[$_courseId], $course[$_courseCodeField], $semYr, $semTyp, $course[$_courseTypeField]);

                                                      $isPass = $course[$_courseCreditMarksField] == $returnResultData[0]; // here get only _resultObtainedCredit from GetResultData() //remove another code for func..
                                                      // $isPass = GetResultData($stuId, $course[$_courseId], $course[$_courseCodeField], $semYr, $semTyp, $course[$_courseTypeField]);


                                                      if ($isPass) {
                                                            $totalObtained += $course[$_courseCreditMarksField];
                                                            $TotalObtained += $course[$_courseCreditMarksField];
                                                      }


                                          ?>
                                                      <tr>
                                                            <td style="text-align: center;"><?php echo $course[$_courseCodeField]; ?></td>
                                                            <td style="text-align: center;"><?php echo $course[$_courseNameField]; ?></td>
                                                            <td style="text-align: center;"><?php echo $course[$_courseCreditMarksField]; ?></td>
                                                            <td style="text-align: center;"><?php echo $isPass ? $course[$_courseCreditMarksField] : 0; ?></td>
                                                            <td style="text-align: center;"><?php echo $returnResultData[1]; ?></td>
                                                            <td style="text-align: center;"><?php echo $course[$_mappingStudentRegistrationType]; ?></td>
                                                      </tr>

                                                <?php } ?>
                                                <tr>
                                                      <td style="text-align: center;"><?php echo ""; ?></td>
                                                      <td style="text-align: center;"><?php echo ""; ?></td>
                                                      <td style="text-align: center;"><b><?php echo "Total Registered"; ?></b></td>
                                                      <td style="text-align: center;"><b><?php echo "Total Obtained"; ?></b></td>
                                                      <td style="text-align: center;"><?php echo ""; ?></td>
                                                      <td style="text-align: center;"><?php echo ""; ?></td>
                                                </tr>
                                                <tr>
                                                      <td style="text-align: center;"><?php echo ""; ?></td>
                                                      <td style="text-align: center;"><?php echo ""; ?></td>
                                                      <td style="text-align: center;"><b><?php echo "$totalRegistered"; ?></b></td>
                                                      <td style="text-align: center;"><b><?php echo "$totalObtained"; ?></b></td>
                                                      <td style="text-align: center;"><?php echo ""; ?></td>
                                                      <td style="text-align: center;"><?php echo ""; ?></td>
                                                </tr>

                                          <?php } else { ?>
                                                <tr>
                                                      <td style="text-align: center;" colspan="6"><?php echo "Not Register Yet."; ?></td>
                                                </tr>
                                          <?php } ?>

                                    </tbody>
                              </table>
                        </div>
                  </div>
            <?php } ?>


            <div class="card" style="display: block;">
                  <div id="marksWrap" style="margin-top:18px; display:block;">
                        <table class="table">
                              <thead>
                                    <tr>
                                          <th style="text-align: center;" colspan="6">Credit Summary </th>
                                    </tr>
                              </thead>
                              <tbody id="marksBody">
                                    <tr>
                                          <td style="text-align: center;"><?php echo ""; ?></td>
                                          <td style="text-align: center;"><?php echo ""; ?></td>
                                          <td style="text-align: center;"><b><?php echo "Total Registered"; ?></b></td>
                                          <td style="text-align: center;"><b><?php echo "Total Obtained"; ?></b></td>
                                          <td style="text-align: center;"><?php echo ""; ?></td>
                                    </tr>
                                    <tr>
                                          <td style="text-align: center;"><?php echo ""; ?></td>
                                          <td style="text-align: center;"><?php echo ""; ?></td>
                                          <td style="text-align: center;"><b><?php echo "$TotalRegistered"; ?></b></td>
                                          <td style="text-align: center;"><b><?php echo "$TotalObtained"; ?></b></td>
                                          <td style="text-align: center;"><?php echo ""; ?></td>
                                    </tr>

                              </tbody>
                        </table>
                  </div>
            </div>
      </div>

</div>

<?php include_once("../footer.php"); ?>

<?php

class CreditReport
{
      public $course_code;
      public $course_name;
      public $course_credit;
      public $obtained_marks;
}

?>