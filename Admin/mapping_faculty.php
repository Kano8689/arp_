<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 1) {
    header("Location: ../");
    exit;
}

require '../vendor/autoload.php';
include_once("../DB/pagination.php");   // ✅ added pagination functions
use PhpOffice\PhpSpreadsheet\IOFactory;

$currentPage = 0;
$currentLimit = 0;


$mappingRes = mysqli_query($conn, "SELECT * FROM $_mappingFacultyTable");
// print("<pre>");
// print_r($mappingRes);
// exit;

$currentYear = date('Y');
$nextYear = date('y', strtotime('+1 year'));
$academicYear = $currentYear . '-' . $nextYear;
// echo $academicYear;
// exit;

// ✅ get page & limit
list($currentPage, $currentLimit) = getPaginationParams();
$redirectUrl = "mapping_faculty.php?page=$currentPage&limit=$currentLimit";


$filterAcademicYear = isset($_GET['filter_academic_year']) ? mysqli_real_escape_string($conn, $_GET['filter_academic_year']) : '';
$filterSemesterType = isset($_GET['filter_semester_type']) ? (int) $_GET['filter_semester_type'] : 0;
$filterProgram = isset($_GET['filter_program']) ? mysqli_real_escape_string($conn, $_GET['filter_program']) : '';
$filterStudent = isset($_GET['filter_student']) ? mysqli_real_escape_string($conn, $_GET['filter_student']) : '';
$filterCourse = isset($_GET['filter_course']) ? mysqli_real_escape_string($conn, $_GET['filter_course']) : '';
$filterFaculty = isset($_GET['filter_faculty']) ? mysqli_real_escape_string($conn, $_GET['filter_faculty']) : '';
$filterSlot = isset($_GET['filter_slot']) ? mysqli_real_escape_string($conn, $_GET['filter_slot']) : '';

$whereClauses = [];

// Filter Academic Year
if ($filterAcademicYear !== '') {
    $whereClauses[] = "$_mappingFacultySemesterYear = '$filterAcademicYear'";
}

// Filter Semester Type
if ($filterSemesterType > 0) {
    $whereClauses[] = "$_mappingFacultySemesterType = $filterSemesterType";
}

// Filter Program - need to get program ID from program name, then filter students enrolled in that program, then filter mapping by those student IDs
if ($filterProgram !== '') {
    $progId = GetProgramNameId($filterProgram, false);

    // Find student IDs in that program
    $studentIds = [];
    $studentQuery = "SELECT $_studentId FROM $_studentTable WHERE $_studentProgram = '$progId'";
    $studentResult = mysqli_query($conn, $studentQuery);
    while ($row = mysqli_fetch_assoc($studentResult)) {
        $studentIds[] = $row[$_studentId];
    }
    if (count($studentIds) > 0) {
        $whereClauses[] = $_stuId . " IN ('" . implode("','", $studentIds) . "')";
    } else {
        // No students in program => no results
        $whereClauses[] = "0";
    }
}

// Filter Student - get student ID from name then filter
if ($filterStudent !== '') {
    $studentIdQuery = "SELECT $_studentId FROM $_studentTable WHERE $_studentName = '$filterStudent' LIMIT 1";
    $resStu = mysqli_query($conn, $studentIdQuery);
    $stuRow = mysqli_fetch_assoc($resStu);
    if ($stuRow) {
        $whereClauses[] = $_stuId . " = '" . $stuRow[$_studentId] . "'";
    } else {
        $whereClauses[] = "0";
    }
}

// Filter Course - get course ID from name and filter
if ($filterCourse !== '') {
    $courseIdQuery = "SELECT $_courseId FROM $_coursesTable WHERE $_courseNameField = '$filterCourse' LIMIT 1";
    $resCourse = mysqli_query($conn, $courseIdQuery);
    $courseRow = mysqli_fetch_assoc($resCourse);
    if ($courseRow) {
        $whereClauses[] = $_mappingFacultyCourseId . " = '" . $courseRow[$_courseId] . "'";
    } else {
        $whereClauses[] = "0";
    }
}

// Filter Faculty - get faculty ID from name and filter
if ($filterFaculty !== '') {
    $facultyIdQuery = "SELECT $_facultyId FROM $_facultyTable WHERE $_facultyName = '$filterFaculty' LIMIT 1";
    $resFac = mysqli_query($conn, $facultyIdQuery);
    $facRow = mysqli_fetch_assoc($resFac);
    if ($facRow) {
        $whereClauses[] = $_mappingFacultyId . " = '" . $facRow[$_facultyId] . "'";
    } else {
        $whereClauses[] = "0";
    }
}

