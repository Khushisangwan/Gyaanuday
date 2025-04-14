<?php
  session_start();
  session_unset();
  session_destroy();

  header("location: /gyaanuday/public/login.php");
  exit;
?>