<?php
/**
 *
 * The demo of some options available though the HttpFoundation\Request component
 *
 * @author      Andrey I. Esaulov <aesaulov@me.com>
 * @package     Symfony2 Components Catalogue
 * @version     0.1
 */

// Start the session to get the ID of this session from the COOKIE value
session_start();

// Load the autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// init the namespaces
use Symfony\Component\HttpFoundation\Request;

// init the request
$request = Request::createFromGlobals();



?>

<!DOCTYPE HTML>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>HttpFoundation\Request Options Demo</title>
</head>

<body>

	<header>
        <nav>
            <ul>
                <li><a href="options_request.php">Request Options</a></li>
                <li><a href="options_response.php">Response Options</a></li>
            </ul>
        </nav>
	</header>

    <section>
        <form action="" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Personal Information</legend>
                <label for="name">Name <input type="text" name="name" id="name" tabindex="2" /></label>
                <label for="surname">Surname <input type="text" name="surname" id="surname" tabindex="1"/></label>
            </fieldset>
            <fieldset>
                <legend>Avatar Picture (jpeg)</legend>
                <input type="file" accept="image/jpeg" name="avatar"  />
            </fieldset>
            <input type="submit" />
            <input type="reset" accesskey="s"/>
        </form>
    </section>

	<section>
		<article>
			<header>
				<h2>Request Options</h2>
				<p>These are some opitons <code>HttpFoundation\Request</code> class lets you can use:</p>
                <ul>
                    <li>URI: <code><?php echo $request->getPathInfo();?></code></li>
                    <li>GET variable: <code><?php echo $request->query->get('framework', 'add ?framework=YOURVALUE to your URL. This is the default value.'); ?></code></li>
                    <li>POST variable: <code><?php echo $request->request->get('surname', 'Submit the form to see the actual value. This is the default value.');?></code></li>
                    <li>SERVER variable: <code><?php echo $request->server->get('HTTP_HOST');?></code></li>
                    <li>Temporary Uploaded File: <code><?php echo $request->files->get('avatar', 'No file uploaded!'); ?></code></li>
                    <li>COOKIE value: <code><?php echo $request->cookies->get('PHPSESSID');?></code></li>
                    <li>Content-Type: <code><?php echo $request->headers->get('Content-Type'); ?></code></li>
                    <li>Method: <code><?php echo $request->getMethod();?></code></li>
                    <li>Languages client accepts: <code><?php print_r($request->getLanguages());?></code></li>
                </ul>
			</header>
            <ul>

            </ul>
		</article>
	</section>

	<aside>
		<h2>About</h2>
		<p>This is the demo of some options available though the <code>HttpFoundation\Request</code> component. </p>
        <p>This demo is the support code for the
        <a href="">Symfony2 Components Catalogue</a> and also available as a part of the package on <a href="">GitHub</a>.</p>
	</aside>

	<footer>
		<p>Copyright 2012 <a href="">Andrey Esaulov</a></p>
	</footer>

</body>

</html>