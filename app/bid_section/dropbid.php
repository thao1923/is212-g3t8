<?php 

require_once '../bootstrap/include/common.php';
require_once '../login page/model/protect.php';


#get input from the drop bid rows 
$username = $_SESSION['username'] ;

$course_code = $_POST['course_code'];
$section = $_POST['section'];
$amount = $_POST['amount'];

#initialize 
$bidDAO = new BidDAO();

#delete bid from specific row
$bidObject = $bidDAO->DropSpecificBid($username, $section, $course_code);

if($bidObject == True) {

#refund edollar if drop bid succeed
$studentDAO = new StudentDAO();
$studentObject = $studentDAO->retrieve($username);
$Add_Edollar = $studentObject->edollar + $amount;
$refunded_edollar = $studentDAO->updateEdollar($username,$Add_Edollar); 

$_SESSION['message'] = "Drop Bid Successful";
header('Location:../login page/studentpage.php');
return;
}
else{
    $_SESSION['message'] = "Fail to drop bid, please contact the IT service";
    header('Location:../login page/studentpage.php');
    return;
}




?>