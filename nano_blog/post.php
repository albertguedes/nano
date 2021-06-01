<?php
/**
 * posts.php - Dispĺay the post.
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
$post   = pg_fetch_array($result); 

pg_free_result($result);

pg_close($link);
 
/**
 * Show the data.
 */

?><!DOCTYPE html>
<html lang="pt-br" >
	<head>
		<title><?=ucwords($post['title'])?> | nano Blog</title>
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
					<h1><?=$post['title'];?></h1>
					<p>
						<em>Created at </em> <strong><?=post_date($post['created'])?></strong> <em>By</em> <strong><?=ucfirst($post['author'])?></strong>
					</p>
					<br>
					<?=$post['content']?>
				</article>
			</div>

			<aside>
				<ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./about.php">About</a></li>
                    <li><a href="./archive.php">Archive</a></li>
                    <li><a href="./contact.php">Contact</a></li>
                </ul>
			</aside>

		</main>

		<footer>
			nano Blog <?=date("Y")?> - Free & Opensource.
		</footer>

	</body>
</html>