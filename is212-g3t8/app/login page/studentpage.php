<?php 

// require_once '../bootstrap/include/CourseDAO.php';
// require_once '../bootstrap/include/Course.php';
// require_once '../bootstrap/include/SectionDAO.php';
// require_once '../bootstrap/include/Section.php';
// require_once '../bootstrap/include/BidDAO.php';
// require_once '../bootstrap/include/Bid.php';
require_once '../bootstrap/include/common.php';
require_once 'model/protect.php';
// require_once '../bootstrap/include/round_noDAO.php';
// require_once '../bootstrap/include/successful_bidDAO.php';
// require_once '../bootstrap/include/Unsuccessful_bidDAO.php';
// require_once '../bootstrap/include/StudentDAO.php';


// if (isset($_SESSION['username'])){
//     $username = $_SESSION['username'];
//     unset ($_SESSION['username']);
// }else{
//     header('Location: login.php');
//     return;
// }

$studentdao = new StudentDAO();
$username = $_SESSION['username'] ;
$bidDAO = new BidDAO();
$bidObject = $bidDAO->retrieve($username);

$Round_dao = new Round_noDAO();
$actual_round = $Round_dao->retrieve();


if ($actual_round == 2){
    
    include_once '../bootstrap/include/round_2.php';

}


$successful_bidDAO = new successful_bidDAO();
$unsuccessful_bidDAO = new unsuccessful_bidDAO();
$UnSuccessful_bids = $unsuccessful_bidDAO->retrieve_unsuccessfulBid($username);

$studentInfo = $studentdao->retrieve($username);

?>

<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
    <body>
    

     <!-- side nav bar vertical -->
     <ul class="ul">
       <p padding='5px'>  <b> WELCOME, <?= $studentInfo->name  ?></b> </p>
       <p> <b>Faculty: <?= $studentInfo->school ?></b> </p>
  <li><a class="active" href="studentpage.php">Home</a></li>
  <li><a href="../bid_section/sectionbidding.php">Search Section</a></li>
  <li><a href="logout.php">Log Out</a></li>
  <p class='btm'> <b> MERLION UNIVERSITY   </b>
      <br> BIOS 
  </p>
</ul>

<div style="margin-left:18%;padding:16px 16px;height:1000px; width:710px; background-color:#f9f9f9">


<!-- start of content -->
    <h2 align='center'> Home Page </h2>
        <?php 

            echo  "<p align='center' >" . "<tr><b>". $studentInfo->name ."</b>" . ", Welcome to the Bidding System Homepage! </p>";
