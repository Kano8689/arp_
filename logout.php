<?php
      include_once("DB/db.php"); 

      session_unset();
      session_destroy();

      // header("location: ../");
      header("location: ../ARP/");
?>