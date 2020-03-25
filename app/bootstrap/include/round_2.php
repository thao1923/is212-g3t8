<?php

include_once('common.php');
//require_once 'common.php';
$Round_dao = new Round_noDAO();
$dao = new BidDAO();
$sectiondao = new SectionDAO();
$Successfulbid_dao = new successful_bidDAO();
//$Student_dao = new StudentDAO();
$round_dao = new Round_noDAO();
$minBid_DAO = new Min_bidDAO();
#require_once 'include/protect.php';
$actual_round = $Round_dao->retrieve();
#generate all classes that are in bid table
$_SESSION['classes'] = $dao -> retrieveAllClasses();
$_SESSION['min_bids'] = [];
$_SESSION['successful_bid'] = [];
$min_bids = array();
$successfulbids = [];

#generate all classes
$all_classes = $sectiondao -> retrieveAllpossibleClasses();


#set base min bid
// foreach($all_classes as $class){
//     $course_code = $class[0];
//     $section = $class[1];
//     $key = $course_code.$section;
    
//     $_SESSION['min_bids'][$key] = 10;
// }
//var_dump( $_SESSION['min_bids']);


$sortclass = new Sort();



#generate all classes

foreach ($_SESSION['classes']  as $class){
   
    $course_code = $class[0];
    $section = $class[1];
    $key = $course_code.$section;
    $bids = $dao -> retrieveAllBidsbyClasses($course_code,$section);
    $successful_spots = $Successfulbid_dao ->count_no_success ($section,$course_code);

    $current_size = $sectiondao ->retrieveSizeByCodeAndSection($course_code, $section);

    
    $bid_no = $dao -> count_no($section,$course_code);
    

    $bids = $sortclass->sort_it($bids, 'amount');

  
    //$clearing_bid = ($bids[$current_size-1]) ->amount;
    
    //var_dump($clearing_bid);
    //var_dump($bids);

    #determine min bid if number of bids = vacancy
    $current_min_bid  = $minBid_DAO->retrieve_min_bid($course_code, $section);
    if($current_size > 0){

    if($bid_no <$current_size ){
        

        $min_bid = $minBid_DAO->retrieve_min_bid($course_code, $section);
        


    }
    if($bid_no== $current_size){
        $clearing_bid = ($bids[$current_size-1])->amount;
        $min_bid = $clearing_bid +1;

        
        //$_SESSION['min_bids'][$key]=$min_bid;


    }


    if($bid_no>$current_size && $current_size > 0){
        $clearing_bid = ($bids[$current_size-1]) ->amount;

        $min_bid = $clearing_bid+1;
        
        $equal_to_clearing_bid = [];


        if($current_size>1){
            $first_failedBid = ($bids[$current_size]) ->amount;

        }else{
            $first_failedBid = $clearing_bid;

        }



        #checks if clearing bid can be added if can't all bids above clearing bid is succesful min bid
        if($clearing_bid == $first_failedBid){

            $min_bid = $clearing_bid+1;


        }elseif($clearing_bid > $first_failedBid){
            $min_bid = $clearing_bid+1; 
            //$_SESSION['min_bids'][$key]=$min_bid;
            foreach($bids as $bid){
                $bid_amount = $bid->amount;
                if($bid_amount > $clearing_bid){ 
                    
                  

                }
            }



        }



    }
    if($min_bid > $current_min_bid ){
        $minBid_DAO->update_min_bid($course_code, $section, $min_bid);
    

    }else{
        //$min_bid = $current_min_bid;
    }

    $current_sbid= [];

    #adds to current_sbid if bid more than minbid -1
    foreach($bids as $bid ){
        $bid_amount = $bid->amount;

        if($bid_amount >= $min_bid-1 ){
            $current_sbid[]= $bid;
            $userid = $bid->userid;
            $course_code = $bid->course_code;
            $section = $bid->section;
            $dao->updateStatusSuccess($userid, $course_code, $section);
        }
    }
    #checks if $number of $current_sbid is less than size of class
    if (count($current_sbid) > $current_size){
        $current_sbid = [];
        foreach($bids as $bid ){
            #corrects to ensure that number of $current_sbid is the less than or equal to class size
            $bid_amount = $bid->amount;
    
            if($bid_amount > $min_bid-1 ){
                $current_sbid[]= $bid;
                $userid = $bid->userid;
                $course_code = $bid->course_code;
                $section = $bid->section;
                $dao->updateStatusSuccess($userid, $course_code, $section);
            }

        }
    }
    #adds all successfulbids from $current_sbid to the list of ALL successfulbids
    $successfulbids[] = $current_sbid;
}


}
//var_dump($successfulbids);
//echo ($min_bid);
$all_bids = $dao->retrieveAll();
$list_sbids = [];
for($j=0;$j<count($successfulbids); $j++){
    $list_sbids += $successfulbids[$j];
}
for($i=0; $i<count($all_bids); $i++){
    if (in_array($all_bids[$i], $list_sbids)){
        $userid = $all_bids[$i]->userid;
        $course_code = $all_bids[$i]->course_code;
        $section = $all_bids[$i]->section;
        $dao->updateStatusSuccess($userid, $course_code, $section);
    }elseif (!in_array($all_bids[$i], $list_sbids)){
        $userid = $all_bids[$i]->userid;
        $course_code = $all_bids[$i]->course_code;
        $section = $all_bids[$i]->section;
        $status = $dao->getStatus($userid, $course_code, $section);
        if ($status=='success'){
            $dao->updateStatusFail($userid, $course_code, $section);
        }
    }
            
}

$_SESSION['successful_bid']= $successfulbids;




//var_dump($_SESSION['min_bids']);
//var_dump($_SESSION['successful_bid']);


    










?>