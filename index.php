<?php
include_once("DB/db.php");

if (isset($_SESSION[$_session_login_type])) {
  $loginType = $_SESSION[$_session_login_type];
  $current_page = basename($_SERVER['PHP_SELF']);

  switch ($loginType) {
    case 1:
      header("location: Admin/");
      break;
    case 2:
      header("location: CEO/");
      break;
    case 3:
      header("location: Faculty/");
      break;
    case 4:
      header("location: Students/");
      break;
  }
}

$_isStudent = false;
$_isFaculty = false;
$_isCEO = false;
$error_Username_Message = "";
$error_Password_Message = "";

// Store old values
$oldUsername = "";
$invalidUsernameClass = "";
$invalidPasswordClass = "";

if (isset($_POST['loginBtn'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);


  $selectUserName = "SELECT * FROM $_loginTable WHERE $_loginUsername='$username'";
  $response = mysqli_query($conn, $selectUserName);

   if ($response && mysqli_num_rows($response) > 0) {
    $row = mysqli_fetch_assoc($response);
    $dbEncPassword = $row[$_loginPassword];
    
    // echo $dbEncPassword."<br>";
    $dbPassword = Decrypt($dbEncPassword);
    // echo $dbPassword;
    // exit;

    if ($password == $dbPassword) {
      // echo "$password";
      // echo "<br>";
      // echo "$$dbPassword";
      // exit;
      $_SESSION[$_session_login_type] = $row[$_loginUserType];
      $_SESSION[$_session_login_name] = $row[$_loginUsername];

      $loginType = $_SESSION[$_session_login_type];

      switch ($loginType) {
        case 1:
          header("location: Admin/");
          break;
        case 2:
          header("location: CEO/");
          break;
        case 3:
          header("location: Faculty/"); 
          break;
        case 4:
          header("location: Students/");
          break;
      }
    } else {
      $error_Password_Message = "Incorrect Password";
      $invalidPasswordClass = "is-invalid";
      $oldUsername = $username;
    }
  } else {
    $error_Username_Message = "Username not found";
    $invalidUsernameClass = "is-invalid";
    $oldUsername = $username;
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Amity University Login</title>

  <!-- CUSTOM CSS -->
  <link rel="stylesheet" href="css_js/style.css">

  <!-- BOOTSTRAP CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="login-bg">
  <div
    style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100vh; padding:20px;">
    <div class="login-container">
      <img src="assets/amity_logo.png" alt="University Logo" class="login-logo m-0">
      <h2 class="login-title">Login</h2>
      <div class="login-subtitle">Enter your university credentials</div>

      <form class="needs-validation" novalidate method="POST">

        <!-- Username -->
        <div class="mb-3 text-start">
          <label for="username" class="form-label login-field">Username</label>
          <input id="username" type="text" name="username" class="form-control m-0 <?php echo $invalidUsernameClass; ?>"
            placeholder="Enter your username" required
            value="<?php echo htmlspecialchars($oldUsername ?? '', ENT_QUOTES); ?>">
          <?php if (!empty($error_Username_Message)) { ?>
            <div class="text-danger"><?php echo $error_Username_Message; ?></div>
          <?php } ?>
        </div>

        <!-- Password -->
        <div class="mb-3 text-start">
          <label for="password" class="form-label login-field">Password</label>
          <input id="password" type="password" name="password"
            class="form-control m-0 <?php echo $invalidPasswordClass; ?>" placeholder="Enter password" required>
          <?php if (!empty($error_Password_Message)) { ?>
            <div class="text-danger"><?php echo $error_Password_Message; ?></div>
          <?php } ?>
        </div>

        <button class="login-btn w-100 fw-semibold" id="loginBtn" type="submit" name="loginBtn">Login</button>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Validation Script -->
  <script>
    (function() {
      'use strict';
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
          } else {
            form.classList.remove('was-validated');
          }
        }, false);
      });
    })();
  </script>
</body>


</html>