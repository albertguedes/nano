<?php
/**
 * about.php - About page of the forum.
 * 
 * author: albert r. c. guedes (albert@teko.net.br)
 * 
 */
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
                    <a href='./index.php' >
                        <img src='./assets/images/logo.svg' alt='nano Forum'>
                    </a>
                </figure>
            </header>

            <div>
                <article>            
                    <h1>About</h1>
                    <br><br>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Condimentum vitae sapien pellentesque habitant morbi tristique senectus et netus. Dis parturient montes nascetur ridiculus mus mauris vitae. Porttitor rhoncus dolor purus non enim. Eu nisl nunc mi ipsum faucibus. Nulla porttitor massa id neque aliquam vestibulum morbi. Enim lobortis scelerisque fermentum dui. Aliquam ultrices sagittis orci a scelerisque. Varius sit amet mattis vulputate enim nulla aliquet. Et magnis dis parturient montes nascetur ridiculus. Lacinia quis vel eros donec. Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Velit ut tortor pretium viverra suspendisse potenti nullam ac tortor. Vitae tempus quam pellentesque nec nam. Nec feugiat nisl pretium fusce id velit ut. Sit amet consectetur adipiscing elit ut. In egestas erat imperdiet sed euismod nisi porta. Imperdiet dui accumsan sit amet. Porttitor massa id neque aliquam vestibulum morbi blandit cursus.</p>
                </article>
            </div>

            <aside>
                <ul>
                    <li><a href="./index.php">Home</a></li>
                    <li><strong>About</strong></li>
                    <li><a href="./forums.php">Forums</a></li>
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