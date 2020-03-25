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
    // Connection
    $Round_dao = new Round_noDAO();
    $actual_round = $Round_dao->retrieve();
    $courseDAO = new CourseDAO() ;
    $courseInfo = $courseDAO->retrieveAllByCode($course) ;
    $studentDAO = new StudentDAO();
    $sortclass = new Sort;
    $successful_bidDAO = new Successful_bidDAO;
    // Common validation
    if (!$courseInfo) {
        $message[] = "invalid course" ;
    }else{
        $sectionDAO = new SectionDAO ;
        $bidDAO = new BidDao();
        $sectionInfo = $sectionDAO->retrieveAllByCodeAndSection($course, $section) ;
        $courseSection = $sectionDAO->retrieveAllByCode($course) ;
        if (!in_array($section, $courseSection)) {
            $message[] = "invalid section" ;
        }
    }
    // Retrive information 
    if (count($message) == 0){
        $vacancy = $sectionDAO->retrieveSizeByCodeAndSection($course, $section);
        // Round 1
        if ($actual_round == 1.0 ){
            $bids = $bidDAO->retrieveAllBidsbyClasses($course, $section);
            if (count($bids) == 0){
                $min_bid = 10.0;
            }else if (count($bids) < $vacancy){
                $min_bid = min(array_column($bids, 'amount'));
            }else{
                $bids_amount = array_column($bids, 'amount');
                sort($bids_amount);
                $min_bid = $bids_amount[$vacancy-1];
            }
            // Create students array with userid, amount, status, balance
            $students = [];
            for ($i=0; $i<count($bids); $i++){
                $balance = $studentDAO->retrieve_edollar ($bids[$i]->userid);
                $student = [
                            'userid' => $bids[$i]->userid,
                            'amount' => (float)$bids[$i]->amount,
                            'balance' => (float)$balance,
                            'status' => 'pending'

                        ];
                    $students[] = $student;
            }
            $students = $sortclass->sort_it($students, 'username');
            $students = $sortclass->sort_it($students, 'amount_arr');
            $result = [
                    "status"=> "success",
                    "vacancy"=> (int)$vacancy,
                    "min-bid-amount"=>(float)$min_bid,
                    "students"=> $students

                ];
        // End round 1
        }elseif ($actual_round == 1.5){
            $successful_bids = $successful_bidDAO->retrieveByCourseSection($course, $section);
            // retrieve vacancy and min bid
            $bids = $bidDAO->retrieveAllBidsbyClasses($course, $section);
            if(count($successful_bids) == 0){
                $min_bid = 0;
            }else{
                $bids_amount = array_column($successful_bids, 'amount');
                sort($bids_amount);
                $min_bid = $bids_amount[$vacancy-1];
            }

            // array for successful bid
            $students_success = [];
            for ($i=0; $i<count($successful_bids); $i++){
                $balance = $studentDAO->retrieve_edollar ($successful_bids[$i]->userid);
                $student = [
                            'userid' => $successful_bids[$i]->userid,
                            'amount' => (float)$successful_bids[$i]->amount,
                            'balance' => (float)$balance,
                            'status' => 'success'

                        ];
                    $students_success[] = $student;
            }
            $students_success = $sortclass->sort_it($students_success, 'username');
            $students_success = $sortclass->sort_it($students_success, 'amount_arr');

            // array for unsuccessful bids
            $students_fail = [];
            for ($i=0; $i<count($bids); $i++){
                $balance = $studentDAO->retrieve_edollar ($bids[$i]->userid);
                $bid = new successful_bid ($bids[$i]->userid, $bids[$i]->amount, $bids[$i]->course_code, $bids[$i]->section );
                if (!in_array($bids[$i], $successful_bids)){
                    $student = [
                        'userid' => $bids[$i]->userid,
                        'amount' => (float)$bids[$i]->amount,
                        'balance' => (float)$balance,
                        'status' => 'fail'

                    ];
                    $students_fail[] = $student;
                }
                
            }
            $students_fail = $sortclass->sort_it($students_fail, 'username');
            $students_fail = $sortclass->sort_it($students_fail, 'amount_arr');

            // result
            $result = [
                "status"=> "success",
                "vacancy"=> (int)$vacancy,
                "min-bid-amount"=>(float)$min_bid,
                "students"=> $students_success+$students_fail

            ];
        
        }elseif ($actual_round == 2.0){
            $min_bidDAO = new Min_bidDAO();
            $min_bid = $min_bidDAO-> retrieve_min_bid($course, $section);
            $bids = $bidDAO->retrieveAllBidsbyClasses($course, $section);
            $students = [];
            for ($i=0; $i<count($bids); $i++){
                $balance = $studentDAO->retrieve_edollar ($bids[$i]->userid);
                $status = $bidDAO->getStatus($bids[$i]->userid, $course, $section);
                if (is_null($status)){
                    $status = 'fail';
                }
                $student = [
                            'userid' => $bids[$i]->userid,
                            'amount' => (float)$bids[$i]->amount,
                            'balance' => (float)$balance,
                            'status' => $status

                        ];
                    $students[] = $student;
            }
            $students = $sortclass->sort_it($students, 'username');
            $students = $sortclass->sort_it($students, 'amount_arr');
            $result = [
                    "status"=> "success",
                    "vacancy"=> (int)$vacancy,
                    "min-bid-amount"=>(float)$min_bid,
                    "students"=> $students

                ];            
        // End round 2
        }elseif ($actual_round == 2.5){
            $successful_bids = $successful_bidDAO->retrieveByCourseSection($course, $section);
            $min_bidDAO = new Min_bidDAO();
            $min_bid = $min_bidDAO-> retrieve_min_bid($course, $section);
            $students_success = [];
            for ($i=0; $i<count($successful_bids); $i++){
                $balance = $studentDAO->retrieve_edollar ($successful_bids[$i]->userid);
                $student = [
                            'userid' => $successful_bids[$i]->userid,
                            'amount' =>(float) $successful_bids[$i]->amount,
                            'balance' =>(float) $balance,
                            'status' => 'success'

                        ];
                $students_success[] = $student;
            }
            $students_success = $sortclass->sort_it($students_success, 'username');
            $students_success = $sortclass->sort_it($students_success, 'amount_arr');
            $result = [
                "status"=> "success",
                "vacancy"=> (int)$vacancy,
                "min-bid-amount"=>(float)$min_bid,
                "students"=> $students_success


            ]; 
        }
    }else{
        $sortclass = new Sort();
        $message = $sortclass->sort_it($message, 'end_message');
        $result = [
                "status" => 'error',
                "message" => $message
            ];
    }
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);

?>
    