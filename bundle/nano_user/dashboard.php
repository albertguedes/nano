<?php
/**
 * dashboard.php - user dashboard page.
 */
session_start();

// Get base url.
$http     = ( isset($_SERVER['HTTPS'] ) ) ? 'https':'http';
$host     = $_SERVER['HTTP_HOST'];
$uri      = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url = "{$http}://{$host}{$uri}";

// Get base_path.
$base_path = dirname($_SERVER['SCRIPT_FILENAME']);

// If user has logged, redirect to dashboard.
if(!(isset($_SESSION['nano_user']['logged']) && $_SESSION['nano_user']['logged']) ){
	header("Location: {$base_url}/logout.php");
	exit;
}

$mods_path = $base_path."/modules";
if( $handle=opendir($mods_path) ){

    $modules = array();
    while( $dir=readdir($handle) ){

        if( ( $dir != "." ) && ( $dir != ".." ) ){
            if( is_dir($mods_path.'/'.$dir) ){ $modules[]=$dir; }
        }

    }

}

?><!DOCTYPE HTML>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>dashboard | nano Admin</title>
        <link href="<?=$base_url?>/assets/css/nano.css" rel="stylesheet" media='all' >
        <link href="<?=$base_url?>/assets/css/nano.user.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href='<?=$base_url?>/assets/images/favicon.ico' />
    </head>
    <body>

        <main> 

            <aside>

                <header>		
                    <figure>
                        <a href="<?=$base_url?>/dashboard.php" >
                            <img src='<?=$base_url?>/assets/images/logo.svg' alt='nano User' >
                        </a>
                    </figure>
                </header>

                <ul>
                    <li><strong>Dashboard</strong></li>
                    <li><a href='<?=$base_url?>/profile/index.php' >Profile</a></li>
                    <li><span>Modules</span><br>
                        <ul>
                            <?php foreach( $modules as $module ): ?>
                            <li><a href='<?=$base_url?>/modules/<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href='<?=$base_url?>/logout.php' >Logout</a></li>
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
