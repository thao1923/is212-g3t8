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
    $sortclass = new Sort();
    // Input validation

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

    if (count($message) == 0){
        // Get round
        $Round_dao = new Round_noDAO();
        $actual_round = $Round_dao->retrieve();
        // Get bids

        if ($actual_round == 1.0 ){
            $message = [];
            $result = [
                "status" => "success",
                "students" => $message
                ];

        }else{
            $successful_bidDAO = new successful_bidDAO();
            $successful_bids = $successful_bidDAO->retrieveByCourseSection($course, $section);
            if (count($successful_bids) > 0) {
                 $successful_bids = $sortclass->sort_it($successful_bids, 'userid');
            }
           
            $return_bids_msg = [];
            for($i=0; $i<count($successful_bids); $i++){
                $msg = [
                        "userid"=> $successful_bids[$i]->userid,
                        "amount"=> (float)$successful_bids[$i]->amount,
                    ];
                $return_bids_msg[] = $msg;
            }
            $message = $return_bids_msg;

            $result = [
                "status" => "success",
                "students" => $message
                ];
        }
    
    }else{
        $sortclass = new Sort();
        $message = $sortclass->sort_it($message, 'end_message');
        $result = [
            'status'=> 'error',
            'message' => $message
        ];
    }
}



header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);



?>