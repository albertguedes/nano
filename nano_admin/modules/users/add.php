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

    $link = pg_connect("host=localhost port=5432 dbname=nano_admin user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query="INSERT INTO nano_admin_users (name,username,email,password,is_active) 
    VALUES ('{$name}',
    '{$username}',
    '{$email}',
    '{$password}',
    '{$is_active}') 
    RETURNING id;";
    
    $result = pg_query($link,$query); 
    $arr = pg_fetch_array( $result );
    $id = $arr[0]['id'];

    pg_free_result($result);

    pg_close($link);

    if($id){

        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'view.php?id='.$id;
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
        <title>add user | nano Admin</title>
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
                        <li><span>Add</span></li>
                    </ul>
                </nav>

                <h1>Add New User</h1>

                <hr>

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

                </main>

        <footer>
            nano Admin <?=date("Y")?> - Free & Open Source
        </footer>

    </body>
</html>