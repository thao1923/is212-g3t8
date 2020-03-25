	<?php
require_once 'include/common.php';
require_once 'include/protect.php';
$Round_dao = new Round_noDAO();
$dao = new BidDAO();
$sectiondao = new SectionDAO();
$Successfulbid_dao = new successful_bidDAO();
$Student_dao = new StudentDAO();
#require_once 'include/protect.php';
$actual_round = $Round_dao->retrieve();
$minBid_DAO = new Min_bidDAO();
$UnSuccessfulbid_dao = new unsuccessful_bidDAO();

if(is_null($actual_round)){


?>

<html>
<head>
<link rel="stylesheet" href="../login page/style.css">
</head>
<body>
   <!-- side nav bar vertical -->
   <ul class="ul">
       <p padding='5px'>  <b> WELCOME Administrator</b> </p>
  <li><a class="active" href="bootstrap.php">Home</a></li>
  <li><a href="../login page/logout.php">Log Out</a></li>
  <p class='btm'> <b> MERLION UNIVERSITY   </b>
      <br> BIOS 
  </p>
</ul>

<div style="margin-left:18%;padding:16px 16px;height:1000px; width:710px; background-color:#f9f9f9">

<form id='bootstrap-form' action="bootstrap-process.php" method="post" enctype="multipart/form-data">
<center>
<h3>	Import Bootstrap file:  </h3>
	<input  id='bootstrap-file' type="file" name="bootstrap-file"><br><br>
	<input  type="submit" name="submit" value="Import">
	</center>
</form>

</html>



<?php
}
	


#Stop round 1



