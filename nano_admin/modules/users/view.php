<?php
/*
 * view.php - Show user given id.
 */
session_start();

// Get base url.
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF'],3), '/\\');
$base_url = "http://{$host}{$uri}";

// Get base_path.
$base_path = dirname($_SERVER['SCRIPT_FILENAME'],3);

// If user has logged, redirect to dashboard.
if( !(isset($_SESSION['nano_admin']['logged']) && $_SESSION['nano_admin']['logged']) ){
	header("Location: {$base_url}/logout.php");
	exit;
}

// If user inst given, return to dashboard.
if( !isset($_GET['id']) || is_null($_GET['id']) ){
	header("Location: {$base_url}/dashboard.php");
	exit;
}

/**
 * Modules config.
 */
$mod_name  = "users";
$mod_url   = $base_url."/modules/".$mod_name;
$mods_path = $base_path."/modules/";

// Get the list of the modules for sidebar menu. 
if( $handle=opendir($mods_path) ){
    $modules = [];
    while( $dir=readdir($handle) ){
        if( ( $dir != "." ) && ( $dir != ".." ) ){
            if( is_dir($mods_path.'/'.$dir) ){ $modules[]=$dir; }
        }
    }
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

?><!DOCTYPE HTML>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>user '<?=$user['username']?>' | nano Admin</title>
        <link href="<?=$base_url?>/assets/css/nano.css" rel="stylesheet" media='all' >
        <link href="<?=$base_url?>/assets/css/nano.admin.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href='<?=$base_url?>/assets/images/favicon.ico' />
    </head>
    <body>

        <main> 

            <aside>

                <header>		
                    <figure>
                        <a href="<?=$base_url?>/dashboard.php" >
                            <img src='<?=$base_url?>/assets/images/logo.svg' alt='nano Admin' >
                        </a>
                    </figure>
                </header>

                <ul>
                    <li><a href='<?=$base_url?>/dashboard.php' >Dashboard</a></li>
                    <li><a href='<?=$base_url?>/profile/index.php' >Profile</a></li>
                    <li><strong>Modules</strong><br>
                        <ul>
                            <?php foreach( $modules as $module ): ?>
                            <li><a href='<?=$base_url?>/modules/<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href='<?=$base_url?>/logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <ul>
                        <li><a href='<?=$base_url?>/dashboard.php' >Dashboard</a></li>
                        <li>/</li>
                        <li><a href='<?=$mod_url?>/index.php' >Users</a></li>
                        <li>/</li>
                        <li><strong>'<?=$user['username']?>'</strong></li>
                    </ul>
                </nav>

                <br>

                <h1>user '<?=$user['username']?>'</h1>

                <hr>      
                    <span>View</span> &#8226;
                    <a href="<?=$mod_url?>/edit.php?id=<?=$user['id']?>" >Edit</a> &#8226;
                    <a href="<?=$mod_url?>/delete.php?id=<?=$user['id']?>" >Delete</a>
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
                                <strong>ACTIVE</strong>
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