<?php
session_start();
session_unset(); 
session_destroy(); // Menghancurkan sesi
header("location: login.php"); // Redirect ke halaman login
exit();
?>
