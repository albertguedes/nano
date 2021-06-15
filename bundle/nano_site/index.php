<?php
/**
 * index.php - script to show pages of the site.
 * 
 */

session_start();

// Get the base url.
$http     = ( isset($_SERVER['HTTPS'] ) ) ? 'https':'http';
$host     = $_SERVER['HTTP_HOST'];
$uri      = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$base_url = "{$http}://{$host}{$uri}";

/**
 * Get the data.
 */

// Connect to the database.
$link = pg_connect("host=localhost port=5432 dbname=nano_site user=nano password=nano");
if(!$link) die("No connected with database.");

// Select the first page.
$query = 'SELECT p.id AS id, 
    p.title AS title,
    p.content AS content
FROM nano_site_pages AS p 
WHERE p.published = true ';

if(isset($_GET['id'])){
    $query.='AND p.id = '.$_GET['id'].';';
}
else {
    $query.='AND p.weight = 1;';
}

$result = pg_query($link,$query);
$page   = pg_fetch_array($result); 
pg_free_result($result);

// Create the items of menu.
$query = 'SELECT p.id AS id, 
    p.weight AS weight,
    p.title AS title
FROM nano_site_pages AS p 
WHERE p.published = true
ORDER BY p.weight ASC;';

$result = pg_query($link,$query);

$items = []; 
while( $item = pg_fetch_array($result) ){ 
    $items[] = $item; 
}  

pg_free_result($result); 

pg_close($link);

?><!DOCTYPE html>
<html lang="pt-br" >
    <head>
        <title><?php ($page['title']) ? $page['title']." | " : ""; ?>nano Site</title>
        <meta charset="utf-8" >
        <link href="<?=$base_url?>/assets/css/nano.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href="<?=$base_url?>/assets/images/favicon.ico"/>
    </head>
    <body>

        <main>

            <header>
                <figure>
                    <a href="<?=$base_url?>/index.php" >
                        <img src="<?=$base_url?>/assets/images/logo.svg" alt="nano CSS" >
                    </a>
                </figure>
            </header>
            
            <div>
                <article>
                    <h1><?=$page['title']?></h1>
                    <?=$page['content']?>
                </article>
            </div>

            <aside>
                <ul>
                    <?php foreach( $items as $item ): ?>
                    <?php if( $item['id'] == $id ): ?>
                    <li><strong><?=$item['title']?></strong></li>
                    <?php elseif( $item['weight'] == 1 ): ?>
                    <li><a href="<?=$base_url?>/index.php" ><?=$item['title']?></a></li>
                    <?php else: ?>
                    <li><a href="<?=$base_url?>/index.php?id=<?=$item['id']?>" ><?=$item['title']?></a></li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </aside>

        </main>

        <footer>
            nano Site <?=date("Y")?> - Free & Open Source.        
        </footer>

    </body>
</html>