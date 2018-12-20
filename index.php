<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Test\Services\ResponseInterface;
use Test\Services\Templating;

session_start();

require_once __DIR__.'/vendor/autoload.php';

$fileLocator = new FileLocator(array(__DIR__));
$routeLoader = new Symfony\Component\Routing\Loader\YamlFileLoader($fileLocator);
$routes = $routeLoader->load('config/routes.yaml');

$container = new ContainerBuilder();
$containerLoader = new YamlFileLoader($container, new FileLocator(__DIR__));
$containerLoader->load('config/services.yaml');
$container->compile();

$request = Request::createFromGlobals();

$router = new Router(
    $routeLoader,
    'config/routes.yaml'
);

$templating = Templating::getInstance();
$templating->addExtension(
    new \Symfony\Bridge\Twig\Extension\RoutingExtension($router->getGenerator())
);


$parameters = $router->match($request->getPathInfo());
list($controller, $action) = \explode('::', $parameters['_controller']);

$controller = $container->get($controller);
$request->query->add(array_filter($parameters, function($key) {
    return strpos($key, '_') !== 0;
}, ARRAY_FILTER_USE_KEY));

/**
 * @var ResponseInterface $response
 */
$response = $controller->$action($request);
$response->send();
