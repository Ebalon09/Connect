<?php

use Symfony\Component\HttpFoundation\Request;
use Test\Services\ResponseInterface;

session_start();

require_once __DIR__.'/vendor/autoload.php';



$controllerName = "TwitterController";
$actionName = "indexAction";

if(isset($_GET['controller']))
{
    $controllerName = $_GET['controller'];
}
if(isset($_GET['action']))
{
    $actionName = $_GET['action'];
}

$controllerName =  '\Test\Controller\\'.$controllerName;

$request = Request::createFromGlobals();

/** @var mixed $controller */
$controller = new $controllerName();

/**
 * @var ResponseInterface $response
 */
$response = $controller->$actionName($request);

$response->send();
