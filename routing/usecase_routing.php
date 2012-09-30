<?php
/**
 *
 * The simple use case for the Routing component. We are generating a page based on /category/books URL
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
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper;
use Symfony\Component\Routing\Matcher\Dumper\ApacheMatcherDumper;

// create the RouteCollection - an object for configuring our routes
$routes = new RouteCollection;

// and add the route to the collection
// first param 		- route name
// second param 	- route URL
// thirs param		- default value
$routes->add('category_page', new Route('/category/{category_name}', array('category_name' => 'default')));

// init the request
$request = Request::createFromGlobals();

// create the context we are in from the request
$context = new RequestContext();
$context->fromRequest($request);

// init the UrlMatcher from this context
$matcher = new UrlMatcher($routes, $context);

// the user must provide /category in the URL
try {

    // get the dynamic attributes from URL
    $attributes = $matcher->match($request->getPathInfo());

    /**
     * Outputs:
    Array
    (
    [category_name] => books
    [_route] => category_page
    )
     */
    // print_r($attributes);
    $content = 'You are in the category: ' . $attributes['category_name'] . '<br />';

    // generate URL based on Route definitions
    $generator = new UrlGenerator($routes, $context);
    $content  .= 'Url for the computers category is: <a href="' . $generator->generate('category_page', array('category_name' => 'computers')) . '">' . $generator->generate('category_page', array('category_name' => 'computers')) . '</a><br />';

    // want more performance? dump the routes as PHP or Apache rewrite rules:
    $php_dumper = new PhpMatcherDumper($routes);
    $apache_dumper = new ApacheMatcherDumper($routes);

    $content .= "Routes as a PHP class: <pre>" . highlight_string($php_dumper->dump(), true) . "</pre><br />";
    $content .= "Routes as Apache rewrite rules: <pre>" . $apache_dumper->dump() . "</pre><br />";

    $response = new Response($content);
    $response->send();

} catch (ResourceNotFoundException $e) {

    // if /category is not provided we make use of handy ResourceNotFound exception and generate an authentic 404 page
    $response = new Response('Not Found: You have almost definetly forgot to provide /category param in the URL', 404);
    $response->send();
}

