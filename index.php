<!DOCTYPE html>
<html lang='pt-br'>

    <head>
        <meta charset="UTF-8">
        <title>nano Project</title>
        <link href="assets/css/nano.css" type="text/css" rel="stylesheet" media="all">
        <link rel='shortcut icon' type='image/x-icon' href="./assets/images/favicon.ico"/>
    </head>

    <body>

        <main>

            <nav>
                <ul>
                    <li><a href="./index.html" >Home</a></li>
                </ul>
            </nav>

            <header>
                <figure>
                    <a href="./index.html" >
                        <img src="assets/images/logo.svg" alt="nano Project" >
                    </a>
                </figure>
            </header>

            <article>

                <h1>nano Project</h1>

                <p><strong>nano Project</strong> is a didatic set of some of most 
                basic web applications that web developers work, with the objective 
                to train and exercises the programmers on fundaments of php with database in its pur form.</p>
                <p>To realize this, i elliminate the maximum complications possible, as classes, includes, libs, etc, i.e. procedural programming only without distractions.</p>
                <p>Here I established some rules, which I called _ 'nano philosophy'_:</p>

                <br>

                <h2>NANO PHILOSOPHY</h2>

                <ol>
                    <li>each page must function autonomously, that is, a page has no dependence on any external code. 
                    The goal is to program a page with all the code in mind, without sharing your attention with 
                    other codes.</li>

                    <li>the visual is done without classes or ids, with the aim of focusing your attention only on 
                    where and what data and should show. But everyone likes a nice look, so I developed the css 
                    framework 'nano.css' to style and make the pages less ugly.</li>

                    <li>the programming is procedural / functional, as it works with oriented objects and its classes 
                    try to use inclusion files. Procedural / functional programming attacks what we want right 
                    away.</lI>

                    <li>The modules fo the project follow the same philosophy of independency. They depend of database
                    or session data, but never depend of other modules or apps.</li>
                </ol>

                <br>

                <h2>Apps</h2>

                <p><a href="./bundle/nano_css">nano CSS</a>: a css framework idless, classless and tag driven to rapid devolpment for who dont like much front-end development.</p>
                <p><a href="./bundle/nano_admin">nano Admin</a>: a admin dashboard to use with all others apps.</p>
                <p><a href="./bundle/nano_user">nano User</a>: a user dashboard area for apps that need interaction, like the forum, ecommerce or social network.</p>
                <p><a href="./bundle/nano_site">nano Site</a>: a basic website.</p>
                <p><a href="./bundle/nano_blog">nano Blog</a>: a single user blog.</p>
                <p><a href="./bundle/nano_forum">nano Forum</a>: a online forum.</p>
                <p><a href="./bundle/nano_commerce">nano Commerce</a>: a ecommerce.</p>

                <br>

                <h2>NOTES</h2>

                <ul>
                    <li>This project dont ain to be a production app, only for training and pratice.</li>
                    <li>Any package has security implementation ( like sql injection, xss, csrf, passwords are encripted with MD5 hash only, etc ).</li>
                    <li>Of course, you can freely use this project as a base for your own project, but I am not 
                    responsible for any problem caused by the use of third parties.</li>
                </ul>

                <br>

                <h2>License</h2>

                <p>This project is under MIT License of use, modification and distribution.</p>

            </article>

        </main>

        <footer>
            <strong>nano Project</strong>  &copy; <?=date('Y')?> - <em>Free & Open Source</em>
        </footer>

    </body>

</html>