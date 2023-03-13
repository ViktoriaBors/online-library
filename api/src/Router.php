<?php

require 'vendor/autoload.php';

use Viki\Api;


// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');  // cache for 1 day
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');  
}


// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}

$dispatcher = \FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/api/collection', "Viki\Api\Controllers\BooksController::getBookCollection");
    $r->addRoute('GET', '/api/categories', "Viki\Api\Controllers\CategoriesController::getAllCategories");
    $r->addRoute('GET', '/api/languages', "Viki\Api\Controllers\LanguagesController::getAllLanguages");
    $r->addRoute('POST', '/api/register', "Viki\Api\Controllers\UsersController::registerNewUser");
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
$pos = strpos($uri, '?'); //find the position of ? in the url - if there is none returns false
if ($pos !== false) {
  $query = substr($uri, $pos + 1); // get query string out
  $query_components = explode("&", $query); // separate them at & as an array
  $uri = substr($uri, 0, $pos); //then strip the uri from the query
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "not found 404";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "not allowed 405";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // var_dump($handler);
        // var_dump($vars);
        // var_dump($query_components);
        if($handler === "Viki\Api\Controllers\BooksController::getBookCollection"){
            $response = $handler($query_components);
            header('Content-Type: application/json');
            echo json_encode($response);
        } else if ($handler === "Viki\Api\Controllers\CategoriesController::getAllCategories"
            || $handler === "Viki\Api\Controllers\LanguagesController::getAllLanguages"){
                $response = $handler();
                header('Content-Type: application/json');
                echo json_encode($response);
        } else if ($handler === "Viki\Api\Controllers\UsersController::registerNewUser"){
            $requestBody = file_get_contents('php://input');
            $data = json_decode($requestBody, true);
            $handler($data);
        };
        break;
}
?>
