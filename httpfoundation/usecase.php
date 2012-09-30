<?php
/**
 *
 * The demo file for the HttpFoundation components use case
 *
 * @author      Andrey I. Esaulov <aesaulov@me.com>
 * @package     Symfony2 Components Catalogue
 * @version     0.1
 */

// Load the autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// init the namespaces
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// init the request
$request = Request::createFromGlobals();

// process the $_GET['name'] global and assign the default value 'World' if variable not exists
$input = $request->get('name', 'World');

// init the response
$response = new Response(sprintf('Hello, %s!', htmlspecialchars($input, ENT_QUOTES, 'UTF-8')));

// output the response
$response->send();