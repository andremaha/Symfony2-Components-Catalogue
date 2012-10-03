<?php
/**
 *
 * The refactoring of a simple controller using the conventions of the Routing component.
 * We now use the object-oriented notation and introduce a controller class.
 * We'll build a simple app that will convert miles to kilometers and back using two URLs:
 *  /convert/miles2km/{miles}
 *
 * @author      Andrey I. Esaulov <aesaulov@me.com>
 * @package     Symfony2 Components Catalogue
 * @version     0.1
 */

// Load the autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// init namespaces
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

// init collection of routes
$routes = new RouteCollection();

// now call the method miles2km when visiting URL /convert/miles2km
$routes->add('miles2km', new Route('/convert/miles2km/{miles}', array(
        'miles' => 1,
        '_controller' => array(new ConverterController(), 'miles2kmAction')
    )));

// init request
$request = Request::createFromGlobals();

// init context and matcher
$context = new RequestContext;
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $request->attributes->add($matcher->match($request->getPathInfo()));
    $response = call_user_func($request->attributes->get('_controller'), $request);
} catch (ResourceNotFoundException $e) {
    $response = new Response('Not Found! You most likely forgot to add /convert/miles2km/{number} to your URL', 400);
} catch (\Exception $e) {
    $response = new Response('You broke everything! Why would you go and do that?', 500);
}

$response->send();

// define the controller class
class ConverterController
{
    // main convertion logic goes in this method
    public function miles2kmAction($request) {
        $miles = $request->attributes->get('miles');

        if (!is_numeric($miles)) {
            $response = new Response('Miles should be a number', 500);
            return $response;
        }
        $km = $miles * 1.6;
        $response = new Response("$miles mile(s) is $km kilometer(s)");
        return $response;
    }
}