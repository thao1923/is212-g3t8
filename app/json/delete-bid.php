<?php
require_once '../bootstrap/include/common.php';
require_once '../bootstrap/include/token.php';

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


if (!isset($request['course'])){
    $message[] = 'missing course';
}elseif(empty($request['course'])){
    $message[] = 'blank course';
}else{
    $course = $request['course'];
}


if (!isset($request['section'])){
    $message[] = 'missing section';
}elseif(empty($request['section'])){
    $message[] = 'blank section';
}else{
    $section = $request['section'];
}

if (count($message) > 0){
    $sortclass = new Sort();
    $message = $sortclass->sort_it($message, 'end_message');
    $result = [
                "status" => 'error',
                "message" => $message
            ];
    
}else{
    // Connection
    $studentdao = new StudentDAO();
    $studentInfo = $studentdao->retrieve($username);
    $Round_dao = new Round_noDAO();
    $actual_round = $Round_dao->retrieve();
    $bidDAO = new BidDAO() ;
    $bidInfo = $bidDAO->retrieve($username) ;
    


    // Input validation

    if(is_null($studentInfo)) {
        $message[] = "invalid userid";
    }

    $courseDAO = new CourseDAO() ;
    $courseInfo = $courseDAO->retrieveAllByCode($course) ;
    if (!$courseInfo) {
        $message[] = "invalid course" ;
    }else{
        $sectionDAO = new SectionDAO ;
        $sectionInfo = $sectionDAO->retrieveAllByCodeAndSection($course, $section) ;
        $courseSection = $sectionDAO->retrieveAllByCode($course) ;
        if (!in_array($section, $courseSection)) {
            $message[] = "invalid section" ;
        }
    }

    if (count($message) == 0) {
        $amount =$bidDAO->retrieveAmountOfBid($username, $course, $section);
        $bid = new Bid($username, $amount, $course, $section);
        if (!in_array($bid, $bidInfo)){
            $message[] = "no such bid";
        }
        
        if ($actual_round == 1.5 || $actual_round == 2.5){
            $message[] = 'round ended';
        }
    }else{
        $sortclass = new Sort();
        $message = $sortclass->sort_it($message, 'end_message');
        $result = [
                "status" => 'error',
                "message" => $message
            ];
    }



    if (count($message) > 0){
        $sortclass = new Sort();
        $message = $sortclass->sort_it($message, 'message');
        $result = [
                'status'=> 'error',
                'message' => $message
                ]; 
    }else{
        $result = [
            'status'=> 'success'
            ];
        
        $amount_alr_bidded = $bidDAO->retrieveAmountOfBid($username, $course, $section);
        $bidDAO->DropSpecificBid($username, $section, $course);
        $updated_edollar = $studentInfo->edollar + $amount_alr_bidded;
        $studentdao->updateEdollar($username, $updated_edollar);
        

    }
}


header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);

?>