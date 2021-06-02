<?php
/*
 * register.php - Script create a user.
 */
session_start();

// Get base url.
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url = "http://{$host}{$uri}";

// Get base_path.
$base_path = dirname($_SERVER['SCRIPT_FILENAME']);

// If user has logged, redirect to dashboard.
if(isset($_SESSION['nano_user']['logged']) && $_SESSION['nano_user']['logged'] ){
	header("Location: {$base_url}/dashboard.php");
	exit;
}

$message = '';
if( isset($_POST['recover']) && !is_null($_POST['recover']) ){

    $recover = $_POST['recover'];
    $email   = $recover['email'];

    if( !empty($email) ){
      
        $link = pg_connect("host=localhost port=5432 dbname=nano_user user=nano password=nano");
        if(!$link) die("No connected with database.");

        $query="SELECT u.id
        FROM nano_user_users AS u
        WHERE u.email='{$email}'
        AND u.is_active='t';";
        
        $result = pg_query($link,$query); 
        $num_rows = pg_num_rows($result);

        pg_free_result($result);

        pg_close($link);

        if( $num_rows == 1 ){

            /**
             * Set new password for the user.
             */
            $string = substr(str_shuffle("qwertyuiopasdfghjklzxcvbnm"),0,8);
            $password = md5($string);

            $link = pg_connect("host=localhost port=5432 dbname=nano_user user=nano password=nano");
            if(!$link) die("No connected with database.");

            $query="UPDATE nano_user_users u SET u.password = '{$password}'
            WHERE u.email = '{$email}'
            AND u.is_active = 't';";
            
            $result = pg_query($link,$query); 
    
            pg_free_result($result);
    
            pg_close($link);

            /**
             * Send email with new password.
             */
            $to      = $email;
            $subject = 'Recover Password | nano User';
            $message = 'Your new password is: '.$string.'.';
            $header  = [
                'From'     => 'nano User <admin@fakemail.com>',
                'X-Mailer' => 'PHP/' . phpversion()
            ];
        
            $status = mail($to,$subject,$message,$header);
        
            $message = "A message was sended to your email. Follow instructions to recover password.";
            if( !$status ){
                $message = 'Wasn\'t possible to recover your password. Contact admin or try again later.';
            }

        }
        else{
            $message = "User not registred.";
        }

    }
    else{
        $message = "Email is invalid.";
    }

}

?><!DOCTYPE HTML>
<html lang='pt-br' >
	<head>
		<meta charset='utf-8' >	
		<title>Recover Password | nano User</title>
		<link href="<?=$base_url?>/assets/css/nano.css" rel="stylesheet" media='all' >
		<link href="<?=$base_url?>/assets/css/nano.user.login.css" rel="stylesheet" media='all' >
		<link rel='shortcut icon' type='image/x-icon' href='<?=$base_url?>/assets/images/favicon.ico' />
	</head>
	<body>

		<main>

			<header>		
				<figure>
					<a href="<?=$base_url?>/index.php" >
						<img src='<?=$base_url?>/assets/images/logo.svg' alt='nano User' >
					</a>
				</figure>
			</header>

            <nav>
                <ul>
                    <li>
                        <a href="<?=$base_url?>/index.php" >Login</a>
                    </li>
                    <li>/</li>
                    <li><strong>Recover Password</strong>
                </ul>
            </nav>

			<article>

                <h1>Recover Password</h1>

                <?php if($message): ?>
                <p><strong><?=$message?></strong></p>
                <?php else: ?>
				<form action='<?=$base_url?>/recover.php' method='POST' >
                    <label>Email</label>
                    <input type='email' name='recover[email]' placeholder='Type your registred email' >
                    <input type='submit' value='Recover' >
				</form>
                <?php endif; ?>

			</article>

		</main>

	</body>	
</html>