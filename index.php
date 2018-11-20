<?php

session_start();

require_once './Services/ParameterBag.php';
require_once './Services/Request.php';
require_once './Services/Session.php';
require_once './Services/Response.php';
require_once './Services/ResponseInterface.php';
require_once './Services/ResponseRedirect.php';
require_once "./Services/Database.php";
require_once "./Services/Templating.php";
require_once "./Model/Tweet.php";
require_once "./Repository/BaseRepository.php";
require_once "./Repository/TweetRepository.php";
require_once "./Model/User.php";
require_once "./Repository/UserRepository.php";
require_once "./Model/Likes.php";
require_once "./Repository/LikeRepository.php";

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

require_once './Controller/'.$controllerName.'.php';

$request = new Request($_GET, $_POST);

/** @var mixed $controller */
$controller = new $controllerName();
/**
 * @var ResponseInterface $response
 */
$response = $controller->$actionName($request);

$response->send();
