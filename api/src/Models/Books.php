<?php
namespace Viki\Api\Models;

use Viki\Api\Models\Sql;


class Books extends Sql{


    private static $table_name = "books";

    public static function conn(){
        return parent::conn();
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
            $result = self::conn()->query("SELECT *
            FROM books 
            INNER JOIN categories ON books.category = categories.categoryId
            INNER JOIN authors ON books.author = authors.authorId
            INNER JOIN languages ON books.language = languages.langId
            WHERE books.category = $category AND books.language = $language 
            AND (books.title LIKE '$search' OR authors.authorName LIKE '$search')");
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        } else  if($query_components[0][1] == 'undefined' && $query_components[1][1] !== 'undefined' ){
            $result = self::conn()->query("SELECT *
            FROM books 
            INNER JOIN categories ON books.category = categories.categoryId
            INNER JOIN authors ON books.author = authors.authorId
            INNER JOIN languages ON books.language = languages.langId
            WHERE books.language = $language
            AND (books.title LIKE '$search' OR authors.authorName LIKE '$search')");
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;   
        } else  if($query_components[0][1] !== 'undefined' && $query_components[1][1] == 'undefined' ){
                $result = self::conn()->query("SELECT *
                FROM books 
                INNER JOIN categories ON books.category = categories.categoryId
                INNER JOIN authors ON books.author = authors.authorId
                INNER JOIN languages ON books.language = languages.langId
                WHERE books.category = $category
                AND (books.title LIKE '$search' OR authors.authorName LIKE '$search')");
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data;            
        } else {
            $result = self::conn()->query("SELECT *
            FROM books
            INNER JOIN categories ON books.category = categories.categoryId
            INNER JOIN authors ON books.author = authors.authorId
            INNER JOIN languages ON books.language = languages.langId
            AND (books.title LIKE '$search' OR authors.authorName LIKE '$search')");
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

    }
    
    public static function createNewIssue($issue) {
        $allowedFields = self::getAllowedFields();
        foreach ($issue as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $user[$key] = Sanitize::sanitizeString($issue[$key]);
            }
        }
        extract($issue);
    
        $returnDate = date('Y-m-d', strtotime('+21 days'));
        $conn = self::conn(); 
    
        $stmt = $conn->prepare("INSERT INTO issuedbooks (userId, bookId, isIssued, returnDate) VALUES (?, ?, 1, ?)");
        $stmt->bind_param("iis", $userId, $bookId, $returnDate);
        $stmt->execute();
        $stmt->close();
    
        $stmt2 = $conn->prepare("UPDATE books SET availability = availability - 1 WHERE bookId = ?");
        $stmt2->bind_param("i", $bookId);
        $stmt2->execute();
        $stmt2->close();
    
        $result = [
            "result" => "successful",
            "message" => "Successful book issue. Do not forget to return the book latest $returnDate."
        ];
        return $result;
    }

    public static function getUserHistory($query) {
        $query_components = [];
        for($i=0; $i < count($query); $i++){
            array_push($query_components,explode("=", $query[$i]));
        }
        $userId = $query_components[0][1];
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $result = self::conn()->query("SELECT issuedbooks.issueId, books.title, authors.authorName, categories.categoryName, languages.language, issuedbooks.returnDate
            FROM issuedbooks 
            INNER JOIN books ON books.bookId = issuedbooks.bookId
            INNER JOIN categories ON categories.categoryId = books.category
            INNER JOIN languages ON languages.langId = books.language
            INNER JOIN authors ON authors.authorId = books.author
            WHERE userId = $userId AND isReturned = 0;");

       return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getAllBooks() {

        $result = self::conn()->query("SELECT bookId, title as title, authorName as author, categoryName as category, languages.language as language, availability, books.isActive
        FROM books 
        INNER JOIN categories ON books.category = categories.categoryId
        INNER JOIN authors ON books.author = authors.authorId
        INNER JOIN languages ON books.language = languages.langId
        
        ");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    public static function updateBookById($book) {

        $allowedFields = self::getAllowedFields();
        foreach ($book as $key => $field) {
            if (in_array($key, $allowedFields)) {
                if (is_array($field)) {
                    foreach ($field as $subKey => $subField) {
                        $book[$key][$subKey] = Sanitize::sanitizeString($subField);
                    }
                } else {
                    $book[$key] = Sanitize::sanitizeString($field);
                }
            }
        }
        
        extract($book);

        $conn = self::conn();
        if (isset($book['isActive'])){
            $status = $book['isActive'] === 'Active' ? 1 : 0;
            $statusChange = !$status;
            $sql = "UPDATE books SET
                isActive = ? WHERE bookId = ?"; 
        } else if (isset($book)){
            $sql = "UPDATE books SET
            title = ?, author = ?, language = ?, category = ?, availability = ?  WHERE bookId = ?"; 
        }
        $stmt = $conn->prepare($sql);

        if (isset($book['isActive'])){
            $bind_success = $stmt->bind_param("ii",$statusChange, $bookId);
        } else if (isset($book)){
            $bind_success = $stmt->bind_param("ssssii",$title, $author, $language, $category, $availability, $bookId);
        }

        if ($bind_success === false) {
            // bind_param failed, handle the error
            echo "bind_param error: " . $stmt->error;
        }

        $exec_success = $stmt->execute();

        if ($exec_success === false) {
            // execute failed, handle the error
            echo "execute error: " . $stmt->error;
        } else {
            $result = [
                "result"=>"successful"
            ];
            return $result;
        }

    $stmt->close();

}

public static function getBookById($id) {
    $id= $id['id'];
    $conn = self::conn(); 
    $stmt = $conn->prepare("select * from books WHERE bookId = ? ");
    $stmt->bind_param("i", $id);
    $stmt->execute() ; 
    $result = $stmt->get_result(); 
    $data = $result->fetch_assoc();
    return $data;
}

public static function addNewBook($book) {

    $allowedFields = self::getAllowedFields();
    foreach ($book as $key => $field) {
        if (in_array($key, $allowedFields)) {
            if (is_array($field)) {
                foreach ($field as $subKey => $subField) {
                    $book[$key][$subKey] = Sanitize::sanitizeString($subField);
                }
            } else {
                $book[$key] = Sanitize::sanitizeString($field);
            }
        }
    }

    extract($book);

    $title = $book['title'];
    $author = $book['author'];
    $language = $book['language'];
    $category = $book['category'];
    $availability = $book['availability'];
    $description =  $book['description'];
    $url = '/img/cover/na.jpg';
    $conn = self::conn(); 
    $sql = "INSERT INTO books 
    (title, author, language, category, availability, url, description)
    VALUES (?,?,?,?,?,?,?) ;";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("siiiiss", $title, $author, $language, $category, $availability, $url, $description);
    $bind_success = $stmt->bind_param("siiiiss", $title, $author, $language, $category, $availability, $url, $description);

        if ($bind_success === false) {
            // bind_param failed, handle the error
            echo "bind_param error: " . $stmt->error;
        }

        $exec_success = $stmt->execute();

        if ($exec_success === false) {
            // execute failed, handle the error
            echo "execute error: " . $stmt->error;
        } else {
            $result = [
                "result"=>"successful"
            ];
            return $result;
        }

    $stmt->close();
}


}

?>