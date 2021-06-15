<?php
/**
 * index.php - Main page of the blog.
 */

/**
 * Function to format post data.
 */
function post_date($datetime){
    $DateTime = new \DateTime($datetime);
    return $DateTime->format("Y M d");
}

/**
 * Get the data.
 */

// Connect to the database.
$link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
if(!$link) die("No connected with database.");

// Select the last 10 published posts. the difference that here we get the content to show a cutted version on page.
$query = 'SELECT p.id AS id, 
    p.created_at AS created, 
    u.name AS author, 
    p.title AS title,
    p.content AS content
FROM nano_blog_posts AS p 
INNER JOIN nano_blog_users AS u 
    ON u.id = p.author_id
WHERE p.published = true 
ORDER BY p.created_at DESC
LIMIT 10;';

$result    = pg_query($link,$query);
$num_posts = pg_num_rows($result);

$posts = []; 
while( $post = pg_fetch_array($result) ){ 
    $posts[] = $post; 
}  

pg_free_result($result); 

pg_close($link);

/**
 * Show the data.
 */
?><!DOCTYPE html>
<html lang="pt-br" >
    <head>
        <title>nano Blog</title>
        <meta charset="utf-8" >
        <link href="./assets/css/nano.css" rel="stylesheet" >
        <link href="./assets/css/nano.blog.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href='./assets/images/favicon.ico' />
    </head>
    <body>

        <main>

            <header>          
                <figure>
                    <a href='./index.php' >
                        <img src='./assets/images/logo.svg' alt='nano Blog' >
                    </a>
                </figure>
            </header>

            <div>
                <article>            
                    <?php if( $num_posts > 0 ):?>

                    <h1>Últimos Posts</h1>

                    <?php foreach( $posts as $post ): ?>
                    <h2>
                        <a href="./post.php?id=<?=$post['id']?>" >
                            <?=ucwords($post['title'])?>
                        </a>
                    </h2>
                    
                    <p>
                        <small>
                            <em>Created at </em> <strong><?=post_date($post['created']); ?></strong> <em>By</em> <strong><?=ucfirst($post['author'])?></strong>
                        </small>
                    </p>
                    
                    <br>
                    
                    <?=substr($post['content'],0,300)?> ... <a href="./post.php?id=<?=$post['id']?>" >leia mais</a><br>
                    
                    <br>
                    <hr>
                    <br>
                    
                    <?php endforeach; ?>

                    <?php else: ?>

                    <p>Nenhum post foi criado.</p>

                    <?php endif; ?>

                </article>
            </div>

            <aside>
                <ul>
                    <li><span>Home</span></li>
                    <li><a href="./about.php">About</a></li>
                    <li><a href="./archive.php">Archive</a></li>
                    <li><a href="./contact.php">Contact</a></li>
                </ul>
            </aside>

        </main>

        <footer>
            nano Blog <?=date("Y")?> - Free & Open Source
        </footer>

    </body>
</html>