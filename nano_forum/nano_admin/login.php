<?php
/*
 * login.php - Página de autenticação para área de administração.
 * 
 */

session_start();

if($_SESSION['logged']){

	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'dashboard.php';

	header("Location: http://$host$uri/$extra");

	exit;

}

$link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
if(!$link) die("No connected with database.");

$credentials = $_POST['credentials'];
$username = $credentials['username'];
$password = $credentials['password'];

$query = "SELECT u.id FROM nano_blog_users AS u 
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
	 $_SESSION['logged']=true;
	 $_SESSION['user'] = $users[0];
	 header("Location: ./dashboard.php");
}
else{
	$_SESSION['logged'] = false;
	header("Location: ./index.php");
}
