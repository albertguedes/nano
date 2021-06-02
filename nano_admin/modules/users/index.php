<?php
/*
 * index.php - script to administrate users.
 */
session_start();

// Get base url.
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF'],3), '/\\');
$base_url = "http://{$host}{$uri}";

// Get base_path.
$base_path = dirname($_SERVER['SCRIPT_FILENAME'],3);

// If user has logged, redirect to dashboard.
if( !(isset($_SESSION['nano_admin']['logged']) && $_SESSION['nano_admin']['logged']) ){
	header("Location: {$base_url}/logout.php");
	exit;
}

$link = pg_connect("host=localhost port=5432 dbname=nano_admin user=nano password=nano");
if(!$link) die("No connected with database.");

$query = "SELECT u.*
FROM nano_admin_users AS u
ORDER BY u.username;";

$result   = pg_query($link,$query);
$num_rows = pg_num_rows($result);

$users = [];
while( $user = pg_fetch_array($result) ){ $users[]= $user; } 

pg_free_result($result);

pg_close($link); 

$modules_path = "{$base_path}/modules";
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
        <link href="<?=$base_url?>/assets/css/nano.css" rel="stylesheet" media='all' >
        <link href="<?=$base_url?>/assets/css/nano.admin.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href='<?=$base_url?>/assets/images/favicon.ico' />        
    </head>
    <body>

        <main> 

            <aside>

                <header>		
                    <figure>
                        <a href="<?=$base_url?>/dashboard.php" >
                            <img src='<?=$base_url?>/assets/images/logo.svg' alt='nano Admin' >
                        </a>
                    </figure>
                </header>

                <ul>
                    <li><a href='<?=$base_url?>/dashboard.php' >Dashboard</a></li>
                    <li><a href='<?=$base_url?>/profile/index.php' >Profile</a></li>
                    <li><strong>Modules</strong><br>
                        <ul>
                            <?php foreach( $modules as $module ): ?>
                            <?php if($module == 'users'): ?>
                            <li><strong><?=ucfirst($module)?></strong></li>
                            <?php else: ?>
                            <li><a href='<?=$base_url?>/<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
                            <?php endif;?> 
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href='<?=$base_url?>/logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <ul>
                        <li><a href='<?=$base_url?>/dashboard.php' >Dashboard</a></li>
                        <li>/</li>
                        <li><span>Users</span></li>
                    </ul>
                </nav>

                <h1>Users Administration</h1>

                <p>To add new user click <a href='<?=$base_url?>/modules/users/add.php' >here</a>.</p>

                <br>
                <?php if( $num_rows > 0 ): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th colspan=2></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $users as $user ): ?>
                        <tr>
                            <td><?=$user['id']?></td>
                            <td><a href='<?=$base_url?>/modules/users/view.php?id=<?=$user['id']?>' ><?=$user['username']?></a></td>
                            <td>
                                <?php if( $user['is_active'] == 't' ): ?>
                                <i>ACTIVE</i>
                                <?php else: ?>
                                <b>BLOCKED</b>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href='<?=$base_url?>/modules/users/edit.php?id=<?=$user['id']?>' title="Edit User" ><u>&xodot;</u></a>
                                <a href='<?=$base_url?>/modules/users/delete.php?id=<?=$user['id']?>' title="Delete User" ><b>&xotime;</b></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No users created.</p>
                <?php endif; ?>

            </article>

        </main>

        <footer>
            nano Admin <?=date("Y")?> - Free & Open Source
        </footer>

    </body>
</html>
