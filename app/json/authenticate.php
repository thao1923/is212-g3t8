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
                "message" => ['invalid password']
            ];
                
        }
    
    }else{
        $result = [
            "status"=> "error",
            "message" => ['invalid username']
        ];

    }
}else{
    $errors = []; 
    if (!isset($_POST['password'])) {
        $errors[] = "missing password";
    }elseif(empty($_POST['password'])){
        $errors[] = "blank password";
    }

    if (!isset($_POST['username'])) {
        $errors[] = "missing username";
    }elseif(empty($_POST['username'])){
            $errors[] = "blank username";
    }

    $result = [
        "status" => "error",
        "message" =>  $errors
        ];
}
 


header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
 
?>