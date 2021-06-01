<?php
/*
 * register.php - Script create a user.
 */
session_start();

if( isset($_SESSION['nano_user']['logged']) && $_SESSION['nano_user']['logged'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = './dashboard.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$message = '';
if($_POST['register']){

    $register = $_POST['register'];

    $name      = $register['name'];
    $username  = $register['username'];
    $email     = $register['email'];
    $password  = $register['password'];
    $password_confirm  = $register['password_confirm'];
    $is_active = true;

    if( !empty($password) && ($password == $password_confirm) ){

        $password = md5($password);
        
        $link = pg_connect("host=localhost port=5432 dbname=nano_user user=nano password=nano");
        if(!$link) die("No connected with database.");

        $query="INSERT INTO nano_user_users (name,username,email,password,is_active) 
        VALUES ('{$name}',
        '{$username}',
        '{$email}',
        '{$password}',
        '{$is_active}') 
        RETURNING id;";
        
        $result = pg_query($link,$query); 

        pg_free_result($result);

        pg_close($link);

        if($result){
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = './index.php';
            header("Location: http://$host$uri/$extra");
            exit;
        }
        else{
            $message = "User not registred. Contact the admin.";
        }

    }
    else{
        $message = "Password empty or dont match. Try again.";
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
					<a href="./index.php" >
						<img src='./assets/images/logo.svg' alt='nano User' >
					</a>
				</figure>
			</header>

            <nav>
                <ul>
                    <li>
                        <a href="./index.php" >Login</a>
                    </li>
                    <li>/</li>
                    <li><strong>Register</strong>
                </ul>
            </nav>

			<article>

                <h1>Register</h1>

                <?php if($message): ?><p><strong><?=$message?></strong></p><?php endif; ?>

				<form action='./register.php' method='POST' >
                    <label>Name</label><br>
                    <input type='text'     name='register[name]'     placeholder='Type your complete name' ><br>
                    <br>
                    <label>Username</label><br>
					<input type='text'     name='register[username]' placeholder='Type a username' ><br>
                    <br>
                    <label>Email</label><br>
					<input type='email'    name='register[email]'    placeholder='Type a valid email' ><br>
                    <br>
                    <label>Password</label><br>
					<input type='password' name='register[password]' placeholder='Type a password' ><br>
                    <br>
                    <input type='password' name='register[password_confirm]' placeholder='Confirm the password' ><br>
                    <br>
					<input type='submit' value='Register' >
				</form>
			</article>

		</main>

	</body>	
</html>