if ($actual_round > 0){
	echo "<head>
<link rel='stylesheet' href='../login page/style.css'>
</head>
<body>
   <!-- side nav bar vertical -->
   <ul class='ul'>
       <p padding='5px'>  <b> WELCOME, Administrator</b> </p>
  <li><a class='active' href='bootstrap.php'>Home</a></li>
  <li><a href='../login page/logout.php'>Log Out</a></li>
  <p class='btm'> <b> MERLION UNIVERSITY   </b>
      <br> BIOS 
  </p>
</ul>

<div style='margin-left:18%;padding:16px 16px;height:1000px; width:710px; background-color:#f9f9f9'>";

echo "

<form id='bootstrap-form' action='bootstrap-process.php' method='post' enctype='multipart/form-data'>
<center>
<h3>	Import Bootstrap file:  </h3>
	<input  id='bootstrap-file' type='file' name='bootstrap-file'><br><br>
	<input  type='submit' name='submit' value='Import'>
	</center>
</form>
<br>

"
;

	if($actual_round == 1.0){
		

		if (!isset($_POST["Stop_round1"])){

		echo " <center> <h3> Bidding Round: <br><br> ";
		echo ($actual_round );
		echo "</h3>";
		
		echo "<html><form id='stop-action' action='bootstrap.php' method='post' enctype='multipart/form-data'>";
		echo "<br><input type='submit' name='Stop_round1' value='stop'> </center>";
		
			
		echo "</html>";

		}else{
			echo"<center>Round 1 Stopped and Bids are cleared</center>";
			$Round_dao->update_round(1.5);
			$actual_round = $Round_dao->retrieve();
			$_SESSION['sbids'] = [];
			
			

			
			#retrieve all classes, a class being courseid+section
			$_SESSION['classes'] = $dao -> retrieveAllClasses();
			
			#function to sort bids
			function sortByAmount($x, $y) {
				return strcmp($y->amount, $x->amount);
			}
			
			
			
			foreach ($_SESSION['classes'] as $class){
				$successfulbids = [];
				#determine total number of bids for the class
				$no_of_bids = count($dao -> retrieveAllBidsbyClasses($class[0], $class[1]));
				#create list for all bids
				$bids_list = $dao -> retrieveAllBidsbyClasses($class[0], $class[1]);
			
			   
				#Get number of vacancies/size of the class
				$size_class = ($sectiondao->retrieveSizeByCodeAndSection($class[0], $class[1]));
				
				$successfulbids = [];
				

			
				#for vacancy more than number of bids
				if ($size_class > $no_of_bids){
					foreach($bids_list as $bids){
			
						$userid = ($bids->userid);
						$amount = ($bids->amount);
						$course_code = $bids->course_code;
						$section = $bids->section;
			
						
						$Successfulbid_dao->add_successful($bids->userid, $bids->amount, $bids->course_code, $bids->section, $actual_round );
			
			
						
					}
					
				#when number of bids more than vacancy
				}elseif($no_of_bids> $size_class){
					
			
				   
			
					#sort ascending order bids 
					usort($bids_list, 'sortByAmount');
					
			
	
					
					if(count($bids_list) > 1){
						$clearing_bid = ($bids_list[$size_class-1]) ->amount;

					}
					
					#string to float
					$clearing_bid = (float)$clearing_bid;
					
					
					$equal_to_clearing_bid = [];
					$successfulbids = [];
					$first_fail_bid = ($bids_list[$size_class]) ->amount;


			
					foreach($bids_list as $bid){
			
						$bid_amount = $bid->amount;
			
						if( $bid_amount > $clearing_bid){
							
							$successfulbids[] = $bid;
							
			
						}elseif(($bid->amount) == $clearing_bid){
							
							$equal_to_clearing_bid[] = $bid;
						  
						}
			
			
					}
					// if($clearing_bid == $first_fail_bid){

					// }elseif(count($equal_to_clearing_bid) == 1){
					// 	$successfulbids[] = $equal_to_clearing_bid[0];
						
					// }
					if(count($equal_to_clearing_bid) == 1){
						foreach($equal_to_clearing_bid as $bid){
							$successfulbids[] = $bid;
						}

					}



					foreach ($successfulbids as $successful_bid){
					
						#successsful bids are added to the successful_bid table
						$Successfulbid_dao->add_successful($successful_bid->userid, $successful_bid->amount, $successful_bid->course_code, $successful_bid->section, $actual_round );
						$_SESSION['sbids'] = $successful_bid;
	
						
					}
					// elseif(count($equal_to_clearing_bid > 1 && $clearing_bid>$first_fail_bid ){
					// 	foreach($equal_to_clearing_bid as $bid){
					// 		$successfulbids[] = $bid;

					// 	}
						
						

					// }
			
				}elseif($no_of_bids== $size_class){
													
					#sort ascending order bids 
					usort($bids_list, 'sortByAmount');
					
			
	

					$clearing_bid = ($bids_list[$size_class-1]) ->amount;


					
					#string to float
					$clearing_bid = (float)$clearing_bid;
					
					
					$equal_to_clearing_bid = [];
					$successfulbids = [];



			
					foreach($bids_list as $bid){
			
						$bid_amount = $bid->amount;
			
						if( $bid_amount > $clearing_bid){
							
							$successfulbids[] = $bid;
							
			
						}elseif(($bid->amount) == $clearing_bid){
							
							$equal_to_clearing_bid[] = $bid;
						  
						}
			
					}

					if(count($equal_to_clearing_bid) == 1){
						foreach($equal_to_clearing_bid as $bid){
							$successfulbids[] = $bid;
						}

					}

					foreach ($successfulbids as $successful_bid){
						#successsful bids are added to the successful_bid table
						$Successfulbid_dao->add_successful($successful_bid->userid, $successful_bid->amount, $successful_bid->course_code, $successful_bid->section, $actual_round );
						$_SESSION['sbids'] = $successful_bid;
	
						
					}
					// elseif(count($equal_to_clear

				}
			


				}
				
				foreach ($_SESSION['classes'] as $class){
					$course_code = $class[0];
					$section = $class[1];
					$bids = $dao -> retrieveAllBidsbyClasses($course_code,$section);
					$successful_spots = $Successfulbid_dao ->count_no_success ($section,$course_code);

					$current_size = $sectiondao ->retrieveSizeByCodeAndSection($course_code, $section);
					

					$new_size = $current_size - $successful_spots;
					#update section size
					$sectiondao->updateSize($section,$course_code,$new_size);
					
}
				$unsuccessful_bids = [];
				$all_bids = $dao->retrieve_all();
				$all_successfulbid = $Successfulbid_dao ->retrieve_all();


				foreach ($all_bids as $bid){
					if(!in_array($bid,$all_successfulbid)){
						$unsuccessful_bids[] = $bid;
						


					}
				}
				# update edollar from successful bid table
				$update_bid_list = $Successfulbid_dao->retrieve_userid_amount();

				foreach($unsuccessful_bids as $updates){
					$id = $updates[0];
					$bid_amount = $updates[1];
					
					$current_edollar = $Student_dao ->retrieve_edollar($id);
					

					$amount_remaining = $current_edollar + $bid_amount;
					
					$Student_dao->updateEdollar($id,$amount_remaining);
					$UnSuccessfulbid_dao->add_unsuccessful($updates[0],$updates[1],$updates[2],$updates[3]);
				}
				#set min bids
				$allpossibleclasses = $sectiondao -> retrieveAllpossibleClasses();
				foreach($allpossibleclasses as $class){
					$course_code = $class[0];
					$section = $class [1];
					$minBid_DAO-> add_min_bid($course_code, $section, 10);
				}

				
				



			
			}




			
		}

		
	if($actual_round == 1.5){
	
		if (!isset($_POST["Start_round2"])){
			echo " <center> <h3> Bidding Round: <br><br> ";
			echo($actual_round);
			echo "</h3>";
			echo "<html><form id='start-round2' action='bootstrap.php' method='post' enctype='multipart/form-data'>";
			echo "<input type='submit' name='Start_round2' value='Start_Round 2'></center>";
		
			echo "</html>";
		}else{
			$Round_dao->update_round(2);
			$dao->removeAll();
			$actual_round = $Round_dao->retrieve();

			
			
		}
	}
	if($actual_round == 2){
		if (!isset($_POST["stop_round2"])){
			echo " <center> <h3> Bidding Round: <br><br> ";
			echo($actual_round);
			echo "</h3>";
			echo "<html><form id='start-round2' action='bootstrap.php' method='post' enctype='multipart/form-data'>";
			echo "<input type='submit' name='stop_round2' value='stop'></center>";
			
			echo "</html>";
		
		}else{
			echo"<center>Round 2 Stopped and Bids are cleared</center>";
			$Round_dao->update_round(2.5);
			$actual_round = $Round_dao->retrieve();
			include_once 'include/round_2.php';
			$successfulbids = $_SESSION['successful_bid'];
			#round 2 clearing code
			if(isset(($_SESSION['successful_bid']))){
				#as successful_bid is an array of arrays we remove it to become just an array of objects

				 foreach($_SESSION['successful_bid'] as $s_bids){
					$successfulbids [] = $s_bids;
					foreach($s_bids as $s_bid){
						$Successfulbid_dao->add_successful($s_bid->userid, $s_bid->amount, $s_bid->course_code, $s_bid->section, $actual_round );


						$current_size = $sectiondao ->retrieveSizeByCodeAndSection($s_bid->course_code,$s_bid->section);
						

						$new_size = $current_size - $successful_spots;
						#update section size
						$sectiondao->updateSize($s_bid->section,$s_bid->course_code,$new_size);
					}
			 	}




			}
			

			$all_successfulbid = $Successfulbid_dao ->retrieve_all();
			$all_bids = $dao->retrieve_all();

			if(isset($all_bids)){
				foreach ($all_bids as $bid){
					if(!in_array($bid,$all_successfulbid)){
						$unsuccessful_bids[] = $bid;
						
	
	
					}
				}
			}

			if(isset($unsuccessful_bids)){
				foreach($unsuccessful_bids as $updates){
					$id = $updates[0];
					$bid_amount = $updates[1];
					
					$current_edollar = $Student_dao ->retrieve_edollar($id);
					
	
					$amount_remaining = $current_edollar + $bid_amount;
					
					$Student_dao->updateEdollar($id,$amount_remaining);
					$UnSuccessfulbid_dao->add_unsuccessful($updates[0],$updates[1],$updates[2],$updates[3]);
					
	
				}
				
			}






			
		
		}
			
		
	
	}
	if($actual_round == 2.5){
		if (!isset($_POST["Reset"])){
			echo " <center> <h3> Bidding Round: <br><br> ";
			echo($actual_round);
			echo "</h3>";
			echo "<html><form id='start-round2' action='bootstrap.php' method='post' enctype='multipart/form-data'>";
			echo "<input type='submit' name='Reset' value='Reset'></center>";
			
			echo "</html>";
		
		}else{
			$Round_dao->removeAll();
			header("Location: bootstrap.php");
			
		}
			
		
	
	}


}









?>