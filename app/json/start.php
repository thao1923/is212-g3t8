<?php

require_once "../bootstrap/include/common.php";
require_once "../bootstrap/include/token.php";

if (!isset($_GET['token'])){
    $result = [
        "status" => 'error',
        "error" => ["missing token"]
        ];
}else{
    $token = $_GET['token'];
    if (empty($token)){
        $result = [
                "status" => 'error',
                "error" => ["blank token"]
                ];
    }else{
        if (!verify_token($token)){
            $result = [
                "status" => 'error',
                "error" => ["invalid token"]
            ];
        } else{
            $Round_dao = new Round_noDAO();
            $actual_round = $Round_dao->retrieve();
            $bidDAO = new BidDAO();

            if ($actual_round == 1 || is_null($actual_round)){
                if (is_null($actual_round)){
                    $Round_dao->add_round(1);
                    $actual_round = (int) $Round_dao->retrieve();
                }
                $actual_round = (int) $actual_round;
                $result = [ 
                        "status" => "success",
                        "round" => $actual_round
                        ];
            }elseif ($actual_round == 2 || $actual_round == 1.5){
                if ($actual_round == 1.5){
                    $Round_dao->update_round(2);
                    $bidDAO->removeAll();
                    $actual_round = (int) $Round_dao->retrieve();
                }
                $actual_round = (int) $actual_round;
                $result = [ 
                        "status" => "success",
                        "round" => $actual_round
                        ];
            }elseif ($actual_round == 2.5){
                $result = [ 
                            "status" => "error",
                            "message" =>[ "round 2 ended" ]
                            ];
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);


?>