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
	
	
	