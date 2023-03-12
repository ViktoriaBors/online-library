<?php
namespace Viki\Api;

class RouterGet
{
    public function __construct($uri)
    {
        $url_components_path = parse_url($uri, PHP_URL_PATH);
        $url_components_query = parse_url($uri, PHP_URL_QUERY);
        $url_path = explode("/", $url_components_path);
        $url_query = explode("&", $url_components_query);
        // url_path: "", api, "collection"
        // url_query: "", category=2, language=3

        if($url_path[2] === 'categories'){// categories
            $response = CategoriesController::getAllCategories();
            // Set the response headers and encode the response as JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        }else if($url_path[2] === 'languages'){// categories
                $response = LanguagesController::getAllLanguages();
                // Set the response headers and encode the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
        } else if ($url_path[2] === 'collection'){ //collection
            $response = BooksController::getBookCollection($url_query);
            // Set the response headers and encode the response as JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        }
/*
        if (isset($url_path[2])) {
            $id = $url_path[2];
            // Get a specific user by ID
            $response = UserController::getUserById($id);
            // Set the response headers and encode the response as JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            // Get all user
            $response = UserController::getAllUser();
            // Set the response headers and encode the response as JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        */
    }
}

?>