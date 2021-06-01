<?php
/*
 * index.php - Show user profile.
 */
session_start();

if( !$_SESSION['nano_user']['logged'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$id = $_SESSION['nano_user']['user'];

$link = pg_connect("host=localhost port=5432 dbname=nano_user user=nano password=nano");
if(!$link) die("No connected with database.");

$query = 'SELECT u.* 
FROM nano_user_users AS u
WHERE u.id=$1;';

$result = pg_prepare($link,'get_profile',$query);
$result = pg_execute($link,'get_profile',[$id]);

$profile = pg_fetch_array($result);

pg_free_result($result);

pg_close($link);

$modules_path = "../modules";
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
        <title>profile | nano Admin</title>
        <link href="../assets/css/nano.css" rel="stylesheet" media='all' >
        <link href="../assets/css/nano.user.css" rel="stylesheet" media='all' >
        <link rel='shortcut icon' type='image/x-icon' href='../assets/images/favicon.ico' />
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
                    <li><strong>Profile</strong></li>
                    <li><span>Modules</span><br>
                        <ul>
                            <?php foreach( $modules as $module ): ?>
                            <li><a href='../modules/<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href='../logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <ul>
                        <li><a href='../dashboard.php' >Dashboard</a></li>
                        <li>/</li>
                        <li><strong>Profile</strong></li>
                    </ul>
                </nav>

                <h1>Profile</h1>

                <hr>      
                    <span>View</span> &#8226;
                    <a href="./edit.php" >Edit</a>
                <hr>

                <br>

                <table>
                    <tbody>
                        <tr><td><strong>Registred at</strong></td><td><?=$profile['created_at']?></td></tr>
                        <tr><td><strong>Updated at</strong></td><td><?=$profile['updated_at']?></td></tr>
                        <tr><td><strong>Username</strong></td><td><?=$profile['username']?></td></tr>
                        <tr><td><strong>Name</strong></td><td><?=$profile['name']?></td></tr>
                        <tr><td><strong>Email</strong></td><td><a href='mailto:<?=$profile['email']?>' ><?=$profile['email']?></a></td></tr>
                    </tbody>
                </table>

            </article>

        </main>

        <footer>
            nano User <?=date('Y')?> - Free & Open Source
        </footer>

    </body>	
</html>
