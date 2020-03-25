
<html>
    <head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
</html>
<?php 
require_once '../bootstrap/include/CourseDAO.php';
require_once '../bootstrap/include/Course.php';
require_once '../login page/model/common.php';
require_once '../login page/model/protect.php';


#retrieve course bidding table
$coursedao = new CourseDAO();
$courseInfo = $coursedao->retrieveAll();

for($i = 1; $i <= count($courseInfo); $i++) {

    $course  = $courseInfo[$i-1];
echo "
<table> 

<tr>
                <td>$course->course_code</td>
                <td>$course->school</td>
                <td>$course->title</td>
                <td>$course->description</td>
                <td>$course->exam_date</td>
                <td>$course->exam_start</td>
                <td>$course->exam_end</td>
                        </tr>

                    </table> ";



}






?>