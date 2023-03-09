<?php
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
   Session_start();
   Session_destroy();
   header('Location: ../voter_login.php');
   exit;
}
?>
