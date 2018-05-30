<?php
/**
 * Basic application handler and router. This file builds up the various
 * paths and mappings supported by the module and routes requests to
 * specific methods implemented in the `lesson.php` file for the module.
 */

namespace EAMann\BobbyTables;

use function EAMann\BobbyTables\Lesson\find_user;
use function EAMann\BobbyTables\Lesson\serve_file;
use function EAMann\BobbyTables\Util\show_search;
use League\Container\Container;
use League\Route\RouteCollection;
use League\Route\Http\Exception\NotFoundException;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Zend\Diactoros\{Response, ServerRequestFactory};
use Zend\Diactoros\Response\{RedirectResponse, SapiEmitter};

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/exercise.php';

session_start();

$container = new Container;

$container->share('response', Response::class);
$container->share('request', function () {
    return ServerRequestFactory::fromGlobals(
        $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
    );
});

$container->share('emitter', SapiEmitter::class);

$route = new RouteCollection($container);

$route->map('GET', '/query', function (ServerRequestInterface $request, ResponseInterface $response) {

    return show_search($request, $response, '');
});

$route->map('POST', '/query', function (ServerRequestInterface $request, ResponseInterface $response) {
    $data = $request->getParsedBody();

    $found = find_user($data['name']);

    return show_search($request, $response, $found);
});

// Handle 404s specifically (i.e. for missing favicons)
try {
    $response = $route->dispatch($container->get('request'), $container->get('response'));
} catch( NotFoundException $e ) {
    $response = new Response("Not found", 404);
}

$container->get('emitter')->emit($response);