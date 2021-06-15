<?php
/*
 * logout.php - script to logout.
 * 
 */
session_start();
session_destroy();

// Get base url.
$http     = ( isset($_SERVER['HTTPS'] ) ) ? 'https':'http';
$host     = $_SERVER['HTTP_HOST'];
$uri      = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url = "{$http}://{$host}{$uri}";

header("Location: {$base_url}/index.php");

exit;
