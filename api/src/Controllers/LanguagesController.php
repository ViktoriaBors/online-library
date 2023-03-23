<?php
namespace Viki\Api\Controllers;

use Viki\Api;
use Viki\Api\Models\Languages;
use Viki\Api\Models\Sql;

class LanguagesController extends Sql{

    private static $table_name = "languages";
    
    public static function conn(){
        return parent::conn();
    }

    public static function getAllLanguages() {
        $data = Languages::getAllLanguages();
        return $data;
    }
    

    public static function updateLanguageById($language) {

        $data = Languages::updateLanguageById($language);
        echo json_encode($data);

}

public static function getLanguageById($id) {
    $data = Languages::getLanguageById($id);
    return $data;
}

    public static function addNewLanguage($language) {
        $data = Languages::addNewLanguage($language);
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