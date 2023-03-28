<?php
namespace Viki\Api\Controllers;

use Viki\Api\Models\Sql;
use Viki\Api;
use Viki\Api\Models\IssuedBooks;

class IssuedBooksController extends Sql{


    private static $table_name = "books";

    public static function conn(){
        return parent::conn();
    }

    public static function createNewIssue($issue) {
        $data = IssuedBooks::createNewIssue($issue);
        echo json_encode($data);
    }
    

    public static function getUserHistory($query) {
        $data = IssuedBooks::getUserHistory($query);
        return $data;
        
    }

    public static function returnBook($issue)
    {
        $issueId = $issue['issueId'];

        $to = "fromlabtoweb.projects@gmail.com";
        $subject = 'Return request';
        $message = 'The following issue is requested to return:' . $issueId . '<br><br>';
        $headers = 'From: fromlabtoweb.projects@gmail.com' . "\r\n" .
                   'Reply-To: fromlabtoweb.projects@gmail.com' . "\r\n" .
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
    
    public static function getAllIssuedBooks() {
            $data = IssuedBooks::getAllIssuedBooks();
            return $data;
        }

    public static function returnBookConfirm($issue) {
        $data = IssuedBooks::returnBookConfirm($issue);
        echo json_encode($data);
        }


}

?>