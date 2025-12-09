<?php

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 3) {
      header("Location: ../");
      exit;
}

$facLgnId = str_replace($defaultLoginExtension, "", $_SESSION[$_session_login_name] ?? "");


// echo $facLgnId."<br>";
// exit;

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

function GetFacultyMappingDetailAllData()
{
      global $conn, $_mappingTable;
      global $_facultyId;
      global $_mapId, $_styid, $_facId, $_crseId, $_sltId, $_slotYear, $_semesterType;
      $lgnFacultyId = GetFacultyDetailCellData($_facultyId);

      // echo $lgnFacultyId;
      // exit;

      $sql = "SELECT * FROM $_mappingTable WHERE $_facId = '$lgnFacultyId'";
      $res = mysqli_query($conn, $sql);
      return $res ?? null;
}

function GetCourseMappingDetailCellData($field, $value)
{
      global $conn, $_coursesTable;
      global $_courseId;

      $sql = "SELECT * FROM $_coursesTable WHERE $_courseId = '$value'";
      $res = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($res);
      // echo $sql;
      // exit;
      return $row[$field] ?? null;
}

function GetStudentIdCourseMappingAry($value)
{
      global $conn, $_mappingTable;
      global $_stuId;

      $sql = "SELECT * FROM $_mappingTable WHERE $_stuId = '$value'";
      $res = mysqli_query($conn, $sql);
      // $row = mysqli_fetch_assoc($res);
      // echo $sql;
      // exit;
      return $res;
}

function GetStudentDetailCellData($field, $id)
{
      global $conn, $_studentTable;
      global $_studentId;

      $sql = "SELECT * FROM $_studentTable WHERE $_studentId = '$id'";
      // echo $sql;
      // exit;
      $res = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($res);
      return $row[$field] ?? null;
}

function GetProgramDetailCellData($field, $id)
{
      global $conn, $_programTableName;
      global $_programId;

      $sql = "SELECT * FROM $_programTableName WHERE $_programId = '$id'";
      // echo $sql;
      // exit;
      $res = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($res);
      return $row[$field] ?? null;
}
function GetSlotNameId($val, $isGetName = true)
{
      global $conn, $_slotTable;
      global $_slotId, $_slotName;

      $field = $isGetName ? $_slotId : $_slotName;

      $res = mysqli_query($conn, "SELECT * FROM $_slotTable WHERE $field = '$val'");
      $row = mysqli_fetch_assoc($res);

      if ($isGetName)
            return $row[$_slotName] ?? null;
      else
            return $row[$_slotId] ?? null;
}

function GetCourseDetailCellData($field, $id)
{
      global $conn, $_coursesTable;
      global $_courseId;

      $sql = "SELECT * FROM $_coursesTable WHERE $_courseId = '$id'";
      // echo $sql;
      // exit;
      $res = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($res);
      return $row[$field] ?? null;
}

$facultyCourseMappingRes = GetFacultyMappingDetailAllData();
$studentCourseOfFacIds = array();
$courseIds = array();
while ($data = mysqli_fetch_assoc($facultyCourseMappingRes)) {
      $courseIds[] = $data[$_crseId];
      $studentCourseOfFacIds[] = $data[$_stuId];
}
$courseIds = array_unique($courseIds);

// print("<pre>");
// echo "Before: ";
// print_r($courseIds);
// echo "<br>";
// print_r($studentCourseOfFacIds);
// echo "After: ";
// print_r($courseIds);
// echo "<br>";
// exit;
// exit;

include_once("../header.php");
?>


<div class="container">
      <div class="page-header">
            <h3>Result</h3>
            <div class="breadcrumb-box">
                  <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Faculty</span></a>
                  <span class="sep">></span>
                  <span class="crumb-link" style="pointer-events:none;opacity:.6">Result</span>
            </div>
      </div>
      <div class="">
            <a>
                  <?php
                  foreach ($courseIds as $key => $value) {
                        echo GetCourseMappingDetailCellData($_courseNameField, $value) . "<br>";
                  }
                  ?>
            </a>
      </div>
      <?php echo "<br>"; ?>
      <?php echo "<br>"; ?>

      <div class="">
            <table style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
                  <thead>
                        <tr style="background-color: #f2f2f2; border: 1px solid #ccc;">
                              <th style="border: 1px solid #ccc; padding: 8px;">#</th>
                              <th style="border: 1px solid #ccc; padding: 8px;">Enrollment No</th>
                              <th style="border: 1px solid #ccc; padding: 8px;">Name</th>
                              <th style="border: 1px solid #ccc; padding: 8px;">Program</th>
                              <th style="border: 1px solid #ccc; padding: 8px;">Slot</th>
                              <th style="border: 1px solid #ccc; padding: 8px;">Course Code</th>
                              <th style="border: 1px solid #ccc; padding: 8px;">Course Name</th>
                        </tr>
                  </thead>
                  <tbody>
                        <?php $num = 1;
                        foreach ($studentCourseOfFacIds as $key => $value) { ?>
                              <tr style="border: 1px solid #ccc;">
                                    <td style="border: 1px solid #ccc; padding: 8px;"><?php echo $num++; ?></td>
                                    <td style="border: 1px solid #ccc; padding: 8px;"><?php echo GetStudentDetailCellData($_studentCode, $value); ?></td>
                                    <td style="border: 1px solid #ccc; padding: 8px;"><?php echo GetStudentDetailCellData($_studentName, $value); ?></td>
                                    <td style="border: 1px solid #ccc; padding: 8px;"><?php echo GetProgramDetailCellData($_programNameField, GetStudentDetailCellData($_studentProgram, $value)); ?></td>
                                    <td style="border: 1px solid #ccc; padding: 8px;">
                                          <?php $mapStuData = GetStudentIdCourseMappingAry($value);
                                          mysqli_data_seek($mapStuData, 0);
                                          while ($raw = mysqli_fetch_assoc($mapStuData)) { ?>
                                                <?php echo GetSlotNameId($raw[$_sltId]); ?><br>
                                          <?php } ?>
                                    </td>
                                    <td style="border: 1px solid #ccc; padding: 8px;"><?php mysqli_data_seek($mapStuData, 0);
                                                                                          while ($raw = mysqli_fetch_assoc($mapStuData)) { ?>
                                                <?php echo GetCourseDetailCellData($_courseCodeField, $raw[$_crseId]); ?><br>
                                          <?php } ?></td>
                                    <td style="border: 1px solid #ccc; padding: 8px;"><?php mysqli_data_seek($mapStuData, 0);
                                                                                          while ($raw = mysqli_fetch_assoc($mapStuData)) { ?>
                                                <?php echo GetCourseDetailCellData($_courseNameField, $raw[$_crseId]); ?><br>
                                          <?php } ?></td>
                              </tr>
                        <?php } ?>
                  </tbody>
            </table>

      </div>
</div>

<?php include_once("../footer.php"); ?>