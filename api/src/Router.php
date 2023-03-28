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
    $r->addRoute('GET', '/api/books', "Viki\Api\Controllers\BooksController::getAllBooks");
    $r->addRoute('GET', '/api/categories', "Viki\Api\Controllers\CategoriesController::getAllCategories");
    $r->addRoute('GET', '/api/languages', "Viki\Api\Controllers\LanguagesController::getAllLanguages");
    $r->addRoute('GET', '/api/authors', "Viki\Api\Controllers\AuthorsController::getAllAuthors");
    $r->addRoute('POST', '/api/register', "Viki\Api\Controllers\UsersController::registerNewUser");
    $r->addRoute('POST', '/api/loginUser', "Viki\Api\Controllers\UsersController::loginUser");
    $r->addRoute('POST', '/api/forgotPassword', "Viki\Api\Controllers\UsersController::forgotPassword");
    $r->addRoute('POST', '/api/updatePassword', "Viki\Api\Controllers\UsersController::updateUserByEmail");
    $r->addRoute('POST', '/api/activateUser', "Viki\Api\Controllers\UsersController::activateUser");
    $r->addRoute('GET', '/api/getUser/{id:\d+}', "Viki\Api\Controllers\UsersController::getUsersById");
    $r->addRoute('POST', '/api/loginAdmin', "Viki\Api\Controllers\UsersController::loginAdmin");
    $r->addRoute('GET', '/api/category/{id:\d+}', "Viki\Api\Controllers\CategoriesController::getCategoryById");
    $r->addRoute('GET', '/api/language/{id:\d+}', "Viki\Api\Controllers\LanguagesController::getLanguageById");
    $r->addRoute('GET', '/api/author/{id:\d+}', "Viki\Api\Controllers\AuthorsController::getAuthorById");
    $r->addRoute('GET', '/api/book/{id:\d+}', "Viki\Api\Controllers\BooksController::getBookById");
    

    $r->addGroup('/api/user', function (FastRoute\RouteCollector $r) {
        $r->addRoute('POST', '/issueBook', "Viki\Api\Controllers\IssuedBooksController::createNewIssue");
        $r->addRoute('GET', '/userHistory', "Viki\Api\Controllers\IssuedBooksController::getUserHistory");
        $r->addRoute('POST', '/returnBook', "Viki\Api\Controllers\IssuedBooksController::returnBook");       
        $r->addRoute('POST', '/updateUser', "Viki\Api\Controllers\UsersController::updateUserById");
    });
    $r->addGroup('/api/admin', function (FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/counts', "Viki\Api\Controllers\UsersController::getCounts");
        $r->addRoute('POST', '/categoryStatus', "Viki\Api\Controllers\CategoriesController::updateCategoryById");
        $r->addRoute('POST', '/categoryName', "Viki\Api\Controllers\CategoriesController::updateCategoryById");
        $r->addRoute('POST', '/newCategory', "Viki\Api\Controllers\CategoriesController::addNewCategory");
        $r->addRoute('POST', '/languageStatus', "Viki\Api\Controllers\LanguagesController::updateLanguageById");
        $r->addRoute('POST', '/language', "Viki\Api\Controllers\LanguagesController::updateLanguageById");
        $r->addRoute('POST', '/newLanguage', "Viki\Api\Controllers\LanguagesController::addNewLanguage");
        $r->addRoute('POST', '/authorStatus', "Viki\Api\Controllers\AuthorsController::updateAuthorById");
        $r->addRoute('POST', '/authorName', "Viki\Api\Controllers\AuthorsController::updateAuthorById");
        $r->addRoute('POST', '/newAuthor', "Viki\Api\Controllers\AuthorsController::addNewAuthor");
        $r->addRoute('POST', '/bookStatus', "Viki\Api\Controllers\BooksController::updateBookById");
        $r->addRoute('POST', '/book', "Viki\Api\Controllers\BooksController::updateBookById");
        $r->addRoute('POST', '/newBook', "Viki\Api\Controllers\BooksController::addNewBook");
        $r->addRoute('GET', '/users', "Viki\Api\Controllers\UsersController::getAllUsers");
        $r->addRoute('GET', '/user/{id:\d+}', "Viki\Api\Controllers\UsersController::getUserById");
        $r->addRoute('POST', '/userBanStatus', "Viki\Api\Controllers\UsersController::updateUserById");
        $r->addRoute('POST', '/userApproveStatus', "Viki\Api\Controllers\UsersController::updateUserById");
        $r->addRoute('POST', '/changeUserRole', "Viki\Api\Controllers\UsersController::updateUserById");
        $r->addRoute('POST', '/deleteUser', "Viki\Api\Controllers\UsersController::deleteUserById");
        $r->addRoute('GET', '/issuedbooks', "Viki\Api\Controllers\IssuedBooksController::getAllIssuedBooks");
        $r->addRoute('POST', '/returnBook', "Viki\Api\Controllers\IssuedBooksController::returnBookConfirm");
        $r->addRoute('POST', '/paidFine', "Viki\Api\Controllers\IssuedBooksController::finePaid"); 
    });
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
        /*
        echo "not found 404";
        header("HTTP/1.0 404 Not Found");
        die();
        */
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        /*
        header("HTTP/1.0 405 Method Not Allowed");
        echo "not allowed 405";
        die();
        */
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // var_dump($handler);
        // var_dump($vars);
        // var_dump($query_components);
        if($handler === "Viki\Api\Controllers\BooksController::getBookCollection"
           || $handler === "Viki\Api\Controllers\IssuedBooksController::getUserHistory"
           ){
            $response = $handler($query_components);
            header('Content-Type: application/json');
            echo json_encode($response);
        } else if ($handler === "Viki\Api\Controllers\CategoriesController::getAllCategories"
            || $handler === "Viki\Api\Controllers\LanguagesController::getAllLanguages"
            || $handler === "Viki\Api\Controllers\AuthorsController::getAllAuthors"
            || $handler === "Viki\Api\Controllers\UsersController::getCounts"
            || $handler === "Viki\Api\Controllers\BooksController::getAllBooks"
            || $handler === "Viki\Api\Controllers\UsersController::getAllUsers"
            || $handler === "Viki\Api\Controllers\IssuedBooksController::getAllIssuedBooks"
            ){
                $response = $handler();
                header('Content-Type: application/json');
                echo json_encode($response);
        } else if ($handler === "Viki\Api\Controllers\UsersController::registerNewUser"
            || $handler === "Viki\Api\Controllers\UsersController::loginUser"
            || $handler === "Viki\Api\Controllers\UsersController::activateUser"
            || $handler ===  "Viki\Api\Controllers\UsersController::forgotPassword"
            || $handler === "Viki\Api\Controllers\UsersController::updateUserByEmail"
            || $handler ===  "Viki\Api\Controllers\IssuedBooksController::createNewIssue"
            || $handler === "Viki\Api\Controllers\IssuedBooksController::returnBook"
            || $handler === "Viki\Api\Controllers\UsersController::updateUserById"
            || $handler === "Viki\Api\Controllers\UsersController::loginAdmin"
            || $handler === "Viki\Api\Controllers\CategoriesController::updateCategoryById"
            || $handler === "Viki\Api\Controllers\CategoriesController::addNewCategory"
            || $handler === "Viki\Api\Controllers\LanguagesController::updateLanguageById"
            || $handler === "Viki\Api\Controllers\LanguagesController::addNewLanguage"
            || $handler === "Viki\Api\Controllers\AuthorsController::updateAuthorById"
            || $handler === "Viki\Api\Controllers\AuthorsController::addNewAuthor"
            || $handler ===  "Viki\Api\Controllers\BooksController::updateBookById"
            || $handler ===  "Viki\Api\Controllers\BooksController::addNewBook"
            || $handler ===  "Viki\Api\Controllers\UsersController::deleteUserById"
            || $handler === "Viki\Api\Controllers\IssuedBooksController::returnBookConfirm"
            || $handler === "Viki\Api\Controllers\IssuedBooksController::finePaid"
            ){
            $requestBody = file_get_contents('php://input');
            $data = json_decode($requestBody, true);
            $handler($data);
        } else if ($handler === "Viki\Api\Controllers\UsersController::getUsersById"
            || $handler === "Viki\Api\Controllers\CategoriesController::getCategoryById"
            || $handler === "Viki\Api\Controllers\LanguagesController::getLanguageById"
            || $handler === "Viki\Api\Controllers\AuthorsController::getAuthorById" 
            || $handler === "Viki\Api\Controllers\BooksController::getBookById"  
            || $handler === "Viki\Api\Controllers\UsersController::getUserById"        
        ){
            $response = $handler($vars);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        break;
}
?>
