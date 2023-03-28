<?php
namespace Viki\Api\Models;

use Viki\Api;
use Viki\Api\Models\Sanitize;
use Viki\Api\Models\Sql;

class Languages extends Sql{

    private static $table_name = "languages";
    
    public static function conn(){
        return parent::conn();
    }

    public static function getAllowedFields()
    {
        $result = self::conn()->query("show columns from languages");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $fields = [];

        foreach ($data as $d) {
            array_push($fields, $d['Field']);
        }
        return $fields;
    }

    public static function getAllLanguages() {
        $result = self::conn()->query("select langId, language, isActive from languages");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    public static function updateLanguageById($language) {
        $stmtError = null;
        $allowedFields = self::getAllowedFields();
        foreach ($language as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $language[$key] = Sanitize::sanitizeString($language[$key]);
            }
        }
        extract($language);

        $conn = self::conn();
        if (isset($language['isActive'])){
            $status = $language['isActive'] === 'Active' ? 1 : 0;
            $statusChange = !$status;
            $sql = "UPDATE languages SET
                isActive = ? WHERE langId = ?"; 
        } else if (isset($language)){
            $sql = "UPDATE languages SET
            language = ? WHERE langId = ?"; 
        }
        $stmt = $conn->prepare($sql);

        if (isset($language['isActive'])){
            $bind_success = $stmt->bind_param("ii",$statusChange, $langId);
        } else if (isset($language)){
            $bind_success = $stmt->bind_param("si",$language, $langId);
        }

        if ($bind_success === false) {
            // bind_param failed, handle the error
            $stmtError = $stmt->error;
        }

        $exec_success = $stmt->execute();

        if ($exec_success === false) {
            // execute failed, handle the error
            $stmtError = $stmt->error;
        } 

        $stmt->close();

        if(!$stmtError){
            $result = [
                "result"=>"successful"
            ];
            return $result;
        }  else {
            $result = [
                "result"=>"error",
                "message"=> 'Something went wrong',
                "error"=>  $stmtError 
            ];
            return $result;
        }   

}

public static function getLanguageById($id) {
    $stmtError = null;
    $id= $id['id'];
    $conn = self::conn(); 
    $stmt = $conn->prepare("select * from languages WHERE langId = ? ");
    $stmt->bind_param("i", $id);
    $bind_success = $stmt->bind_param("i", $id);
    if ($bind_success === false) {
        // bind_param failed, handle the error
        $stmtError = $stmt->error;
    }

    $exec_success = $stmt->execute();

    if ($exec_success === false) {
        // execute failed, handle the error
        $stmtError = $stmt->error;
    } 
    $stmt->execute() ; 
    $result = $stmt->get_result(); 
    $data = $result->fetch_assoc();
    if(!$stmtError){
        return $data;
    } else {
        $result = [
            "result"=>"error",
            "message"=> 'Something went wrong',
            "error"=>  $stmtError 
        ];
        return $result;
    }
}

     
    public static function addNewLanguage($language) {
        $stmtError = null;
        $allowedFields = self::getAllowedFields();
        foreach ($language as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $language[$key] = Sanitize::sanitizeString($language[$key]);
            }
        }
        extract($language);
        
        $conn = self::conn(); 
        $sql = "INSERT INTO languages 
        (language)
        VALUES (?) ;";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $language);
        $bind_success = $stmt->bind_param("s", $language);
    
            if ($bind_success === false) {
                // bind_param failed, handle the error
                $stmtError = $stmt->error;
            }
    
            $exec_success = $stmt->execute();
    
            if ($exec_success === false) {
                // execute failed, handle the error
                $stmtError = $stmt->error;
            } 
            
            
        $stmt->close();
        if(!$stmtError){
            return $data;
        } else {
            $result = [
                "result"=>"error",
                "message"=> 'Something went wrong',
                "error"=>  $stmtError 
            ];
            return $result;
        }

    }


}

?>