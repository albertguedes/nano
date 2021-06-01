<?php
/**
 * index.php - Main page of the blog.
 * 
 * author: albert r. c. guedes (albert@teko.net.br)
 * 
 */

session_start();

/**
 * Send a email if has a POST contact variable.
 */

$message = '';

if( $_POST['contact'] ){
    
    $contact = $_POST['contact'];

    $to      = 'youruser@localhost';
    $subject = $contact['subject'];
    $message = $contact['message'];
    $header  = [
        'From'     => $contact['name'].'<'.$contact['email'].'>',
        'Reply-To' => $contact['email'],
        'X-Mailer' => 'PHP/' . phpversion()
    ];

    $status = mail($to,$subject,$message,$header);

    $message = 'Message sended with success.';
    if( !$status ){
        $message = 'Message not sended. Try again later.';
    }

}

/**
 * Show the data.
 */
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
                    <a href='./index.php' >
                        <img src='./assets/images/logo.svg' alt='nano Forum' >
                    </a>
                </figure>
            </header>

            <div>
                <article>

                    <h1>Contact</h1>

                    <?php if($message): ?>
                        <p><strong><?php echo $message ?></strong></p>
                        <br>
                    <?php endif; ?>

                    <form action="./contact.php" method="POST" >
                        <p><strong>Name</strong></p>
                        <p><input type="text" name="contact[name]" placeholder="Type yor complete name" value="" ></p>
                        <br>
                        <p><strong>Email</strong></p>
                        <p><input type="text" name="contact[email]" placeholder="Type a valid email" value="" ></p>
                        <br>
                        <p><strong>Subject</strong></p>
                        <p><input type="text" name="contact[subject]" placeholder="Type the subject of the message" value="" ></p>
                        <br>
                        <p><strong>Message</strong></p>
                        <p><textarea name="contact[message]" placeholder="Type your message" value="" ></textarea></p>
                        <br>
                        <p><input type="submit" value="Send Message" ></p>
                    </form>

                </article>
            </div>

            <aside>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./about.php">About</a></li>
                    <li><a href="./forums.php">Forums</a></li>
                    <li><strong>About</strong></li>
                </ul>
                <hr>
                <ul>
                    <li><a href="./nano_user">Login</a></li>
                    <li><a href="./nano_user/register.php">Register</a></li>                    
                </ul>
            </aside>

        </section>

        <footer>
            nano Forum / <?=date("Y")?> / Free & Open Source
        </footer>

    </body>
</html>