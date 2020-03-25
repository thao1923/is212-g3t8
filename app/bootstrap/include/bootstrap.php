<?php
require_once 'common.php';

function trimData($data){
	$trim_data = [];
	for($i = 0; $i < count($data); $i++){
		$field = trim($data[$i]);
		$trim_data[] = $field;
	}
	return $trim_data;
	
}

function checkDay($date){
	if (!is_numeric($date)){
		return FALSE;
	}
	$check = date('Ymd', strtotime($date));
	if ($date != $check){
		return False;
	}
	return True;
}

function checkTime($time){
	
	$check = date('G:i', strtotime($time));

	if ($time != $check){
		return False;
	}
	return True;
}

function checkChars($word, $limit){
	if (strlen($word) > $limit){
		return False;
	}
	return True;
}

function toString($a){
	return (string) $a;
}

function doBootstrap() {

	$errors = array();
	# need tmp_name -a temporary name create for the file and stored inside apache temporary folder- for proper read address
	$zip_file = $_FILES["bootstrap-file"]["tmp_name"];

	# Get temp dir on system for uploading
	$temp_dir = sys_get_temp_dir();

	# keep track of number of lines successfully processed for each file
	$student_processed=0;
	$bid_processed=0;
	$course_completed_processed=0;
	$prerequisite_processed=0;
	$section_processed=0;
	$course_processed=0;

	# check file size
	if ($_FILES["bootstrap-file"]["size"] <= 0)
		$errors[] = "input files not found";

	else {
		
		$zip = new ZipArchive;
		$res = $zip->open($zip_file);

		if ($res === TRUE) {
			$zip->extractTo($temp_dir);
			$zip->close(); // IMPORTANT
		
			$student_path = "$temp_dir/student.csv";
			$course_path = "$temp_dir/course.csv";
			$section_path = "$temp_dir/section.csv";
			$prerequisite_path = "$temp_dir/prerequisite.csv";
			$course_completed_path = "$temp_dir/course_completed.csv";
			$bid_path = "$temp_dir/bid.csv";				
			
			
			$student = @fopen($student_path, 'r');
			$course = @fopen($course_path, 'r');
			$section = @fopen($section_path, 'r');
			$prerequisite = @fopen($prerequisite_path, 'r');
			$course_completed = @fopen($course_completed_path, 'r');
			$bid = @fopen($bid_path, 'r');


			if (empty($student) || empty($bid) || empty($course_completed) 
			|| empty($prerequisite) || empty($section) || empty($course)){
				$errors[] = "input files not found";
				if (!empty($student)){
					fclose($student);
					@unlink($student_path);
				} 
				
				if (!empty($bid)) {
					fclose($bid);
					@unlink($bid_path);
				}
				
				if (!empty($course_completed)) {
					fclose($course_completed);
					@unlink($course_completed_path);
				}

				if (!empty($prerequisite)) {
					fclose($prerequisite);
					@unlink($prerequisite_path);
				}

				if (!empty($section)) {
					fclose($section);
					@unlink($section_path);
				}

				if (!empty($course)) {
					fclose($course);
					@unlink($course_path);
				}
								
			}
			else {
				$connMgr = new ConnectionManager();
				$conn = $connMgr->getConnection();

				# start processing
				
				# truncate current SQL tables

				$StudentDAO = new StudentDAO();
				$StudentDAO->removeAll();

				$CourseDAO = new CourseDAO();
				$CourseDAO->removeAll();
				
				$SectionDAO = new SectionDAO();
				$SectionDAO->removeAll();

				$PrerequisiteDAO = new PrerequisiteDAO();
				$PrerequisiteDAO->removeAll();

				$CompleteDAO = new CompleteDAO();
				$CompleteDAO->removeAll();

				$BidDAO = new BidDAO();
				$BidDAO->removeAll();

				$Min_bidDAO = new Min_bidDAO();
				$Min_bidDAO->removeAll();

				$Round_noDAO = new Round_noDAO();
				$Round_noDAO->removeAll();

				$successful_bidDAO = new successful_bidDAO();
				$successful_bidDAO->removeAll();

				
				$unsuccessful_bidDAO = new unsuccessful_bidDAO();
				$unsuccessful_bidDAO ->removeAll();




				# Student table
				$header = fgetcsv($student);
				$line = 1;
				while(($data = fgetcsv($student)) != false ){
					$line++;
					$message = [];
					$data = trimData($data);

					for ($i=0;$i<=4;$i++){
                        if($i == 3){
                            $data[$i]=strtoupper(trim($data[$i]));
                        }
                        $data[$i]=trim($data[$i]);
                        if (strlen($data[$i])==0){
                            //check for empty cell
                            $message[]="blank $header[$i]";

                        }
                    }

					if (empty($message)){
						$isValidUserid = checkChars($data[0], 128) ;
						$duplicateUserid = $StudentDAO->retrieve($data[0]) ;
						$isValidPwd = checkChars($data[1], 128) ;
						$isValidName = checkChars($data[2], 100) ;

						if (!$isValidUserid) {
							$message[] = 'invalid userid' ;
						} 

						if ($duplicateUserid) {
							$message[] = 'duplicate userid' ;
						}

						$is_2_dec = True;
						preg_match('^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$^', "$data[4]", $matches);
						if (!empty($matches)) {
							if ($matches[0] != $data[4]) {
								$is_2_dec = False;
							}
						}
						if (!is_numeric($data[4]) || $data[4] < 0 || !$is_2_dec) {
							$message[] = 'invalid e-dollar' ;
						}
					
						if (!$isValidPwd) {
							$message[] = 'invalid password' ;
						}
					
						if (!$isValidName) {
							$message[] = 'invalid name';
						}
					}
					
					
					if (count($message) > 0){
						$errors[] = [
									'file' => 'student.csv',
									'line' => $line,
									'message' => $message
									];
						continue;
					}
					

					$stuObj = new Student($data[0], $data[1], $data[2], $data[3], $data[4]);
					$StudentDAO->add($stuObj);
					$student_processed++;
				}

				# Course table
				$header = fgetcsv($course);
				$line = 1;
				while(($data = fgetcsv($course)) != false ){
					$data = trimData($data);
					$message=[];
                    for ($i=0;$i<=6;$i++){
                        if($i == 0 or $i == 1){
                        	$data[$i]=strtoupper(trim($data[$i]));
                        }
                        $data[$i]=trim($data[$i]);
                        if (strlen($data[$i])==0){
                            //check for empty cell
                            $message[]="blank $header[$i]";
                            
                        }
					}
					$line++;
					if (empty($message)){
						$isExamDate = checkDay($data[4]);
						$isExamStart = checkTime($data[5]);
						$isExamEnd = checkTime($data[6]);
						$isValidTitle = checkChars($data[2], 100);
						$isValidDesc = checkChars($data[3], 1000);
					
						if (!$isExamDate){
							$message[] = "invalid exam date";
						}

						if (!$isExamStart){
							$message[] = "invalid exam start";
						}else{
							if (!$isExamEnd){
								$message[] = 'invalid exam end';
							}elseif (strtotime($data[6]) < strtotime($data[5])){
								$message[] = 'invalid exam end';
							}
						}

						if (!$isValidTitle){
							$message[] = "invalid title";
						}

						if (!$isValidDesc){
							$message[] = "invalid description";
						}
					}
					

					if (count($message) > 0){
						$errors[] = [
									'file' => 'course.csv',
									'line' => $line,
									'message' => $message
									];
						continue;
					}

					$courseObj = new Course($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
					$CourseDAO->add($courseObj);
					$course_processed++;
				}

				# Section table
				$header = fgetcsv($section);
				$line = 1;
				while(($data = fgetcsv($section)) != false ){
					$line++;
					$message = [];
					$data = trimData($data);
                    for ($i=0;$i<=7;$i++){
                        if($i == 0 or $i == 1){
                            $data[$i]=strtoupper(trim($data[$i]));
                            }
                            $data[$i]=trim($data[$i]);
                        if (strlen($data[$i])==0){
                            //check for empty cell
                            $message[]="blank $header[$i]";

                        }
                    }


					if (empty($message)){
						$isStart = checkTime($data[3]);
						$isEnd = checkTime($data[4]);
						$isValidInstr = checkChars($data[5], 100);
						$isValidVenue = checkChars($data[6], 100);
						$check_course = $CourseDAO->retrieveAllByCode($data[0]);
					
						if (!$check_course){
							$message[] = "invalid course";
						}else{
							if (strtoupper($data[1][0]) != 'S' || !in_array(substr($data[1], 1), array_map('toString',range('1', '99')), true)){
								$message[] = "invalid section";
							}  
						}

						if (!is_numeric($data[2]) || $data[2] >= 8){
							$message[] = 'invalid day';
						}

						if (!$isStart){
							$message[] = "invalid start";
						}else{
							if (!$isEnd){
								$message[] = "invalid end";
							}elseif ( strtotime($data[4]) <  strtotime($data[3])){
								$message[] = "invalid end";
							}
						}

						if (!$isValidInstr){
							$message[] = "invalid instructor";
						}

						if (!$isValidVenue){
							$message[] = "invalid venue";
						}

						if (!is_numeric($data[7]) || $data[7] <= 0){
							$message[] = "invalid size";
						}
					}
					
					

					if (count($message) > 0){
						$errors[] = [
									'file' => 'section.csv',
									'line' => $line,
									'message' => $message
									];
						continue;
					}
					
					$secObj = new Section($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
					$SectionDAO->add($secObj);
					$section_processed++;
				}

				# Prerequisite table
				$header = fgetcsv($prerequisite);
				$line = 1;
				while(($data = fgetcsv($prerequisite)) != false ){
					$line++;
					$message = [] ;
					$data = trimData($data);
					for ($i=0;$i<=1;$i++){
                        $data[$i]=strtoupper(trim($data[$i]));
                        if (strlen($data[$i])==0){
                            //check for empty cell
                            $message[]="blank $header[$i]";
                        }
                    }


					if (empty($message)){
						$check_course = $CourseDAO->retrieveAllByCode($data[0]);
						$check_prerequisite = $CourseDAO->retrieveAllByCode($data[1]) ;
					
						if(!$check_course) {
							$message[] = "invalid course";
						}

						if (!$check_prerequisite) {
							$message[] = "invalid prerequisite" ;
						}

					}
					

					if (count($message) > 0){
						$errors[] = [
									'file' => 'prerequisite.csv',
									'line' => $line,
									'message' => $message
									];
						continue;
					}

					$preObj = new Prerequisite($data[0], $data[1]);
					$PrerequisiteDAO->add($preObj);
					$prerequisite_processed++;
				}
				
				
				# Course_completed table
				$header = fgetcsv($course_completed);
				$line = 1;
				while(($data = fgetcsv($course_completed)) != false ){
					$line++;
					$message = [];
					$data = trimData($data);
					for ($i=0;$i<=1;$i++){
                        if($i == 1){
                            $data[$i]=strtoupper(trim($data[$i]));
                            }
                            $data[$i]=trim($data[$i]);
                        if (strlen($data[$i])==0){
                            //check for empty cell
                            $message[]="blank $header[$i]";
                        }
                    }

					if (empty($message)){
						$check_userid = $StudentDAO->retrieve($data[0]);
						$check_course = $CourseDAO->retrieveAllByCode($data[1]);
					
						if(!$check_userid) {
							$message[] = "invalid userid";
							if (!$check_course){
								$message[] = "invalid course";
							}
						}else{
							$user_completed = $CompleteDAO->retrieve($data[0]); 
							if ($check_course) {
								$prerequisite = $PrerequisiteDAO->retrieveByCode($data[1]) ; //array
								if (!empty($prerequisite)) {
									for ($i=0; $i<count($prerequisite); $i++) {
										if (!in_array($prerequisite[$i], $user_completed)) {
											$message[] = "invalid course completed";
											break;
										}
									}
								}
							}else {
								$message[] = "invalid course";
							}
						}
					}
					
					

					if (count($message) > 0){
						$errors[] = [
									'file' => 'course_completed.csv',
									'line' => $line,
									'message' => $message
									];
						continue;
					}


					$course_comObj = new Complete($data[0], $data[1]);
					$CompleteDAO->add($course_comObj);
					$course_completed_processed++;
				}
				
				# bid table
				$header = fgetcsv($bid);
				$line = 1;
				while(($data = fgetcsv($bid)) != false ){
					$line++;
					$message = [] ;
					$data = trimData($data);

					for ($i=0;$i<=3;$i++){
                        if($i == 2 or $i == 3){
                            $data[$i]=strtoupper(trim($data[$i]));
                            }
                            $data[$i]=trim($data[$i]);
                        if (strlen($data[$i])==0){
                            //check for empty cell
                            $message[]="blank $header[$i]";
                        }
					}
					
					if (empty($message)){
						$check_userid = $StudentDAO->retrieve($data[0]);
						$check_course = $CourseDAO->retrieveAllByCode($data[2]);
					
						if(!$check_userid) {
							$message[] = "invalid userid";
						}
						
						$is_2_dec = True;
						preg_match('^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$^', "$data[1]", $matches);
						if (!empty($matches)) {
							if ($matches[0] != $data[1]) {
								$is_2_dec = False;
							}
						}
						if (!is_numeric($data[1]) || $data[1] < 10.00 || !$is_2_dec) {
							$message[] = 'invalid amount' ;
						}
						
						if(!$check_course) {
							$message[] = "invalid course";
						}
						else {
							$check_section = $SectionDAO->retrieveAllByCode($data[2]) ;
							if (!in_array($data[3], $check_section)) {
								$message[] = "invalid section" ;
							}
							$course_school = $check_course->school;
							if ($check_userid){
								$student_school = $check_userid->school;
								if ($student_school != $course_school){
									$message[] = "not own school course";
								}
							}
						
						}
						
						if ($check_userid && $check_course && $check_section){
							$check_update = FALSE;
							$courses_bidded_in_bid = $BidDAO->retrieve($data[0]);
							foreach($courses_bidded_in_bid as $course_bid){
								if ($data[2] == $course_bid->course_code){
									$check_update = True;
									break;
								}
							}
							if (count($courses_bidded_in_bid) < 5 ){
							
								$bidding_course = $SectionDAO->retrieveAllByCodeAndSection($data[2], $data[3]);
								for($i=0; $i<count($courses_bidded_in_bid); $i++){
									$code = $courses_bidded_in_bid[$i]->course_code;
									$section_bid = $courses_bidded_in_bid[$i]->section;
									$section_bidded_fullinfo =  $SectionDAO->retrieveAllByCodeAndSection($code, $section_bid);
									$course_bidded_fullinfo = $CourseDAO->retrieveAllByCode($code);

									if ($section_bidded_fullinfo->day == $bidding_course->day && ($check_course->course_code != $course_bidded_fullinfo->course_code)) {

										if (($bidding_course->start < $section_bidded_fullinfo->start && $bidding_course->end > $section_bidded_fullinfo->start)
										|| ($bidding_course->start > $section_bidded_fullinfo->start && $bidding_course->start < $section_bidded_fullinfo->end)
										|| ($bidding_course->start == $section_bidded_fullinfo->start)
										&& (!in_array('class timetable clash', $message))){
											$message[] = 'class timetable clash';
										}
									} 

									if ($check_course->exam_date == $course_bidded_fullinfo->exam_date && ($check_course->course_code != $course_bidded_fullinfo->course_code)) {
										if (($check_course->exam_start < $course_bidded_fullinfo->exam_start && $check_course->exam_end > $course_bidded_fullinfo->exam_start)
         								|| ($check_course->exam_start > $course_bidded_fullinfo->exam_start && $check_course->exam_start < $course_bidded_fullinfo->exam_end )
       									  || ($check_course->exam_start == $course_bidded_fullinfo->exam_start)
       									  && (!in_array('exam timetable clash', $message)) ){
											$message[] = 'exam timetable clash';
										}
									} 
								

									// if ($section_bidded_fullinfo->day == $bidding_course->day && $section_bidded_fullinfo->start == $bidding_course->start
									// 	&& !in_array('class timetable clash', $message)){
									// 	$message[] = 'class timetable clash';
									// }
							
								}

							}
								$user_completed = $CompleteDAO->retrieve($data[0]) ; 	
							if (in_array($data[2], $user_completed)) {
								$message[] = "course completed";
							}

							
							$prerequisite = $PrerequisiteDAO->retrieveByCode($data[2]) ; //array
							if (!empty($prerequisite)) {
								for ($i=0; $i<count($prerequisite); $i++) {
									if (!in_array($prerequisite[$i], $user_completed)) {
										$message[] = "incomplete prerequisites";
										break;
									}
								}
							}

						
							
							if (count($courses_bidded_in_bid) >= 5){
							
								if (!$check_update){
									$message[] = "section limit reached";
								}      
							}					
						}

						$updated_edollar = 0;
						if(count($message) == 0){
						
							if($check_update){
								$prev_bid = $BidDAO->retrieveBidByUseridCourse($data[0], $data[2]);
								$prev_amount = $prev_bid->amount;
								$updated_edollar = $check_userid->edollar + $prev_amount;
							
							}
						}
						if ($check_userid){
							if ($updated_edollar == 0){
								$updated_edollar =  $check_userid->edollar;
							}
							if ($updated_edollar < $data[1]){
								$message[] = "not enough e-dollar";
							}
							
						}
					}
					
					

					if (count($message) > 0){
						$errors[] = [
									'file' => 'bid.csv',
									'line' => $line,
									'message' => $message
									];
						continue;
					}

					
					if ($check_update){
						$BidDAO->DropBid($prev_bid->userid, $prev_bid->course_code);
						
					}
					   
					$updated_edollar = $updated_edollar - $data[1];
					$StudentDAO->updateEdollar($data[0], $updated_edollar);
					$bidObj = new Bid($data[0], $data[1], $data[2], $data[3]);
					$BidDAO->add($bidObj);					
					$bid_processed++;
				}

			}
		}
	}

	# Sample code for returning JSON format errors. remember this is only for the JSON API. Humans should not get JSON errors.

	if (!isEmpty($errors))
	{	
		if (in_array("input files not found", $errors)){
			$result = [ 
				"status" => "error",
				"error" => $errors
			];
		}else{
			$sortclass = new Sort();
			$file=array_column($errors,"file");
			$lines=array_column($errors,"line");
			array_multisort($file,SORT_ASC,$lines,SORT_ASC,$errors);
			$result = [ 
				"status" => "error",
					"num-record-loaded" => [
						["bid.csv" => $bid_processed],
						["course.csv" => $course_processed],
						["course_completed.csv" => $course_completed_processed],
						["prerequisite.csv" => $prerequisite_processed],
						["section.csv" => $section_processed],
						["student.csv" => $student_processed]
				],
				"error" => $errors
			];
		}
	}

	else
	{	
		$result = [ 
			"status" => "success",
			"num-record-loaded" => [
				["bid.csv" => $bid_processed ], 
				["course.csv" => $course_processed],
				["course_completed.csv" => $course_completed_processed],
				["prerequisite.csv" => $prerequisite_processed],
				["section.csv" => $section_processed],
				["student.csv" => $student_processed]
			]
		];
	}
	return $result;



}
?>