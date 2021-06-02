<?php
/*
 * index.php - script to login on nano admin.
 */
session_start();

// Get base url.
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url = "http://{$host}{$uri}";

// Get base_path.
$base_path = dirname($_SERVER['SCRIPT_FILENAME']);

// If user has logged, redirect to dashboard.
if(isset($_SESSION['nano_admin']['logged']) && $_SESSION['nano_admin']['logged'] ){
	header("Location: {$base_url}/dashboard.php");
	exit;
}

?><!DOCTYPE HTML>
<html lang='pt-br' >
	<head>
		<meta charset='utf-8' >	
		<title>nano Admin</title>
		<link href="<?=$base_url?>/assets/css/nano.css" rel="stylesheet" media='all' >
		<link href="<?=$base_url?>/assets/css/nano.admin.login.css" rel="stylesheet" media='all' >
		<link rel='shortcut icon' type='image/x-icon' href='<?=$base_url?>/assets/images/favicon.ico' />
	</head>
	<body>

		<main>

			<header>		
				<figure>
					<a href="#" >
						<img src="<?=$base_url?>/assets/images/logo.svg" alt='nano Admin' >
					</a>
				</figure>
			</header>

			<article>
				<form action="<?=$base_url?>/login.php" method='POST' >
					<input type='text'     name='credentials[username]' placeholder='Type your username' >
					<input type='password' name='credentials[password]' placeholder='Type your password' >
					<input type='submit' value='Login' >
				</form>
			</article>

		</main>

	</body>	
</html>