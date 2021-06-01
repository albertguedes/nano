<?php
/**
 * topic.php - List of the posts of the topic.
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
function post_date($datetime = '',$format = "Y M d h:i:s" ){
    $DateTime = new \DateTime($datetime);
    return $DateTime->format($format);
}

$id = $_GET['id'];

/**
 * Get the data.
 */

// Connect to the database.
$link = pg_connect("host=localhost port=5432 dbname=nano_forum user=nano password=nano");
if(!$link) die("No connected with database.");

// Get the forum.
$query = 'SELECT t.id AS id,
    t.created_at AS created_at,
    t.title AS title, 
    t.description AS description,
    u.name AS author,    
    f.title AS forum_title,
    f.id AS forum_id
FROM nano_forum_topics AS t 
INNER JOIN nano_forum_forums AS f 
    ON f.id = t.forum_id
INNER JOIN nano_forum_users AS u 
    ON u.id = t.author_id
WHERE t.published = true
AND t.id = $1;';

$result = pg_prepare($link,'get_topic',$query);
$result = pg_execute($link,'get_topic',[$id]);

$topic = pg_fetch_array($result);

pg_free_result($result);

// Select the posts of topic.
$query = 'SELECT p.id AS id, 
p.created_at AS created_at, 
u.name AS author, 
p.title AS title,
p.content AS content
FROM nano_forum_posts AS p 
INNER JOIN nano_forum_users AS u 
ON u.id = p.author_id
WHERE p.published = true 
AND p.topic_id = $1
ORDER BY p.created_at DESC;';

$result = pg_prepare($link,'get_posts',$query);
$result = pg_execute($link,'get_posts',[$id]);

$num_posts = pg_num_rows($result);

$posts = []; 
while( $post = pg_fetch_array($result) ){ 
    $posts[] = $post; 
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
                            <li><a href="./forum.php?id=<?=$topic['forum_id']?>" ><?=$topic['forum_title']?></a></li>
                            <li>/</li>
                            <li><strong><?=$topic['title']?></strong></li>
                        </ul>
                    </nav>

                    <h1><?=$topic['title']?></h1>    
                    <p>
                        <small>
                                <em>Created at</em> <strong><?=post_date($topic['created_at'],"Y M d");?></strong> <em>By</em> <strong><?=ucfirst($topic['author'])?></strong>
                        </small>
                    </p>
                    <br>
                    <section><?=ucfirst($topic['description'])?></section>

                    <br><br><br>
                    <hr>    
                    <br><br>

                    <?php if( $num_posts > 0 ):?>

                    <?php foreach( $posts as $post ): ?>
                    <fieldset>
                        <h6><a id="<?=$post['id']?>" href="./topic.php?id=<?=$topic['id']?>#<?=$post['id']?>" >#<?=$post['id']?> <?=$post['title']?></a></h6>
                        <hr>
                        <p>
                        <small>
                            <strong><?php echo ucfirst($post['author']); ?></strong> say at <strong><?=post_date($post['created_at'])?></strong>:
                        </small>
                        </p>
                        <br>
                        <section><em><?=$post['content']?></em></section>
                        <br><br>
                    </fieldset>

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