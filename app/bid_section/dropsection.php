<?php 
require_once '../bootstrap/include/CourseDAO.php';
require_once '../bootstrap/include/Course.php';
require_once '../bootstrap/include/StudentDAO.php';
require_once '../bootstrap/include/Student.php';
require_once '../bootstrap/include/SectionDAO.php';
require_once '../bootstrap/include/Section.php';
require_once '../login page/model/common.php';
require_once '../login page/model/protect.php';
require_once '../bootstrap/include/CompleteDAO.php';
require_once '../bootstrap/include/Complete.php';
require_once '../bootstrap/include/PrerequisiteDAO.php';
require_once '../bootstrap/include/Prerequisite.php';
require_once '../bootstrap/include/successful_bid.php';
require_once '../bootstrap/include/successful_bidDAO.php';
require_once '../bootstrap/include/Round_noDAO.php';

#get input from the drop bid rows 
$username = $_SESSION['username'] ;
$course_code = $_POST['bid_course'];
$section = $_POST['bid_sections'];
$amount = $_POST['bid_amount'];


#initialize 
$successfulbidDAO = new successful_bidDAO();
$Round_dao = new Round_noDAO();

#delete bid from specific row
$successfulbidObject = $successfulbidDAO->DropSpecificSection($username, $section, $course_code);

if($successfulbidObject == True) {

#refund edollar if drop section succeeds
$studentDAO = new StudentDAO();
$studentObject = $studentDAO->retrieve($username);
$Add_Edollar = $studentObject->getEdollar() + $amount;
$refunded_edollar = $studentDAO->updateEdollar($username,$Add_Edollar); 


$actual_round = $Round_dao->retrieve();


$sectionDAO = new SectionDAO();
$current_size = $sectionDAO->retrieveSizeByCodeAndSection($course_code, $section);
$sectionDAO->updateSize($section,$course_code,$current_size+1);


$_SESSION['message'] = "Drop Section Successful";
header('Location:../login page/studentpage.php');
return;
}
else{
    $_SESSION['message'] = "Fail to drop bid, please contact the IT service";
    header('Location:../login page/studentpage.php');
   
}




?>