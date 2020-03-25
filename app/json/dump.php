<?php

require_once "../bootstrap/include/common.php";
require_once "../bootstrap/include/token.php";


if (!isset($_GET['token'])){
    $result = [
        "status" => 'error',
        "error" => ["missing token"]
        ];
}else{
    $token = $_GET['token'];
    if (empty($token)){
        $result = [
                "status" => 'error',
                "error" => ["blank token"]
                ];
    }else{
        if (!verify_token($token)){
            $result = [
                "status" => 'error',
                "error" => ["invalid token"]
            ];
        } else{

            $courseDAO = new CourseDAO();
            $sectionDAO = new SectionDAO();
            $studentDAO = new StudentDAO();
            $prerequisiteDAO = new PrerequisiteDAO();
            $bidDAO = new BidDAO();
            $completeDAO = new CompleteDAO();
            $successful_bidDAO = new successful_bidDAO();

            // Course
            $courses =  $courseDAO->retrieveAll();
            //Sort
            $sortclass = new Sort();
            $courses = $sortclass->sort_it($courses, 'course_code');
            $new_courses = [];
            // Rename, change type
            foreach($courses as $index=>$course){
                $new_course  = [];
                $new_course['course'] = $course->course_code; 
                $new_course['school'] = $course->school; 
                $new_course['title'] = $course->title; 
                $new_course['description'] = $course->description; 
                $new_course['exam date'] = implode('',explode('-', $course->exam_date)); 
                if ($course->exam_start[0] == 0){
                     $new_course['exam start'] = substr(implode('',explode(':', $course->exam_start)), 1, 3); 
                }
               else{
                $new_course['exam start'] = substr(implode('',explode(':', $course->exam_start)), 0, 4); 
               }
               if ($course->exam_end[0] == 0){
                $new_course['exam end'] = substr(implode('',explode(':', $course->exam_end)), 1, 3); 
            }
                else{
                    $new_course['exam end'] = substr(implode('',explode(':', $course->exam_end)), 0, 4); 
                }
                $new_courses[] = $new_course;
            }

            // Section
            $sections =  $sectionDAO->retrieveAll();
            //Sort
            $sections = $sortclass->sort_it($sections, 'section');
            $sections = $sortclass->sort_it($sections, 'course_code');
            $new_sections = [];
            $day_convert = [
                            '1'=> 'Monday',
                            '2'=> 'Tuesday',
                            '3'=> 'Wednesday',
                            '4'=> 'Thursday',
                            '5'=> 'Friday',
                            '6'=> 'Saturday',
                            '7'=> 'Sunday',
                            ];
            // Rename, change type
            foreach($sections as $index=>$section){
                $new_section  = [];
                $new_section['course'] = $section->course_code; 
                $new_section['section'] = $section->section; 
                $day = (int) $section->day;
                $new_section['day'] = $day_convert[$day]; 
                if ($section->start[0] == 0){
                $new_section['start'] = substr(implode('',explode(':', $section->start)), 1, 3);  
            }
            else { 
            $new_section['start'] = substr(implode('',explode(':', $section->start)), 0, 4);
            }
            if ($section->end[0] == 0){
                $new_section['end'] = substr(implode('',explode(':', $section->end)), 1, 3); 
            }
            else{
                $new_section['end'] = substr(implode('',explode(':', $section->end)), 0, 4); 
            }
                $new_section['instructor'] = $section->instructor; 
                $new_section['venue'] = $section->venue; 
                $new_section['size'] = (int) $section->size; 
                $new_sections[] = $new_section;
            }

            //Student
            $students =  $studentDAO->retrieveAll();
            // Sort
            $students = $sortclass->sort_it($students, 'userid');
            $new_students = [];
            // Rename, change type
            foreach($students as $index=>$student){
                $new_student  = [];
    
                $new_student['userid'] = $student->userid; 
                $new_student['password'] = $student->password; 
                $new_student['name'] = $student->name; 
                $new_student['school'] = $student->school; 
                $new_student['edollar'] = (float) $student->edollar; 

                $new_students[] = $new_student;
            }

            // Prerequisite
            $prerequisites = $prerequisiteDAO->retrieveAll();
            // Sort
            $prerequisites = $sortclass->sort_it($prerequisites, 'prerequisite');
            $prerequisites = $sortclass->sort_it($prerequisites, 'course_code');
            $new_prerequisites = [];
            // Rename, change type
            foreach($prerequisites as $index=>$prerequisite){
                $new_prerequisite  = [];
    
                $new_prerequisite['course'] = $prerequisite->course_code; 
                $new_prerequisite['prerequisite'] = $prerequisite->prerequisite; 

                $new_prerequisites[] = $new_prerequisite;
            }

            // Bid
            $bids = $bidDAO->retrieveAll();
            // Sort
            $bids = $sortclass->sort_it($bids, 'userid');
            $bids = $sortclass->sort_it($bids, 'amount');
            $bids = $sortclass->sort_it($bids, 'section');
            $bids = $sortclass->sort_it($bids, 'course_code');
            // Rename, change type
            $new_bids = [];
            foreach($bids as $index=>$bid){
                $new_bid  = [];
                $new_bid['userid'] = $bid->userid;
                $new_bid['amount'] = (float) $bid->amount;
                $new_bid['course'] = $bid->course_code; 
                $new_bid['section'] = $bid->section; 

                $new_bids[] = $new_bid;
            }

            //Completed course
            $completes = $completeDAO->retrieveAll();
            // Sort
            $completes = $sortclass->sort_it($completes, 'userid');
            $completes = $sortclass->sort_it($completes, 'course_code');
            $new_completes = [];
            //Rename, change type
            foreach($completes as $index=>$complete){
                $new_complete  = [];
                $new_complete['userid'] = $complete->userid;
                $new_complete['course'] = $complete->course_code; 

                $new_completes[] = $new_complete;
            }

            // section-students
            $Round_dao = new Round_noDAO();
            $actual_round = $Round_dao->retrieve();
            $successful_bids = [];
            if ($actual_round == 1.5 || $actual_round == 2.0){
                $successful_bids = $successful_bidDAO->successRound1();
            }elseif($actual_round == 2.5 ){
                $successful_bids = $successful_bidDAO->successRound2();
            }

            $new_successful_bids = [];

            // Sort
            $successful_bids = $sortclass->sort_it($successful_bids, 'userid');
            $successful_bids = $sortclass->sort_it($successful_bids, 'course_code');
            // Rename, change type
            foreach($successful_bids as $index=>$successful_bid){
                $new_successful_bid  = [];
                $new_successful_bid['userid'] = $successful_bid->userid;
                $new_successful_bid['course'] = $successful_bid->course_code; 
                $new_successful_bid['section'] = $successful_bid->section; 
                $new_successful_bid['amount'] = (float) $successful_bid->amount;


                $new_successful_bids[] = $new_successful_bid;
            }

            $result = [ 
                    "status" => "success",
                    "course" => $new_courses,
                    "section" => $new_sections,
                    "student" => $new_students,
                    "prerequisite" =>$new_prerequisites,
                    "bid" => $new_bids,
                    "completed-course" => $new_completes,
                    "section-student" =>$new_successful_bids
                    ];

        }
    }
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION);

?>