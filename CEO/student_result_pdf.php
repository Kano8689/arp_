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
      global $conn, $_departmentTable, $_departmentId;

      $sql = "SELECT * FROM $_departmentTable WHERE $_departmentId='$id'";
      $res = mysqli_query($conn, $sql);

      $res = mysqli_fetch_assoc($res);

      return $res[$field] ?? "";
}
function GetResultTableId($id = null)
{
      global $conn, $_resultTable, $_resultId, $_resultStdDtlId, $_resultStdCrseId, $_resultResultYear, $_resultResultSemType;

      global $stdRetriveId, $resultRetriveYr, $resultRetriveSem;

      if ($id == null)
            $sql = "SELECT * FROM $_resultTable WHERE $_resultResultYear='$resultRetriveYr' AND $_resultResultSemType='$resultRetriveSem' AND $_resultStdDtlId IN $stdRetriveId";
      else
            $sql = "SELECT * FROM $_resultTable WHERE $_resultResultYear='$resultRetriveYr' AND $_resultResultSemType='$resultRetriveSem' AND $_resultStdDtlId='$id'";
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
                  return "TEL";  // Term End or other specific label
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
                  padding-top: 60px;
                  /* button space */
            }

            /* Fixed Download Button */
            #downloadPdfBtn {
                  position: fixed;
                  top: 10px;
                  right: 20px;
                  z-index: 9999;
                  font-weight: 600;
                  padding: 12px 24px;
                  border-radius: 8px;
                  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
            }

            /* Watermark */
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

            /* A4 Perfect PDF Fit */
            .a4-page {
                  width: 190mm;
                  min-height: 277mm;
                  padding: 8mm;
                  margin: 5mm auto;
                  background: #ffffff;
                  box-shadow: none;
                  position: relative;
                  page-break-after: always;
                  page-break-inside: avoid;
            }



            .a4-page .grade-statement {
                  width: 100% !important;
                  height: 100% !important;
                  padding: 10mm 8mm !important;
                  /* Compact padding */
                  margin: 0 !important;
                  box-shadow: none !important;
                  border-radius: 0 !important;
                  max-width: none !important;
            }

            .grade-statement {
                  background: #ffffff;
                  padding: 2rem 2.5rem;
                  /* Screen padding */
                  border-radius: 12px;
                  max-width: 900px;
                  margin: 0 auto;
                  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
                  position: relative;
                  z-index: 1;
                  transition: box-shadow 0.3s ease;
            }

            .grade-statement:hover {
                  box-shadow: 0 10px 26px rgba(0, 0, 0, 0.18);
            }

            /* Compact Header */
            .statement-header {
                  font-weight: 700;
                  text-align: center;
                  font-size: 1.35rem !important;
                  /* PDF ma chhoto */
                  margin-bottom: 1rem !important;
                  text-decoration: underline solid #2980b9;
                  color: #2980b9;
                  letter-spacing: 1px;
                  padding-bottom: 0.3rem;
            }

            .info-row {
                  font-size: 0.9rem !important;
                  /* Compact */
                  margin-bottom: 0.4rem !important;
            }

            .info-row b {
                  color: #34495e;
                  font-weight: 600;
                  letter-spacing: 0.03em;
            }

            /* Compact Table */
            .table {
                  border-collapse: separate;
                  border-spacing: 0;
                  width: 100%;
                  border-radius: 8px;
                  overflow: hidden;
                  box-shadow: 0 4px 12px rgba(41, 128, 185, 0.15);
                  font-size: 0.85rem !important;
                  /* Table chhoto */
                  margin: 8px 0 !important;
            }

            .table thead th {
                  vertical-align: middle;
                  text-align: center;
                  background-color: #2980b9;
                  color: #fff;
                  padding: 8px 10px !important;
                  /* Tight padding */
                  font-weight: 600;
                  letter-spacing: 0.03em;
                  border: none;
                  font-size: 0.85rem !important;
                  user-select: none;
            }

            .table tbody td {
                  vertical-align: middle;
                  text-align: center;
                  border-top: 1px solid #e3e6ea;
                  padding: 6px 10px !important;
                  /* Tight padding */
                  color: #2c3e50;
                  font-weight: 500;
                  font-size: 0.82rem !important;
            }

            .table tbody tr:hover {
                  background-color: #ecf5fb;
                  transition: background-color 0.3s ease;
            }

            .table .text-start {
                  text-align: left !important;
                  font-weight: 600;
            }

            /* Summary rows compact */
            .row.mt-4,
            .row {
                  margin-top: 0.3rem !important;
                  font-size: 0.88rem !important;
            }

            /* Signature compact */
            .signature-area {
                  margin-top: 1.8rem !important;
                  text-align: right;
                  font-size: 1rem !important;
                  font-weight: 700;
                  color: #2980b9;
                  font-style: italic;
                  letter-spacing: 0.03em;
            }

            .signature-area .signature-img {
                  margin-top: 2rem !important;
                  max-height: 55px !important;
                  max-width: 140px !important;
                  margin-bottom: 5px;
                  display: block;
                  margin-left: auto;
                  margin-right: 0;
            }

            /* Footer compact */
            .footer-note {
                  font-size: 0.78rem !important;
                  margin-top: 1.2rem !important;
                  color: #7f8c8d;
                  text-align: center;
                  font-style: italic;
                  letter-spacing: 0.01em;
                  line-height: 1.2 !important;
                  user-select: none;
            }

            /* Print/PDF Perfect A4 */
            @page {
                  size: A4 portrait;
                  margin: 0mm;
            }

            @media print {
                  body {
                        background: white !important;
                        padding: 0 !important;
                        margin: 0 !important;
                  }

                  #downloadPdfBtn {
                        display: none !important;
                  }

                  .watermark {
                        opacity: 0.04 !important;
                  }

                  .a4-page {
                        margin: 0 !important;
                        box-shadow: none !important;
                        width: 210mm !important;
                        height: 297mm !important;
                        padding: 8mm !important;
                  }
            }

            /* Responsive */
            @media (max-width: 767px) {
                  .grade-statement {
                        padding: 1.2rem 1.2rem;
                        font-size: 0.92rem;
                  }

                  .a4-page {
                        width: 100%;
                        margin: 3mm auto;
                        padding: 8mm;
                  }
            }
      </style>
