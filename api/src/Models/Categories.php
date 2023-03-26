<?php
namespace Viki\Api\Models;

use Viki\Api;
use Viki\Api\Models\Sanitize;
use Viki\Api\Models\Sql;

class Categories extends Sql{

    private static $table_name = "categories";
    
    public static function conn(){
        return parent::conn();
    }

    public static function getAllowedFields()
    {
        $result = self::conn()->query("show columns from categories");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $fields = [];

        foreach ($data as $d) {
            array_push($fields, $d['Field']);
        }
        return $fields;
    }

    public static function getAllCategories() {
        $result = self::conn()->query("select categoryId, categoryName, isActive from categories");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    public static function updateCategoryById($category) {

        $allowedFields = self::getAllowedFields();
        foreach ($category as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $category[$key] = Sanitize::sanitizeString($category[$key]);
            }
        }
        extract($category);

        $conn = self::conn();
        if (isset($category['isActive'])){
            $status = $category['isActive'] === 'Active' ? 1 : 0;
            $statusChange = !$status;
            $sql = "UPDATE categories SET
                isActive = ? WHERE categoryId = ?"; 
        } else if (isset($categoryName)){
            $sql = "UPDATE categories SET
            categoryName = ? WHERE categoryId = ?"; 
        }
        $stmt = $conn->prepare($sql);

        if (isset($category['isActive'])){
            $bind_success = $stmt->bind_param("ii",$statusChange, $categoryId);
        } else if (isset($categoryName)){
            $bind_success = $stmt->bind_param("si",$categoryName, $categoryId);
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

public static function getCategoryById($id) {
    $id= $id['id'];
    $conn = self::conn(); 
    $stmt = $conn->prepare("select * from categories WHERE categoryId = ? ");
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

     
    public static function addNewCategory($category) {

        $allowedFields = self::getAllowedFields();
        foreach ($category as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $category[$key] = Sanitize::sanitizeString($category[$key]);
            }
        }
        extract($category);
        $newCategory = $category['categoryName'];
        $conn = self::conn(); 
        $sql = "INSERT INTO categories 
        (categoryName)
        VALUES (?) ;";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $newCategory);
        $bind_success = $stmt->bind_param("s", $newCategory);
    
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