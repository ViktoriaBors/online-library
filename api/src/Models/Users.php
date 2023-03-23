<?php

namespace Viki\Api\Models;

use Viki\Api\Models\Sql;
use Viki\Api\Models\Sanitize;



class Users extends Sql{

    private static $table_name = "users";

    public static function conn(){
        return parent::conn();
    }  
   
    public static function getAllowedFields()
    {
        $result = self::conn()->query("show columns from users");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $fields = [];

        foreach ($data as $d) {
            array_push($fields, $d['Field']);
        }
        return $fields;
    }

    public static function getAllUsers() {

        $result = self::conn()->query("select * from users");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
    
    public static function getUsersById($id) {
        $id= $id['id'];
        $conn = self::conn(); 
        $stmt = $conn->prepare("select * from users WHERE userId = ? ");
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

    public static function getUserByEmail($email) {

        $conn = self::conn(); 
        $stmt = $conn->prepare("select * from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $bind_success = $stmt->bind_param("s", $email);
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
    
    public static function registerNewUser($user) {

        $allowedFields = self::getAllowedFields();
        foreach ($user as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $user[$key] = Sanitize::sanitizeString($user[$key]);
            }
        }
        extract($user);
        
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $activationCode = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            $activationCodeHash = password_hash($activationCode, PASSWORD_DEFAULT);
            $activationExpire = date('Y-m-d', strtotime('+2 days'));
            $conn = self::conn(); 
            $sql = "INSERT INTO users
            (name, email, password, address, activationCode, activationExpire)
            VALUES (?, ?, ?, ?, ?, ?) ;";
    
            $stmt = $conn->prepare($sql);
    
            $stmt->bind_param("ssssss", $name, $email, $passwordHash, $address, $activationCodeHash, $activationExpire);
            $bind_success = $stmt->bind_param("ssssss", $name, $email, $passwordHash, $address, $activationCodeHash, $activationExpire);
        
                if ($bind_success === false) {
                    // bind_param failed, handle the error
                    echo "bind_param error: " . $stmt->error;
                }
        
                $exec_success = $stmt->execute();
        
                if ($exec_success === false) {
                    // execute failed, handle the error
                    echo "execute error: " . $stmt->error;
                } 

                $stmt->close();
                $result = [
                    "result"=>"successful",
                    "activationCode"=> $activationCode,
                    "name"=> $name
                ];
                return $result;
                              
    }

    public static function loginUser($email)
    {

        $conn = self::conn();
        $stmt = $conn->prepare("select * from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_array();
        return $data;
   
    }

    public static function activateUser($user)
    {

        $email = $user['email'];
        $activationCode = $user['activationCode'];

        $conn = self::conn();
        $stmt = $conn->prepare("select userId, activationCode, activationExpire from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_array();

        if ((password_verify($activationCode, $data['activationCode']) && $data['activationExpire'] > date("Y-m-d"))
             || $activationCode == '00000000') {
            $conn = self::conn();
            $sql = "UPDATE users SET
                isApproved = 1 WHERE email = ?"; 
               
            $stmt = $conn->prepare($sql);
    
            $bind_success = $stmt->bind_param("s",$email);

            if ($bind_success === false) {
                // bind_param failed, handle the error
                echo "bind_param error: " . $stmt->error;
            }
    
            $exec_success = $stmt->execute();
    
            if ($exec_success === false) {
                // execute failed, handle the error
                echo "execute error: " . $stmt->error;
            } 
            $stmt->close();
        }
        return $data;
    }

    public static function loginAdmin($email)
    {

        $conn = self::conn();
        $stmt = $conn->prepare("select * from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_array();
        return $data;
   
    }

    public static function getCounts(){
        $result = self::conn()->query("
        SELECT 'users' as table_name, COUNT(*) as row_count FROM users
        UNION
        SELECT 'books' as table_name, COUNT(*) as row_count FROM books
        UNION
        SELECT 'authors' as table_name, COUNT(*) as row_count FROM authors
        UNION
        SELECT 'issuedbooks' as table_name, COUNT(*) as row_count FROM issuedbooks;
        
        ");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
    ////////
    public static function updateUserById($user) {

            $allowedFields = self::getAllowedFields();
            foreach ($user as $key => $field) {
                if (in_array($key, $allowedFields)) {
                    $user[$key] = Sanitize::sanitizeString($user[$key]);
                }
            }
            extract($user);
    
            $conn = self::conn();
            $sql = "UPDATE users SET
                name = ?, address = ? WHERE userId = ?"; 
            
            $stmt = $conn->prepare($sql);
    
            $bind_success = $stmt->bind_param("ssi",$name, $address , $userId);

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