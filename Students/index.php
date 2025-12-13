<?php

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 4) {
  header("Location: ../");
  exit;
}

$stdLgnId = str_replace($defaultLoginExtension, "", $_SESSION[$_session_login_name] ?? "");


// echo $stdLgnId."<br>";
// exit;

function GetProgramField($field, $id)
{
  global $conn, $programTableName;
  global $programId, $programNameField, $programSemField, $programDeptField, $graduationTypeField;

  $slct = "SELECT $field FROM $programTableName WHERE $programId = '$id'";
  $res = mysqli_query($conn, $slct);
  $data = mysqli_fetch_assoc($res);
  return $data[$field] ?? null;
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

function GetProgramDetailCellData($field, $id)
{
  global $conn, $_programTableName;
  global $_programId, $_programNameField, $_programSemField, $_programDeptField;

  $sql = "SELECT * FROM $_programTableName WHERE $_programId = '$id'";
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$field] ?? null;
}
function GetDepartmentDetailCellData($field, $id)
{
  global $conn, $_departmentTable;
  global $_departmentId, $_departmentName;

  $sql = "SELECT * FROM $_departmentTable WHERE $_departmentId = '$id'";
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$field] ?? null;
}

include_once("../header.php");
?>


<div class="container">
  <div class="page-header">
    <h3>Dashboard</h3>
    <div class="breadcrumb-box">
      <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Student</span></a>
      <span class="sep">></span>
      <span class="crumb-link" style="pointer-events:none;opacity:.6">Dashboard</span>
    </div>
  </div>

  <div style="display: flex; justify-content: flex-end;">
    <div class="card-right-details">
      <p><b>Student Code:</b> <?php echo GetStudentDetailCellData($_studentCode); ?></p>
      <p><b>Student Name:</b> <?php echo GetStudentDetailCellData($_studentName); ?></p>
      <p><b>Student Program:</b> <?php echo GetProgramDetailCellData($_programNameField, GetStudentDetailCellData($_studentProgram)); ?></p>
      <p><b>Student Department:</b> <?php echo GetDepartmentDetailCellData($_departmentName, GetProgramDetailCellData($_programDeptField, GetProgramDetailCellData($_programId, GetStudentDetailCellData($_studentProgram)))); ?></p>
      <p><b>Student Admit Year:</b> <?php echo GetStudentDetailCellData($_studentAdmitYear); ?></p>
    </div>
  </div>
</div>

<!-- Main -->
<div class="container">


<!-- ----------------------------------------------------------------------------- -->

<link rel="stylesheet" href="sem-grid.css">
<div class="sem-grid" id="semGrid">
<?php 
$semCount = GetProgramDetailCellData($_programSemField, GetStudentDetailCellData($_studentProgram));
for ($i=1; $i <= $semCount; $i++) { ?>
  <form method="post" class="sem-card-form">
    <button type="submit" class="sem-card-btn" disabled>
      <h4>Semester <?php echo "$i"; ?></h4>
      <div class="stat">GPA: <b><?php echo "N/A"; ?></b></div>
      <div class="stat">CGPA: <b><?php echo "N/A"; ?></b></div>
    </button>
  </form>
  <?php } ?>
</div>

<!-- ----------------------------------------------------------------------------- -->





</div>

<script>
</script>



<?php include_once("../footer.php"); ?>