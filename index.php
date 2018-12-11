<?php

use Test\Services\Request;
use Test\Services\ResponseInterface;

session_start();

require_once 'Autoloader.php';
$autoloader = new Autoloader();
$autoloader->registerNamespace("Test/", "src/");

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

$request = new Request($_GET, $_POST);

/** @var mixed $controller */
$controller = new $controllerName();
/**
 * @var ResponseInterface $response
 */
$response = $controller->$actionName($request);

$response->send();
