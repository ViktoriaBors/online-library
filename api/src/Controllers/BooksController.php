<?php
namespace Viki\Api\Controllers;

use Viki\Api\Models\Sql;
use Viki\Api;
use Viki\Api\Models\Books;

class BooksController extends Sql{


    private static $table_name = "books";

    public static function conn(){
        return parent::conn();
    }
    

    public static function getBookCollection($query) {
        $data = Books::getBookCollection($query);
        return $data;

    }
    
 
        public static function getAllBooks() {
            $data = Books::getAllBooks();
            return $data;
        }

        public static function updateBookById($book) {

            $data = Books::updateBookById($book);
            echo json_encode($data);
    }

    public static function getBookById($id) {
        $data = Books::getBookById($id);
        return $data;
    }

    public static function addNewBook($book) {
        $data = Books::addNewBook($book);
        echo json_encode($data);
    }

}

?>