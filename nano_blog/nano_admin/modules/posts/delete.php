<?php
/*
 * delete.php - Script to delete post.
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

if($_GET['answer']){

    $link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query='DELETE FROM nano_blog_posts AS p WHERE p.id=$1;';
    
    $result = pg_prepare($link,'delete',$query);
    $result = pg_execute($link,'delete',[$id]);

    $user = pg_fetch_array($result);

    pg_free_result($result);

    pg_close($link);

	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'index.php';
	header("Location: http://$host$uri/$extra");
	exit;

}

$modules_path = "../";
if( $handle=opendir($modules_path) ){
    $modules = [];
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
        <title>Delete Post '<?=$post['title']?>' | nano Admin</title>
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
                    <li><a href='../../main.php' >Dashboard</a></li>
                    <?php foreach( $modules as $module ): ?>
                    <li><a href='../<?php echo $module; ?>/index.php' ><?=ucfirst($module)?></a></li>
                    <?php endforeach; ?>
                    <li><a href='../../logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav id="breadcrumbs" >
                    <div>
                        <ul>
                            <li><a href='../../dashboard.php' >Dashboard</a></li>
                            <li><a href='./index.php' >Posts</a></li>
                            <li><a href="./view.php?id=<?=$id?>" ><?=$post['title']?></a></li>
                            <li><span>Delete</span></li>
                        </ul>
                    </div>
                </nav>

                <br>

                <h1>Exclude '<?=$post['title']?>'</h1>

                <hr>
                <div>
                    <a href="./view.php?id=<?=$id?>" >View</a> &#8226;
                    <a href="./edit.php?id=<?=$id?>" >Editar</a> &#8226;
                    <span>Delete</span>
                </div>
                <hr>

                <br>

                <p>
                    Your are shure ?
                    <a style="color: red;" href="./delete.php?id=<?=$id?>&answer=1" > Yes</a> &#8226;
                    <a href="./view.php?id=<?=$id?>" >No</a>
                </p>

            </article>

        </section>

        <footer>
            <p>nano Admin <?=date('Y')?> - Free & Open Source</p>
            <br>
        </footer>

    </body>
</html>