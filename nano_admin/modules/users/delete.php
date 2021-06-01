<?php
/*
 * delete.php - Script to delete user.
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

$link = pg_connect("host=localhost port=5432 dbname=nano_admin user=nano password=nano");
if(!$link) die("No connected with database.");

$query = 'SELECT u.* 
FROM nano_admin_users AS u
WHERE u.id=$1;';

$result = pg_prepare($link,'get_user',$query);
$result = pg_execute($link,'get_user',[$id]);

$user = pg_fetch_array($result);

pg_free_result($result);

pg_close($link);

if($_GET['answer']){

    $link = pg_connect("host=localhost port=5432 dbname=nano_admin user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query='DELETE FROM nano_admin_users AS u WHERE u.id=$1;';
    
    $result = pg_prepare($link,'delete_user',$query);
    $result = pg_execute($link,'delete_user',[$id]);

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
        <title>delete user '<?=$user['username']?>' | nano Admin</title>
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
                            <li><a href='../<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
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
                        <li><a href='./index.php' >Users</a></li>
                        <li>/</li>
                        <li><a href='./view.php?id=<?=$user['id']?>' >'<?=$user['username']?>'</a></li>
                        <li>/</li>
                        <li><strong>Delete</strong></li>
                    </ul>
                </nav>

                <br>

                <h1>delete user '<?=$user['username']?>'</h1>

                <hr>      
                    <a href="./view.php?id=<?=$user['id']?>" >View</a> &#8226;
                    <a href="./edit.php?id=<?=$user['id']?>" >Edit</a> &#8226;
                    <span>Delete</span>
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
            nano Admin <?=date('Y')?> - Free & Open Source
        </footer>

    </body>
</html>