<?php
/**
 * forum.php - List of the topics of the forum.
 * 
 * author: albert r. c. guedes (albert@teko.net.br)
 * 
 */

if(!$_GET['id'] ){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'index.php';
	header("Location: http://$host$uri/$extra");
	exit;
}

/**
 * Function to format post data.
 */
function post_date($datetime){
    $DateTime = new \DateTime($datetime);
    return $DateTime->format("Y M d");
}

$id = $_GET['id'];

/**
 * Get the data.
 */

// Connect to the database.
$link = pg_connect("host=localhost port=5432 dbname=nano_forum user=nano password=nano");
if(!$link) die("No connected with database.");

// Get the forum.
$query = 'SELECT f.title AS title, 
    f.description AS description,
    u.name AS author  
FROM nano_forum_forums AS f 
INNER JOIN nano_forum_users AS u 
    ON u.id = f.author_id
WHERE f.published = true
AND f.id = $1;';

$result = pg_prepare($link,'get_forum',$query);
$result = pg_execute($link,'get_forum',[$id]);

$forum = pg_fetch_array($result);

pg_free_result($result);

// Select the last 10 published posts. the difference that here we get the content to show a cutted version on page.
$query = 'SELECT t.id AS id, 
    t.created_at AS created, 
    u.name AS author, 
    f.title AS forum,
    t.title AS title,
    t.description AS description
FROM nano_forum_topics AS t 
INNER JOIN nano_forum_users AS u 
    ON u.id = t.author_id
INNER JOIN nano_forum_forums AS f 
    ON f.id = t.forum_id
WHERE t.published = true 
ORDER BY t.title ASC;';

$result     = pg_query($link,$query);
$num_topics = pg_num_rows($result);

$topics = []; 
while( $topic = pg_fetch_array($result) ){ 
    $topics[] = $topic; 
}  

pg_free_result($result); 

pg_close($link);

?><!DOCTYPE html>
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

                    <nav>
                        <ul>
                            <li><a href="./forums.php" >Forums</a></li>
                            <li>/</li>
                            <li><?=$forum['title']?></li>
                        </ul>
                    </nav>

                    <h1><?=$forum['title']?></h1>    
                    <p>
                        <small>
                                <em>Created at</em> <strong><?=post_date($forum['created']);?></strong> <em>By</em> <strong><?=ucfirst($forum['author'])?></strong>
                        </small>
                    </p>
                    <br>
                    <section><em><?=ucfirst($forum['description'])?></em></section>

                    <br><br><br>
                    <hr>    
                    <br><br>

                    <?php if( $num_topics > 0 ):?>

                        <?php foreach( $topics as $topic ): ?>
                        <h3>
                            <a href="./topic.php?id=<?=$topic['id']?>" >
                                <?=ucwords($topic['title'])?>
                            </a>
                        </h3>
                        <p>
                            <small>
                                <em>Created at</em> <strong><?=post_date($topic['created']);?></strong> <em>By</em> <strong><?=ucfirst($topic['author'])?></strong>
                            </small>
                        </p>
                        <br>
                        <section>
                            <?=ucfirst($topic['description'])?>
                        </section>
                        <br>
                        <br>
                        <p><a href="./topic.php?id=<?=$topic['id']?>" ><strong>Entrar</strong></a></p>
                        <br>
                        <hr>
                        <br><br>
                        <?php endforeach; ?>

                    <?php else: ?>

                    <p>No topics.</p>

                    <?php endif; ?>
                </article>
            </div>
            
            <aside>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./about.php">About</a></li>
                    <li><a href="./forum.php">Forum</a></li>
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