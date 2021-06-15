<?php
/*
 * posts/index.php - Main script to administration posts.
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

$query = "SELECT p.*
FROM nano_blog_posts AS p
ORDER BY p.title;";

$result   = pg_query($link,$query);
$num_rows = pg_num_rows($result);

$posts = [];
while( $post = pg_fetch_array($result) ){ $posts[]= $post; } 

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

?><!DOCTYPE html>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>posts | nano Admin</title>
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
                            <li><span>Posts</span></li>
                        </ul>
                    </div>
                </nav>

                <br>

                <h1>Posts Administration</h1>

                <p>To add new post click <a href='./add.php' >here</a>.</p>

                <?php if( $num_rows > 0 ): ?>
                <hr>

                <table>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach( $posts as $post ): ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><a href='view.php?id=<?=$post['id']?>' ><?=$post['title']?></a></td>
                            <td>
                                <?php if( $post['published'] == 't' ): ?>
                                <span style="color: green;" >ACTIVE</span>
                                <?php else: ?>
                                <span style="color: red;" >BLOCKED</span>
                                <?php endif; ?>
                            </td>
                            <td style='text-align:center;' ><a style="color: blue;" href='edit.php?id=<?=$post['id']?>' >&lt;/&gt;</a></td>
                            <td style='text-align:center;' ><a style="color: red;" href='delete.php?id=<?=$post['id']?>' >X</a></td>
                        </tr>
                        <?php $i++; endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No posts.</p>
                <?php endif; ?>

            </article>

        </section>

        <footer>
            <p>nano Admin <?=date("Y")?> - Free & Open Source</p>
            <br>
        </footer>

    </body>
</html>
