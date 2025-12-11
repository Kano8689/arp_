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
  global $_facultyId, $_facultyCode, $_facultyName, $_facultyDepartment, $_facultyEmail, $_facultyJoinDate;
  global $facLgnId;

  $sql = "SELECT * FROM $_facultyTable WHERE $_facultyCode = '$facLgnId'";
  // echo "$sql";
  // exit;
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$field] ?? null;
}

function GetDepartmentDetailCellData($id)
{
  global $conn, $_departmentTable;
  global $_departmentId, $_departmentName;

  $sql = "SELECT * FROM $_departmentTable WHERE $_departmentId = '$id'";
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($res);
  return $row[$_departmentName] ?? null;
}

include_once("../header.php");
?>


<div class="container">
  <div class="page-header">
    <h3>Faculty Dashboard</h3>
    <div class="breadcrumb-box">
      <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Faculty</span></a>
      <span class="sep">></span>
      <span class="crumb-link" style="pointer-events:none;opacity:.6">Dashboard</span>
    </div>
  </div>
  
  <div style="display: flex; justify-content: flex-end;">
    <div class="card-right-details">
      <p><b>Faculty Name</b> : <?php echo GetFacultyDetailCellData($_facultyName); ?></p>
      <p><b>Faculty Code</b> : <?php echo GetFacultyDetailCellData($_facultyCode); ?></p>
      <p><b>Faculty Department</b> : <?php echo GetDepartmentDetailCellData(GetFacultyDetailCellData($_facultyDepartment)); ?></p>
      <p><b>Faculty Email</b> : <?php echo GetFacultyDetailCellData($_facultyEmail); ?></p>
      <p><b>Faculty Join Date</b> : <?php echo date("d-m-Y", strtotime(GetFacultyDetailCellData($_facultyJoinDate))); ?></p>
    </div>
  </div>

</div>

<?php include_once("../footer.php"); ?>