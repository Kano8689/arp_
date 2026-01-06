<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 2) {
    header("Location: ../");
    exit;
}


$display = "none";


if (isset($_POST['loadResult'])) {
    $display = "block";
    $selectedYear = $_POST['yearSelect'];
    $selectedSem = $_POST['semSelect'];
    $selectedProgram = $_POST['programSelect'];

    $_SESSION['rsltYr'] = $selectedYear;

    $selectedSem = $selectedSem == "FALL" ? 1 : ($selectedSem == "SUMMER" ? 2 : 0);


    $studentsData = SelectStudentsFromProgram($selectedProgram);
    $stuIds = [];
    $studentsData = mysqli_fetch_all($studentsData, MYSQLI_ASSOC);
    foreach ($studentsData as $row) {
        $stuIds[] = $row[$_studentId];
    }
    $Ids = "(" . implode(",", $stuIds) . ")";


    $sql = "SELECT * FROM $_resultTable WHERE $_resultResultYear='$selectedYear' AND $_resultResultSemType='$selectedSem' AND $_resultStdDtlId IN $Ids";
    $studentProgramResultData = mysqli_query($conn, $sql);
}


function CourseAllFieldDataSelect()
{
    global $conn, $_programTableName;

    $sql = "SELECT * FROM $_programTableName";
    $res = mysqli_query($conn, $sql);

    return $res;
}
function CourseSingleFieldFetch($ary, $field)
{
    return $ary[$field];
}
function StudentFieldFetch($ary, $field)
{
    return $ary[$field];
}
function StudentGetFromIds($ids)
{
    global $conn, $_studentTable, $_studentId;
    $selectedYear = $_SESSION['rsltYr'];

    if ($ids == "()") {
        // echo"NULL";
        $ids = "(0)";
    } else {
        // echo"NOT-NULL";
    }
    // echo"<br>"; 

    $sql = "SELECT * FROM $_studentTable WHERE $_studentId IN $ids";
    $res = mysqli_query($conn, $sql);

    return $res;
}

function SelectStudentsFromProgram($proName)
{
    global $conn, $_studentTable, $_studentProgram;

    $sql = "SELECT * FROM $_studentTable WHERE $_studentProgram='$proName'";
    $res = mysqli_query($conn, $sql);

    return $res;
}
function ResultValidationSelect() {}

include_once("../header.php");
?>


<div class="container">
    <!-- Title + Breadcrumb -->
    <div class="page-header">
        <h3>Student Result</h3>
        <div class="breadcrumb-box">
            <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>CEO</span></a>
            <span class="sep">›</span>
            <span class="crumb-link crumb-disabled">Student Result</span>
        </div>
    </div>

    <div class="card" style="display: <?php echo $display == "none" ? "block" : "none"; ?>;">
        <form method="POST" class="student_form" style="margin: 0; padding: 0;">
            <select name="yearSelect" id="yearSelect">
                <option name="" value="">Result Year</option>
                <?php $currentYear = date('Y');
                for ($i = 0; $i < 3; $i++) {
                    $startYear = ($currentYear - $i);
                    $reasultYear = $startYear;
                ?>
                    <option value="<?php echo $reasultYear; ?>"><?php echo $reasultYear; ?></option>
                <?php } ?>
            </select>

            <select name="semSelect" id="semSelect">
                <option name="" value="">Semester</option>
                <option value="FALL">FALL</option>
                <option value="SUMMER">SUMMER</option>
            </select>

            <select name="programSelect" id="programSelect">
                <option name="" value="">Program</option>
                <?php $courseData = CourseAllFieldDataSelect();
                while ($row = mysqli_fetch_assoc($courseData)) { ?>
                    <option value="<?php echo CourseSingleFieldFetch($row, $_programId); ?>"><?php echo CourseSingleFieldFetch($row, $_programNameField); ?></option>
                <?php } ?>
            </select>

            <button class="btn" name="loadResult">Load Result</button>
        </form>
    </div>


    <div class="card" style="display: <?php echo $display; ?>;">
        <?php $currentStudents = StudentGetFromIds($Ids);
        if (mysqli_num_rows($currentStudents) > 0) {
            $stuIDs = [];
            $currentStudents = mysqli_fetch_all($currentStudents, MYSQLI_ASSOC);
            foreach ($currentStudents as $row) {
                $stuIDs[] = $row[$_studentId];
            }
            $IDs = "(" . implode(",", $stuIds) . ")";
        ?>
            <form id="" method="POST" action="student_result_pdf.php" target="_blank" style="margin: 0; padding: 0;">
                <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem; margin-right: 30px;">
                    <input type="hidden" id="stdId" name="stdId" value="<?php echo $IDs; ?>">
                    <input type="hidden" id="yr" name="yr" value="<?php echo $selectedYear; ?>">
                    <input type="hidden" id="sem" name="sem" value="<?php echo $selectedSem; ?>">
                    <button type="submit" name="view_all" id="view_all" class="view btn">
                        View All
                    </button>
                    <!-- <a href="student_result_pdf.php" target="_blank" name="download_all" class="download btn" id="download_all">Download All</a> -->
                </div>
            </form>

            
            <table class="table">
                <thead id="" style="font-weight: bolder;">
                    <th style="text-align: center;">No.</th>
                    <th style="text-align: center;">Enrollment No.</th>
                    <th style="text-align: center;">Name</th>
                    <th style="text-align: center;">Action</th>
                </thead>

                <tbody id="">
                    <?php
                    $currentStudents = StudentGetFromIds($Ids);
                    $num = 1;
                    while ($stuData = mysqli_fetch_assoc($currentStudents)) { ?>
                        <tr>
                            <td style="text-align: center;" class="enNo" name="enNo"><?php echo $num++; ?></td>
                            <td style="text-align: center;" class="enNo" name="enNo"><?php echo StudentFieldFetch($stuData, $_studentCode); ?></td>
                            <td style="text-align: center;" class="stdName" name="stdName"><?php echo StudentFieldFetch($stuData, $_studentName); ?></td>

                            <td style="text-align: center;">
                                <form id="studentForm" method="POST" action="student_result_pdf.php" target="_blank" style="margin: 0; padding: 0;">
                                    <input type="hidden" id="stdId" name="stdId" value="<?php echo StudentFieldFetch($stuData, $_studentId); ?>">
                                    <input type="hidden" id="yr" name="yr" value="<?php echo $selectedYear; ?>">
                                    <input type="hidden" id="sem" name="sem" value="<?php echo $selectedSem; ?>">

                                    <button type="submit" name="view_result" id="view_result" class="view btn">
                                        View
                                    </button>
                                </form>
                            </td>
                        </tr>
                <?php }
                }else{ ?>
                <p>No Records Found</p>
               <?php  } ?>
                </tbody>
            </table>
            <!-- </form> -->
    </div>


</div>

<?php include_once("../footer.php"); ?>