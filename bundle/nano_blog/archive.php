<?php
/**
 * archive.php - Archive of the posts of the blog.
 * 
 * created: 2015-05-13
 * author: albert r. c. guedes (albert@teko.net.br)
 * 
 * 
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
    p.title AS title
FROM nano_blog_posts AS p 
INNER JOIN nano_blog_users AS u 
    ON u.id = p.author_id
WHERE p.published = true 
ORDER BY p.created_at DESC;';

$result = pg_query($link,$query);
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
        <title>archive | nano Blog</title>
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
        
                    <h1>Archive</h1>

                    <?php if( $num_posts > 0 ):?>

                    <ul>
                        <?php foreach( $posts as $post ): ?>
                        <li>
                            <?=post_date($post['created'])?> - 
                            <a href="./post.php?id=<?=$post['id']?>" >
                                <?=ucwords($post['title'])?>
                            </a>
                            <br>
                            <br>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php else: ?>

                    <p>No posts.</p>

                    <?php endif; ?>
                </article>
            </div>

            <aside>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./about.php">About</a></li>
                    <li><span>Archive</span></li>
                    <li><a href="./contact.php">Contact</a></li>
                </ul>
            </aside>

        </main>

        <footer>
            nano Blog <?=date("Y")?> - Free & Open Source
        </footer>

    </body>
</html>