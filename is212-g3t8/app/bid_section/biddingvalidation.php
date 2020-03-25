<?php 

require_once '../bootstrap/include/CourseDAO.php';
require_once '../bootstrap/include/Course.php';
require_once '../bootstrap/include/StudentDAO.php';
require_once '../bootstrap/include/Student.php';
require_once '../bootstrap/include/SectionDAO.php';
require_once '../bootstrap/include/Section.php';
require_once '../login page/model/common.php';
require_once '../login page/model/protect.php';
require_once '../bootstrap/include/BidDAO.php';
require_once '../bootstrap/include/Bid.php';
require_once '../bootstrap/include/CompleteDAO.php';
require_once '../bootstrap/include/Complete.php';
require_once '../bootstrap/include/PrerequisiteDAO.php';
require_once '../bootstrap/include/Prerequisite.php';

?>

<?php
    
    //get input values and username via session
    $username = $_SESSION['username'] ;
    $course_code = $_POST['course_code'] ;
    $section_number = $_POST['section_number'] ;
    $EAmount = $_POST['e_amount'] ;
    $course_code = strtoupper($course_code);
    $section_number = strtoupper($section_number);
    
     $invalidInput = [] ;

    $courseDAO = new CourseDAO() ;
    $courseInfo = $courseDAO->retrieveAllByCode($course_code) ;
    if (!$courseInfo) {
        $invalidInput[] = "invalid course code";
        $_SESSION['message'] = $invalidInput ;
        
    }

    $sectionDAO = new SectionDAO ;
    $sectionInfo = $sectionDAO->retrieveAllByCodeAndSection($course_code, $section_number) ;
    $courseSection = $sectionDAO->retrieveAllByCode($course_code) ;
    if (!in_array($section_number, $courseSection)) {
        $invalidInput[] = "invalid section code";
        $_SESSION['message'] = $invalidInput;
    }
    
    $studentDAO = new StudentDAO() ;
    $studentInfo = $studentDAO->retrieve($username) ;
    
    $bidDAO = new BidDAO() ;
    $bidInfo = $bidDAO->retrieve($username) ;

    $completeDAO = new CompleteDAO() ;
    $courseCompleted = $completeDAO->retrieve($username) ;

    $prerequisiteDAO = new PrerequisiteDAO ;
    $prerequisiteInfo = $prerequisiteDAO->retrieveByCode($course_code) ;



    //get student edollar info 
    function getStudentEdollar($username) {
        $studentDAO = new StudentDAO();
        $studentObject = $studentDAO->retrieve($username);
        $student_edollar = $studentObject->getEdollar();
        return $student_edollar;
        }


     
    if (isset($_POST['course_code'] ) && $_POST['section_number'] && $_POST['e_amount'] && count($invalidInput) == 0) {

        $error = [] ;

        // check if course is offered by school 
        $courseSchool = $courseInfo->school ;
        $studentSchool = $studentInfo->school ;
        if ($courseSchool != $studentSchool) {
            $error[] = "not own school course" ; 
            $_SESSION['message'] = $error ; 
            
        }

        // check if course is completed
        if (in_array($course_code, $courseCompleted)) {
            $error[] = 'course completed' ;
        }

        // check for section limit 
        if (count($bidInfo) >= 5) {
            $error[] =  "section limit reached" ;
            $_SESSION['message'] = $error;
        }

        // check for sufficient balance in student edollar
        if (getStudentEdollar($username) < 10.00) {
            $error[] = "Insufficient Edollar";
            $_SESSION['message'] = $error;
        }

        // check if prerequisite is completed for the course
        if (!empty($prerequisiteInfo)) {
            for ($i=0; $i<count($prerequisiteInfo); $i++) {
                if (!in_array($prerequisiteInfo[$i], $courseCompleted)) {
                    $error[] = "incomplete prerequisites";
                    $_SESSION['message'] = $error;
                    break;
                    
                }
            }
        }

        foreach ($bidInfo as $biddedCourse) {
            // one section per course
            if ($biddedCourse->course_code == $course_code) {
                $error[] = "only one section per course" ;
                $_SESSION['message'] = $error;
            }
            // check exam timetable
            $biddedCourseInfo = $courseDAO->retrieveAllByCode($biddedCourse->course_code) ;

            if ($biddedCourseInfo->exam_date == $courseInfo->exam_date) {
                if(($courseInfo->exam_end > $biddedCourseInfo->exam_start && $courseInfo->exam_end < $biddedCourseInfo->exam_end)||($courseInfo->exam_start>$biddedCourseInfo->exam_start && $courseInfo->exam_start<$biddedCourseInfo->exam_end)){
                    $error[] = "exam timetable clash" ;
                    $_SESSION['message'] = $error;
                    
                }

            }
            // check class timetable
            $biddedSectionInfo = $sectionDAO->retrieveAllByCodeAndSection($biddedCourse->course_code, $biddedCourse->section) ;
            if ($biddedSectionInfo->day == $sectionInfo->day){
                if((($sectionInfo->end > $biddedSectionInfo->start && $sectionInfo->end < $biddedSectionInfo->end)||($sectionInfo->start > $biddedSectionInfo->start && $sectionInfo->start<$biddedSectionInfo->end))) {
                    #$sectionInfo->end >= $biddedSectionInfo->start && $sectionInfo->end  <= $biddedSectionInfo->end))
                    $error[] = "class timetable clash" ;
                    $_SESSION['message'] = $error;
                }

            } 
        }

      
                
        if (count($error) == 0) {
            
            // do validation, if successful insert to bid table
            $bid = new Bid ($username,$EAmount, strtoupper($course_code), strtoupper($section_number) );
            $bid->userid = $username;
            $bid->amount = $_POST['e_amount'] ;
            $bid->course_code = strtoupper($_POST['course_code']) ;
            $bid->section = strtoupper($_POST['section_number']);

           
            #deduct edollar and update Student table once validation succeeds            
            $studentDAO = new StudentDAO();
            $studentObject = $studentDAO->retrieve($username);
            $deducted_edollar = $studentObject->getEdollar() - $EAmount;
            $updated_edollar = $studentDAO->updateEdollar($username,$deducted_edollar); 

            

            #call for add bid object table
           
            $bidDAO = new BidDAO();
            $bidObject = $bidDAO->add($bid);
            $_SESSION['message'] = 'Bid successfully placed!';
            header('Location:placebid.php');
            return; 
            // echo "Bid Successfully placed!"; 
            // echo " <a href='../login page/studentpage.php'>Back to Home Page</a> ";
        }
        else {
            foreach ($error as $msg) {
                // echo "<center>";
                // echo $msg ; 
                // echo "</br>";
                $_SESSION['message'] = $msg;
                header('Location:placebid.php');
                 return;
            }
            // echo " <a href='../bid_section/placebid.php'>Return</a>"   ; 
            // echo "</center>";
        }
        
    }

    else {
        foreach ($invalidInput as $error) {
            // echo "<center>";
            // echo "$error" ;
            $_SESSION['message'] = $error;
            header('Location:placebid.php');
             return;
        }
        // echo " <a href='../bid_section/placebid.php'>Return</a>";
        // echo "</center>";
    }

?>