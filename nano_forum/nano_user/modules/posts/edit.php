<?php
/*
 * edit.php - Script to edit a post.
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

pg_close($link);

$message = '';
if($_POST['post']){

    $post = $_POST['post'];

    $title     = $post['title'];
    $content   = $post['content'];
    $published = $post['published'];

    $link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query="UPDATE nano_blog_posts
    SET title='".$title."',
    content='".$content."',
    published='".$published."' 
    WHERE id=$1;";
    
    $result = pg_prepare($link,'edit',$query);
    $result = pg_execute($link,'edit',[$id]);
    if($result){
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'view.php?id='.$id;
        header("Location: http://$host$uri/$extra");
        exit;  
    }
    else{
        $message = "Post not updated. Contact the admin.";
    }

    pg_free_result($result);

    $query = 'SELECT p.* 
    FROM nano_blog_posts AS p
    WHERE p.id=$1;';

    $result = pg_prepare($link,'get',$query);
    $result = pg_execute($link,'get',[$id]);

    $post = pg_fetch_array($result);

    pg_free_result($result);

    pg_close($link);

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
        <title>Edit Post | nano Admin</title>
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
                            <li><a href="./view.php?id=<?=$post['id']?>" ><?=$post['title']?></a></li>
                            <li><span>Edit</span></li>
                        </ul>
                    </div>
                </nav>

                <br>
                
                <h1>Update '<?=$post['title']?>'</h1>

                <hr>
                <div>
                    <a href="./view.php?id=<?=$post['id']?>" >View</a> &#8226;
                    <span>Edit</span></a> &#8226;
                    <a href="./delete.php?id=<?=$post['id']?>" >Delete</a>
                </div>
                <hr>
                
                <br>

                <?php if($message): ?>
                <p><strong><?=$message?></strong></p>
                <?php endif; ?>

                <form action="./edit.php?id=<?=$post['id']?>" method="POST" >
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Title</strong></td>
                                <td><input type="text" name="post[title]" value="<?=$post['title']?>" ></td>
                            </tr>
                            <tr>
                                <td><strong>Content</strong></td>
                                <td><textarea name="post[content]" ><?=$post['content']?></textarea></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    <input type="radio" name="post[published]" value='t' <?php if($post['published']=='t'): ?>checked='checked'<?php endif; ?>/> Published 
                                    <input type="radio" name="post[published]" value='f' <?php if($post['published']=='f'): ?>checked='checked'<?php endif; ?>/> Not Published
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p><input type="submit" value="Edit" /></p>
                </form>

            </article>

        </section>

        <footer>
            <p>nano Admin <?=date("Y")?> - Free & Open Source</p>
            <br>
        </footer>

    </body>
</html>