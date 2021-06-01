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

$link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
if(!$link) die("No connected with database.");

$query = 'SELECT u.* 
FROM nano_blog_users AS u
WHERE u.id=$1;';

$result = pg_prepare($link,'get_user',$query);
$result = pg_execute($link,'get_user',[$id]);

$user = pg_fetch_object($result);

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
        <title>User '<?php echo $user->username; ?>' | nano Admin</title>
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
                            <li><a href='./index.php' >Users</a></li>
                            <li><span><?=$user->username?></span></li>
                        </ul>
                    </div>
                </nav>

                <br>

                <h1>'<?=$user->username?>'</h1>

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
                        <tr><td><strong>ID</strong></td><td><?=$user->id?></td></tr>
                        <tr><td><strong>Name</strong></td><td><?=$user->name?></td></tr>
                        <tr><td><strong>Email</strong></td><td><a href='mailto:<?=$user->email?>' ><?=$user->email?></a></td></tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                <?php if( $user->is_active ): ?>
                                <span style="color: green;" >ACTIVE</span>
                                <?php else: ?>
                                <span style="color: red;" >BLOCKED</span>
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