// Filter Slot - get slot ID from name and filter
if ($filterSlot !== '') {
    $slotIdQuery = "SELECT $_slotId FROM $_slotTable WHERE $_slotName = '$filterSlot' LIMIT 1";
    $resSlot = mysqli_query($conn, $slotIdQuery);
    $slotRow = mysqli_fetch_assoc($resSlot);
    if ($slotRow) {
        $whereClauses[] = $_mappingFacultySlotId . " = '" . $slotRow[$_slotId] . "'";
    } else {
        $whereClauses[] = "0";
    }
}

$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = " WHERE " . implode(" AND ", $whereClauses);
}

$filterQuery = '';
foreach (['filter_academic_year', 'filter_semester_type', 'filter_program', 'filter_student', 'filter_course', 'filter_faculty', 'filter_slot'] as $param) {
    if (isset($_GET[$param]) && $_GET[$param] !== '') {
        $filterQuery .= '&' . $param . '=' . urlencode($_GET[$param]);
    }
}


// Delete Mapping
if (isset($_GET['del_id'])) {
    $del_id = $_GET['del_id'];
    $deleteProgram = "DELETE FROM $_mappingFacultyTable WHERE $_mappingFacultyTblId = $del_id";

    mysqli_query($conn, $deleteProgram);
    header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
    exit;
}

if (isset($_POST['editFacMapping'])) {
    $edit_map_id = $_POST['edit_map_id'];

    $academicYear = $_POST['academic_year'];
    $semesterType = $_POST['semester_type'];
    // $program = $_POST['program'];
    $student = $_POST['student'];
    $course = $_POST['course'];
    $slot = $_POST['slot'];
    $faculty = $_POST['faculty'];
    $slotYear = $_POST['semester_year'];

    $stuId = GetStudentNameId($student, false);
    $facId = GetFacultyNameId($faculty, false);
    $crseId = GetCourseNameId($course, false);
    $sltId = GetSlotNameId($slot, false);
    $semesterType = $semesterType == "Fall" ? 1 : (($semesterType == "Summer") ? 2 : 0);

    FacultyUniqueForFreeze($facId, $crseId, $slotYear, $semesterType);
    
    $where = "$_stuId='$stuId' AND $_mappingFacultySlotId='$sltId' AND $_mappingFacultySemesterYear='$slotYear' AND $_mappingFacultySemesterType='$semesterType'";

    if (isUniqueOrNot($conn, $_mappingFacultyTable, $where)) {
        $updateMapping = "UPDATE $_mappingFacultyTable SET $_stuId='$stuId', $_mappingFacultyId='$facId', $_mappingFacultyCourseId='$crseId', $_mappingFacultySlotId='$sltId', $_mappingFacultySemesterYear='$slotYear', $_mappingFacultySemesterType='$semesterType' WHERE $_mappingFacultyTblId='$edit_map_id'";

        $abc = mysqli_query($conn, $updateMapping);
        header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
    } else {
        $uniqError = "Record is not updated due to mapping already exists..";
    }
}

if (isset($_POST['addMapping'])) {
    $academicYear = $_POST['academic_year'];
    $semesterType = $_POST['semester_type'];
    $course = $_POST['course'];
    $slot = $_POST['slot'];
    $faculty = $_POST['faculty'];
    $slotYear = $_POST['semester_year'];

    $facId = GetFacultyNameId($faculty, false);
    $crseId = GetCourseNameId($course, false);
    $sltId = GetSlotNameId($slot, false);
    $semesterType = $semesterType == "Fall" ? 1 : (($semesterType == "Summer") ? 2 : 0);

    FacultyUniqueForFreeze($facId, $crseId, $slotYear, $semesterType);

    $where = "$_mappingFacultyId='$facId' AND $_mappingFacultySlotId='$sltId' AND $_mappingFacultySemesterYear='$slotYear' AND $_mappingFacultySemesterType='$semesterType'";

    if (isUniqueOrNot($conn, $_mappingFacultyTable, $where)) {
        $insertMapping = "INSERT INTO $_mappingFacultyTable ($_mappingFacultyId, $_mappingFacultyCourseId, $_mappingFacultySlotId, $_mappingFacultySemesterYear, $_mappingFacultySemesterType) VALUES ('$facId', '$crseId', '$sltId', '$slotYear', '$semesterType')";

        $abc = mysqli_query($conn, $insertMapping);
        header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit
    } else {
        $uniqError = "Record is not insetred due to mapping already exists..";
    }
}

