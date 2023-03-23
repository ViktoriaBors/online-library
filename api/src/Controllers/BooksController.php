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
    
    public static function createNewIssue($issue) {
        $data = Books::createNewIssue($issue);
        echo json_encode($data);
    }

    public static function getUserHistory($query) {
        $data = Books::getUserHistory($query);
        return $data;
        
    }

    public static function returnBook($issue)
    {
        $issueId = $issue['issueId'];

        $to = "boszka88@gmail.com";
        $subject = 'Return request';
        $message = 'The following issue is requested to return:' . $issueId . '<br><br>';
        $headers = 'From: boszka88@gmail.com' . "\r\n" .
                   'Reply-To: boszka88@gmail.com' . "\r\n" .
                   'Content-Type: text/html; charset=UTF-8' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion(); 
     if (mail($to, $subject, $message, $headers)) {
            $result = [
            "result"=>"successful",
            "message"=>"Successful registration of returning the book."
                ];
            echo json_encode($result);
        } else {
            $result = [
                "result"=>"error",
                "message"=>"Failed to register book return. Please try again later."
            ];
            echo json_encode($result);
            }
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
//////////////////
    
    public static function deleteUser($id) {

    $conn = self::conn(); 
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ? ");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return array('success' => true);
    } else {
        return array('error' => true, 'message' => 'Error deleting user');
    }
}

}

?>