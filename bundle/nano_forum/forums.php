<?php
/**
 * forums.php - List of the forums.
 * 
 * author: albert r. c. guedes (albert@teko.net.br)
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
$link = pg_connect("host=localhost port=5432 dbname=nano_forum user=nano password=nano");
if(!$link) die("No connected with database.");

// Select the last 10 published posts. the difference that here we get the content to show a cutted version on page.
$query = 'SELECT f.id AS id, 
    f.created_at AS created, 
    u.name AS author, 
    f.title AS title,
    f.description AS description
FROM nano_forum_forums AS f 
INNER JOIN nano_forum_users AS u 
    ON u.id = f.author_id
WHERE f.published = true 
ORDER BY f.title ASC
LIMIT 10;';

$result     = pg_query($link,$query);
$num_forums = pg_num_rows($result);

$forums = []; 
while( $forum = pg_fetch_array($result) ){ 
    $forums[] = $forum; 
}  

pg_free_result($result); 

pg_close($link);

?>
<!DOCTYPE html>
<html lang="pt-br" >
    <head>
        <title>nano Forum</title>
        <meta charset="utf-8" >
        <link href="./assets/css/nano.css" rel="stylesheet" >
        <link href="./assets/css/nano.forum.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href="./assets/images/favicon.ico"/>
    </head>
    <body>

        <main>

            <header>          
                <figure>
                    <a href='./index.php' ><img src='./assets/images/logo.svg' alt='nano Forum' ></a>
                </figure>
            </header>

            <div>
                <article>            
        
                    <h1>Forums</h1>

                    <br><br>

                    <?php if( $num_forums > 0 ):?>

                    <?php foreach( $forums as $forum ): ?>
                    <h3>
                        <a href="./forum.php?id=<?=$forum['id']?>" >
                            <?=ucwords($forum['title'])?>
                        </a>
                    </h3>
                    <p>
                        <small>
                            <em>Created at</em> <strong><?=post_date($forum['created']);?></strong> <em>By</em> <strong><?=ucfirst($forum['author'])?></strong>
                        </small>
                    </p>
                    <br>
                    <section>
                        <?=ucfirst($forum['description'])?>
                    </section>
                    <br>
                    <br>
                    <p><a href="./forum.php?id=<?=$forum['id']?>" ><strong>Entrar</strong></a></p>
                    <br>
                    <hr>
                    <br><br>
                    <?php endforeach; ?>

                    <?php else: ?>

                    <p>No forums.</p>

                    <?php endif; ?>
                </article>
            </div>

            <aside>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./about.php">About</a></li>
                    <li><strong>Forums</strong></li>
                    <li><a href="./contact.php">Contact</a></li>
                </ul>
                <hr>
                <ul>
                    <li><a href="./nano_user">Login</a></li>
                    <li><a href="./nano_user/register.php">Register</a></li>                    
                </ul>
            </aside>

        </main>

        <footer>
            nano Forum / <?=date("Y")?> / Free & Open Source
        </footer>

    </body>
</html>