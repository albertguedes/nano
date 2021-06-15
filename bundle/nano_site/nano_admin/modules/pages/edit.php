<?php
/*
 * edit.php - Script to edit a page.
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

pg_close($link);

$message = '';
if($_POST['page']){

    $page = $_POST['page'];

    $title     = $page['title'];
    $content   = $page['content'];
    $weight    = $page['weight'];
    $published = $page['published'];

    $link = pg_connect("host=localhost port=5432 dbname=nano_site user=nano password=nano");
    if(!$link) die("No connected with database.");

    $query="UPDATE nano_site_pages
    SET title='".$title."',
    content='".$content."',
    weight=".$weight.",
    published='".$published."' 
    WHERE id=$1;";
    
    $result = pg_prepare($link,'edit',$query);
    $result = pg_execute($link,'edit',[$id]);
    if($result){
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'view.php?id='.$id;
        header("Location: http://$host$uri/$extra");
        exit;  
    }
    else{
        $message = "Page not updated. Contact the admin.";
    }

    pg_free_result($result);

    $query = 'SELECT p.* 
    FROM nano_site_pages AS p
    WHERE p.id=$1;';

    $result = pg_prepare($link,'get',$query);
    $result = pg_execute($link,'get',[$id]);

    $page = pg_fetch_array($result);

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

?><!DOCTYPE html>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>Edit Page '<?=$page['title']?>' | nano Admin</title>
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
                        <li><a href="./index.php" >Pages</a></li>
                        <li>/</li>
                        <li><a href="./view.php?id=<?=$page['id']?>" >'<?=$page['title']?>'</a></li>
                        <li>/</li>
                        <li><strong>Edit</strong></li>
                    </ul>
                </nav>

                <br>
                
                <h1>Edit Page '<?=$page['title']?>'</h1>

                <hr>
                    <a href="./view.php?id=<?=$page['id']?>" >View</a> &#8226;
                    <strong>Edit</strong> &#8226;
                    <a href="./delete.php?id=<?=$page['id']?>" >Delete</a>
                <hr>
                
                <br>

                <?php if($message): ?>
                <p><strong><?=$message?></strong></p>
                <?php endif; ?>

                <form action="./edit.php?id=<?=$page['id']?>" method="POST" >
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Title</strong></td>
                                <td><input type="text" name="page[title]" value="<?=$page['title']?>" ></td>
                            </tr>
                            <tr>
                                <td><strong>Content</strong></td>
                                <td><textarea name="page[content]" ><?=$page['content']?></textarea></td>
                            </tr>
                            <tr>
                                <td><strong>Weight</strong></td>
                                <td><input type="number" name="page[weight]" min='1' value="<?=$page['weight']?>" ></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    <input type="radio" name="page[published]" value='t' <?php if($page['published']=='t'): ?>checked='checked'<?php endif; ?>/> Published 
                                    <input type="radio" name="page[published]" value='f' <?php if($page['published']=='f'): ?>checked='checked'<?php endif; ?>/> Not Published
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p><input type="submit" value="Edit" /></p>
                </form>

            </article>

        </main>

        <footer>
            nano Admin <?=date("Y")?> - Free & Open Source
        </footer>

    </body>
</html>