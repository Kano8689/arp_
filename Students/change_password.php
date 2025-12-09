<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 4) {
    header("Location: ../");
    exit;
}

function GetStudentDetailCellData($field)
{
    global $conn, $_studentTable;
    global $_studentId, $_studentCode, $_studentName, $_studentProgram, $_studentAdmitYear, $_studentPassword;
    global $stdLgnId;

    $sql = "SELECT * FROM $_studentTable WHERE $_studentCode = '$stdLgnId'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    return $row[$field] ?? null;
}
$stdLgnId = str_replace($defaultLoginExtension, "", $_SESSION[$_session_login_name] ?? "");

// echo $stdLgnId;
// exit;

if (isset($_POST['updatePassword'])) {
    $loginId = $_POST['loginId'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    $loginFullId = $loginId;

    if ($newPassword == $confirmPassword) {
        if($oldPassword != $newPassword){
            $encPas = Encrypt($newPassword);
    
            $updatePassword = "UPDATE $_loginTable SET $_loginPassword = '$encPas' WHERE $_loginUsername='$loginFullId' and $_loginUserType='4'";
            $ret = mysqli_query($conn, $updatePassword);
    
            $UpdateDetailsPassword = "UPDATE $_studentTable SET $_studentPassword='$encPas' WHERE $_studentCode='$stdLgnId'";
            $up = mysqli_query($conn, $UpdateDetailsPassword);
    
            header("Location: change_password.php");
        }
        else{
            $error = "new password and old password are match!, those should be diffrent!";
        }
    } else {
        $error = "new password and confirm password are not match!";
    }
}

if (isset($_POST['resetForm'])) {
    header("Location: change_password.php");
}

include_once("../header.php");
?>

<div class="container">
    <!-- Title + Breadcrumb -->
    <div class="page-header">
        <h3>Change Password</h3>
        <div class="breadcrumb-box">
            <a href="../" class="crumb-link"><span class="home-emoji">🏠</span><span>Student</span></a>
            <span class="sep">›</span>
            <span class="crumb-link crumb-disabled">Change Password</span>
        </div>
    </div>

    <!-- Change Password Form -->
    <div style="position:fixed; top:55%; left:55%; transform:translate(-50%, -50%);">
        <form method="POST" style="width:550px; background:#fff; padding:20px; border-radius:6px; box-shadow:0 0 8px #ccc;">
            <h3 id="changePasswordTitle" class="ChangePasswordTitle" style="margin-bottom:15px;">Change Password</h3>
            <hr>
            <br>
            <!-- <label class="fw-bold" for="userType">User Type :</label>
      <select id="userType" name="userType" required style="width:100%; margin-bottom:15px;">
        <option value="">Select User Type </option>
        <option value="Admin">Admin</option>
        <option value="CEO">CEO</option>
        <option value="Faculty">Faculty</option>
        <option value="Student">Student</option>
      </select> -->

            <label class="fw-bold">Login Id or Enrollment No :</label>
            <input type="text" id="loginId" name="loginId" value="<?php echo $stdLgnId . $defaultLoginExtension; ?>" placeholder="Login Id or Enrollment No" required style="width:100%; margin-bottom:15px;" readonly>

            <label class="fw-bold">Old Password :</label>
            <input type="password" id="oldPassword" name="oldPassword" placeholder="Old Password" required style="width:100%; padding-bottom:15px;">

            <label class="fw-bold">New Password :</label>
            <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required style="width:100%; padding-bottom:15px;">

            <label class="fw-bold">Confirm Password :</label>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required style="width:100%; margin-bottom:15px;">

            <div class="modal-actions" style="display:flex; gap:10px;">
                <button name="updatePassword" class="btn" style="padding:10px 20px;" type="submit">Update Password</button>
                <button class="btn btn-light" name="resetForm" style="padding:10px 20px;" type="button" onclick="this.form.reset()">Reset Form</button>
            </div>

        </form>
    </div>


</div>