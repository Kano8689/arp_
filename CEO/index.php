<?php
include_once("../DB/db.php");
if (!isset($_SESSION[$_session_login_type]) || $_SESSION[$_session_login_type] != 2) {
    header("Location: ../");
    exit;
}
include_once("../header.php");
?>