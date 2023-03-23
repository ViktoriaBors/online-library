<?php
namespace Viki\Api\Models;

use Viki\Api;
use Viki\Api\Models\Sanitize;
use Viki\Api\Models\Sql;

class Authors extends Sql{

    private static $table_name = "authors";
    
    public static function conn(){
        return parent::conn();
    }

    public static function getAllowedFields()
    {
        $result = self::conn()->query("show columns from authors");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $fields = [];

        foreach ($data as $d) {
            array_push($fields, $d['Field']);
        }
        return $fields;
    }

    public static function getAllAuthors() {
        $result = self::conn()->query("select authorId, authorName, isActive from authors");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    public static function updateAuthorById($author) {

        $allowedFields = self::getAllowedFields();
        foreach ($author as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $author[$key] = Sanitize::sanitizeString($author[$key]);
            }
        }
        extract($author);

        $conn = self::conn();
        if (isset($author['isActive'])){
            $status = $author['isActive'] === 'Active' ? 1 : 0;
            $statusChange = !$status;
            $sql = "UPDATE authors SET
                isActive = ? WHERE authorId = ?"; 
        } else if (isset($author)){
            $sql = "UPDATE authors SET
            authorName = ? WHERE authorId = ?"; 
        }
        $stmt = $conn->prepare($sql);

        if (isset($author['isActive'])){
            $bind_success = $stmt->bind_param("ii",$statusChange, $authorId);
        } else if (isset($author)){
            $bind_success = $stmt->bind_param("si",$authorName, $authorId);
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

public static function getAuthorById($id) {
    $id= $id['id'];
    $conn = self::conn(); 
    $stmt = $conn->prepare("select * from authors WHERE authorId = ? ");
    $stmt->bind_param("i", $id);
    $bind_success = $stmt->bind_param("i", $id);
    if ($bind_success === false) {
        // bind_param failed, handle the error
        echo "bind_param error: " . $stmt->error;
    }

    $exec_success = $stmt->execute();

    if ($exec_success === false) {
        // execute failed, handle the error
        echo "execute error: " . $stmt->error;
    } 
    $stmt->execute() ; 
    $result = $stmt->get_result(); 
    $data = $result->fetch_assoc();
    return $data;
}

     
    public static function addNewAuthor($author) {

        $allowedFields = self::getAllowedFields();
        foreach ($author as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $author[$key] = Sanitize::sanitizeString($author[$key]);
            }
        }
        extract($author);
        $newAuthor = $author['authorName'];
        $conn = self::conn(); 
        $sql = "INSERT INTO authors 
        (authorName)
        VALUES (?) ;";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $newAuthor);
        $bind_success = $stmt->bind_param("s", $newAuthor);
    
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

    //////////////
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