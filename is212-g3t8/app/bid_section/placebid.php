<?php
 
require_once '../bootstrap/include/CourseDAO.php';
require_once '../bootstrap/include/Course.php';
require_once '../bootstrap/include/SectionDAO.php';
require_once '../bootstrap/include/Section.php';
require_once '../login page/model/common.php';
require_once '../login page/model/protect.php';


#session userid
$username = $_SESSION['username'] ;

#call studentDAO
$studentdao = new studentDAO();
$studentInfo = $studentdao->getUserID($username);
?>

<html>
<head>
<link rel="stylesheet" href="../login page/style.css">
</head>
    <body>
  

     <!-- side nav bar vertical -->
     <ul class="ul">
       <p padding='5px'>  <b> WELCOME, <?= $studentInfo->getName() ?></b> </p>
       <p> <b>Faculty: <?= $studentInfo->school ?></b> </p>
  <li><a class="active" href="../login page/studentpage.php">Home</a></li>
  <li><a href="sectionbidding.php">Search Section</a></li>
  <li><a href="../login page/logout.php">Log Out</a></li>
  <p class='btm'> <b> MERLION UNIVERSITY   </b>
      <br> BIOS 
  </p>
</ul>

<div style="margin-left:18%;padding:16px 16px;height:1000px; width:710px; background-color:#f9f9f9">


    <h1 align='center'> Welcome to Round 1 Bidding</h1>
         <p align='center'> Your current E-Balance :   
                <b> e$<?= $studentInfo->edollar ?></b> </p>
    <!-- rmb to session the student name over -->
    <form action = 'biddingvalidation.php' method  = 'POST'>
   <table  border='1px' width='420px' height='80px' align='center' >
   <tr>
   <th> Course Code</th>
   <th> Section Number</th>
   <th> e$ Amount </th>
    </tr>
   
   
 <tr>
   
   <!-- insert dropdown list to fetch coursecode etc when logic is done -->

   <td> <input type='text'  name='course_code' required > </td>
  <td> <input type='text' name='section_number' required></td>
  <!-- <td> <input type='float' pattern="^\d*(\.\d{0,2})?$" name='e_amount' min=10.00 max='<?= $studentInfo->getEdollar() ?>' required  > </td> -->
  <td><input type='number' step='.01' name='e_amount' min='10' max='<?= $studentInfo->getEdollar() ?>'  required>  </td>
   </tr>
  
   <!-- <tr>
   <td> <input type='text' name='course_code2' > </td>
  <td> <input type='text' name='section_number2' ></td>
  <td> <input type='text' name='e_amount2' > </td>
   </tr>
   <tr>
   <td> <input type='text' name='course_code3' > </td>
  <td> <input type='text' name='section_number3' ></td>
  <td> <input type='text' name='e_amount3' > </td>
   </tr>
   <tr>
   <td> <input type='text' name='course_code4' > </td>
  <td> <input type='text' name='section_number4' ></td>
  <td> <input type='text' name='e_amount4' > </td>
   </tr>

   <tr>
   <td> <input type='text' name='course_code5' > </td>
  <td> <input type='text' name='section_number5' ></td>
  <td> <input type='text' name='e_amount5' > </td>
   </tr>
  -->
    </table>
    <p align='center'>
    <input type="submit" value="Submit Bid" >
    </p>
    <?php 

      if (isset($_SESSION['message'])){
        $msg = $_SESSION['message'];
        echo "<center style = 'color:blue;'>$msg</center>"; 
        }
        unset ($_SESSION['message']);
    ?>
    <br>
    <center>
            <a href='../login page/studentpage.php'>Back</a>
            </center>
    </body>


</html>