<?php
/*
 * view.php - Show user given id.
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

$query = 'SELECT u.* 
FROM nano_site_users AS u
WHERE u.id=$1;';

$result = pg_prepare($link,'get_user',$query);
$result = pg_execute($link,'get_user',[$id]);

$user = pg_fetch_array($result);

pg_free_result($result);

pg_close($link);

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
        <title>user '<?=$user['username']?>' | nano Admin</title>
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
                        <li><strong>'<?=$user['username']?>'</strong></li>
                    </ul>
                </nav>

                <br>

                <h1>user '<?=$user['username']?>'</h1>

                <hr>      
                    <span>View</span> &#8226;
                    <a href="./edit.php?id=<?=$user['id']?>" >Edit</a> &#8226;
                    <a href="./delete.php?id=<?=$user['id']?>" >Delete</a>
                <hr>

                <table>
                    <tbody>
                        <tr><td><strong>ID</strong></td><td><?=$user['id']?></td></tr>
                        <tr><td><strong>Created at</strong></td><td><?=$user['created_at']?></td></tr>
                        <tr><td><strong>Updated at</strong></td><td><?=$user['updated_at']?></td></tr>
                        <tr><td><strong>Name</strong></td><td><?=$user['name']?></td></tr>
                        <tr><td><strong>Email</strong></td><td><a href='mailto:<?=$user['email']?>' ><?=$user['email']?></a></td></tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                <?php if( $user['is_active'] == 't' ): ?>
                                <i>ACTIVE</i>
                                <?php else: ?>
                                <b>BLOCKED</b>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </article>

        </main>

        <footer>
            nano Admin <?=date('Y')?> - Free & Open Source
        </footer>

    </body>	
</html>