if (isset($_POST['addFile'])) {
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

    switch ($ext) {
        case 'csv':
            $handle = fopen($_FILES['file']['tmp_name'], "r");

            while ($row = fgetcsv($handle)) {
                GetAndSaveDataFromFile($row);
            }

            break;

        case 'xlsx':
        case 'xls':
            $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            foreach ($sheetData as $index => $row) {
                GetAndSaveDataFromFile($row);
            }

            break;

        case 'sql':
            $sqlContent = file_get_contents($_FILES['file']['tmp_name']);
            if ($sqlContent) {
                $queries = explode(";", $sqlContent);
                foreach ($queries as $query) {
                    $trimmedQuery = trim($query);
                    if ($trimmedQuery) {
                        mysqli_query($conn, $trimmedQuery);
                    }
                }
            }
            break;
    }
    header("Location: $redirectUrl$filterQuery");  // ✅ redirect with page & limit

    // exit;
}

function GetAndSaveDataFromFile($ary)
{
    global $conn, $_mappingFacultyTable, $defaultLoginExtension;
    global $_stuId, $_mappingFacultyId, $_mappingFacultyCourseId, $_mappingFacultySlotId, $_mappingFacultySemesterYear, $_mappingFacultySemesterType;
    global $_loginTable, $_loginUsername, $_loginPassword, $_loginUserType;

    $fields = [$_stuId, $_mappingFacultyId, $_mappingFacultyCourseId, $_mappingFacultySlotId, $_mappingFacultySemesterYear, $_mappingFacultySemesterType];

    // From student table
    $mappingStuEnNo = mysqli_real_escape_string($conn, $ary[1] ?? '');
    $mappingStuName = mysqli_real_escape_string($conn, $ary[2] ?? '');
    $mappingAdtYr = mysqli_real_escape_string($conn, $ary[3] ?? '');
    // From student to program table
    $mappingProgram = mysqli_real_escape_string($conn, $ary[4] ?? '');
    // From facultuy table
    $mappingFacName = mysqli_real_escape_string($conn, $ary[5] ?? '');
    // From course table
    $mappingCourseCode = mysqli_real_escape_string($conn, $ary[6] ?? '');
    $mappingCourseName = mysqli_real_escape_string($conn, $ary[7] ?? '');
    $mappingCourseType = mysqli_real_escape_string($conn, $ary[8] ?? '');
    $mappingCourseTheory = mysqli_real_escape_string($conn, $ary[9] ?? '');
    $mappingCoursePracticle = mysqli_real_escape_string($conn, $ary[10] ?? '');
    $mappingCourseCredits = mysqli_real_escape_string($conn, $ary[11] ?? '');
    // From slot table
    $mappingSlotName = mysqli_real_escape_string($conn, $ary[12] ?? '');
    // From direct table
    $mappingSlotYear = mysqli_real_escape_string($conn, $ary[13] ?? '');
    $mappingSemType = mysqli_real_escape_string($conn, $ary[14] ?? '');

    // $stuId, $facId, $crseId, $sltId, $sltYr, $semTyp;

    $stuId = GetStudentNameId($mappingStuName, false);
    $facId = GetFacultyNameId($mappingFacName, false);
    $crseId = GetCourseNameId($mappingCourseName, false);
    $sltId = GetSlotNameId($mappingSlotName, false);

    $semesterType = strtolower($mappingSemType) == "fall" ? 1 : (strtolower($mappingSemType) == "summer" ? 2 : 0);



    $data = [$stuId, $facId, $crseId, $sltId, $mappingSlotYear, $semesterType];

    // echo $crseId."<br>";
    // echo $mappingCourseName."<br>";
    // exit;
    $whereData = $_stuId . " = '" . $stuId . "' and " . $_mappingFacultySlotId . " = '" . $sltId . "' and " . $_mappingFacultySemesterYear . " = '" . $mappingSlotYear . "' and " . $_mappingFacultySemesterType . " = '" . $semesterType . "'";

    // echo $semesterType."<br>";
    // echo $whereData."<br>";
    // exit;

    FacultyUniqueForFreeze($facId, $crseId, $mappingSlotYear, $semesterType);

    FieldStringSetter($conn, $_mappingFacultyTable, $fields, $data, $whereData);
}

