<?php
/**
 * dashboard.php - admin dashboard page.
 */

session_start();
 
if(!$_SESSION['logged']){

	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'index.php';

	header("Location: http://$host$uri/$extra");

	exit;

}

$modules_path = "./modules";
if( $handle=opendir($modules_path) ){

    $modules = array();
    while( $dir=readdir($handle) ){

        if( ( $dir != "." ) && ( $dir != ".." ) ){
            if( is_dir($modules_path.'/'.$dir) ){ $modules[]=$dir; }
        }

    }

}

?>
<!DOCTYPE HTML>
<html lang='pt-br' >
    <head>
        <meta charset='utf-8' >	
        <title>nano Admin</title>
        <link href="./assets/css/nano.css" rel="stylesheet" media='all' >
    </head>
    <body>

        <section style='padding:0;' >

            <aside style='min-height: 640px' >

                <header style='padding:0;' >
                    <figure>
                        <p><img src='./assets/images/logo.png' alt='nanoAdmin' style='float:left;' ></p>
                        <h4>admin</h4>
                    </figure>
                </header>

                <ul>
                    <li><span>Dashboard</span></li>
                    <?php foreach( $modules as $module ): ?>
                    <li><a href='modules/<?php echo $module; ?>/index.php' ><?php print ucfirst($module); ?></a></li>
                    <?php endforeach; ?>
                    <li><a href='./logout.php' >Logout</a></li>
                </ul>

            </aside>

            <article>

                <nav>
                    <div>
                        <ul>
                            <li><span>Dashboard</span></li>
                        </ul>
                    </div>
                </nav>

                <br>
        
                <h1>Administration Panel</h1>

                <p>Use this área to show statistics, messages and others data and notifications.</p>

            </article>
           
        </section>

        <footer>
            <p>nano Admin <?=date('Y')?> - Free & Open Source</p>
            <br>
        </footer>

    </body>	
</html>