//  echo  "<p align='center' >" . "<tr><b>". $_SESSION['username'] ."</b>" . ", Welcome to the Bidding System Homepage! </p>";

                    echo "
                <p align='center'> Your current E-Balance : " .
                "<b> e$" . $studentInfo->edollar. "</b> </p>";
                



        if($actual_round== 1){

        
        ?>
   
   <!-- bid details -->
        <!-- <a href="../bid_section/placebid.php">
        <img width='150' height='60' style='display:block; margin-left:auto; margin-right:auto; ' src="cart.png"> </a>
        -->
        
       <table width='350px' align='center' style=border-collapse:collapse; border='2px solid black'>
       <tr height='40px'>
       <td><b><center> Round 1</b> <center> </td>

       <!-- integration from admin round 1 -->

       <td ><center > Round 1 bid has started!<center> </td>
        </tr>
       <tr>
       <td colspan='2'>  <a href="../bid_section/placebid.php" class='a'>  Place Bid Here    </td>
       </tr>

       </table>
       </br>

  

      <table align='center' style=border-collapse:collapse; border='1px solid black'>
  
   
    
       <?php 
        }elseif($actual_round== 2){
                ?>
        
            <!-- bid details -->
                <!-- <a href="../bid_section/placebid.php">
                <img width='150' height='60' style='display:block; margin-left:auto; margin-right:auto; ' src="cart.png"> </a>
                -->
                
            <table width='350px' align='center' style=border-collapse:collapse; border='2px solid black'>
            <tr height='40px'>
            <td><b><center> Round 2</b> <center> </td>
          
            <!-- integration from admin round 1 -->

            <td><center> Round 2 bid has started!<center> </td>
            </tr>

            <tr>
            <td colspan='2'>  <a href="../bid_section/placebid2.php" class='a'>  Place Bid Here    </td></a>
            </td>
            </tr>

            </table>
            </br>

        

            <table align='center' style=border-collapse:collapse; border='1px solid black'>
        
        
            
            <?php
            
            $successfulbidObject = $successful_bidDAO->retrieveByUserID($username);
            #if not empty, show table from database
            if(!empty($successfulbidObject)|| !empty($bidObject)||!empty($UnSuccessful_bids)){
                                       
                echo " 
                <br><br>
                <h3 align='center' style='background-color:#70bcc0'>View bidding results</h3>
                <tr align='center'>
                <th>Course Code</th>
                <th>Section Number</th>
                <th>E$ Placed</th>
                <th>Status </th>
                <th > </th> 
                </tr> 
                ";

            }
            if(!empty($successfulbidObject))  {

                


                for($i = 1; $i <=count($successfulbidObject); $i++)  {
                    $SuccessfulBidInfo = $successfulbidObject[$i-1];
                    echo
            
             
            
                     "
                          <tr align='center'>
                          <td>{$SuccessfulBidInfo->course_code}</td>
                          <td>{$SuccessfulBidInfo->section}</td>
                          <td>{$SuccessfulBidInfo->amount}</td>
                          <td>Success</td>

            
                          <td>
                          <form action='../bid_section/dropsection.php' method='POST'> 
                          <input type='hidden' name='bid_course' value='{$SuccessfulBidInfo->course_code}' /> 
                          <input type='hidden' name='bid_sections' value='{$SuccessfulBidInfo->section}' /> 
                          <input type='hidden' name='bid_amount' value='{$SuccessfulBidInfo->amount}' /> 
                           
            
                          <input type='submit' value='Drop Section'> 
                          
                          
            
                          </form>
                          </td>
                          </tr>
                  
                    ";
                   
            
               }





        }

            if(!empty($UnSuccessful_bids))  {
                    


                foreach($UnSuccessful_bids as $ubid)  {
                    $course = $ubid[2];
                    $section =$ubid[3];
                    $amount = $ubid[1];
            
            
            
                echo "
                        <tr align='center'>
                        <td>$course</td>
                        <td>$section</td>
                        <td>$amount </td>
                        <td>Fail</td>
                        <td></td>

            

                        </tr>
                
                    ";
                
            
            }





        }
    }

        
        #retrieve successful bids placed from the userid as a summary , allow to drop bid here too 

        
    #if not empty, show table from database
       

        //  foreach($bidObject as $bidInfo) {
         //  echo "  <table  align='center' style=border-collapse:collapse; border='1px solid black'>";

       
       
         // determine status of first round bids based on round no, if round = 1 all bids are always pending until admin clears

         if($actual_round == 1){
             $pending= 'Pending';
             if(!empty($bidObject))  {

            
                echo " 
                 <br><br>
                <h3 align='center' style='background-color:#70bcc0' >View bidding results</h3>
                <tr align='center'>
                <th>Course Code</th>
                <th>Section Number</th>
                <th>E$ Placed</th>
                <th>Status</th>
                <th > </th>
                </tr> 
            ";
             
         
            for($i = 1; $i <=count($bidObject); $i++)  {
                $bidInfo = $bidObject[$i-1];

                    
        
            echo "
                    <tr align='center'>
                    <td>{$bidInfo->course_code}</td>
                    <td>{$bidInfo->section}</td>
                    <td>{$bidInfo->amount}</td>
                    <td>$pending</td>
                    

                    <td>
                    <form action='../bid_section/dropbid.php' method='POST'> 
                    <input type='hidden' name='course_code' value='{$bidInfo->course_code}' /> 
                    <input type='hidden' name='section' value='{$bidInfo->section}' />
                    <input type='hidden' name='amount' value='{$bidInfo->amount}' />
                    
                                        
                    

                    <input type='submit' value='Drop Bid' > 
                    
                    

                    </form>
                    </td>
                    </tr>
            
                ";
                

            }
        }
         }elseif($actual_round == 2){
             
            
            if(!empty($bidObject))  {


        
           for($i = 1; $i <=count($bidObject); $i++)  {
               $bidInfo = $bidObject[$i-1];
               
               $results = [];

               if(isset(($_SESSION['successful_bid']))){
                   #as successful_bid is an array of arrays we remove it to become just an array of objects


                    foreach($_SESSION['successful_bid'] as $s_bid){
                        foreach($s_bid as $bidItem){
                            $results[] = $bidItem;

                        }
                       


                }




               }

               if (in_array($bidInfo,$results)){
                   

                    $pending= 'Success';


               }else{
                   $pending = 'Fail';
               }
               

                   
       
           echo "
                   <tr align='center'>
                   <td>{$bidInfo->course_code}</td>
                   <td>{$bidInfo->section}</td>
                   <td>{$bidInfo->amount}</td>
                   <td>$pending</td>
                   

                   <td>
                   <form action='../bid_section/dropbid.php' method='POST'> 
                   <input type='hidden' name='course_code' value='{$bidInfo->course_code}' /> 
                   <input type='hidden' name='section' value='{$bidInfo->section}' />
                   <input type='hidden' name='amount' value='{$bidInfo->amount}' />
                   
                                       
                   

                   <input type='submit' value='Drop Bid'> 
                   
                   

                   </form>
                   </td>
                   </tr>
           
               ";
               

           }
             




         }
     }elseif($actual_round == 1.5 || $actual_round == 2.5){

        $Successful_bids = $successful_bidDAO->retrieve_successfulBid($username);
        $UnSuccessful_bids = $unsuccessful_bidDAO->retrieve_unsuccessfulBid($username);

        if (count($Successful_bids)> 0 ||(count($UnSuccessful_bids)>0  )){
            echo " 
            <br><br>
            <table align='center' style=border-collapse:collapse; border='1px solid black'>
           <h3 align='center' style='background-color:#70bcc0'>View bidding results</h3>
           <tr align='center'>
           <th>Course Code</th>
           <th>Section Number</th>
           <th>E$ Placed</th>
           <th>Status</th>

           </tr> 
       ";

        }

        if(count($Successful_bids)> 0){

            foreach($Successful_bids as $sbid){


           echo"
                   <tr align='center'>
                   <td>{$sbid[2]}</td>
                   <td>{$sbid[3]}</td>
                   <td>{$sbid[1]}</td>
                   <td>Success</td>
                   ";

            }

        }
        if(count($UnSuccessful_bids)>0){
            foreach($UnSuccessful_bids as $ubid){


       echo"
               <tr align='center'>
               <td>{$ubid[2]}</td>
               <td>{$ubid[3]}</td>
               <td>{$ubid[1]}</td>
               <td>Fail</td>
               ";

        }
        echo "</table>";





     }
    }

       ?>
       </table>

       <!-- error msg -->
       <?php 
       if (isset($_SESSION['message'])){
        $msg = $_SESSION['message'];
        echo "<center style = 'color:blue;'>$msg</center>"; 
        }
        unset ($_SESSION['message']);
    ?>
    <br>




    </body>
</html>