</head>

<body>
      <!-- Fixed Download Button -->
      <button id="downloadPdfBtn" class="btn btn-danger position-fixed">
            📄 Download All Results as PDF
      </button>

      <!-- Watermark -->
      <div class="watermark"></div>

      <div class="container">
            <?php
            // Tame tamara PHP variables ready rakho: $stdRetriveId, $resultRetriveYr, etc.
            // PHP code to fetch result data goes here
            $ResData = SelectStudentsFromId($stdRetriveId);
            ?>

            <!-- Result Container for PDF -->
            <div id="resultContainer">
                  <?php while ($data = mysqli_fetch_assoc($ResData)) { ?>
                        <!-- Each result in A4 page -->
                        <div class="a4-page">
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
                                    <div class="row mb-3">
                                          <div class="col-md-6 info-row"><b>School / Institute:</b> <?php echo htmlspecialchars(SelectDepartmentDetailsFromId(SelectProgramDetailsFromId($p_id, $_programDeptField), $_departmentName)); ?></div>
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

                                                $creditRegistered = 0;
                                                $earnedRegistered = 0;
                                                $declareDate = "N/A";

                                                if ($resultIds == null || count($resultIds) <= 0) {
                                                      $ressultError = "Result Is Not Declared..";
                                                ?>
                                                      <tr>
                                                            <td colspan="6"><?php echo $ressultError; ?></td>
                                                      </tr>
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
                                                                  <td><?php echo GetCourseFieldFormId($cid, $_courseCreditMarksField); ?></td>
                                                                  <td><?php echo GetResultFieldFromId($rid, $_resultObtainedCredit); ?></td>
                                                                  <td><?php echo GetResultFieldFromId($rid, $_resultObtainedGrade); ?></td>
                                                            </tr>
                                                <?php
                                                            $creditRegistered += GetCourseFieldFormId($cid, $_courseCreditMarksField);
                                                            $earnedRegistered += GetResultFieldFromId($rid, $_resultObtainedCredit);
                                                            $declareDate = date("d/m/Y", strtotime(GetResultFieldFromId($rid, $_resultResultDeclareData)));
                                                      }
                                                }
                                                ?>
                                          </tbody>
                                    </table>

                                    <div class="row mt-4">
                                          <div class="col-md-4"><b>Credits Registered:</b> <?php echo $creditRegistered; ?></div>
                                          <div class="col-md-4"><b>SGPA:</b> 0.00</div>
                                    </div>

                                    <div class="row">
                                          <div class="col-md-4"><b>Credits Earned:</b> <?php echo $earnedRegistered; ?></div>
                                          <div class="col-md-4"><b>CGPA:</b> 8.38</div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-7"><b>Date:</b> <?php echo $declareDate; ?></div>
                                    </div>

                                    <div class="signature-area">
                                          <img src="../assets/signature.png" alt="Signature" class="signature-img">
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
                        </div>
                  <?php } ?>
            </div>
      </div>



      <!-- Bootstrap -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

      <!-- html2pdf.js - Reliable CDN -->
      <script src="https://unpkg.com/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>

      <!-- PDF Download - Fixed -->
      <script>
            document.addEventListener('DOMContentLoaded', function() {
                  const btn = document.getElementById('downloadPdfBtn');

                  function checkLibrary() {
                        if (typeof html2pdf !== 'undefined') {
                              setupButton();
                        } else {
                              setTimeout(checkLibrary, 500);
                        }
                  }

                  function setupButton() {
                        btn.addEventListener('click', function() {
                              console.log('Generating PDF for', document.querySelectorAll('.a4-page').length, 'pages...');

                              const element = document.getElementById('resultContainer');
                              if (!element || element.children.length === 0) {
                                    alert('No results found!');
                                    return;
                              }

                              const opt = {
                                    margin: [8, 8, 8, 8],
                                    filename: 'Amity_All_Results_' + Date.now() + '.pdf',
                                    image: {
                                          type: 'jpeg',
                                          quality: 0.98
                                    },
                                    html2canvas: {
                                          scale: 1.2,
                                          useCORS: false,
                                          letterRendering: true,
                                          allowTaint: true
                                    },
                                    jsPDF: {
                                          unit: 'mm',
                                          format: 'a4',
                                          orientation: 'portrait'
                                    },
                                    pagebreak: {
                                          mode: ['avoid-all', 'css', 'legacy'],
                                          before: '.a4-page:last-of-type'
                                    }
                              };

                              // Progress show
                              btn.innerHTML = 'Generating PDF... ⏳';
                              btn.disabled = true;

                              html2pdf()
                                    .set(opt)
                                    .from(element)
                                    .save()
                                    .then(() => {
                                          console.log('✅ All pages downloaded!');
                                          btn.innerHTML = '📄 Download All Results as PDF';
                                          btn.disabled = false;
                                    })
                                    .catch(error => {
                                          console.error('❌ Error:', error);
                                          alert('Failed: ' + error.message);
                                          btn.innerHTML = '📄 Download All Results as PDF';
                                          btn.disabled = false;
                                    });
                        });
                        console.log('✅ Button ready! Results count:', document.querySelectorAll('.a4-page').length);
                  }

                  checkLibrary();
            });
      </script>

</body>

</html>