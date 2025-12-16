<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

include_once("../DB/db.php");

if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 4) {
    header("Location: ../");
    exit;
}

if (!isset($_GET['action']) || $_GET['action'] !== 'download') {
    header("Location: ./");
    exit;
}

$stdLgnId = str_replace($defaultLoginExtension, "", $_SESSION[$_session_login_name] ?? "");
$sem_count = $_SESSION[$_sem_count];

function GetStudentDetailCellData($field)
{
    global $conn, $_studentTable, $_studentCode, $stdLgnId;
    $sql = "SELECT * FROM $_studentTable WHERE $_studentCode = '$stdLgnId'";
    $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
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

    return mysqli_query($conn, $sql);
}

function GetResultData($stu, $crse, $code, $yr, $typ, $crsetyp)
{
    global $conn;
    global $_resultTable, $_resultStdDtlId, $_resultStdCrseId, $_resultResultYear, $_resultResultSemType;
    global $_resultCa1, $_resultCa2, $_resultCa3, $_resultLabMarks, $_resultInternalMarks;
    global $_resultObtainedCredit, $_resultObtainedGrade;

    $typ = strtolower($typ);
    $typ = $typ == "fall" ? 1 : ($typ == "summer" ? 2 : 0);

    $sql = "SELECT $_resultObtainedCredit, $_resultObtainedGrade, $_resultCa1, $_resultCa2, $_resultCa3, $_resultLabMarks, $_resultInternalMarks 
            FROM $_resultTable 
            WHERE $_resultStdDtlId='$stu' AND $_resultStdCrseId='$crse' AND $_resultResultYear='$yr' AND $_resultResultSemType='$typ'";

    $MarksData = mysqli_query($conn, $sql);
    $MarksData = mysqli_fetch_assoc($MarksData);

    return [$MarksData[$_resultObtainedCredit] ?? "", $MarksData[$_resultObtainedGrade] ?? ""];
}

// Create Spreadsheet
$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()
    ->setCreator("Student Portal")
    ->setTitle("Credit Report");

$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Credit Report");

// Get Student ID
$stuId = GetStudentDetailCellData($_studentId);

// Fetch Semester Data
$SelectSemValue = "SELECT sv.*, st.$_studentId
                   FROM $_semesterValueTable sv 
                   LEFT JOIN $_studentTable st ON sv.$_semesterValueStudentId = st.$_studentId
                   ORDER BY sv.$_semesterValueSemNo ASC";
$SelectSemData = mysqli_query($conn, $SelectSemValue);

$currentRow = 1;
$TotalRegistered = 0;
$TotalObtained = 0;

