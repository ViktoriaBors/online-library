<?php

namespace Viki\Api\Controllers;

use Viki\Api;
use Viki\Api\Models\Users;
use Viki\Api\Models\Sql;
use Firebase\JWT\JWT;

class UsersController extends Sql{

    private static $table_name = "users";

    public static function conn(){
        return parent::conn();
    }  
   
    public static function getAllUsers() {
        $data = Users::getAllUsers();
        return $data;
    }
    
    public static function getUsersById($id) {
        $data = Users::getUsersById($id);
        return $data;
    }

    public static function getUserByEmail($email) {
        $data = Users::getUserByEmail($email);
        return $data;
    }
    
    public static function registerNewUser($user) {
        extract($user);
        if(!self::getUserByEmail($email)){
            $data = Users::registerNewUser($user);
            if ($data['result'] === "successful") {
                  // send email
            $to = $email;
            $subject = 'Welcome to Multilingual online children library!';
            $message = 'Dear ' . $data['name'] . ',<br><br>';
            $message .= 'Welcome to Multilingual online children library! Your account has been successfully created.<br><br>';
            $message .= 'Please use the following activation code to activate your account within the next two days: <b>' . $data['activationCode'] . '</b><br><br>';
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
            } 
        } else { // email registered already
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
        $data = Users::loginUser($email);
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
                // Authenticate the user and generate a JWT token
                $userId = $data['userId'];
                $role = $data['role'];
                $secretKey = 'key';
                $tokenPayload = array(
                'role'=> $role,
                'sub' => $userId,
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24) // token expires in 1 day
                );
                $jwtToken = JWT::encode($tokenPayload, $secretKey, 'HS256');

                // Send the JWT token in the response
                header('Content-Type: application/json');
                echo json_encode((array('token' => $jwtToken)));

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
        
        $data = Users::activateUser($user);
        // Authenticate the user and generate a JWT token
        $userId = $data['userId'];
        $role = $data['role'];
        $secretKey = 'key';
        $tokenPayload = array(
        'role'=> $role,
        'sub' => $userId,
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24 ) // token expires in 1 hour
        );
        $jwtToken = JWT::encode($tokenPayload, $secretKey, 'HS256');

        // Send the JWT token in the response
        header('Content-Type: application/json');
        echo json_encode(array('token' => $jwtToken));
    }

    public static function updateUserById($user) {
            $data = Users::updateUserById($user);
            echo json_encode($data);
    }

    public static function loginAdmin($admin)
    {
        $email = $admin['email'];
        $password = $admin['password'];
        $data = Users::loginAdmin($email);
        if (password_verify($password, $data['password']) && $data['isBanned'] == 0 && ($data['role'] === 'admin' || $data['role']=== 'sudo')) {
                $result = [
                    "result"=>"successful",
                    "message"=>"You are logged in"
                ];
                // Authenticate the user and generate a JWT token
                $userId = $data['userId'];
                $role = $data['role'];
                $secretKey = 'key';
                $tokenPayload = array(
                    'role'=> $role,
                    'sub' => $userId,
                    'iat' => time(),
                    'exp' => time() + (60 * 60) // token expires in 1 hour
                    );
                $jwtToken = JWT::encode($tokenPayload, $secretKey, 'HS256');

                // Send the JWT token in the response
                header('Content-Type: application/json');
                echo json_encode((array('token' => $jwtToken)));
        } else {
            $result = [
                "result"=>"error",
                "message"=>"Login failed."
            ];
            echo json_encode($result);
        }
    }

    public static function getCounts(){
        $counts = Users::getCounts();
        return $counts;
    }
      
///////////// 
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