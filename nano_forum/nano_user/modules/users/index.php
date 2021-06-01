<?php
/*
 * users/index.php - Main script to administration users.
 */

session_start();

if(!$_SESSION['logged']){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
if(!$link) die("No connected with database.");

$query = "SELECT u.*
FROM nano_blog_users AS u
ORDER BY u.username;";

$result   = pg_query($link,$query);
$num_rows = pg_num_rows($result);

$users = [];
while( $user = pg_fetch_object($result) ){ $users[]= $user; } 

pg_free_result($result);

pg_close($link); 

$modules_path = "../";
if( $handle = opendir($modules_path) ){
    $modules = [];
    while( $dir = readdir($handle) ){
        if( ( $dir != "." ) && ( $dir != ".." ) ){
            if( is_dir($modules_path.'/'.$dir) ){ $modules[]=$dir; }
        }
    }
}

?>
<!DOCTYPE html>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>Users | nano Admin</title>
        <link href="../../assets/css/nano.css" rel="stylesheet" media='all' >
    </head>
    <body>

        <section style='padding:0;' >

            <aside style='min-height: 640px' >

                <header style='padding:0;' >
                    <figure>
                    <p><img src='../../assets/images/logo.png' alt='nanoAdmin' style='float:left;' ></p>
                    <h4>admin</h4>
                    </figure>
                </header>

                <ul>
                    <li><a href='../../dashboard.php' >Dashboard</a></li>
                    <?php foreach( $modules as $module ): ?>
                    <li><a href='../<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
                    <?php endforeach; ?>
                    <li><a href='../../logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <div>
                        <ul>
                            <li><a href='../../dashboard.php' >Dashboard</a></li>
                            <li><span>Users</span></li>
                        </ul>
                    </div>
                </nav>

                <br>

                <h1>Users Administration</h1>

                <p>To add new user click <a href='./add.php' >here</a>.</p>

                <?php if( $num_rows > 0 ): ?>
                <hr>

                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach( $users as $user ): ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><a href='view.php?id=<?=$user->id?>' ><?=$user->username?></a></td>
                            <td>
                                <?php if( $user->is_active == 't' ): ?>
                                <span style="color: green;" >ACTIVE</span>
                                <?php else: ?>
                                <span style="color: red;" >BLOCKED</span>
                                <?php endif; ?>
                            </td>
                            <td style='text-align:center;' ><a style="color: blue;" href='edit.php?id=<?=$user->id?>' >&lt;/&gt;</a></td>
                            <td style='text-align:center;' ><a style="color: red;" href='delete.php?id=<?=$user->id?>' >X</a></td>
                        </tr>
                        <?php $i++; endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No users.</p>
                <?php endif; ?>

            </article>

        </section>

        <footer>
            <p>nano Admin <?=date("Y")?> - Free & Open Source</p>
            <br>
        </footer>

    </body>
</html>
