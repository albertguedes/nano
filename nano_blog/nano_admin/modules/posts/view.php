<?php
/*
 * view.php - Show post given the id.
 */

session_start();

if( !$_SESSION['logged'] || !$_GET['id'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$id = $_GET['id'];

$link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
if(!$link) die("No connected with database.");

$query = 'SELECT p.* 
FROM nano_blog_posts AS p
WHERE p.id=$1;';

$result = pg_prepare($link,'get',$query);
$result = pg_execute($link,'get',[$id]);

$post = pg_fetch_array($result);

pg_free_result($result);

/**
 * Get the author of post.
 */
$query = 'SELECT u.id, u.username 
FROM nano_blog_posts AS p
JOIN nano_blog_users AS u ON u.id = p.author_id
WHERE p.id=$1;';

$result = pg_prepare($link,'get_author',$query);
$result = pg_execute($link,'get_author',[$post['id']]);

$author = pg_fetch_array($result);

pg_free_result($result);

pg_close($link);

/**
 * Get the path of the modules.
 */
$modules_path = "../";
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
        <title>Post '<?=$post['title']?>' | nano Admin</title>
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
                            <li><a href='./index.php' >Posts</a></li>
                            <li><span><?=$post['title']?></span></li>
                        </ul>
                    </div>
                </nav>

                <br>

                <h1>'<?=$post['title']?>'</h1>

                <hr>      
                <div>
                    <span>View</span></a> &#8226;
                    <a href="./edit.php?id=<?=$id?>" >Edit</a> &#8226;
                    <a href="./delete.php?id=<?=$id?>" >Delete</a>
                </div>
                <hr>

                <br>

                <table>
                    <tbody>
                        <tr><td><strong>ID</strong></td><td><?=$post['id']?></td></tr>
                        <tr><td><strong>Created at</strong></td><td><?=$post['created_at']?></td></tr>
                        <tr><td><strong>Updated at</strong></td><td><?=$post['updated_at']?></td></tr>
                        <tr><td><strong>Author</strong></td><td><a href="../users/view.php?id=<?=$author['id']?>" ><?=$author['username']?></td></tr>
                        <tr><td><strong>Title</strong></td><td><?=$post['title']?></td></tr>
                        <tr><td><strong>Content</strong></td><td><?=$post['content']?></td></tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                <?php if( $post['published'] ): ?>
                                <span style="color: green;" >PUBLISHED</span>
                                <?php else: ?>
                                <span style="color: red;" >NOT PUBLISHED</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </article>

        </section>

        <footer>
            <p>nano Admin <?=date('Y')?> - Free & Open Source</p>
            <br>
        </footer>

    </body>	
</html>
