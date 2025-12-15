<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 2) {
      header("Location: ../");
      exit;
}

if (isset($_POST['view_result'])) {
      $stdRetriveId = $_POST['stdId'];
      $stdRetriveId = "($stdRetriveId)";
      $resultRetriveYr = $_POST['yr'];
      $resultRetriveSem = $_POST['sem'];
      $resultRetriveSemType = ($resultRetriveSem == 1 || $resultRetriveSem == '1') ? "FALL" : (($resultRetriveSem == 2 || $resultRetriveSem == '2' ? "SUMMER" : "None"));
}
if (isset($_POST['view_all'])) {
      $stdRetriveId = $_POST['stdId'];
      $resultRetriveYr = $_POST['yr'];
      $resultRetriveSem = $_POST['sem'];
      $resultRetriveSemType = ($resultRetriveSem == 1 || $resultRetriveSem == '1') ? "FALL" : (($resultRetriveSem == 2 || $resultRetriveSem == '2' ? "SUMMER" : "None"));
}



function SelectStudentsFromId($id = null)
{
      global $conn, $_studentTable, $_studentId;

      $sql = "SELECT * FROM $_studentTable WHERE $_studentId IN $id";
      $res = mysqli_query($conn, $sql);

      return $res;
}
function StudentFieldFetch($ary, $field)
{
      return $ary[$field] ?? "";
}
function SelectProgramDetailsFromId($id, $field)
{
      global $conn, $_programTableName, $_programId;

      $sql = "SELECT * FROM $_programTableName WHERE $_programId='$id'";
      $res = mysqli_query($conn, $sql);

      $res = mysqli_fetch_assoc($res);

      return $res[$field] ?? "";
}
function SelectDepartmentDetailsFromId($id, $field)
{
      global $conn, $_deptTable, $_deptId;

      $sql = "SELECT * FROM $_deptTable WHERE $_deptId='$id'";
      $res = mysqli_query($conn, $sql);

      $res = mysqli_fetch_assoc($res);

      return $res[$field] ?? "";
}
function GetResultTableId($id = null)
{
      global $conn, $_resultTable, $_resultId, $_resultStdDtlId, $_resultStdCrseId, $_resultYear, $_resultSemType;

      global $stdRetriveId, $resultRetriveYr, $resultRetriveSem;

      if ($id == null)
            $sql = "SELECT * FROM $_resultTable WHERE $_resultYear='$resultRetriveYr' AND $_resultSemType='$resultRetriveSem' AND $_resultStdDtlId IN $stdRetriveId";
      else
            $sql = "SELECT * FROM $_resultTable WHERE $_resultYear='$resultRetriveYr' AND $_resultSemType='$resultRetriveSem' AND $_resultStdDtlId='$id'";
      $res = mysqli_query($conn, $sql);

      $ids = [];
      while ($data = mysqli_fetch_assoc($res)) {
            $ids[] = $data[$_resultId];
      }

      return $ids;
}
function GetResultCourse($id)
{
      $id = "(" . implode(", ", $id) . ")";
      global $conn, $_resultTable, $_resultId, $_resultStdCrseId;

      $sql = "SELECT * FROM $_resultTable WHERE $_resultId IN $id";
      $res = mysqli_query($conn, $sql);

      // echo "$sql";
      // exit;
      while ($data = mysqli_fetch_assoc($res)) {
            $ids[] = $data[$_resultStdCrseId];
      }
      return $ids ?? null;
}
function GetResultRow($id)
{
      global $conn, $_resultTable, $_resultId;

      $sql = "SELECT * FROM $_resultTable WHERE $_resultId='$id'";
      $res = mysqli_query($conn, $sql);

      $res = mysqli_fetch_assoc($res);

      return $res;
}
function GetResultFieldFromId($id, $field)
{
      global $conn, $_resultTable, $_resultId;

      $sql = "SELECT * FROM $_resultTable WHERE $_resultId='$id'";
      $res = mysqli_query($conn, $sql);

      $res = mysqli_fetch_assoc($res);

      return $res[$field];
}
function GetCourseFieldFormId($id, $field)
{
      global $conn, $_coursesTable, $_courseId;

      $sql = "SELECT * FROM $_coursesTable WHERE $_courseId='$id'";
      $res = mysqli_query($conn, $sql);

      $res = mysqli_fetch_assoc($res);

      return $res[$field] ?? null;
}
function getCourseTypeLabel($type)
{
      switch ($type) {
            case 1:
                  return "T";   // Theory
            case 2:
                  return "P";   // Practical
            case 3:
                  return "TEP";  // Term End or other specific label
            default:
                  return "";    // Default if none matches
      }
}

$n = 1;

