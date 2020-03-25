<?php

include '../bootstrap/include/bootstrap.php';
require_once '../login page/model/token.php';

if (isset($_POST['token'])){
    $token = $_POST['token'];
    if (empty($token)){
        $result = [
            "status" => 'error',
            "error" => ["blank token"]
            ];
    }else{
        if (verify_token($token)){
            $result = doBootstrap();
            $Round_dao = new Round_noDAO();
            $Round_dao->add_round(1);
        } else{
            $result = [
                "status" => 'error',
                "error" => ["invalid token"]
            ];
        }
    }
}else{
    $result = [
        "status" => 'error',
        "error" => ["missing token"]
        ];

}



header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
?>
