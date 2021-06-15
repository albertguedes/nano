<?php
/**
 * posts.php - Dispĺay the post.
 * 
 * created: 2015-05-12
 * author: albert r. c. guedes (albert@teko.net.br)
 * 
 */

session_start();

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

if(!$_GET['id']) die("404 - PAGE NOT FOUND<br><br><a href='./index.php'>&larr; Return</a>");

$id = $_GET['id'];

$link = pg_connect("host=localhost port=5432 dbname=nano_blog user=nano password=nano");
if(!$link) die("No connection to database");

/**
 * Fetch post from id.
 */
$query = 'SELECT p.id AS id, 
	p.created_at AS created, 
	u.name AS author, 
	p.title AS title, 
	p.content AS content
FROM nano_blog_posts AS p
INNER JOIN nano_blog_users AS u ON u.id = p.author_id
WHERE p.id=$1 AND p.published=true;';

pg_prepare($link,'get',$query);
$result = pg_execute($link,'get',[$id]);
$post   = pg_fetch_object($result); 

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
        <link href="./assets/css/nano.forum.css" rel="stylesheet" >
        <link rel='shortcut icon' type='image/x-icon' href="./assets/images/favicon.ico"/>
	</head>
	<body>

		<header>
			
			<figure>
				<p><a href='./index.php' ><img src='./assets/images/logo.png' alt='nano blog' style='float:left;' ></a></p>
				<h4>blog</h4>
			</figure>

		</header>

		<section>

			<article>
				<h1><?php echo $post->title; ?></h1>
				<p>
					<em>Created At </em> <strong><?php echo post_date($post->created); ?></strong> <em>By</em> <strong><?php echo ucfirst($post->author); ?></strong>
				</p>
				<br>
				<?php echo $post->content; ?>
			</article>

			<aside>
				<ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./about.php">About</a></li>
                    <li><a href="./archive.php">Archive</a></li>
                    <li><a href="./contact.php">Contact</a></li>
                </ul>
			</aside>

			<br><br><br>

		</section>

		<footer>
			<p>nano Blog &copy; <?php echo date("Y"); ?> - Free & Opensource.</p>
			<br>
		</footer>

	</body>
</html>