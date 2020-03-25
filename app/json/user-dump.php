<?php
require_once "../bootstrap/include/common.php";
require_once "../bootstrap/include/token.php";

$request = $_GET['r'];
$request = json_decode($request, true);
$message = [];

#verify token
if (!isset($_GET['token'])){
    $message[] = "missing token"; 
}else{
    $token = $_GET['token'];
    if (empty($token)){
        $message[] = "blank token";
    }else{
        if (!verify_token($token)){
            $message[] =  "invalid token";
        }
    }
}

//input common validation
if (!isset($request['userid'])){
    $message[] = 'missing userid';
}elseif(empty($request['userid'])){
    $message[] = 'blank userid';
}else{
    $username = $request['userid'];
}

if (count($message) > 0){
    $sortclass = new Sort();
    $message = $sortclass->sort_it($message, 'end_message');
    $result = [
                "status" => 'error',
                "message" => $message
            ];
    
}else{

    // Connect
    $studentdao = new StudentDAO();

    $studentInfo = $studentdao->retrieve($username);

    if(is_null($studentInfo)) {
        $result = [
                    "status" => "error",
                    "message" => ["invalid userid"]
                ];
    }else{
        $result = [ "status" => "success",
                    "userid"=> $studentInfo->userid,
                    "password" => $studentInfo->password,
                    "name" => $studentInfo->name,
                    "school" => $studentInfo->school,
                    "edollar" => (float) $studentInfo->edollar
                ];
    }
}


header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);





?>