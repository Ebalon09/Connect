<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Router;
use Test\Services\ResponseInterface;
use Test\Services\Templating;

session_start();

require_once __DIR__.'/vendor/autoload.php';

$fileLocator = new FileLocator(array(__DIR__));
$loader = new YamlFileLoader($fileLocator);
$routes = $loader->load('config/routes.yaml');

$request = Request::createFromGlobals();

$router = new Router(
    $loader,
    'config/routes.yaml'
);

$templating = Templating::getInstance();
$templating->addExtension(
    new \Symfony\Bridge\Twig\Extension\RoutingExtension($router->getGenerator())
);


$parameters = $router->match($request->getPathInfo());
list($controller, $action) = \explode('::', $parameters['_controller']);

$controller = new $controller();

/**
 * @var ResponseInterface $response
 */
$response = $controller->$action($request);
$response->send();