// Process each semester
for ($i = 1; $i <= $sem_count; $i++) {
    $semDataObj = mysqli_fetch_assoc($SelectSemData);
    
    if (!$semDataObj) {
        break;
    }
    
    $semYrVlu = str_replace("-", "/", $semDataObj[$_semesterValueSemYrTyp]);
    $semYr = substr($semYrVlu, 0, 4);
    $semTyp = substr($semYrVlu, 5, strlen($semYrVlu));

    // Semester Header
    $sheet->setCellValue("A$currentRow", "Semester $i ($semYrVlu)");
    $sheet->mergeCells("A$currentRow:F$currentRow");
    $sheet->getStyle("A$currentRow")->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle("A$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A$currentRow")->getFill()
          ->setFillType(Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FF215C98');
    $sheet->getStyle("A$currentRow")->getFont()->getColor()->setARGB('FFFFFFFF');
    $currentRow++;

    // Column Headers
    $headers = ['Course Code', 'Course Name', 'Registered Credit', 'Obtained Credit', 'Result/Grade', 'Registration Type'];
    foreach ($headers as $col => $header) {
        $cellAddr = chr(65 + $col) . $currentRow;

        $sheet->setCellValue($cellAddr, $header);

        // Yellow fill
        $sheet->getStyle($cellAddr)->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFFFC000');

        // Font
        $sheet->getStyle($cellAddr)->getFont()
              ->setBold(true)
              ->getColor()->setARGB('FF215C98');

        // Center alignment
        $sheet->getStyle($cellAddr)->getAlignment()
              ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Borders
        $sheet->getStyle($cellAddr)->getBorders()->getAllBorders()
              ->setBorderStyle(Border::BORDER_THIN);
    }
    $currentRow++;

    // Get Courses for Semester
    $SemCourse = GetCourseDataFromSemYrTyp($stuId, $semYr, $semTyp);

    if (mysqli_num_rows($SemCourse) > 0) {
        $totalRegistered = 0;
        $totalObtained = 0;

        while ($course = mysqli_fetch_assoc($SemCourse)) {
            $totalRegistered += $course[$_courseCreditMarksField];
            $TotalRegistered += $course[$_courseCreditMarksField];

            $returnResultData = GetResultData(
                $stuId,
                $course[$_courseId],
                $course[$_courseCodeField],
                $semYr,
                $semTyp,
                $course[$_courseTypeField]
            );

            $isPass = $course[$_courseCreditMarksField] == $returnResultData[0];

            if ($isPass) {
                $totalObtained += $course[$_courseCreditMarksField];
                $TotalObtained += $course[$_courseCreditMarksField];
            }

            // Add Course Row
            $sheet->setCellValue("A$currentRow", $course[$_courseCodeField]);
            $sheet->setCellValue("B$currentRow", $course[$_courseNameField]);
            $sheet->setCellValue("C$currentRow", $course[$_courseCreditMarksField]);
            $sheet->setCellValue("D$currentRow", $isPass ? $course[$_courseCreditMarksField] : 0);
            $sheet->setCellValue("E$currentRow", $returnResultData[1]);
            $sheet->setCellValue("F$currentRow", $course[$_mappingStudentRegistrationType]);

            // Apply styles to all cells in row
            for ($col = 0; $col < 6; $col++) {
                $cellAddress = chr(65 + $col) . $currentRow;

                $sheet->getStyle($cellAddress)->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle($cellAddress)->getBorders()->getAllBorders()
                      ->setBorderStyle(Border::BORDER_THIN);
            }

            $currentRow++;
        }

        // Total Row for Semester (labels)
        $sheet->setCellValue("C$currentRow", "Total Registered");
        $sheet->setCellValue("D$currentRow", "Total Obtained");
        $sheet->getStyle("C$currentRow")->getFont()->setBold(true);
        $sheet->getStyle("D$currentRow")->getFont()->setBold(true);
        $sheet->getStyle("C$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $currentRow++;

        // Totals Value Row for Semester
        $sheet->setCellValue("C$currentRow", $totalRegistered);
        $sheet->setCellValue("D$currentRow", $totalObtained);
        $sheet->getStyle("C$currentRow")->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle("D$currentRow")->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle("C$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("C$currentRow")->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFFFC000');
        $sheet->getStyle("D$currentRow")->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFFFC000');

        $currentRow++;

    } else {
        // No courses registered
        $sheet->setCellValue("A$currentRow", "Not Register Yet.");
        $sheet->mergeCells("A$currentRow:F$currentRow");
        $sheet->getStyle("A$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $currentRow++;
    }

    // Empty row between semesters
    $currentRow++;
}

// Credit Summary Section
$sheet->setCellValue("A$currentRow", "Credit Summary");
$sheet->mergeCells("A$currentRow:F$currentRow");
$sheet->getStyle("A$currentRow")->getFont()->setBold(true)->setSize(12);
$sheet->getStyle("A$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A$currentRow")->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()->setARGB('FF215C98');
$sheet->getStyle("A$currentRow")->getFont()->getColor()->setARGB('FFFFFFFF');
$currentRow++;

// Summary Headers
$sheet->setCellValue("C$currentRow", "Total Registered");
$sheet->setCellValue("D$currentRow", "Total Obtained");
$sheet->getStyle("C$currentRow")->getFont()->setBold(true);
$sheet->getStyle("D$currentRow")->getFont()->setBold(true);
$sheet->getStyle("C$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("D$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$currentRow++;

// Summary Values
$sheet->setCellValue("C$currentRow", $TotalRegistered);
$sheet->setCellValue("D$currentRow", $TotalObtained);
$sheet->getStyle("C$currentRow")->getFont()->setBold(true)->setSize(11);
$sheet->getStyle("D$currentRow")->getFont()->setBold(true)->setSize(11);
$sheet->getStyle("C$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("D$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->getStyle("C$currentRow")->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()->setARGB('FFFFC000');
$sheet->getStyle("D$currentRow")->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()->setARGB('FFFFC000');

// Set Column Widths
$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(18);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(18);

// Generate filename with student ID
$fileName = "Credit_Report_" . $stdLgnId . "_" . date('Y-m-d_H-i-s') . ".xlsx";

// Output as download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
