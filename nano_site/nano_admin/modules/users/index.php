<?php
/*
 * index.php - script to administrate users.
 */
session_start();

if(!( isset($_SESSION['logged']) && $_SESSION['logged'] )){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../logout.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$link = pg_connect("host=localhost port=5432 dbname=nano_site user=nano password=nano");
if(!$link) die("No connected with database.");

$query = "SELECT u.*
FROM nano_site_users AS u
ORDER BY u.username;";

$result   = pg_query($link,$query);
$num_rows = pg_num_rows($result);

$users = [];
while( $user = pg_fetch_object($result) ){ $users[]= $user; } 

pg_free_result($result);

pg_close($link); 

$mod = 'users';

$modules_path = "../";
if( $handle = opendir($modules_path) ){
    $modules = [];
    while( $dir = readdir($handle) ){
        if( ( $dir != "." ) && ( $dir != ".." ) ){
            if( is_dir($modules_path.'/'.$dir) ){ $modules[]=$dir; }
        }
    }
}

?><!DOCTYPE HTML>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>users | nano Admin</title>
        <link href="../../assets/css/nano.css" rel="stylesheet" media='all' >
        <link href="../../assets/css/nano.admin.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href='../assets/images/favicon.ico' />        
    </head>
    <body>

        <main> 

            <aside>

                <header>		
                    <figure>
                        <a href="../../dashboard.php" >
                            <img src='../../assets/images/logo.svg' alt='nano Admin' >
                        </a>
                    </figure>
                </header>

                <ul>
                    <li><a href='../../dashboard.php' >Dashboard</a></li>
                    <li><a href='../../profile/index.php' >Profile</a></li>
                    <li><strong>Modules</strong><br>
                        <ul>
                            <?php foreach( $modules as $module ): ?>
                            <?php if($module == $mod): ?>
                            <li><strong><?=ucfirst($module)?></strong></li>
                            <?php else: ?>
                            <li><a href='../<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
                            <?php endif;?> 
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href='../../logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <ul>
                        <li><a href='../../dashboard.php' >Dashboard</a></li>
                        <li>/</li>
                        <li><span>Users</span></li>
                    </ul>
                </nav>

                <h1>Users Administration</h1>

                <p>To add new user click <a href='./add.php' >here</a>.</p>

                <br>
                <?php if( $num_rows > 0 ): ?>
                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th colspan=2></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach( $users as $user ): ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><a href='./view.php?id=<?=$user->id?>' ><?=$user->username?></a></td>
                            <td>
                                <?php if( $user->is_active == 't' ): ?>
                                <i>ACTIVE</i>
                                <?php else: ?>
                                <b>BLOCKED</b>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href='./edit.php?id=<?=$user->id?>' title="Edit User" ><u>&xodot;</u></a>
                                <a href='./delete.php?id=<?=$user->id?>' title="Delete User" ><b>&xotime;</b></a>
                            </td>
                        </tr>
                        <?php $i++; endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No users.</p>
                <?php endif; ?>

            </article>

        </main>

        <footer>
            nano Admin <?=date("Y")?> - Free & Open Source
        </footer>

    </body>
</html>
