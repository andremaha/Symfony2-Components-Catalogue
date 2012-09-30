<?php
/**
 *
 * The demo of some options HttpFoundation\Response component allows us to use.
 *
 * @author      Andrey I. Esaulov <aesaulov@me.com>
 * @package     Symfony2 Components Catalogue
 * @version     0.1
 */

// Load the autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// init the namespaces
use Symfony\Component\HttpFoundation\Response;


$response = new Response();

// set the body
$response->setContent("{'hello': 'World!'}");

// set the status code
$response->setStatusCode(200);

// set the content type
$response->headers->set('Content-Type', 'application/json');

// configure the cache
$response->setMaxAge(10);

// output the response
$response->send();