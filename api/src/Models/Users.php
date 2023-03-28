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
        $stmtError = null;
        $result = self::conn()->query("select userId, name, email, isApproved, isBanned, role from users");
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
    
    public static function getUsersById($id) {
        $stmtError = null;
        $id= $id['id'];
        $conn = self::conn(); 
        $stmt = $conn->prepare("select * from users WHERE userId = ? ");
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

    public static function getUserByName($name) {
        $stmtError = null;
        $conn = self::conn(); 
        $stmt = $conn->prepare("select * from users WHERE name = ? ");
        $stmt->bind_param("s", $name);
        $bind_success = $stmt->bind_param("s", $name);
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

    public static function getUserByEmail($email) {
        $stmtError = null;
        $conn = self::conn(); 
        $stmt = $conn->prepare("select * from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $bind_success = $stmt->bind_param("s", $email);
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
    
    public static function registerNewUser($user) {
        $stmtError = null;
        $allowedFields = self::getAllowedFields();
        foreach ($user as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $user[$key] = Sanitize::sanitizeString($user[$key]);
            }
        }
        extract($user);
        
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);;
            $conn = self::conn(); 
            $sql = "INSERT INTO users
            (name, email, password, address)
            VALUES (?, ?, ?, ?) ;";
    
            $stmt = $conn->prepare($sql);
    
            $stmt->bind_param("ssss", $name, $email, $passwordHash, $address);
            $bind_success = $stmt->bind_param("ssss", $name, $email, $passwordHash, $address);
        
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
                $activation = self::activationCode($user['email']);
                if(!$stmtError){
                    $result = [
                        "result"=>"successful",
                        "activationCode"=> $activation['activationCode'],
                        "name"=> $user['name']
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

    public static function loginUser($email)
    {
        $stmtError = null;
        $conn = self::conn();
        $stmt = $conn->prepare("select * from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $bind_success =   $stmt->bind_param("s", $email);
        if ($bind_success === false) {
            // bind_param failed, handle the error
            $stmtError = $stmt->error;
        }

        $exec_success = $stmt->execute();

        if ($exec_success === false) {
            // execute failed, handle the error
            $stmtError = $stmt->error;
        } 
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_array();
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

    public static function activateUser($user)
    {
        $stmtError = null;
        $email = $user['email'];
        $activationCode = $user['activationCode'];

        $conn = self::conn();
        
        $stmt = $conn->prepare("select userId, activationCode, activationExpire, role from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_array();

        if ((password_verify($activationCode, $data['activationCode']) && $data['activationExpire'] > date("Y-m-d"))
             || $activationCode == '00000000') {
            $conn = self::conn();
            $sql = "UPDATE users SET activationCode = '',
                isApproved = 1 WHERE email = ?"; 
               
            $stmt = $conn->prepare($sql);
    
            $bind_success = $stmt->bind_param("s",$email);

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
        }
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

    public static function loginAdmin($email)
    {
        $stmtError = null;
        $conn = self::conn();
        $stmt = $conn->prepare("select * from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $bind_success =  $stmt->bind_param("s", $email);
        if ($bind_success === false) {
            // bind_param failed, handle the error
            $stmtError = $stmt->error;
        }

        $exec_success = $stmt->execute();

        if ($exec_success === false) {
            // execute failed, handle the error
            $stmtError = $stmt->error;
        } 
        $result = $stmt->get_result();
        $data = $result->fetch_array();
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

    public static function updateUserById($user) {
        $stmtError = null;
            $allowedFields = self::getAllowedFields();
            foreach ($user as $key => $field) {
                if (in_array($key, $allowedFields)) {
                    $user[$key] = Sanitize::sanitizeString($user[$key]);
                }
            }
            extract($user);
            $conn = self::conn();

            if(isset($user['name']) || isset($user['address'])){
            $sql = "UPDATE users SET
                name = ?, address = ? WHERE userId = ?"; 
            } else if (isset($user['isBanned'])){
                $status = $user['isBanned'] === 'Banned' ? 1 : 0;
                $statusChange = !$status;
                $sql = "UPDATE users SET
                isBanned = ? WHERE userId = ?"; 
            } else if (isset($user['isApproved'])){
                $status = $user['isApproved'] === 'Approved' ? 1 : 0;
                $statusChange = !$status;
                $sql = "UPDATE users SET
                isApproved = ? WHERE userId = ?"; 
            } else if (isset($user['role'])){
                $sql = "UPDATE users SET
                role = ? WHERE userId = ?"; 
            }
            
            $stmt = $conn->prepare($sql);

            if(isset($user['name']) || isset($user['address'])){
              $bind_success = $stmt->bind_param("ssi",$name, $address , $userId);
                } else if (isset($user['isBanned'])){
                    $bind_success = $stmt->bind_param("ii",$statusChange , $userId);
                } else if (isset($user['isApproved'])){
                    $bind_success = $stmt->bind_param("ii",$statusChange , $userId);
                } else if (isset($user['role'])){
                    $bind_success = $stmt->bind_param("si",$role , $userId);
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
            } else {
                $result = [
                    "result"=>"error",
                    "message"=> 'Something went wrong',
                    "error"=>  $stmtError 
                ];
                return $result;
            }

    }

public static function activationCode ($userEmail){
    $stmtError = null;
            $activationCode = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            $activationCodeHash = password_hash($activationCode, PASSWORD_DEFAULT);
            $activationExpire = date('Y-m-d', strtotime('+1 days'));
            $conn = self::conn(); 
            $sql = "UPDATE users SET activationCode = ?, activationExpire =? WHERE email = ? ;";
    
            $stmt = $conn->prepare($sql);
    
            $stmt->bind_param("sss", $activationCodeHash, $activationExpire, $userEmail);
            $bind_success = $stmt->bind_param("sss", $activationCodeHash, $activationExpire, $userEmail);
        
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
                        "result"=>"successful",
                        "activationCode"=> $activationCode
                    ];
                    return $result;  
                } else {
                    $result = [
                        "result"=>"error",
                        "message"=> 'Something went wrong',
                        "error"=>  $stmtError 
                    ];
                    return $result;
                }
                             
    }


    public static function updateUserByEmail($user) {
        $stmtError = null;
        $allowedFields = self::getAllowedFields();
        foreach ($user as $key => $field) {
            if (in_array($key, $allowedFields)) {
                $user[$key] = Sanitize::sanitizeString($user[$key]);
            }
        }
        extract($user);
        $email = $user['email'];
        $activationCode = $user['code'];
        $password = $user['password'];
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userData = self::getUserByEmail($email);
        
        if ((password_verify($activationCode, $userData['activationCode']) && $userData['activationExpire'] > date("Y-m-d"))
        || $activationCode == '00000000') {

            $conn = self::conn();
    
            $sql = "UPDATE users SET
            password = ?, activationCode = '' WHERE userId = ?"; 
            
            $stmt = $conn->prepare($sql);
            $bind_success = $stmt->bind_param("si",$passwordHash, $userData['userId']);
    
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
                    "result"=>"successful",
                    "message"=>"Password updated successfully. Login now."
                ];
                return $result;
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
    
    public static function deleteUserById($id) {
        $stmtError = null;
    $id = $id['userId'];
    $conn = self::conn(); 
    $stmt = $conn->prepare("DELETE FROM users WHERE userId = ? ");
    $stmt->bind_param("i", $id);
    $bind_success =  $stmt->bind_param("i", $id);
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