function FacultyUniqueForFreeze($fac, $crse, $year, $sem)
{
    global $conn, $_freezePushPermissionTable, $_freezePushPermissionAcademicYear;
    global $_freezePushPermissionFacId, $_freezePushPermissionCourseId, $_freezePushPermissionAcademicYear, $_freezePushPermissionAcademicSem;

    $where = "$_freezePushPermissionFacId='$fac' AND $_freezePushPermissionCourseId='$crse' AND $_freezePushPermissionAcademicYear='$year' AND $_freezePushPermissionAcademicSem='$sem'";

    if (isUniqueOrNot($conn, $_freezePushPermissionTable, $where)) {
        // echo "HEllo...<br>";
        $InsertingFacPer = "INSERT INTO $_freezePushPermissionTable ($_freezePushPermissionFacId, $_freezePushPermissionCourseId, $_freezePushPermissionAcademicYear, $_freezePushPermissionAcademicSem) VALUES ('$fac', '$crse', $year, '$sem')";
        // echo "$InsertingFacPer";
        mysqli_query($conn, $InsertingFacPer);
        // exit;
    }
}

function GetStudentNameId($val, $isGetName = true)
{
    global $conn, $_studentTable;
    global $_studentId, $_studentName;

    $field = $isGetName ? $_studentId : $_studentName;

    $res = mysqli_query($conn, "SELECT * FROM $_studentTable WHERE $field = '$val'");
    $row = mysqli_fetch_assoc($res);

    if ($isGetName)
        return $row[$_studentName] ?? null;
    else
        return $row[$_studentId] ?? null;
}

function GetFacultyNameId($val, $isGetName = true)
{
    global $conn, $_facultyTable;
    global $_facultyId, $_facultyName;

    $field = $isGetName ? $_facultyId : $_facultyName;

    $res = mysqli_query($conn, "SELECT * FROM $_facultyTable WHERE $field = '$val'");
    $row = mysqli_fetch_assoc($res);

    if ($isGetName)
        return $row[$_facultyName] ?? null;
    else
        return $row[$_facultyId] ?? null;
}
function GetFacultyCodeField($field, $id)
{
    global $conn, $_facultyTable;
    global $_facultyId;

    $sql = "SELECT * FROM $_facultyTable WHERE $_facultyId = '$id'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    return $row[$field] ?? null;
}

