<?php

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Router;
use Test\Services\Templating;

session_start();

require_once __DIR__.'/vendor/autoload.php';

$fileLocator = new FileLocator(array(__DIR__));
$routeLoader = new Symfony\Component\Routing\Loader\YamlFileLoader($fileLocator);

$router = new Router(
    $routeLoader,
    'config/routes.yaml'
);

$container = new ContainerBuilder();
$containerLoader = new YamlFileLoader($container, new FileLocator(__DIR__));
$containerLoader->load('config/services.yaml');

$container->addCompilerPass(new RegisterListenersPass(EventDispatcher::class));


$container->compile();

$eventDispatcher = $container->get(EventDispatcher::class);

$entityManager = $container->get(EntityManagerInterface::class);


$eventDispatcher->addSubscriber(new RouterListener($router, new RequestStack()));

$request = Request::createFromGlobals();

$templating = Templating::getInstance();
$templating->addExtension(
    new RoutingExtension($router)
);

$controllerResolver = new ContainerControllerResolver($container);
$argumentResolver = new ArgumentResolver();

$kernel = new httpKernel($eventDispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

$response = $kernel->handle($request);
$response->send();
