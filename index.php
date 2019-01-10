<?php

use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Test\Events\AccountDataChangedEvent;
use Test\Events\CreatedAccoutEvent;
use Test\Listener\AccountDataChangedListener;
use Test\Listener\CreatedAccountListener;
use Test\Listener\SecurityListener;
use Test\Services\Templating;

session_start();

require_once __DIR__.'/vendor/autoload.php';

$fileLocator = new FileLocator(array(__DIR__));
$routeLoader = new Symfony\Component\Routing\Loader\YamlFileLoader($fileLocator);
$routes = $routeLoader->load('config/routes.yaml');

$matcher = new UrlMatcher($routes, new RequestContext());

$router = new Router(
    $routeLoader,
    'config/routes.yaml'
);

$container = new ContainerBuilder();
$containerLoader = new YamlFileLoader($container, new FileLocator(__DIR__));
$containerLoader->load('config/services.yaml');

$container->compile();

$eventDispatcher = $container->get(EventDispatcher::class);

$entityManager = $container->get(EntityManagerInterface::class);


$eventDispatcher->addListener(CreatedAccoutEvent::NAME, array(new CreatedAccountListener, 'sendAccountCreatedMail'));
$eventDispatcher->addListener(AccountDataChangedEvent::NAME, array(new AccountDataChangedListener, 'sendDataUpdatedMail'));
$eventDispatcher->addListener(KernelEvents::REQUEST, array(new SecurityListener($entityManager), 'onKernelRequest'));
$eventDispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

$request = Request::createFromGlobals();

$templating = Templating::getInstance();
$templating->addExtension(
    new \Symfony\Bridge\Twig\Extension\RoutingExtension($router->getGenerator())
);

$controllerResolver = new ContainerControllerResolver($container);
$argumentResolver = new ArgumentResolver();

$kernel = new httpKernel($eventDispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

$response = $kernel->handle($request);
$response->send();
