<?php
include_once("DB/db.php");
$loginType = $_SESSION[$_session_login_type];
$currentPage = basename($_SERVER['PHP_SELF']); // gets current file name
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARP Faculty Panel</title>

    <!-- <link rel="stylesheet" href="css_js/style.css"> -->
    <link rel="stylesheet" href="../css_js/style.css">
    <link rel="stylesheet" href="../Admin/css_js/styles.css">
    <link rel="stylesheet" href="../Faculty/css_js/styles.css">
    <link rel="stylesheet" href="../Students/css_js/styles.css">
    <link rel="stylesheet" href="css_js/styles.css">
</head>

<body>

    <!-- Topbar -->
    <div class="topbar">
        <div class="logo"><img src="../assets/amity-logo.png" alt="Amity" class="logo-img"></div>
        <!-- <div class="logo"><img src="assets/amity-logo.png" alt="Amity" class="logo-img"></div> -->
        <div>
            <div class="title">ARP</div>
            <div class="small-muted">
                Amity Result Portal
            </div>
        </div>

        <!-- Menu Button -->
        <button class="menu-btn" id="menuBtn">&#9776;</button>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="brand">
            <?php
            $userRole = ($loginType == 1) ? "Admin Portal" : (($loginType == 2) ? "CEO Portal" : (($loginType == 3) ? "Faculty Portal" : (($loginType == 4) ? "Student Portal" : "Unknown")));
            echo $userRole;
            ?>
        </div>

        <!-- Dashboard -->
        <a href="../" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">Dashboard</a>

        <!-- Admin Panel -->
        <?php if ($loginType == 1) { ?>
            <a href="add_program.php" class="<?= $currentPage == 'add_program.php' ? 'active' : '' ?>">Programs</a>
            <a href="add_course.php" class="<?= $currentPage == 'add_course.php' ? 'active' : '' ?>">Courses</a>
            <a href="mapping_faculty.php" class="<?= $currentPage == '  mapping_faculty.php' ? 'active' : '' ?>">Mapping (Faculty)</a>
            <a href="mapping_student.php" class="<?= $currentPage == '  mapping_student.php' ? 'active' : '' ?>">Mapping (Student)</a>
            <a href="add_faculty.php" class="<?= $currentPage == 'add_faculty.php' ? 'active' : '' ?>">Faculty</a>
            <a href="add_student.php" class="<?= $currentPage == 'add_student.php' ? 'active' : '' ?>">Students</a>
            <a href="change_password.php" class="<?= $currentPage == 'change_password.php' ? 'active' : '' ?>">Change
                Password</a>
        <?php } ?>

        <!-- CEO Panel -->
        <?php if ($loginType == 2) { ?>
            <a href="student_result.php" class="<?= $currentPage == 'student_result.php' ? 'active' : '' ?>">Student Result</a>
            <a href="faculty_permissions.php" class="<?= $currentPage == 'faculty_permissions.php' ? 'active' : '' ?>">Freeze / Unfreeze</a>
            <a href="student_credit_report.php" class="<?= $currentPage == 'student_credit_report.php' ? 'active' : '' ?>">Student Credit Report</a>
            <a href="change_password.php" class="<?= $currentPage == 'change_password.php' ? 'active' : '' ?>">Change Password</a>
        <?php } ?>

        <!-- Faculty Panel -->
        <?php if ($loginType == 3) { ?>
            <a href="faculty_marks.php" class="<?= $currentPage == 'faculty_marks.php' ? 'active' : '' ?>">Marks</a>
            <a href="change_password.php" class="<?= $currentPage == 'change_password.php' ? 'active' : '' ?>">Change
                Password</a>
        <?php } ?>

        <!-- Students Panel -->
        <?php if ($loginType == 4) { ?>
            <a href="student_marks.php" class="<?= $currentPage == 'student_marks.php' ? 'active' : '' ?>">Marks</a>
            <a href="student_result.php" class="<?= $currentPage == 'student_result.php' ? 'active' : '' ?>">Result</a>
            <a href="credit_report.php" class="<?= $currentPage == 'credit_report.php' ? 'active' : '' ?>">Credit Report</a>
            <a href="change_password.php" class="<?= $currentPage == 'change_password.php' ? 'active' : '' ?>">Change Password</a>
        <?php } ?>


        <!-- Common -->
        <a href="../logout.php">Logout</a>
    </div>