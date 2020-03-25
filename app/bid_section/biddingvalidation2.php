<?php 

require_once '../bootstrap/include/common.php';

require_once '../login page/model/protect.php';

?>

<?php
    
    //get input values and username via session
    $username = $_SESSION['username'] ;
    $course_code = $_POST['course_code'] ;
    $section_number = $_POST['section_number'] ;
    $course_code = strtoupper($course_code);
    $section_number = strtoupper($section_number);
    $EAmount = $_POST['e_amount'] ;

    $invalidInput = [] ;
    $Min_bidDAO = new Min_bidDAO();
    $successful_bidDAO = new successful_bidDAO();
    $successfulbids = $successful_bidDAO->retrieveByUsername($username);
   
    


    
    $courseDAO = new CourseDAO() ;
    $courseInfo = $courseDAO->retrieveAllByCode($course_code) ;
    if (!$courseInfo) {
        $invalidInput[] = "invalid course code" ;
        $_SESSION['message'] = $invalidInput;
        
    }

    $sectionDAO = new SectionDAO ;
    $sectionInfo = $sectionDAO->retrieveAllByCodeAndSection($course_code, $section_number) ;
    $courseSection = $sectionDAO->retrieveAllByCode($course_code) ;
    if (!in_array($section_number, $courseSection)) {
        $invalidInput[] = "invalid section code" ;
        $_SESSION['message'] = $invalidInput;
    }
    
    $studentDAO = new StudentDAO() ;
    $studentInfo = $studentDAO->retrieve($username) ;
    
    $bidDAO = new BidDAO() ;
    $bidInfo = $bidDAO->retrieve($username) ;
    #addprevious successful bids into bidinfo for validation logic below
    $bidInfo = array_merge($bidInfo,$successfulbids);
   

    $completeDAO = new CompleteDAO() ;
    $courseCompleted = $completeDAO->retrieve($username) ;

    $prerequisiteDAO = new PrerequisiteDAO ;
    $prerequisiteInfo = $prerequisiteDAO->retrieveByCode($course_code) ;



    //get student edollar info 

    // function getStudentEdollar($username) {
    //     $studentDAO = new StudentDAO();
    //     $studentObject = $studentDAO->retrieve($username);
    //     $student_edollar = $studentObject->getEdollar();
    //     return $student_edollar;
    //     }

    $student_edollar = $studentDAO->retrieve_edollar ($username);
    
     
    if (isset($_POST['course_code'] ) && $_POST['section_number'] && $_POST['e_amount'] && count($invalidInput) == 0) {
        $min_bid = $Min_bidDAO->retrieve_min_bid($_POST['course_code'], $_POST['section_number']);

        $error = [] ;

        // check if course is completed
        if (in_array($course_code, $courseCompleted)) {
            $error[] = 'course completed' ;
        }

        // check for section limit 
        if (count($bidInfo) >= 5) {
            $error[] = "section limit reached" ;
            $_SESSION['message'] = $error;
        }
        //check if above min bid
        if($_POST['e_amount'] < ($min_bid)){
            $error[] = "Bid has to be higher than $min_bid" ;
            $_SESSION['message'] = $error;
        }

        // check for sufficient balance in student edollar
        if ($student_edollar < 10.00) {
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
            $bid = new Bid ($username,$EAmount, $course_code, $section_number );
            $bid->userid = $username;
            $bid->amount = $_POST['e_amount'] ;
            $bid->course_code = strtoupper($_POST['course_code']) ;
            $bid->section = strtoupper($_POST['section_number']);

           
            #deduct edollar and update Student table once validation succeeds            
            $studentDAO = new StudentDAO();
            $studentObject = $studentDAO->retrieve($username);
            $deducted_edollar =$student_edollar - $EAmount;
            $updated_edollar = $studentDAO->updateEdollar($username,$deducted_edollar); 

            

            #call for add bid object table
           
            $bidDAO = new BidDAO();
            $bidObject = $bidDAO->add($bid);
            $_SESSION['message'] = "Bid Successfully placed!"; 
            header('Location:placebid2.php');
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
                header('Location:placebid2.php');
                return;
            }
            // echo " <a href='../bid_section/placebid2.php'>Return</a>"   ; 
            // echo "</center>";
        }
        
    }

    else {
        foreach ($invalidInput as $error) {
            // echo "<center>";
            // echo "$error" ;
            $_SESSION['message'] = $error;
                header('Location:placebid2.php');
                return;
        }
    }

?>