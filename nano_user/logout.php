<?php
/*
 * logout.php - script to logout from nano users.
 */
session_start();
session_destroy();

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'index.php';
header("Location: http://$host$uri/$extra");

exit;
