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

if (!isset($request['amount'])){
    $message[] = 'missing amount';
}elseif(empty($request['amount'])){
    $message[] = 'blank amount';
}else{
    $amount = $request['amount'];
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
    $prerequisiteDAO = new PrerequisiteDAO ;
    $prerequisiteInfo = $prerequisiteDAO->retrieveByCode($course) ;
    $completeDAO = new CompleteDAO() ;
    $courseCompleted = $completeDAO->retrieve($username) ;

    // check round
    if ($actual_round == 1.5 || $actual_round == 2.5){
        $message[] = 'round ended';
        $result = [
                "status" => 'error',
                "message" => $message
                ];
    }
    if (count($message) == 0){
        // Input validation

        if(is_null($studentInfo)) {
            $message[] = "invalid userid";
        }

        $is_2_dec = True;
        preg_match('^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$^', "$amount", $matches);
        if (!empty($matches)) {
	        if ($matches[0] != $amount) {
		        $is_2_dec = False;
	        }
        }

        if ($amount < 10 || !$is_2_dec ){
            $message[] = 'invalid amount';
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

        
    }

    if (count($message) == 0){
        if ($actual_round == 2){
            include "../bootstrap/include/round_2.php";
            $minBid_DAO = new Min_bidDAO();
            $min_bid = $minBid_DAO->retrieve_min_bid($course, $section);
            if ($amount < $min_bid){
                $message[] = "bid too low";
            }       
        }elseif ($actual_round == 1){
            $courseSchool = $courseInfo->school ;
            $studentSchool = $studentInfo->school ;
            if ($courseSchool != $studentSchool) {
                $message[] = "not own school course" ; 
            }
        
        }

        

        // check prerequisite
        if (!empty($prerequisiteInfo)) {
            for ($i=0; $i<count($prerequisiteInfo); $i++) {
                if (!in_array($prerequisiteInfo[$i], $courseCompleted)) {
                    $message[] = "incomplete prerequisites";
                    break;
                }   
            }
        }

        // Check course completed
        $user_completed = $completeDAO->retrieve($username) ; 	
		if (in_array($course, $user_completed)) {
			$message[] = "course completed";
        }

        // check number of bids
        $check_update = FALSE;
        foreach($bidInfo as $bid){
            if ($course == $bid->course_code){
                $check_update = True;
                break;
            }
        }

        if (count($bidInfo) >= 5){
            if (!$check_update){
                $message[] = "section limit reached";
            }      

        }

        
        // check enrolled course
        $successful_bidDAO = new successful_bidDAO() ;
        $successful_bid = $successful_bidDAO->retrieveByUsername($username);
        if (count($successful_bid) >= 1 && !$check_update){
            $successful_bid_course = array_column($successful_bid, 'course_code');
            $successful_bid_section = array_column($successful_bid, 'section');
            if (in_array($course, $successful_bid_course) && in_array($section, $successful_bid_section) ){
                $message[] = "course enrolled";
                $message[] = "exam timetable clash";
                $message[] = "class timetable clash";
            }
        }

        // check vacancy
        if ($sectionInfo->size == 0){
            $message[] = "no vacancy";
        }
        $bidInfo += $successful_bid;
        foreach ($bidInfo as $biddedCourse) {
            // check exam timetable
            $biddedCourseInfo = $courseDAO->retrieveAllByCode($biddedCourse->course_code) ;
            if ($biddedCourseInfo->exam_date == $courseInfo->exam_date && ($biddedCourseInfo->course_code != $courseInfo->course_code)){
                if (($courseInfo->exam_start < $biddedCourseInfo->exam_start && $courseInfo->exam_end > $biddedCourseInfo->exam_start)
                    || ($courseInfo->exam_start > $biddedCourseInfo->exam_start && $courseInfo->exam_start < $biddedCourseInfo->exam_end )
                    || ($courseInfo->exam_start == $biddedCourseInfo->exam_start)
                    && (!in_array('exam timetable clash', $message)) ){
                        $message[] = "exam timetable clash" ;
                }
            }
            // check class timetable
            $biddedSectionInfo = $sectionDAO->retrieveAllByCodeAndSection($biddedCourse->course_code, $biddedCourse->section) ;
            if ($biddedSectionInfo->day == $sectionInfo->day && ($biddedCourseInfo->course_code != $courseInfo->course_code)){
                if (($sectionInfo->start < $biddedSectionInfo->start && $sectionInfo->end > $biddedSectionInfo->start)
			    	|| ($sectionInfo->start > $biddedSectionInfo->start && $sectionInfo->start < $biddedSectionInfo->end)
				    || ($sectionInfo->start == $biddedSectionInfo->start)
					&& (!in_array('class timetable clash', $message)) ){
                        $message[] = "class timetable clash" ;
                }
            }
        }    
        
    }else{
        $sortclass = new Sort();
        $message = $sortclass->sort_it($message, 'end_message');
        $result = [
                'status'=> 'error',
                'message' => $message
                ];

    }
    $updated_edollar = 0;
    if (count($message) == 0){
        
        if ($check_update){
            $prev_bid = $bidDAO->retrieveBidByUseridCourse($username, $course);
			$prev_amount = $prev_bid->amount;
			$updated_edollar = $studentInfo->edollar + $prev_amount;
			
        }
    }
    
    // check edollar
    if (!is_null($studentInfo)) {
        if ($updated_edollar == 0){
            $updated_edollar = $studentInfo->edollar;
        }
        if ($amount > $updated_edollar && !in_array("invalid amount", $message)){
            $message[] = "insufficient e$";

    
        }
    }
    

    if (count($message) == 0){
        $result = [
                'status'=> 'success'
                ];
                
        if ($check_update ){   

            
            $bidDAO->DropBid($username, $course);
            


        }
        
        $updated_edollar = $updated_edollar - $amount;
        
        $studentdao->updateEdollar($username, $updated_edollar);
        $bidObj = new Bid($username, $amount, $course, $section);
        $bidDAO->add($bidObj);
        if($actual_round == 2.0){
            include "../bootstrap/include/round_2.php";
        }
        
    }else{
        $sortclass = new Sort();
        $message = $sortclass->sort_it($message, 'message');
        $result = [
                'status'=> 'error',
                'message' => $message
                ];
    }
}
 
    
   
       


header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);



?>