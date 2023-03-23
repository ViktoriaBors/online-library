<?php
namespace Viki\Api\Controllers;

use Viki\Api;
use Viki\Api\Models\Authors;
use Viki\Api\Models\Sql;

class AuthorsController extends Sql{

    private static $table_name = "languages";
    
    public static function conn(){
        return parent::conn();
    }

    public static function getAllAuthors() {
        $data = Authors::getAllAuthors();
        return $data;
    }
    

    public static function updateAuthorById($author) {

        $data = Authors::updateAuthorById($author);
        echo json_encode($data);

}

public static function getAuthorById($id) {
    $data = Authors::getAuthorById($id);
    return $data;
}

    public static function addNewAuthor($author) {
        $data = Authors::addNewAuthor($author);
        echo json_encode($data);
    }
    
    ///////////////////////    
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