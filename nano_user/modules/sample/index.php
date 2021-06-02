<?php
/*
 * index.php - Show user profile.
 */
session_start();

if( !isset($_SESSION['nano_user']['logged']) || !$_SESSION['nano_user']['logged'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../index.php';
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
        <title>sample | nano User</title>
        <link href="../assets/css/nano.css" rel="stylesheet" media='all' >
        <link href="../assets/css/nano.user.css" rel="stylesheet" media='all' >
        <link rel='shortcut icon' type='image/x-icon' href='../../assets/images/favicon.ico' />
    </head>
    <body>
        <main>

            <aside>

                <header>		
                    <figure>
                        <a href="../dashboard.php" >
                            <img src='../assets/images/logo.svg' alt='nano User' >
                        </a>
                    </figure>
                </header>

                <ul>
                    <li><a href='../dashboard.php' >Dashboard</a></li>                    
                    <li><a href='../profile/index.php' >Profile</a></li>
                    <li><strong>Sample</strong></li>
                    <li><a href='../logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <ul>
                        <li><a href='../dashboard.php' >Dashboard</a></li>
                        <li>/</li>
                        <li><strong>Sample</strong></li>
                    </ul>
                </nav>

                <h1>Sample</h1>

                <p>This is a sample module for nano user.</p>

            </article>

        </main>

        <footer>
            nano User <?=date('Y')?> - Free & Open Source
        </footer>

    </body>	
</html>
