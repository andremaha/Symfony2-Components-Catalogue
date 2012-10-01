# Symfony2 Components Catalogue

## HttpFoundation

### Purpose

Includes 2 key components to make interactions over HTTP in object-oriented fashion:

* Request
* Response
	
### Use Case

	// init the namespaces
	use Symfony\Component\HttpFoundation\Request;
	use Symonfy\Component\HttpFoundation\Response;
	
	// init the request
	$request = Request::createFromGlobals();
	
	// process the $_GET['name'] global and assign the default value 'World' if variable not exists
	$input = $request->get('name', 'World');
	
	// init the response
	$response = new Response(sprintf('Hello %s', htmlspecialchars($input, ENT_QUOTES, 'UTF-8')));
	
	// output the response
	$response->send();
	
### Options

With the *Request* class we have the total control of the HTTP messages. We can get the global variables or Server variables, cookie values and even HTTP request headers:

	// the URI without query parameters
	$request->getPathInfo();
	
	// the GET variable
	$request->query->get('category');	
	
	// the POST variable with default value
	$request->request->get('surname', 'Surname was not provided');
	
	// the SERVER variable
	$request->server->get('HTTP_HOST');
	
	// instance of UploadedFile
	$request->files->get('filefield');
	
	// the COOKIE value
	$request->cookies->get('PHPSESSID');
	
	// the HTTP headers
	$request->headers->get('content_type');
	
	// the array of languages client accepts
	$request->getLanguages();
	
*Response* class lets us set the content type, status code and even HTTP cache headers:

	$response = new Response();
	
	// set the body
	$response->setContent("{'hello': 'World!'}");
	
	// set the status code
	$response->setStatusCode(200);
	
	// set the content type
	$response->headers->set('Content-Type', 'application/json');
	
	// configure the cache
	$response->setMaxAge(10)
	
	// output the response
	$response->send();
	
## Routing

### Purpose 

Helps to generate SEO-friendly URLs (/category/books), parse them and return the appropriate response based on the information provided in the URL.

### Use Case

Let's create a route that describes the /category/books URL, accounts for a case when no category name is provided and returns the appropreate page when category name is correct:

	// init the namespaces
	use Symfony\Component\HttpFoundation\Request;
	use Symofony\Component\Routing\RouteCollection;
	use Symfony\Component\Routing\Route;
	use Symfony\Component\Routing\RequestContext;
	use Symfony\Component\Routing\Matcher\UrlMatcher;
	
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
	
	// get the dynamic attributes from URL
	$attributes = $matcher->match($request->getPathInfo());
	
	print_r($attributes);
	
### Creating the Controller using Routing

One of the most useful features of the Routing components is the possibility to create a contoller. The controller is the central part of every MVC-architecture and it's mission is to generate a Response based on the data provided in the Request. In the web, such information is stored in URL, making Routing the logical place to store how this iformation is to be handled.

To illustrate this technique we'll build a tiny app that will convert miles to kilometers using the clean and sexy URL: /convert/miles2km/{miles}.

First off we need to make sure we initilize the autoloader and declare all the namspaces we'll be using. I find it convenient to declare all the  classes I am going to use in the script right at the top. It is even more useful for Symfony2 Components, since, as you might notice, there are bunch of them:

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

Next we need to make a use of route collection and add the rule to it. As mentioned before the *add* method has two parameters. The second is the *Route* class which constructor in turn takes two parameters as well. The first parameter describes the URL scheme, the second - an array - with the default values. Besides the default values you can assign one special key to this array - *_controller* - which is any valid function callback. In our case we're going to use the simplistic anonymous function to make the calculations:

	// init collection of routes
	$routes = new RouteCollection();

	// add miles2km route
	$routes->add('miles2km', new Route('/convert/miles2km/{miles}', array(
   		'miles' => 1,
    	'_controller' => function($request) {
        	$miles = (int) $request->attributes->get('miles');

       		if (!is_numeric($miles)) {
            	$response = new Response('Miles should be a number', 500);
            	return $response;
        	}
        	$km = $miles * 1.6;
        	$response = new Response("$miles mile(s) is $km kilometer(s)");
        	return $response;
    	})));

The rest of the script forms the request context and UrlMatcher and was covered above in the Use Case section:

	// init request
	$request = Request::createFromGlobals();

	// init context and matcher
	$context = new RequestContext;
	$context->fromRequest($request);
	$matcher = new UrlMatcher($routes, $context);

Now we need somehow tell our application to use the controller we've provided in the Route. To do that we'll manipulate the request and add an extra attribute. That is the advantage of using HttpFoundation Component right here - we can literly change the request on the fly to cary the information we need thoughout its exection:
	
	try {
    	$request->attributes->add($matcher->match($request->getPathInfo()));
    	$response = call_user_func($request->attributes->get('_controller'), $request);
	} catch (ResourceNotFoundException $e) {
    	$response = new Response('Not Found! You most likely forgot to add /convert/miles2km/{number} to your URL', 400);
	} catch (\Exception $e) {
    	$response = new Response('You broke everything! Why would you go and do that?', 500);
	}

	$response->send();	
	
And so our simple controller is done. Make sure to look in the HttpKernel Section to learn how to refactor the contoller we've just created from the function into the method call of an object. 
	