<?php
/*
 * add.php - Script create a user.
 */
session_start();

if(!$_SESSION['logged']){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$message = '';
if($_POST['user']){

    $user = $_POST['user'];

    $name      = $user['name'];
    $username  = $user['username'];
    $email     = $user['email'];
    $password  = md5($user['password']);
    $is_active = $user['is_active'];

    $link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query="INSERT INTO nano_blog_users (name,username,email,password,is_active) 
    VALUES ('{$name}',
    '{$username}',
    '{$email}',
    '{$password}',
    '{$is_active}')
    RETURNING id;";
    
    $result = pg_query($link,$query); 
    $id = pg_fetch_array( $result );

    die(var_dump($id));
    
    pg_free_result($result);

    pg_close($link);

    if($id){

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = '../../view.php?id='.$id;
        header("Location: http://$host$uri/$extra");
        exit;
    }
    else{
        $message = "User not created. Contact the admin.";
    }

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
        <title>Add User | nano Admin</title>
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
                            <li><span>Add</span></li>
                        </ul>
                    </div>
                </nav>

                <br>

                <h1>Add New User</h1>

                <hr>
                <br>

                <?php if($message): ?>
                <p><strong><?=$message?></strong></p>
                <?php endif; ?>

                <form action="./add.php" method="POST" >
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Name</strong></td>
                                <td><input type="text" name="user[name]" value="" ></td>
                            </tr>
                            <tr>
                                <td><strong>Username</strong></td>
                                <td><input type="text" name="user[username]" value="" ></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td><input type="text" name="user[email]" value="" ></td>
                            </tr>
                            <tr>
                                <td><strong>Password</strong></td>
                                <td><input type="password" name="user[password]" value="" ></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                    <td>
                                        <input type="radio" name="user[is_active]" value='t' checked='checked' /> Active 
                                        <input type="radio" name="user[is_active]" value='f' /> Blocked
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                    <p><input type="submit" value="Add" /></p>
                </form>

            </article>

        </section>

        <footer>
            <p>nano Admin <?=date("Y")?> - Free & Open Source</p>
            <br>
        </footer>

    </body>
</html>