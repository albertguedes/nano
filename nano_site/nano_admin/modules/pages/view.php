<?php
/*
 * view.php - Show page given the id.
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

$query = 'SELECT p.* 
FROM nano_site_pages AS p
WHERE p.id=$1;';

$result = pg_prepare($link,'get',$query);
$result = pg_execute($link,'get',[$id]);

$page = pg_fetch_array($result);

pg_free_result($result);

/**
 * Get the author of post.
 */
$query = 'SELECT u.id, u.username 
FROM nano_site_pages AS p
JOIN nano_site_users AS u ON u.id = p.author_id
WHERE p.id=$1;';

$result = pg_prepare($link,'get_author',$query);
$result = pg_execute($link,'get_author',[$page['id']]);

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
        <title>Page '<?=$page['title']?>' | nano Admin</title>
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
                        <li><a href='./index.php' >Pages</a></li>
                        <li>/</li>
                        <li>'<?=$page['title']?>'</li>
                    </ul>
                </nav>

                <br>

                <h1>'<?=$page['title']?>'</h1>

                <hr>
                    <strong>View</strong> &#8226;
                    <a href="./edit.php?id=<?=$id?>" >Edit</a> &#8226;
                    <a href="./delete.php?id=<?=$id?>" >Delete</a>
                <hr>

                <br>

                <table>
                    <tbody>
                        <tr><td><strong>ID</strong></td><td><?=$page['id']?></td></tr>
                        <tr><td><strong>Created at</strong></td><td><?=$page['created_at']?></td></tr>
                        <tr><td><strong>Updated at</strong></td><td><?=$page['updated_at']?></td></tr>
                        <tr><td><strong>Author</strong></td><td><a href="../users/view.php?id=<?=$author['id']?>" ><?=$author['username']?></td></tr>
                        <tr><td><strong>Weight</strong></td><td><?=$page['weight']?></td></tr>
                        <tr><td><strong>Title</strong></td><td><?=$page['title']?></td></tr>
                        <tr><td><strong>Content</strong></td><td><div><?=$page['content']?></div></td></tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                <?php if( $page['published'] ): ?>
                                <span style="color: green;" >PUBLISHED</span>
                                <?php else: ?>
                                <span style="color: red;" >NOT PUBLISHED</span>
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