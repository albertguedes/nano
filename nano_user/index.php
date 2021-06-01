<?php
/*
 * index.php - script to login on nano users.
 */
session_start();

if(isset($_SESSION['nano_user']['logged'])){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'dashboard.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$message = '';

if($_POST['credentials']){

	$credentials = $_POST['credentials'];

	$username = $credentials['username'];
	$password = $credentials['password'];

	$link = pg_connect("host=localhost port=5432 dbname=nano_user user=nano password=nano");
	if(!$link) die("No connected with database.");

	$query = "SELECT u.id FROM nano_user_users AS u 
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
		$_SESSION['nano_user']['logged'] = true;
		$_SESSION['nano_user']['user']   = $users[0];
		header("Location: ./dashboard.php");
	}
	else{
		$message = 'Wrong username or password';
	}

}

?><!DOCTYPE HTML>
<html lang='pt-br' >
	<head>
		<meta charset='utf-8' >	
		<title>nano User</title>
		<link href="./assets/css/nano.css" rel="stylesheet" media='all' >
		<link href="./assets/css/nano.user.login.css" rel="stylesheet" media='all' >
		<link rel='shortcut icon' type='image/x-icon' href='./assets/images/favicon.ico' />
	</head>
	<body>

		<main>

			<header>		
				<figure>
					<a href="#" >
						<img src='./assets/images/logo.svg' alt='nano User' >
					</a>
				</figure>
			</header>

			<article>
			<br><br>
				<?php if($message): ?><p><strong><?=$message?></strong></p><?php endif; ?>
				<form action='./index.php' method='POST' >
					<input type='text'     name='credentials[username]' placeholder='Type your username' >
					<input type='password' name='credentials[password]' placeholder='Type your password' >
					<input type='submit' value='Login' >
				</form>
				<br><br>
				<p><a href="./register.php" ?>Register</a> - <a href="./forget.php" ?>Forget Password</a></p>
			</article>

		</main>

	</body>	
</html>