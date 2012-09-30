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
	 
	