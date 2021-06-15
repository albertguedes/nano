<?php
/*
 * delete.php - Script to delete user.
 */
session_start();

// Get base url.
$http     = ( isset($_SERVER['HTTPS'] ) ) ? 'https':'http';
$host     = $_SERVER['HTTP_HOST'];
$uri      = rtrim(dirname($_SERVER['PHP_SELF'],3), '/\\');
$base_url = "{$http}://{$host}{$uri}";

// Get base_path.
$base_path = dirname($_SERVER['SCRIPT_FILENAME'],3);

// If user has logged, redirect to dashboard.
if( !(isset($_SESSION['nano_admin']['logged']) && $_SESSION['nano_admin']['logged']) ){
	header("Location: {$base_url}/logout.php");
	exit;
}

// If user id inst given, return to dashboard.
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

/**
 * Get user data by id.
 */
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

if( isset($_GET['answer']) && !is_null($_GET['answer']) && $_GET['answer'] ){

    $link = pg_connect("host=localhost port=5432 dbname=nano_admin user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query='DELETE FROM nano_admin_users AS u WHERE u.id=$1;';
    
    $result = pg_prepare($link,'delete_user',$query);
    $result = pg_execute($link,'delete_user',[$id]);

    $user = pg_fetch_array($result);

    pg_free_result($result);

    pg_close($link);

	header("Location: {$mod_url}/index.php");
	exit;

}

?><!DOCTYPE html>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>delete user '<?=$user['username']?>' | nano Admin</title>
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
                        <li><a href='<?=$mod_url?>/view.php?id=<?=$user['id']?>' >'<?=$user['username']?>'</a></li>
                        <li>/</li>
                        <li><strong>Delete</strong></li>
                    </ul>
                </nav>

                <br>

                <h1>delete user '<?=$user['username']?>'</h1>

                <hr>      
                    <a href="<?=$mod_url?>/view.php?id=<?=$user['id']?>" >View</a> &#8226;
                    <a href="<?=$mod_url?>/edit.php?id=<?=$user['id']?>" >Edit</a> &#8226;
                    <span>Delete</span>
                <hr>

                <br>

                <p>
                    <em>Your are shure ???</em>
                    <a href="<?=$mod_url?>/delete.php?id=<?=$id?>&answer=1" ><b>Yes</b></a> &#8226;
                    <a href="<?=$mod_url?>/view.php?id=<?=$id?>" ><strong>No</strong></a>
                </p>

            </article>

        </section>

        <footer>
            nano Admin <?=date('Y')?> - Free & Open Source
        </footer>

    </body>
</html>