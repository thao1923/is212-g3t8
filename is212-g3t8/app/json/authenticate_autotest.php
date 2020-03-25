<?php

require_once '../login page/model/common.php';
require_once '../login page/model/token.php';

    
if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password']) ){
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if($username == 'admin'){
        if ($password == 'Hello123'){
            $token = generate_token($username);
            $result = [
                "status"=> "success",
                "token" => $token
            ];
        
        }else{
            $result = [
                "status"=> "error",
                "message" => 'Password is incorrect'
            ];
                
        }
    
    }else{
        $dao = new studentDAO();
        $status = $dao -> authenticate($username, $password);

        if ($status == 'SUCCESS'){
            $token = generate_token($username);
            $result = [
                "status"=> "success",
                "token" => $token
            ];
                
        }else{    
            $result = [
                "status" => "error",
                "message" => $status
            ];
               

        }

    
    }
}else{  
    $errors = []; 
    if (empty($_POST['username'])){
        $errors[] = "missing username";
    }

    if (empty($_POST['username'])){
        $errors[] = "missing password";
    }

    $errors = array_filter($errors);

    $result = [
        "status" => "error",
        "message" => array_values( $errors)
        ];
}
 


header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
 
?>