// include_once("../header.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Student Result - Marksheet</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

      <style>
            body {
                  position: relative;
                  min-height: 100vh;
                  background: #f5f7fa;
                  font-family: 'Lato', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                  color: #2c3e50;
                  line-height: 1.6;
            }

            .watermark {
                  position: fixed;
                  top: 50%;
                  left: 50%;
                  width: 400px;
                  height: 400px;
                  opacity: 0.07;
                  transform: translate(-50%, -50%);
                  background: url('path-to-university-logo.png') no-repeat center;
                  background-size: contain;
                  pointer-events: none;
                  z-index: 0;
            }

            .grade-statement {
                  background: #ffffff;
                  padding: 2.5rem 3.5rem;
                  border-radius: 12px;
                  max-width: 900px;
                  margin: 4rem auto 3rem auto;
                  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
                  position: relative;
                  z-index: 1;
                  transition: box-shadow 0.3s ease;
            }

            .grade-statement:hover {
                  box-shadow: 0 10px 26px rgba(0, 0, 0, 0.18);
            }

            .statement-header {
                  font-weight: 700;
                  text-align: center;
                  font-size: 1.6rem;
                  margin-bottom: 2rem;
                  text-decoration: underline solid #2980b9;
                  color: #2980b9;
                  letter-spacing: 1.2px;
            }

            .info-row b {
                  color: #34495e;
                  font-weight: 600;
                  letter-spacing: 0.03em;
            }

            .table {
                  border-collapse: separate;
                  border-spacing: 0;
                  width: 100%;
                  border-radius: 8px;
                  overflow: hidden;
                  box-shadow: 0 4px 12px rgba(41, 128, 185, 0.15);
            }

            .table thead th {
                  vertical-align: middle;
                  text-align: center;
                  background-color: #2980b9;
                  color: #fff;
                  padding: 14px 18px;
                  font-weight: 600;
                  letter-spacing: 0.05em;
                  border: none;
                  user-select: none;
            }

            .table tbody td {
                  vertical-align: middle;
                  text-align: center;
                  border-top: 1px solid #e3e6ea;
                  padding: 12px 18px;
                  color: #2c3e50;
                  font-weight: 500;
            }

            .table tbody tr:hover {
                  background-color: #ecf5fb;
                  transition: background-color 0.3s ease;
            }

            .table .text-start {
                  text-align: left !important;
                  font-weight: 600;
            }

            .signature-area {
                  margin-top: 3.5rem;
                  text-align: right;
                  font-size: 1.2rem;
                  font-weight: 700;
                  color: #2980b9;
                  font-style: italic;
                  letter-spacing: 0.04em;
            }

            .footer-note {
                  font-size: 0.9rem;
                  margin-top: 2rem;
                  color: #7f8c8d;
                  text-align: center;
                  font-style: italic;
                  letter-spacing: 0.02em;
                  user-select: none;
            }

            /* Responsive */
            @media (max-width: 767px) {
                  .grade-statement {
                        padding: 1.5rem 1.5rem;
                        margin: 2rem 1rem;
                        font-size: 0.95rem;
                  }

                  .statement-header {
                        font-size: 1.3rem;
                        margin-bottom: 1.5rem;
                  }

                  .signature-area {
                        font-size: 1rem;
                  }

                  .table thead th,
                  .table tbody td {
                        padding: 10px 12px;
                        font-size: 0.9rem;
                  }
            }

            .watermark {
                  position: fixed;
                  top: 50%;
                  left: 50%;
                  width: 400px;
                  height: 400px;
                  opacity: 0.07;
                  transform: translate(-50%, -50%);
                  background: url('../assets/amity_watermark.png') no-repeat center;
                  background-size: contain;
                  pointer-events: none;
                  z-index: 0;
            }

            .signature-area {
                  text-align: right;
            }

            .signature-area .signature-img {
                  max-height: 70px;
                  margin-bottom: 10px;
                  text-align: right;
                  display: inline-block;
            }
      </style>
</head>

