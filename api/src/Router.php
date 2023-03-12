<?php

require 'vendor/autoload.php';

use Viki\Api;

$dispatcher = \FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/api/collection', "Viki\Api\Controllers\BooksController::getBookCollection");
    $r->addRoute('GET', '/api/categories', "Viki\Api\Controllers\CategoriesController::getAllCategories");
    $r->addRoute('GET', '/api/languages', "Viki\Api\Controllers\LanguagesController::getAllLanguages");
    // {id} must be a number (\d+)
    // The /{title} suffix is optional
    // $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
    // $r->addRoute('GET', '/test', "App\Controllers\Products::getAll");
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
        }
        // ... call $handler with $vars
        break;
}





/*
class Router {

    private $query_string;
    private $parts;

    public function __construct()
    {
       $this->query_string = $_SERVER['REQUEST_URI'];
       $this->parts = explode("/", $this->query_string);

    $controller = "Viki\\Html\\Controllers\\".ucfirst($this->parts[1]);
    if(class_exists($controller)){
        $p= new $controller;
        if(isset($this->parts[2])){
                if(method_exists($p, $this->parts[2])){ 
                    echo "van ilyen method";
                    $result = $p->{$this->parts[2]}($this->parts[3]??null);
            } else {
                echo "nincs ilyen method ";
                $id = $this->parts[2];
                if(is_numeric($id)){
                    $result = $p->getOneById($this->parts[2]??null);
                } else {
                    $result = $p->getOneBySlug($this->parts[2]??null);
                 }
         }
        } else {
            $result = $p->getAll();
         }
    } else {
        if(file_exists("src/Views/".$this->parts[1]."html")){
            require "src/Views/".$this->parts[1]."html";
        } else {
            http_response_code(404);
            require "src/Views/404.html";
        }
    }

        
            
        
       
}
}
*/
?>