function GetCourseNameId($val, $isGetName = true)
{
    global $conn, $_coursesTable;
    global $_courseId, $_courseNameField;

    $field = $isGetName ? $_courseId : $_courseNameField;
    $slct = "SELECT * FROM $_coursesTable WHERE $field = '$val'";
    $res = mysqli_query($conn, $slct);
    $row = mysqli_fetch_assoc($res);

    if ($isGetName)
        return $row[$_courseNameField] ?? null;
    else
        return $row[$_courseId] ?? null;
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

function GetProgramNameId($val, $isGetName = true)
{
    global $conn, $_programTableName;
    global $_programId, $_programNameField;

    $field = $isGetName ? $_programId : $_programNameField;

    $slct = "SELECT * FROM $_programTableName WHERE $field = '$val'";
    $res = mysqli_query($conn, $slct);
    $row = mysqli_fetch_assoc($res);
    
    if ($isGetName)
        return $row[$_programNameField] ?? null;
    else
        return $row[$_programId] ?? null;
}

function GetStudentDetails($fieldName, $fieldValue)
{
    global $conn, $_studentTable;
    global $_studentId;

    $slct = "SELECT $fieldName FROM $_studentTable WHERE $_studentId = '$fieldValue'";
    $res = mysqli_query($conn, $slct);
    $data = mysqli_fetch_assoc($res);
    return $data[$fieldName] ?? null;
}

function GetStuFileDataId($where)
{
    global $conn, $_studentTable;
    global $_studentId;

    $slct = "SELECT * FROM $_studentTable WHERE $where";
    $res = mysqli_query($conn, $slct);
    $data = mysqli_fetch_assoc($res);
    return $data[$_studentId] ?? null;
}

function GetCourseFileDetail($where)
{
    global $conn, $_coursesTable;
    global $_courseCodeField;

    $slct = "SELECT * FROM $_coursesTable WHERE $where";
    $res = mysqli_query($conn, $slct);
    $data = mysqli_fetch_assoc($res);
    return $data[$_courseCodeField] ?? null;
}

function GetCourseDetails($fieldName, $fieldValue)
{
    global $conn, $_coursesTable;
    global $_courseId;

    $slct = "SELECT $fieldName FROM $_coursesTable WHERE $_courseId = '$fieldValue'";
    $res = mysqli_query($conn, $slct);
    $data = mysqli_fetch_assoc($res);
    return $data[$fieldName] ?? null;
}


include_once("../header.php");

list($currentPage, $currentLimit) = getPaginationParams();

// ✅ Force integer values
$currentPage = (int) $currentPage;
$currentLimit = (int) $currentLimit;

$offset = ($currentPage - 1) * $currentLimit;

$deptRes = mysqli_query($conn, "SELECT * FROM $_departmentTable");
$courseRes = mysqli_query($conn, "SELECT * FROM $_coursesTable");
$programRes = mysqli_query($conn, "SELECT * FROM $_programTableName");
$slotRes = mysqli_query($conn, "SELECT * FROM $_slotTable");
$facultiesRes = mysqli_query($conn, "SELECT * FROM $_facultyTable ORDER BY $_facultyId DESC");
$studentsRes = mysqli_query($conn, "SELECT * FROM $_studentTable ORDER BY $_studentId DESC");

$mappingStudentRes = mysqli_query($conn, "SELECT * FROM $_mappingFacultyTable");
$mappingRes = mysqli_query($conn, "SELECT * FROM $_mappingFacultyTable $whereSQL ORDER BY created_at DESC LIMIT $offset, $currentLimit");

$mappingStudentRes1 = mysqli_query($conn, "SELECT * FROM $_mappingFacultyTable");
$mappingRes1 = mysqli_query($conn, "SELECT * FROM $_mappingFacultyTable");
$totalRows = mysqli_num_rows($mappingRes1);


?>

<div class="container">
    <div class="page-header">
        <h3>Mapping</h3>
        <div class="breadcrumb-box">
            <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Admin</span></a>
            <span class="sep">></span>
            <span class="crumb-link" style="pointer-events:none;opacity:.6">Mapping</span>
        </div>
    </div>

    <?php if (isset($uniqError)) { ?>
        <div class="card">
            <?php echo $uniqError; ?>
        </div>
    <?php } ?>


    <div class="card">
        <div class="small-muted">Create and manage Program–Semester–Course–Slot–Faculty mappings</div>

        <div style="margin-top:10px;">
            <button class="btn" type="button" onclick="toggleFilter()">🔍 Filter</button>
            <button class="btn" id="addMappingBtn" onclick="openmapping('facMappingModal')">+ Add Mapping</button>
            <button class="btn" onclick="open3Modal('uploadModal')">+ Add File (csv/xlsx/sql)</button>
        </div>

        <div id="filterBox"
            style="display:<?php echo ($filterAcademicYear || $filterSemesterType || $filterProgram || $filterStudent || $filterCourse || $filterFaculty || $filterSlot) ? 'block' : 'none'; ?>; margin-top:10px; background:#f8f8f8; padding:15px; border-radius:8px;">
            <form method="GET" action="mapping_faculty.php">
                <div style="display:flex; flex-wrap: wrap; gap: 10px;">

                    <select name="filter_faculty" style="flex:1; padding:10px;">
                        <option value="">-- Select Faculty --</option>
                        <?php mysqli_data_seek($facultiesRes, 0);
                        while ($row = mysqli_fetch_assoc($facultiesRes)) {
                            $selected = (isset($_GET['filter_faculty']) && $_GET['filter_faculty'] == $row[$_facultyName]) ? 'selected' : '';
                            echo "<option value='{$row[$_facultyName]}' $selected>{$row[$_facultyCode]} - {$row[$_facultyName]}</option>";
                        }
                        ?>
                    </select>

                    <select name="filter_slot" style="flex:1; padding:10px;">
                        <option value="">-- Select Slot --</option>
                        <?php mysqli_data_seek($slotRes, 0);
                        while ($row = mysqli_fetch_assoc($slotRes)) {
                            $selected = (isset($_GET['filter_slot']) && $_GET['filter_slot'] == $row[$_slotName]) ? 'selected' : '';
                            echo "<option value='{$row[$_slotName]}' $selected>{$row[$_slotName]}</option>";
                        }
                        ?>
                    </select>

                    <select name="filter_academic_year" style="flex:1; padding:10px;">
                        <option value="">-- Select Year --</option>
                        <?php
                        $currentYear = date('Y');
                        for ($i = 0; $i < 5; $i++) {
                            $start = $currentYear - $i;
                            $yr = $start;
                            $selected = (isset($_GET['filter_academic_year']) && $_GET['filter_academic_year'] == $yr) ? 'selected' : '';
                            echo "<option value='$yr' $selected>$yr</option>";
                        }
                        ?>
                    </select>

                    <select name="filter_semester_type" style="flex:1; padding:10px;">
                        <option value="">-- Semester Type --</option>
                        <option value="1" <?= (isset($_GET['filter_semester_type']) && $_GET['filter_semester_type'] == '1') ? 'selected' : '' ?>>Fall</option>
                        <option value="2" <?= (isset($_GET['filter_semester_type']) && $_GET['filter_semester_type'] == '2') ? 'selected' : '' ?>>Summer</option>
                    </select>

                    <select name="filter_course" style="flex:1; padding:10px;">
                        <option value="">-- Select Course --</option>
                        <?php mysqli_data_seek($courseRes, 0);
                        while ($row = mysqli_fetch_assoc($courseRes)) {
                            $selected = (isset($_GET['filter_course']) && $_GET['filter_course'] == $row[$_courseNameField]) ? 'selected' : '';
                            echo "<option value='{$row[$_courseNameField]}' $selected>{$row[$_courseCodeField]} - {$row[$_courseNameField]}</option>";
                        }
                        ?>
                    </select>

                </div>

                <div style="margin-top:10px;">
                    <button class="btn" type="submit">Apply Filter</button>
                    <button type="button" class="btn btn-light" onclick="resetFilter()">Cancel</button>
                </div>
            </form>
        </div>


        <div class="table-responsive" style="margin-top: 20px;">
            <table class="table" style="margin-top:15px;">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th style="text-align: center;">Faculty Code-Name</th>
                        <th style="text-align: center;">Slot</th>
                        <th style="text-align: center;">Year</th>
                        <th style="text-align: center;">Semester Type</th>
                        <th style="text-align: center;">Course Code-Name</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody id="mappingTbody"></tbody>
                <?php $num = 1;
                mysqli_data_seek($mappingRes, 0);
                while ($row = mysqli_fetch_assoc($mappingRes)) { ?>
                    <tr>
                        <?php
                        $sem_typ = $row[$_mappingFacultySemesterType] == 1 ? "FALL" : ($row[$_mappingFacultySemesterType] == 2 ? "SUMMER" : "NONE");
                        $type = GetCourseDetails($_courseTypeField, $row[$_mappingFacultyCourseId]);
                        $course_typ = $type == 1 ? "T" : ($type == 2 ? "P" : ($type == 3 ? "TEL" : "None"));
                        ?>
                        <td style="text-align: center;"><?php echo $num++; ?></td>
                        <td style="text-align: start;"><?php echo GetFacultyCodeField($_facultyCode, $row[$_mappingFacultyId])." - ".GetFacultyNameId($row[$_mappingFacultyId]); ?></td>
                        <td style="text-align: center;"><?php echo GetSlotNameId($row[$_mappingFacultySlotId]); ?></td>
                        <td style="text-align: center;"><?php echo $row[$_mappingFacultySemesterYear]; ?></td>
                        <td style="text-align: center;"><?php echo $sem_typ; ?></td>
                        <td style="text-align: start;"><?php echo GetCourseDetails($_courseCodeField, $row[$_mappingFacultyCourseId])." - ".GetCourseDetails($_courseNameField, $row[$_mappingFacultyCourseId]); ?>
                        </td>
                        <td style="text-align: center; padding: 5px 0;">
                            <form method="POST">
                                <!-- Update button -->
                                 <?php $nq = "N/A"; ?>
                                <a onclick="editMapping('<?php echo $row[$_mappingFacultyTblId]; ?>',
                                                '<?php echo $nq; ?>',
                                                '<?php echo GetFacultyNameId($row[$_mappingFacultyId]); ?>',
                                                '<?php echo GetCourseDetails($_courseNameField, $row[$_mappingFacultyCourseId]); ?>',
                                                '<?php echo GetSlotNameId($row[$_mappingFacultySlotId]); ?>',
                                                '<?php echo $row[$_mappingFacultySemesterType]; ?>',
                                                '<?php echo $row[$_mappingFacultySemesterYear]; ?>',
                                                '<?php echo $currentPage; ?>',
                                                '<?php echo $currentLimit; ?>',
                                                '<?php echo htmlspecialchars($filterAcademicYear); ?>',
                                                '<?php echo htmlspecialchars($filterSemesterType); ?>',
                                                '<?php echo htmlspecialchars($filterProgram); ?>',
                                                '<?php echo htmlspecialchars($filterStudent); ?>',
                                                '<?php echo htmlspecialchars($filterCourse); ?>',
                                                '<?php echo htmlspecialchars($filterFaculty); ?>',
                                                '<?php echo htmlspecialchars($filterSlot); ?>'
                                          )" class="btn btn-light" style="display:inline-block; margin-right:5px;">
                                    ✏️ Edit
                                </a>

                                <!-- Delete button -->
                                <a href="mapping_faculty.php?del_id=<?php echo $row[$_mappingFacultyTblId]; ?>&page=<?php echo $currentPage; ?>&limit=<?php echo $currentLimit; ?><?php echo $filterQuery; ?>"
                                    class="btn btn-danger" style="text-decoration: none;">
                                    🗑 Delete
                                </a>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <!-- ✅ pagination UI -->
        <?php
        $totalRecords = paginationUI($conn, $_mappingFacultyTable, $currentPage, $currentLimit, $whereSQL, $filterQuery);

        // Show record count under table
        if ($totalRecords > 0) {
            $startRecord = ($currentPage - 1) * $currentLimit + 1;
            $endRecord = min($startRecord + $currentLimit - 1, $totalRecords);
            echo "<div style='margin-top:10px; font-weight:bold;'>";
            echo "Showing $startRecord to $endRecord of $totalRecords records";
            echo "</div>";
        }
        ?>
    </div>
</div>

<!-- Mapping Add Modal -->
<div class="modal-overlay" id="facMappingModal">
    <div class="modal">
        <span class="close-btn" onclick="closeMapping('facMappingModal')">&times;</span>

        <h3 id="modalTitle">Add Mapping</h3>
        <hr><br>
        <form id="mappingForm" method="POST">
            <div class="row-2">
                <div>
                    <label class="fw-bold">Semester Year</label>
                    <select name="semester_year" id="semesteryear" required>
                        <option value="">Select Year</option>
                        <?php $num = 0;
                        $currentYear = date('Y'); ?>
                        <?php for ($i = 0; $i < 5; $i++) {
                            $startYear = $currentYear - $i;
                            $academicYear = $startYear;
                        ?>
                            <option value="<?php echo $academicYear; ?>"><?php echo $academicYear; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label class="fw-bold">Semester Type</label>
                    <select name="semester_type" id="semesterName" required>
                        <option value="">Select Semester Type</option>
                        <option value="Fall">Fall</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>

            </div>

            <div class="row-2">

                <div>
                    <label class="fw-bold">Slot</label>
                    <select name="slot" id="slot" required>
                        <option value="">Select Slot</option>
                        <?php mysqli_data_seek($slotRes, 0);
                        while ($row = mysqli_fetch_assoc($slotRes)) { ?>
                            <option value="<?php echo $row[$_slotName]; ?>"><?php echo $row[$_slotName]; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label class="fw-bold">Registration Type</label>
                    <select name="academic_year" id="academicYear" required>
                        <option value="">Select Registration Type</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Backlog">Backlog</option>
                        <option value="Withdrawl">Withdrawl</option>
                        <option value="Improvement">Improvement</option>
                    </select>
                </div>

            </div>

            <div class="row">
                <div>
                    <label class="fw-bold">Course</label>
                    <select name="course" id="course" required>
                        <option value="">Select Course</option>
                        <?php mysqli_data_seek($courseRes, 0);
                        while ($row = mysqli_fetch_assoc($courseRes)) { ?>
                            <option value="<?php echo $row[$_courseNameField]; ?>">
                                <?php echo $row[$_courseCodeField] . " - " . $row[$_courseNameField]; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div>
                    <label class="fw-bold">Faculty</label>
                    <select name="faculty" id="faculty" required>
                        <option value="">Select Faculty</option>
                        <?php mysqli_data_seek($facultiesRes, 0);
                        while ($row = mysqli_fetch_assoc($facultiesRes)) { ?>
                            <option value="<?php echo $row[$_facultyName]; ?>"><?php echo $row[$_facultyCode]." - ".$row[$_facultyName]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="button" class="btn btn-secondary" onclick="closeMapping('facMappingModal')">Cancel</button>
                <button type="submit" name="addMapping" class="btn">Mapping</button>
            </div>

            <input type="hidden" id="editIndex" name="edit_index">
        </form>
    </div>
</div>

<!-- Mapping Edit Modal -->
<div class="modal-overlay" id="editmappingModal">
    <div class="modal">
        <span class="close-btn" onclick="closeMapping('editmappingModal')">&times;</span>

        <h3 id="modalTitle">Edit Mapping</h3>
        <hr><br>
        <form id="mappingForm" method="POST">
            <input type="hidden" name="page" id="edit_page" value="<?php echo $currentPage; ?>">
            <input type="hidden" name="limit" id="edit_limit" value="<?php echo $currentLimit; ?>"><br>

            <input type="hidden" name="edit_map_id" id="edit_map_id" value="">

            <!-- Add these hidden inputs -->
            <input type="hidden" name="filter_academic_year" id="edit_filter_academic_year">
            <input type="hidden" name="filter_semester_type" id="edit_filter_semester_type">
            <input type="hidden" name="filter_program" id="edit_filter_program">
            <input type="hidden" name="filter_student" id="edit_filter_student">
            <input type="hidden" name="filter_course" id="edit_filter_course">
            <input type="hidden" name="filter_faculty" id="edit_filter_faculty">
            <input type="hidden" name="filter_slot" id="edit_filter_slot">
            <div class="row-2">

                <div>
                    <label class="fw-bold">Semester Year</label>
                    <select name="semester_year" id="edit_semesteryear" required>
                        <option value="">Select Year</option>
                        <?php $num = 0;
                        $currentYear = date('Y'); ?>
                        <?php for ($i = 0; $i < 2; $i++) {
                            $startYear = ($currentYear - $i) + 1;
                            $academicYear = $startYear;
                        ?>
                            <option value="<?php echo $academicYear; ?>"><?php echo $academicYear; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label class="fw-bold">Semester Type</label>
                    <select name="semester_type" id="edit_semesterName" required>
                        <option value="">Select Semester Type</option>
                        <option value="Fall">Fall</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>
            </div>


            <div class="row-2">

                <div>
                    <label class="fw-bold">Slot</label>
                    <select name="slot" id="edit_slot" required>
                        <option value="">Select Slot</option>
                        <?php mysqli_data_seek($slotRes, 0);
                        while ($row = mysqli_fetch_assoc($slotRes)) { ?>
                            <option value="<?php echo $row[$_slotName]; ?>"><?php echo $row[$_slotName]; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label class="fw-bold">Registration Type</label>
                    <select name="academic_year" id="academicYear" required>
                        <option value="">Select Registration Type</option>
                        <option value="Fresh">Fresh</option>
                        <option value="Backlog">Backlog</option>
                        <option value="Withdrawl">Withdrawl</option>
                        <option value="Improvement">Improvement</option>
                    </select>
                </div>

            </div>

            <div class="row">
                <div>
                    <label class="fw-bold">Course</label>
                    <select name="course" id="edit_course" required>
                        <option value="">Select Course</option>
                        <?php mysqli_data_seek($courseRes, 0);
                        while ($row = mysqli_fetch_assoc($courseRes)) { ?>
                            <option value="<?php echo $row[$_courseNameField]; ?>">
                                <?php echo $row[$_courseCodeField] . " - " . $row[$_courseNameField]; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div>
                    <label class="fw-bold">Faculty</label>
                    <select name="faculty" id="edit_field_name" required>
                        <option value="">Select Faculty</option>
                        <?php mysqli_data_seek($facultiesRes, 0);
                        while ($row = mysqli_fetch_assoc($facultiesRes)) { ?>
                            <option value="<?php echo $row[$_facultyName]; ?>"><?php echo $row[$_facultyCode]." - ".$row[$_facultyName]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="button" class="btn btn-secondary"
                    onclick="closeMapping('editmappingModal')">Cancel</button>
                <button type="submit" name="editMapping" class="btn">Edit Mapping</button>
            </div>

            <input type="hidden" id="editIndex" name="edit_index">
        </form>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal-overlay" id="uploadModal">
    <div class="modal">
        <span class="close-btn" onclick="close3Modal('uploadModal')">&times;</span>
        <h3>Upload File</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="facultyFile" accept=".csv,.xlsx,.sql">
            <div class="modal-actions">
                <button class="btn" name="addFile">Upload</button>
                <button class="btn btn-light" onclick="close3Modal('uploadModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleFilter() {
        var box = document.getElementById("filterBox");
        box.style.display = (box.style.display === "none" || box.style.display === "") ? "block" : "none";
    }

    function resetFilter() {
        window.location.href = "mapping_faculty.php";
    }
</script>

<?php include_once("../footer.php"); ?>