<body>
      <div class="container">
            <?php
            // PHP code to fetch result data goes here
            $ResData = SelectStudentsFromId($stdRetriveId);
            while ($data = mysqli_fetch_assoc($ResData)) { ?>
                  <div class="grade-statement shadow animate__animated animate__fadeIn">
                        <div class="d-flex justify-content-between mb-3">
                              <div><b>Sl. No.:</b> AUBSS24250064</div>
                              <div><!-- Optional right side info --></div>
                        </div>

                        <div class="statement-header">SEMESTER GRADE STATEMENT</div>

                        <div class="row mb-3">
                              <div class="col-md-6 info-row"><b>Student Enrolment Number:</b> <?php echo htmlspecialchars(StudentFieldFetch($data, $_studentCode)); ?></div>
                        </div>
                        <div class="row mb-3">
                              <div class="col-md-6 info-row"><b>Name of the Candidate:</b> <?php echo htmlspecialchars(StudentFieldFetch($data, $_studentName)); ?></div>
                        </div>
                        <?php $p_id = htmlspecialchars(StudentFieldFetch($data, $_studentProgram)); ?>
                        <div class="col-md-6 info-row"><b>School / Institute:</b> <?php echo  htmlspecialchars(SelectDepartmentDetailsFromId(SelectProgramDetailsFromId($p_id, $_programDeptField),  $_deptName)); ?>
                        </div>
                        <div class="row mb-3">
                              <div class="col-md-6 info-row"><b>Program:</b> <?php echo htmlspecialchars(SelectProgramDetailsFromId($p_id, $_programNameField)); ?></div>
                        </div>
                        <div class="row mb-3">
                              <div class="col-md-6 info-row"><b>Semester:</b> <?php echo htmlspecialchars($resultRetriveYr . " - " . $resultRetriveSemType); ?></div>
                        </div>

                        <table class="table table-bordered align-middle">
                              <thead>
                                    <tr>
                                          <th>Course Code</th>
                                          <th>Course Title</th>
                                          <th>Course Type</th>
                                          <th>Course Credits</th>
                                          <th>Earned Credits</th>
                                          <th>Course Grade</th>
                                    </tr>
                              </thead>
                              <tbody>
                                    <?php
                                    $multiId = explode(",", trim($stdRetriveId, '()'));

                                    $resultIds = GetResultTableId($data[$_studentId]);

                                    // Result Variable Usable
                                    $creditRegistered = 0;
                                    $earnedRegistered = 0;
                                    $declareDate = "N/A";

                                    if ($resultIds == null || count($resultIds) <= 0) {
                                          $ressultError = "Result Is Not Declared.."; ?>

                                          <td colspan="6"><?php echo $ressultError; ?></td>
                                          <?php
                                    } else {
                                          $courseIds = GetResultCourse($resultIds);


                                          for ($i = 0; $i < count($courseIds); $i++) {
                                                $cid = $courseIds[$i];
                                                $rid = $resultIds[$i];
                                          ?>
                                                <tr>
                                                      <td><?php echo GetCourseFieldFormId($cid, $_courseCodeField); ?></td>
                                                      <td class="text-start"><?php echo GetCourseFieldFormId($cid, $_courseNameField); ?></td>
                                                      <td><?php echo getCourseTypeLabel(GetCourseFieldFormId($cid, $_courseTypeField)); ?></td>
                                                      <td><?php echo GetCourseFieldFormId($cid, $_creditMarksField); ?></td>
                                                      <td><?php echo GetResultFieldFromId($rid, $_resultObtainedCredit); ?></td>
                                                      <td><?php echo "NA" ?></td>
                                                      <?php
                                                      $creditRegistered += GetCourseFieldFormId($cid, $_creditMarksField);
                                                      $earnedRegistered += GetResultFieldFromId($rid, $_resultObtainedCredit);
                                                      $declareDate = date("d/m/Y", strtotime(GetResultFieldFromId($rid, $_resultDeclareDate)));
                                                      $declareDateMmYyyy = date("F Y", strtotime(GetResultFieldFromId($rid, $_resultDeclareDate)));
                                                      ?>
                                                </tr>
                                    <?php }
                                    } ?>


                              </tbody>
                        </table>

                        <div class="row mt-4">
                              <div class="col-md-4"><b>Credits Registered:</b> <?php echo $creditRegistered; ?></div>
                              <div class="col-md-4"><b>Total Credits Registered:</b> 47</div>
                              <div class="col-md-4"><b>SGPA:</b> 0.00</div>
                        </div>

                        <div class="row">
                              <div class="col-md-4"><b>Credits Earned:</b> <?php echo $earnedRegistered; ?></div>
                              <div class="col-md-4"><b>Total Credits Earned:</b> 35</div>
                              <div class="col-md-4"><b>CGPA:</b> 8.38</div>
                        </div>

                        <div class="row mt-3">
                              <div class="col-md-7"><b>Date:</b> <?php echo $declareDate; ?></div>
                        </div>

                        <div class="signature-area">
                              <img src="../assets/signature.png" alt="Signature" style="max-height: 70px; display: block; margin-bottom: 10px;">
                              <span><b>Controller of Examinations</b></span>
                        </div>





                        <div class="mt-4 text-center footer-note">
                              <div><b>AMITY UNIVERSITY, BENGALURU</b></div>
                              <div>Opp. to Bengaluru Rural DC Office</div>
                              <div>NH-648, Devanahalli - Doddaballapura Road</div>
                              <div>Bengaluru - 562 110, Karnataka, India.</div>
                              <div>(Please turn over)</div>
                        </div>
                  </div>
            <?php } ?>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>