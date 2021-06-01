<?php
/**
 * dashboard.php - admin dashboard page.
 */
session_start();

if(!$_SESSION['logged']){

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
        <link href="./assets/css/nano.admin.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href='./assets/images/favicon.ico' />        
    </head>
    <body>

        <main> 

            <aside>

                <header>		
                    <figure>
                        <a href="./dashboard.php" >
                            <img src='./assets/images/logo.svg' alt='nano Admin' >
                        </a>
                    </figure>
                </header>

                <ul>
                    <li><strong>Dashboard</strong></li>
                    <li><a href='./profile/index.php' >Profile</a></li>                    
                    <li><span>Modules</span><br>
                        <ul>
                            <?php foreach( $modules as $module ): ?>
                            <li><a href='./modules/<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
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
        
                <h1>Administration Panel</h1>

                <p>Use this área to show statistics, messages and others data and notifications.</p>

            </article>
           
        </main>

        <footer>
            nano Admin <?=date('Y')?> - Free & Open Source
        </footer>

    </body>	
</html>
