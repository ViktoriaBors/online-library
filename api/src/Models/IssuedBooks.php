<?php
namespace Viki\Api\Models;

use Viki\Api\Models\Sql;


class IssuedBooks extends Sql{


    private static $table_name = "issuedbooks";

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
        $result = self::conn()->query("SELECT issuedbooks.issueId, books.title, authors.authorName, categories.categoryName, languages.language, issuedbooks.returnDate
            FROM issuedbooks 
            INNER JOIN books ON books.bookId = issuedbooks.bookId
            INNER JOIN categories ON categories.categoryId = books.category
            INNER JOIN languages ON languages.langId = books.language
            INNER JOIN authors ON authors.authorId = books.author
            WHERE userId = $userId AND isReturned = 0;");

       return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getAllIssuedBooks() {

        $result = self::conn()->query("SELECT issueId, books.title as title, authorName as author, users.name as user, users.email as email, isReturned, lateFine, returnDate
        FROM issuedbooks 
        INNER JOIN books ON books.bookId = issuedbooks.bookId
        INNER JOIN authors ON books.author = authors.authorId     
        INNER JOIN users ON issuedbooks.userId = users.userId  
        WHERE isReturned = 0 OR lateFine > 0  
        ");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
    
    public static function returnBookConfirm($issue) {
        $allowedFields = self::getAllowedFields();
        foreach ($issue as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $issue[$key] = Sanitize::sanitizeString($issue[$key]);
            }
        }
        extract($issue);
        
        // date now $returnDate = date('Y-m-d', strtotime('+21 days'));
        $conn = self::conn(); 
        $issueData = self::getIssuedBookById($issueId);
        $today =  date("Y-m-d");

        if($issueData['returnDate'] < $today){ // late return
            $days = floor((strtotime($today) - strtotime($issueData['returnDate'])) / (60 * 60 * 24));
            $fine = $days * 10;
            // update the fine
            $stmt = $conn->prepare("UPDATE issuedbooks SET lateFine = ? WHERE issueId = ? ");
            $stmt->bind_param("ii", $fine, $issueId);
            $bind_success = $stmt->bind_param("ii", $fine, $issueId);
            if ($bind_success === false) {
                // bind_param failed, handle the error
                echo "bind_param error: " . $stmt->error;
            }
    
            $exec_success = $stmt->execute();
    
            if ($exec_success === false) {
                // execute failed, handle the error
                echo "execute error: " . $stmt->error;
            }  else {
                $stmt->close();
            }
        } 

        if(isset($issue['finePaid'])){ // fine was paid after late return
            $stmt = $conn->prepare("UPDATE issuedbooks SET lateFine = ? WHERE issueId = ? ");
            $fine = 0;
            $stmt->bind_param("ii", $fine, $issueId);
            $bind_success = $stmt->bind_param("ii", $fine, $issueId);
            if ($bind_success === false) {
                // bind_param failed, handle the error
                echo "bind_param error: " . $stmt->error;
            }
    
            $exec_success = $stmt->execute();
    
            if ($exec_success === false) {
                // execute failed, handle the error
                echo "execute error: " . $stmt->error;
            }  else {
                $stmt->close();
                $result = [
                    "result" => "successful",
                    "message" => "Late fine paid."
                ];
                return $result;
            }
        }

        // in every case set the book status to returned
        $issueId = $issue['issueId'];
        $title = $issue['title'];

        $stmt = $conn->prepare("UPDATE issuedbooks SET isIssued = 0, isReturned = 1 WHERE issueId = ? ");
        $stmt->bind_param("i", $issueId);
        $bind_success = $stmt->bind_param("i", $issueId);
        if ($bind_success === false) {
            // bind_param failed, handle the error
            echo "bind_param error: " . $stmt->error;
        }

        $exec_success = $stmt->execute();

        if ($exec_success === false) {
            // execute failed, handle the error
            echo "execute error: " . $stmt->error;
        }  else {
            $stmt->close();
        }
        // start 2.statement  
        $stmt2 = $conn->prepare("UPDATE books SET availability = availability + 1 WHERE title = ?");
        $stmt2->bind_param("s", $title);
        $bind_success = $stmt2->bind_param("s", $title);
        if ($bind_success === false) {
            // bind_param failed, handle the error
            echo "bind_param error: " . $stmt2->error;
        }

        $exec_success = $stmt2->execute();

        if ($exec_success === false) {
            // execute failed, handle the error
            echo "execute error: " . $stmt2->error;
        } else {
            $stmt2->close();
        $result = [
            "result" => "successful",
            "message" => "Successful book return."
        ];
        return $result;
    }
  }


public static function getIssuedBookById($id) {
    $conn = self::conn(); 
    $stmt = $conn->prepare("select * from issuedbooks WHERE issueId = ? ");
    $stmt->bind_param("i", $id);
    $stmt->execute() ; 
    $result = $stmt->get_result(); 
    $data = $result->fetch_assoc();
    return $data;
}

}

?>