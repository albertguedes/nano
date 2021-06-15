<?php
/*
 * add.php - Script to show the form to create a new user.
 */

session_start();

if( !$_SESSION['logged'] || !$_SESSION['user'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$id = $_SESSION['user'];

$link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
if(!$link) die("No connected with database.");

/**
 * Update the profile if receive a post data.
 */
$message = '';
if($_POST['profile']){

    $profile = $_POST['profile'];

    $name      = $profile['name'];
    $email     = $profile['email'];
    if($profile['password']){
        $password  = md5($profile['password']);
    }

    $link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query="UPDATE nano_blog_users
    SET name='".$name."',
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
FROM nano_blog_users AS u
WHERE u.id=$1;';

$result = pg_prepare($link,'get_profile',$query);
$result = pg_execute($link,'get_profile',[$id]);

$profile = pg_fetch_array($result);

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
        <title>Edit Profile | nano Admin</title>
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
                            <li><a href='../../dashboard.php' >Dashboard</a></li>
                            <li><a href='./index.php' >Profile</a></li>
                            <li><span>Edit</span></li>
                        </ul>
                    </div>
                </nav>

                <br>
                
                <h1>Update </h1>

                <hr>
                <div>
                    <a href="./index.php?id=<?=$profile['id']?>" >View</a> &#8226;
                    <span>Edit</span></a>
                </div>
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
            <p>nano Admin <?=date("Y")?> - Free & Open Source</p>
            <br>
        </footer>

    </body>
</html>