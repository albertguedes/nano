<?php
/**
 * dashboard.php - user dashboard page.
 */
session_start();

if(!$_SESSION['nano_user']['logged']){

	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'logout.php';

	header("Location: http://$host$uri/$extra");

	exit;

}

$modules_path = "./modules";
if( $handle=opendir($modules_path) ){

    $modules = array();
    while( $dir=readdir($handle) ){

        if( ( $dir != "." ) && ( $dir != ".." ) ){
            if( is_dir($modules_path.'/'.$dir) ){ $modules[]=$dir; }
        }

    }

}

?><!DOCTYPE HTML>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>dashboard | nano Admin</title>
        <link href="./assets/css/nano.css" rel="stylesheet" media='all' >
        <link href="./assets/css/nano.user.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href='./assets/images/favicon.ico' />        
    </head>
    <body>

        <main> 

            <aside>

                <header>		
                    <figure>
                        <a href="./dashboard.php" >
                            <img src='./assets/images/logo.svg' alt='nano User' >
                        </a>
                    </figure>
                </header>

                <ul>
                    <li><strong>Dashboard</strong></li>
                    <li><a href='./profile/index.php' >Profile</a></li>                    
                    <li><a href='./sample/index.php' >Sample</a></li>
                    <li><a href='./logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <ul>
                        <li><span>Dashboard</span></li>
                    </ul>
                </nav>

                <br>
        
                <h1>User Panel</h1>

                <p>Use this área to show statistics, messages and others data and notifications.</p>

            </article>
           
        </main>

        <footer>
            nano User <?=date('Y')?> - Free & Open Source
        </footer>

    </body>	
</html>
