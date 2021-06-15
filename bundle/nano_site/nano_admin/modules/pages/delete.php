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

$link = pg_connect("host=localhost port=5432 dbname=nano_site user=nano password=nano");
if(!$link) die("No connected with database.");

if($_GET['answer']){

    $query='DELETE FROM nano_site_pages AS p 
    WHERE p.id=$1;';
    
    $result = pg_prepare($link,'delete',$query);
    $result = pg_execute($link,'delete',[$id]);

    pg_free_result($result);

    pg_close($link);

	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'index.php';
	header("Location: http://$host$uri/$extra");
	exit;

}

$query = 'SELECT p.* 
FROM nano_site_pages AS p
WHERE p.id=$1;';

$result = pg_prepare($link,'get',$query);
$result = pg_execute($link,'get',[$id]);

$page = pg_fetch_array($result);

pg_free_result($result);

pg_close($link);

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
        <title>Delete Page '<?=$page['title']?>' | nano Admin</title>
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
                        <li><a href="./index.php" >Pages</a></li>
                        <li>/</li>
                        <li><a href="./view.php?id=<?=$page['id']?>" >'<?=$page['title']?>'</a></li>
                        <li>/</li>
                        <li><strong>Delete</strong></li>
                    </ul>
                </nav>

                <br>

                <h1>Delete Page '<?=$page['title']?>'</h1>

                <hr>
                    <a href="./view.php?id=<?=$id?>" >View</a> &#8226;
                    <a href="./edit.php?id=<?=$id?>" >Editar</a> &#8226;
                    <strong>Delete</strong>
                <hr>

                <br>

                <p>
                    Your are shure ?
                    <a style="color: red;" href="./delete.php?id=<?=$id?>&answer=1" > Yes</a> &#8226;
                    <a href="./view.php?id=<?=$id?>" >No</a>
                </p>

            </article>

        </main>

        <footer>
            nano Admin <?=date('Y')?> - Free & Open Source
        </footer>

    </body>
</html>