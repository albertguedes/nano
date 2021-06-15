<?php
/*
 * add.php - Script to create new post.
 */

session_start();

if( !$_SESSION['logged'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
if(!$link) die("No connected with database.");

$message = '';
if($_POST['post']){

    $post = $_POST['post'];

    $author_id = $_SESSION['user'];
    $title     = $post['title'];
    $content   = $post['content'];
    $published = $post['published'];

    $link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query="INSERT INTO nano_blog_posts
    (author_id,title,content,published) 
    VALUES ({$author_id},
    '{$title}',
    '{$content}',
    '{$published}')
    RETURNING id;";
    
    $result = pg_query($link,$query); 
    $arr = pg_fetch_array( $result );
    $id = $arr[0]['id'];
    
    pg_free_result($result);

    pg_close($link);

    if($id){
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'view.php?id='.$id;
        header("Location: http://$host$uri/$extra");
        exit;  
    }
    else{
        $message = "Post not created. Contact the admin.";
    }

}

$modules_path = "../";
if( $handle=opendir($modules_path) ){
    $modules = array();
    while( $dir=readdir($handle) ){
        if( ( $dir != "." ) && ( $dir != ".." ) ){
            if( is_dir($modules_path.'/'.$dir) ){ $modules[]=$dir; }
        }
    }
}

?><!DOCTYPE html>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>New Post | nano Admin</title>
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

                <nav id="breadcrumbs" >
                    <div>
                        <ul>
                            <li><a href='../../dashbaord.php' >Dashboard</a></li>
                            <li><a href='./index.php' >Posts</a></li>
                            <li>Add</li>
                        </ul>
                    </div>
                </nav>

                <br>
                
                <h1>Add New Post</h1>

                <hr>
                
                <br>

                <?php if($message): ?>
                <p><strong><?=$message?></strong></p>
                <?php endif; ?>

                <form action="./add.php" method="POST" >
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Title</strong></td>
                                <td><input type="text" name="post[title]" value="" ></td>
                            </tr>
                            <tr>
                                <td><strong>Content</strong></td>
                                <td><textarea name="post[content]" ></textarea></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    <input type="radio" name="post[published]" value='t' checked="checked" > Published
                                    <input type="radio" name="post[published]" value='f' > Not Published
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p><input type="submit" value="Add" /></p>
                </form>

            </article>

        </section>

        <footer>
            <p>nano Admin <?=date("Y")?> - Free & Open Source</p>
            <br>
        </footer>

    </body>
</html>