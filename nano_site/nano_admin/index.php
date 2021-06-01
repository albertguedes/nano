<?php
/*
 * index.php - script to login on nano admin.
 */
session_start();

if(isset($_SESSION['logged'])){
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
		<title>nano Admin</title>
		<link href="./assets/css/nano.css" rel="stylesheet" media='all' >
		<link href="./assets/css/nano.admin.login.css" rel="stylesheet" media='all' >
		<link rel='shortcut icon' type='image/x-icon' href='./assets/images/favicon.ico' />
	</head>
	<body>

		<main>

			<header>		
				<figure>
					<a href="#" >
						<img src='./assets/images/logo.svg' alt='nano Admin' >
					</a>
				</figure>
			</header>

			<article>
				<form action='./login.php' method='POST' >
					<input type='text'     name='credentials[username]' placeholder='Type your username' >
					<input type='password' name='credentials[password]' placeholder='Type your password' >
					<input type='submit' value='Login' >
				</form>
			</article>

		</main>

	</body>	
</html>