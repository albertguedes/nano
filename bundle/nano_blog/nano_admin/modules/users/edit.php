<?php
/*
 * add.php - Script to show the form to create a new user.
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

$user = pg_fetch_array($result);

pg_free_result($result);

pg_close($link);

$message = '';
if($_POST['user']){

    $user = $_POST['user'];

    $name      = $user['name'];
    $username  = $user['username'];
    $email     = $user['email'];
    if($user['password']){
        $password  = md5($user['password']);
    }
    $is_active = $user['is_active'];

    $link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query="UPDATE nano_blog_users
    SET name='".$name."',
    username='".$username."',
    email='".$email."',";
    if($user['password']){
        $query.="password='".$password."',";
    }
    $query.="is_active='".$is_active."' 
    WHERE id=$1;";
    
    $result = pg_prepare($link,'edit_user',$query);
    $result = pg_execute($link,'edit_user',[$id]);
    if($result){
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'view.php?id='.$id;
        header("Location: http://$host$uri/$extra");
        exit;
    }
    else{
        $message = "User not updated. Contact the admin.";
    }

    pg_free_result($result);

    $query = 'SELECT u.* 
    FROM nano_blog_users AS u
    WHERE u.id=$1;';

    $result = pg_prepare($link,'get_user',$query);
    $result = pg_execute($link,'get_user',[$id]);

    $user = pg_fetch_array($result);

    pg_free_result($result);

    pg_close($link);

}

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
        <title>Edit User | nano Admin</title>
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
                            <li><a href='../../dashbaord.php' >Dashboard</a></li>
                            <li><a href='./index.php' >Users</a></li>
                            <li><a href="./view.php?id=<?=$user['id']?>" ><?=$user['username']?></a></li>
                            <li><span>Edit</span></li>
                        </ul>
                    </div>
                </nav>

                <br>
                
                <h1>Update '<?=$user['username']?>'</h1>

                <hr>
                <div>
                    <a href="./view.php?id=<?=$user['id']?>" >View</a> &#8226;
                    <span>Edit</span></a> &#8226;
                    <a href="./delete.php?id=<?=$user['id']?>" >Delete</a>
                </div>
                <hr>
                
                <br>

                <?php if($message): ?>
                <p><strong><?=$message?></strong></p>
                <?php endif; ?>

                <form action="./edit.php?id=<?=$user['id']?>" method="POST" >
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Name</strong></td>
                                <td><input type="text" name="user[name]" value="<?=$user['name']?>" ></td>
                            </tr>
                            <tr>
                                <td><strong>Username</strong></td>
                                <td><input type="text" name="user[username]" value="<?=$user['username']?>" ></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td><input type="text" name="user[email]" value="<?=$user['email']?>" ></td>
                            </tr>
                            <tr>
                                <td><strong>Password</strong></td>
                                <td>
                                    <input type="password" name="user[password]" value="" ><br>
                                    <small>Keep empty to not change password.</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong><br></td>
                                <td>
                                    <input type="radio" name="user[is_active]" value='t' <?php if($user['is_active']=='t'): ?>checked='checked'<?php endif; ?>/> Active 
                                    <input type="radio" name="user[is_active]" value='f' <?php if($user['is_active']=='f'): ?>checked='checked'<?php endif; ?>/> Blocked
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