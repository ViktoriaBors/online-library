<?php
namespace Viki\Api\Controllers;

use Viki\Api;
use Viki\Api\Models\Categories;
use Viki\Api\Models\Sql;

class CategoriesController extends Sql{

    private static $table_name = "categories";
    
    public static function conn(){
        return parent::conn();
    }

    public static function getAllCategories() {
        $data = Categories::getAllCategories();
        return $data;
    }
    

    public static function updateCategoryById($category) {

        $data = Categories::updateCategoryById($category);
        echo json_encode($data);

}

public static function getCategoryById($id) {
    $data = Categories::getCategoryById($id);
    return $data;
}

    public static function addNewCategory($category) {
        $data = Categories::addNewCategory($category);
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