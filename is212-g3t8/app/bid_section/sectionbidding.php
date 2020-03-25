<?php

// require_once '../bootstrap/include/CourseDAO.php';
// require_once '../bootstrap/include/Course.php';
// require_once '../bootstrap/include/SectionDAO.php';
// require_once '../bootstrap/include/Section.php';
// require_once '../login page/model/common.php';
require_once '../bootstrap/include/common.php';
require_once '../login page/model/protect.php';

$studentdao = new StudentDAO();
$username = $_SESSION['username'] ;
$studentInfo = $studentdao->retrieve($username);

?>
<?php

// retrive all course related information 
$coursedao = new CourseDAO() ;
$courseInfo = $coursedao->retrieveAll() ;


// form processing 
$courseInput = '' ;
if (isset($_POST['course'])) {
	$courseInput = $_POST['course'];
}

// retrieve all sections for a particular course 
$sectiondao = new SectionDAO() ;
$allSection = $sectiondao->retrieveAllByCode($courseInput) ;

// retrieve all course & section related information 
$courseSectionInfo = [] ;
foreach ($allSection as $section) {
    $allInfo = $sectiondao->retrieveAllByCodeAndSection($courseInput,$section) ;
    array_push($courseSectionInfo, $allInfo) ;
}


?>

<html>
<head>
<link rel="stylesheet" href="../login page/style.css">
</head>
<body>
 <!-- side nav bar vertical -->
 <ul class="ul">
       <p padding='5px'>  <b> WELCOME, <?= $studentInfo->name  ?> </b> </p>
       <p> <b>Faculty: <?= $studentInfo->school ?></b> </p>
  <li><a href="../login page/studentpage.php">Home</a></li>
  <li><a class="active" href="sectionbidding.php" >Search Section</a></li>
  <li><a href="../login page/logout.php">Log Out</a></li>
  <p class='btm'> <b> MERLION UNIVERSITY   </b>
      <br> BIOS 
  </p>
</ul>

<div style="margin-left:18%;padding:30px 16px;height:1000px; width:600px; background-color:#f9f9f9">


    
    <form method='post' action='sectionbidding.php' align='center'>

    <select name = 'course'  >
    <?php 
        foreach ($courseInfo as $course) {
            $selected = '' ;
            $courseCode = $course->course_code ;
            if ($courseCode == $courseInput) {
                $selected = 'selected' ;
            }
            echo "
                <option  value = $courseCode $selected> $courseCode - $course->title </option>" ;
        }
        ?>
    </select>
    <input type = 'submit' value = 'Search' name = 'action' /> 
    
    <br/><br/>
    
    
    
</body>
</html>


<?php

// display course & section information 
if (isset($_POST['action']) && $_POST["action"] == 'Search') {
    echo "<table align='center' border = 1>" ;

                echo "
                <tr>
                    <th>Section</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Room</th>
                    <th>Instructor</th>
                    <th>Vacancies</th>
                </tr>" ;
                foreach ($courseSectionInfo as $eachCourseSectionInfo) {
                    echo "
                        
                        <tr>
                            <td>$eachCourseSectionInfo->section</td>
                            <td>$eachCourseSectionInfo->day</td> 
                            <td>$eachCourseSectionInfo->start</td>
                            <td>$eachCourseSectionInfo->end</td>    
                            <td>$eachCourseSectionInfo->venue</td>
                            <td>$eachCourseSectionInfo->instructor</td>
                            <td>$eachCourseSectionInfo->size</td>
                        </tr>" ;
                }

    echo " </table> " ;
}

?>

<?php


?>