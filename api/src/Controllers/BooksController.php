<?php
namespace Viki\Api\Controllers;

use Viki\Api;
use Viki\Api\Classes\Sanitize;

class BooksController {


    private static $table_name = "books";

    public static function conn(){
        $sql = new Api\Sql();
        return $conn = $sql::conn();
    }
    
   
    public static function getAllowedFields()
    {
        $result = self::conn()->query("show columns from books");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $fields = [];

        foreach ($data as $d) {
            array_push($fields, $d['Field']);
        }
        return $fields;
    }

    public static function getAllBooks() {

        $result = self::conn()->query("select * from books");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    public static function getBookCollection($query) {
        //$query[0] == 'category', "2"
        //$query[1] == 'language', "1" 
        // $query[2] == 'search, 'word'

        $query_components = [];
        for($i=0; $i < count($query); $i++){
            array_push($query_components,explode("=", $query[$i]));
        }
        $category = $query_components[0][1];
        $language = $query_components[1][1];
        $search = $query_components[2][1];
        if ( $search !== 'undefined'){
            $search = '%' . $query_components[2][1] . '%';
        } else {
            $search = '%%';
        }
        if($query_components[0][1] !== 'undefined' && $query_components[1][1] !== 'undefined'){
            $result = self::conn()->query("SELECT books.title, books.description, books.url, categories.categoryName, 
            authors.authorName, languages.language
            FROM books 
            INNER JOIN categories ON books.category = categories.categoryId
            INNER JOIN authors ON books.author = authors.authorId
            INNER JOIN languages ON books.language = languages.langId
            WHERE books.category = $category AND books.language = $language 
            AND (books.title LIKE '$search' OR authors.authorName LIKE '$search')");
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        } else  if($query_components[0][1] == 'undefined' && $query_components[1][1] !== 'undefined' ){
            $result = self::conn()->query("SELECT books.title, books.description, books.url, categories.categoryName, 
            authors.authorName, languages.language
            FROM books 
            INNER JOIN categories ON books.category = categories.categoryId
            INNER JOIN authors ON books.author = authors.authorId
            INNER JOIN languages ON books.language = languages.langId
            WHERE books.language = $language
            AND (books.title LIKE '$search' OR authors.authorName LIKE '$search')");
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;   
        } else  if($query_components[0][1] !== 'undefined' && $query_components[1][1] == 'undefined' ){
                $result = self::conn()->query("SELECT books.title, books.description, books.url, categories.categoryName, 
                authors.authorName, languages.language
                FROM books 
                INNER JOIN categories ON books.category = categories.categoryId
                INNER JOIN authors ON books.author = authors.authorId
                INNER JOIN languages ON books.language = languages.langId
                WHERE books.category = $category
                AND (books.title LIKE '$search' OR authors.authorName LIKE '$search')");
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data;            
        } else {
            $result = self::conn()->query("SELECT books.title, books.description, books.url, categories.categoryName, 
            authors.authorName, languages.language
            FROM books
            INNER JOIN categories ON books.category = categories.categoryId
            INNER JOIN authors ON books.author = authors.authorId
            INNER JOIN languages ON books.language = languages.langId
            AND (books.title LIKE '$search' OR authors.authorName LIKE '$search')");
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

    }
    
    public static function getBooksById($id) {

        $conn = self::conn(); 
        $stmt = $conn->prepare("select * from books WHERE id = ? ");
        $stmt->bind_param("i", $id);
        $stmt->execute() ; 
        $result = $stmt->get_result(); 
        $data = $result->fetch_assoc();
        
        return $data;
    }
    
    public static function createUser($user) {

        $allowedFields = self::getAllowedFields();
        foreach ($user as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $user[$key] = Sanitize::sanitizeString($user[$key]);
            }
        }
        extract($user);

        $conn = self::conn(); 
        $sql = "INSERT INTO user 
        (first_name, last_name, email_id)
        VALUES (?, ?, ?) ;";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sss", $first_name, $last_name, $email_id);
        $bind_success = $stmt->bind_param("sss", $first_name, $last_name, $email_id);
    
            if ($bind_success === false) {
                // bind_param failed, handle the error
                echo "bind_param error: " . $stmt->error;
            }
    
            $exec_success = $stmt->execute();
    
            if ($exec_success === false) {
                // execute failed, handle the error
                echo "execute error: " . $stmt->error;
            } else {
                return array(
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email_id' => $user['email_id']
                );
            }

        $stmt->close();
    }
    
    public static function updateUser($id, $user) {

            $allowedFields = self::getAllowedFields();
            foreach ($user as $key => $field) {
                if (in_array($key, $allowedFields)) {
                    $user[$key] = Sanitize::sanitizeString($user[$key]);
                }
            }
            extract($user);
    
            $conn = self::conn();
            $sql = "UPDATE user SET
                first_name = ?, last_name = ?, email_id = ? WHERE id = ?"; 
            
   
            $stmt = $conn->prepare($sql);
    
            $bind_success = $stmt->bind_param("sssi",$first_name, $last_name, $email_id , $id);

            if ($bind_success === false) {
                // bind_param failed, handle the error
                echo "bind_param error: " . $stmt->error;
            }
    
            $exec_success = $stmt->execute();
    
            if ($exec_success === false) {
                // execute failed, handle the error
                echo "execute error: " . $stmt->error;
            } else {
                return array(
                    'id' => $id,
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email_id' => $user['email_id']
                );
            }

        $stmt->close();

    }
    
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