<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Test\Events\CreatedAccoutEvent;
use Test\Listener\CreatedAccountListener;
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


$eventDispatcher = $container->get(EventDispatcher::class);

$eventDispatcher->addListener(CreatedAccoutEvent::NAME, array(new CreatedAccountListener, 'sendAccountCreatedMail'));




$request = Request::createFromGlobals();

$router = new Router(
    $routeLoader,
    'config/routes.yaml'
);

$templating = Templating::getInstance();
$templating->addExtension(
    new \Symfony\Bridge\Twig\Extension\RoutingExtension($router->getGenerator())
);

$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

$controllerResolver = new ContainerControllerResolver($container);
$argumentResolver = new ArgumentResolver();

$kernel = new httpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

$response = $kernel->handle($request);
$response->send();
