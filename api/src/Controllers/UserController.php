<?php

namespace Viki\Api\Controllers;

use Viki\Api;
use Viki\Api\Classes\Sanitize;

class UsersController{

    private static $table_name = "users";

    public static function conn(){
        $sql = new Api\Sql();
        return $conn = $sql::conn();
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

        $conn = self::conn(); 
        $stmt = $conn->prepare("select * from users WHERE userId = ? ");
        $stmt->bind_param("i", $id);
        $stmt->execute() ; 
        $result = $stmt->get_result(); 
        $data = $result->fetch_assoc();
        
        return $data;
    }

    public static function getUserByEmail($email) {

        $conn = self::conn(); 
        $stmt = $conn->prepare("select * from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
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
        
        if(!self::getUserByEmail($email)){
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
                } else {
            // send email
            $to = $email;
            $subject = 'Welcome to Multilingual online children library!';
            $message = 'Dear ' . $name . ',<br><br>';
            $message .= 'Welcome to Multilingual online children library! Your account has been successfully created.<br><br>';
            $message .= 'Please use the following activation code to activate your account within the next two days: <b>' . $activationCode . '</b><br><br>';
            $message .= 'After activating your account, you can login to the website with your email and password.<br><br>';
            $message .= 'Thank you for joining us!<br>';
            $headers = 'From: boszka88@gmail.com' . "\r\n" .
                       'Reply-To: boszka88@gmail.com' . "\r\n" .
                       'Content-Type: text/html; charset=UTF-8' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            // Send email
            if (mail($to, $subject, $message, $headers)) {
                $result = [
                    "result"=>"successful",
                    "message"=>"Successful registration. Activation code has been sent to your email. Please check the spam-folder too."
                ];
                echo json_encode($result);
            } else {
                $result = [
                    "result"=>"error",
                    "message"=>"Failed to send activation code. Please try again later."
                ];
                echo json_encode($result);
            }
            
    $stmt->close();
}
        } else {
            $result = [
                "result"=>"error",
                "message"=>"This email is already in the system!"
            ];
            echo json_encode($result);
        }
        
    }

    public static function loginUser($user)
    {

        $email = $user['email'];
        $password = $user['password'];

        $conn = self::conn();
        $stmt = $conn->prepare("select * from users WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_array();

        if (password_verify($password, $data['password']) && $data['isBanned'] == 0) {
            if($data['isApproved'] === 0){
                $result = [
                    "result"=>"activation",
                    "message"=>"Use your activation code now"
                ];
                echo json_encode($result);
            } else {
                $result = [
                    "result"=>"successful",
                    "message"=>"You are logged in"
                ];
                echo json_encode($result);
            }
        } else {
            $result = [
                "result"=>"error",
                "message"=>"Login failed."
            ];
            echo json_encode($result);
        }
    }

    public static function activateUser($user)
    {

        $email = $user['email'];
        $activationCode = $user['activationCode'];

        $conn = self::conn();
        $stmt = $conn->prepare("select activationCode, activationExpire from users WHERE email = ? ");
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
            } else {
                $result = [
                    "result"=>"successful",
                    "message"=>"Activation is successful."
                ];
                echo json_encode($result);
            }
        $stmt->close();
        } else {
            $result = [
                "result"=>"error",
                "message"=>"Activation failed."
            ];
            echo json_encode($result);
        }
    }
    
    public static function updateUser($id, $user) {

            $allowedFields = self::getAllowedFields();
            foreach ($user as $key => $field) {
                if (in_array($key, $allowedFields)) {
                    $user[$key] = Sanitize::sanitizeString($user[$key]);
                }
            }
            extract($user);
    
            $conn = self::conn();
            $sql = "UPDATE user SET
                first_name = ?, last_name = ?, email_id = ? WHERE id = ?"; 
            
   
            $stmt = $conn->prepare($sql);
    
            $bind_success = $stmt->bind_param("sssi",$first_name, $last_name, $email_id , $id);

            if ($bind_success === false) {
                // bind_param failed, handle the error
                echo "bind_param error: " . $stmt->error;
            }
    
            $exec_success = $stmt->execute();
    
            if ($exec_success === false) {
                // execute failed, handle the error
                echo "execute error: " . $stmt->error;
            } else {
                return array(
                    'id' => $id,
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email_id' => $user['email_id']
                );
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