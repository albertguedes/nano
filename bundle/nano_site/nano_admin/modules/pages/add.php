<?php
/*
 * add.php - Script to create new post.
 */

session_start();

if( !$_SESSION['logged'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = '../../index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

$link = pg_connect("host=localhost port=5432 dbname=nano_site user=nano password=nano");
if(!$link) die("No connected with database.");

$message = '';
if($_POST['page']){

    $post = $_POST['page'];

    $author_id = $_SESSION['user'];
    $weight    = $post['weight'];
    $title     = $post['title'];
    $content   = $post['content'];
    $published = $post['published'];

    $link = pg_connect("host=localhost port=5432 dbname=nano_site user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query="INSERT INTO nano_site_pages
    (author_id,weight,title,content,published) 
    VALUES ({$author_id},
    {$weight},
    '{$title}',
    '{$content}',
    '{$published}')
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
        $message = "Post not created. Contact the admin.";
    }

}

$modules_path = "../";
if( $handle=opendir($modules_path) ){
    $modules = [];
    while( $dir=readdir($handle) ){
        if( ( $dir != "." ) && ( $dir != ".." ) ){
            if( is_dir($modules_path.'/'.$dir) ){ $modules[]=$dir; }
        }
    }
}

?><!DOCTYPE html>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>New Page | nano Admin</title>
        <link href="../../assets/css/nano.css" rel="stylesheet" >
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
                        <li><strong>Add</strong></li>
                    </ul>
                </nav>
                
                <h1>Add New Page</h1>

                <hr>
                
                <br>

                <?php if($message): ?>
                <p><strong><?=$message?></strong></p>
                <?php endif; ?>

                <form action="./add.php" method="POST" >
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Title</strong></td>
                                <td><input type="text" name="page[title]" value="" ></td>
                            </tr>
                            <tr>
                                <td><strong>Content</strong></td>
                                <td><textarea name="page[content]" ></textarea></td>
                            </tr>
                            <tr>
                                <td><strong>Weight</strong></td>
                                <td><input type="number" name="page[weight]" min="1" ></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    <input type="radio" name="page[published]" value='t' checked="checked" > Published
                                    <input type="radio" name="page[published]" value='f' > Not Published
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