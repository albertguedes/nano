<?php
/*
 * index.php - script to login on nano user.
 * 
 * author: albert r. c. guedes (albert@teko.net.br) 
 */

session_start();

if($_SESSION['logged']){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'dashboard.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

?><!DOCTYPE HTML>
<html lang='pt-br' >
	<head>
		<meta charset='utf-8' >	
		<title>nano User</title>
		<link href="assets/css/nano.css" rel="stylesheet" media='all' >
	</head>
	<body>
		<header>		
			<figure>
				<img src='./assets/images/logo.png' alt='nano User' >
				<h4>user</h4>
			</figure>
			<br><br><br>
			<form action='./login.php' method='POST' >
				<?php str_repeat('&nbsp;',5); ?>
				<input type='text'     name='credentials[username]' placeholder='Type your username' >
				<input type='password' name='credentials[password]' placeholder='Typ your password' >
				<input type='submit'   name='submit'   value='Login' >
			</form>
		</header>
	</body>	
</html>