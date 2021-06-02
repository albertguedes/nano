<?php
/*
 * login.php - script to authenticat user.
 */
session_start();

// Get base url.
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url = "http://{$host}{$uri}";

$link = pg_connect("host=localhost port=5432 dbname=nano_admin user=nano password=nano");
if(!$link) die("No connected with database.");

$credentials = $_POST['credentials'];

$username = $credentials['username'];
$password = $credentials['password'];

$query = "SELECT u.id FROM nano_admin_users AS u 
WHERE u.username = $1 
AND u.password = MD5($2)
AND u.is_active = 't';";

$result = pg_prepare($link,'login',$query);
$result = pg_execute($link,'login',[$username,$password]);

$num_rows = pg_num_rows($result);
$users    = pg_fetch_array($result);

pg_free_result($result);

pg_close($link);

if( $num_rows == 1 ){
	 $_SESSION['nano_admin']['logged']=true;
	 $_SESSION['nano_admin']['user'] = $users[0];
	 header("Location: {$base_url}/dashboard.php");
}
else{
	$_SESSION['nano_admin']['logged'] = false;
	header("Location: {$base_url}/logout.php");
}
