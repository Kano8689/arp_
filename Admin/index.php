<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 1) {
    header("Location: ../");
    exit;
}
include_once("../header.php");

#Total Program count fetch
$sql = "SELECT COUNT(*) as total_programs FROM program_table";
$result = $conn->query($sql);

$total_programs = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_programs = $row['total_programs'];
}

#Total Course count fetch
$coursesql = "SELECT COUNT(*) as total_course FROM courses_table";
$result = $conn->query($coursesql);

$total_course = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_course = $row['total_course'];
}

#Total Student count fetch
$studentsql = "SELECT COUNT(*) as total_student FROM students_details";
$result = $conn->query($studentsql);

$total_student = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_student = $row['total_student'];
}

#Total Facult count fetch
$facultysql = "SELECT COUNT(*) as total_faculty FROM faculties_details";
$result = $conn->query($facultysql);

$total_faculty = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_faculty = $row['total_faculty'];
}


?>


<div class="container">
    <div class="page-header">
        <h3>Admin Details</h3>
        <div class="breadcrumb-box">
            <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Admin</span></a>
            <span class="sep">›</span>
            <span class="crumb-link" style="pointer-events:none;opacity:.6">Dashboard</span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="dashboard-grid">
        <div class="dash-tile" onclick="location.href='add_program.php'">
            <div class="icon">🏫</div>
            <h3>Programs</h3>
            <p class="tile-sub" id="sumPrograms">Manage programs</p>
        </div>

        <div class="dash-tile" onclick="location.href='add_course.php'">
            <div class="icon">📚</div>
            <h3>Courses</h3>
            <p class="tile-sub" id="sumCourses">Manage courses</p>
        </div>

        <div class="dash-tile" onclick="location.href='add_student.php'">
            <div class="icon">🧑‍🎓</div>
            <h3>Students</h3>
            <p class="tile-sub" id="sumStudents">Student records</p>
        </div>

        <div class="dash-tile" onclick="location.href='add_faculty.php'">
            <div class="icon">🧑‍🏫</div>
            <h3>Faculty</h3>
            <p class="tile-sub" id="sumFaculty">Faculty directory</p>
        </div>

        <div class="dash-tile" onclick="location.href='mapping_faculty.php'">
            <div class="icon">🧭</div>
            <h3>Mapping (Faculty)</h3>
            <p class="tile-sub">Coming after above modules</p>
        </div>

        <div class="dash-tile" onclick="location.href='mapping_student.php'">
            <div class="icon">🧭</div>
            <h3>Mapping (Student)</h3>
            <p class="tile-sub">Coming after above modules</p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dash-tile">
        <div class="icon"></div>
            <h3>Total Program Available</h3><br>
           <p style="font-size: 25px;"><b id="total_programs"><?php echo $total_programs; ?></b></p><br>
        </div>
        <div class="dash-tile">
            <h3>Total Courses Available</h3><br>
            <p style="font-size: 25px;"><b id="total_course"><?php echo $total_course; ?></b></p>
        </div>
        <div class="dash-tile">
            <h3>Total Students</h3><br>
            <p style="font-size: 25px;"><b id="total_student"><?php echo $total_student; ?></b></p><br>
        </div>
        <div class="dash-tile">
            <h3>Total Faculty</h3><br>
            <p style="font-size: 25px;"><b id="total_faculty"><?php echo $total_faculty; ?></b></p>
        </div>
    </div>  

    <!-- Admin info card -->
    <div class="card" style="margin-top:18px">
        <h3>Welcome, Admin</h3>
        <p class="small-muted">Use the tiles above to navigate. This dashboard is the main entry; all modules open from
            here.</p>
    </div>
</div>
<script>
function animateCount(id, end, duration) {
    let element = document.getElementById(id);
    let start = 0;
    let range = end - start;
    let stepTime = Math.abs(Math.floor(duration / range));
    let current = start;
    let timer = setInterval(function() {
        current += 1;
        element.innerText = current;
        if (current >= end) {
            clearInterval(timer);
        }
    }, stepTime);
}

// Animate all counts for 5 seconds
animateCount("total_programs", <?php echo $total_programs; ?>, 1000);
animateCount("total_course", <?php echo $total_course; ?>, 1000);
animateCount("total_student", <?php echo $total_student; ?>, 1000);
animateCount("total_faculty", <?php echo $total_faculty; ?>, 1000);
// repeat for other tiles
</script>
<?php include_once("../footer.php"); ?>
