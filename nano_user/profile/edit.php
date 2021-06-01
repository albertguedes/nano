<?php
/*
 * edit.php - Script to edit user profile.
 */
session_start();

if( !$_SESSION['nano_user']['logged'] || !$_SESSION['nano_user']['user'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$id = $_SESSION['nano_user']['user'];

$link = pg_connect("host=localhost port=5432 dbname=nano_user user=nano password=nano");
if(!$link) die("No connected with database.");

/**
 * Update the profile if receive a post data.
 */
$message = '';
if($_POST['profile']){

    $profile = $_POST['profile'];

    $datetime = new DateTime('NOW');

    $updated_at = $datetime->format('Y-m-d h:i:s.m');
    $name       = $profile['name'];
    $email      = $profile['email'];
    if($profile['password']){
        $password  = md5($profile['password']);
    }

    $query="UPDATE nano_user_users
    SET updated_at='".$updated_at."',
    name='".$name."',
    email='".$email."',";
    if($profile['password']){
        $query.="password='".$password."',";
    }
    $query=trim($query,',');
    $query.=" WHERE id=$1;";
    
    $result = pg_prepare($link,'edit_profile',$query);
    $result = pg_execute($link,'edit_profile',[$id]);

    pg_free_result($result);

    pg_close($link);
    
    if($result){
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php';
        header("Location: http://$host$uri/$extra");
        exit;  
    }
    else{
        $message = "Profile not updated. Contact the admin.";
    }

}

/**
 * Get the actual profile.
 */
$query = 'SELECT u.* 
FROM nano_user_users AS u
WHERE u.id=$1;';

$result = pg_prepare($link,'get_profile',$query);
$result = pg_execute($link,'get_profile',[$id]);

$profile = pg_fetch_array($result);

pg_free_result($result);

pg_close($link);

$modules_path = "../modules";
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
        <title>edit profile | nano Admin</title>
        <link href="../assets/css/nano.css" rel="stylesheet" media='all' >
        <link href="../assets/css/nano.user.css" rel="stylesheet" media='all' >
        <link rel='shortcut icon' type='image/x-icon' href='../assets/images/favicon.ico' />
    </head>
    <body>
        <main>

            <aside>

                <header>		
                    <figure>
                        <a href="../dashboard.php" >
                            <img src='../assets/images/logo.svg' alt='nano Admin' >
                        </a>
                    </figure>
                </header>

                <ul>
                    <li><a href='../dashboard.php' >Dashboard</a></li>                    
                    <li><strong>Profile</strong></li>
                    <li><span>Modules</span><br>
                        <ul>
                            <?php foreach( $modules as $module ): ?>
                            <li><a href='../modules/<?=$module?>/index.php' ><?=ucfirst($module)?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href='../logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <div>
                        <ul>
                            <li><a href='../../dashboard.php' >Dashboard</a></li>
                            <li>/</li>
                            <li><a href='./index.php' >Profile</a></li>
                            <li>/</li>
                            <li><span>Edit</span></li>
                        </ul>
                    </div>
                </nav>

                <br>
                
                <h1>Edit Profile</h1>

                <hr>
                    <a href="./index.php" >View</a> &#8226;
                    <span>Edit</span>
                <hr>
                
                <br>

                <?php if($message): ?>
                <p><strong><?=$message?></strong></p>
                <?php endif; ?>

                <form action="./edit.php" method="POST" >
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Name</strong></td>
                                <td><input type="text" name="profile[name]" value="<?=$profile['name']?>" ></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td><input type="text" name="profile[email]" value="<?=$profile['email']?>" ></td>
                            </tr>
                            <tr>
                                <td><strong>Password</strong></td>
                                <td><input type="password" name="profile[password]" value="" ><br>
                                    <small>Keep empty to not change password.</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p><input type="submit" value="Edit" /></p>
                </form>

            </article>

        </section>

        <footer>
            nano Admin <?=date("Y")?> - Free & Open Source
        </footer>

    </body>
</html>