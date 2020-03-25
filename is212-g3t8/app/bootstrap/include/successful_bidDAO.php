<?php
class successful_bidDAO{
    public function add_successful($userid, $amount, $course_code, $section, $a_round) {
        $sql = "INSERT IGNORE INTO successful_bid (userid, amount, course_code, section, round) VALUES (:userid, :amount, :course_code, :section, :a_round)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':a_round', $a_round, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public function retrieveAll() {
        $sql = "Select * from successful_bid";
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new successful_bid ($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result; 
    }

    public function retrieveByUsername($username) {
        $sql = "Select * from successful_bid where userid=:username";
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new successful_bid ($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result; 
    }

    public function retrieveByCourseSection($course_code, $section) {
        $sql = "Select * from successful_bid where course_code=:course_code and section=:section";;
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new successful_bid ($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result; 
    }

    public function retrieveAmountOfBid($username, $course_code, $section) {
        $sql = "Select amount from successful_bid where userid=:username and course_code=:course_code and section=:section";
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();



        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['amount'];
        }
        
    }

    

    public function drop_bid($bid){
        
        $sql = "DELETE from successful_bid where userid = :userid AND amount = :amount AND course_code = :course_code AND section = :section";
      

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_STR);
        $stmt->bindParam(':course_code', $bid->course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
        $isdeleteOk = false;
        if ($stmt->execute()) {
            $isdeleteOK = True;
        }
        return $isdeleteOk;



    }

    
    public  function retrieve_userid_amount() {
        $sql = 'SELECT userid,amount  FROM successful_bid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $stmt->execute();
        $return_list = [];

        while($row = $stmt->fetch()) {
            $return_list[] = [$row['userid'], $row['amount']];
        }

        return ($return_list);

    }
    public function count_no_success($section,$course_code) {
        $connMgr = new ConnectionManager();           
        $conn = $connMgr->getConnection();

        $sql = 'SELECT count(*) FROM successful_bid where section = :section and course_code = :course_code';
        //UPDATE section SET size=:size WHERE section=:section AND course_code=:course_code' ;      
        
       
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':section',$section, PDO::PARAM_STR);
        $stmt->bindParam(':course_code',$course_code, PDO::PARAM_STR);

        $occupied = 0;
        $stmt->execute();
        while($row = $stmt->fetch()) {
            $occupied = $row['count(*)'];
        }
    
        return $occupied;
    }
    public  function retrieve_successfulBid($userid) {
        $sql = 'SELECT *  FROM successful_bid WHERE userid = :userid' ;
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);

        $stmt->execute();
        $return_list = [];

        while($row = $stmt->fetch()) {
            $return_list[] = [$row['userid'],$row['amount'],$row['course_code'], $row['section']];
        }

        return ($return_list);

    }
    public  function retrieve_all() {
        $sql = 'SELECT *  FROM successful_bid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);

        $stmt->execute();
        $return_list = [];

        while($row = $stmt->fetch()) {
            $return_list[] = [$row['userid'],$row['amount'],$row['course_code'], $row['section']];
        }

        return ($return_list);

    }


    #retrieve by student id

    public  function retrieveByUserID($userid) {
        $sql = 'select * from successful_bid where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] =  new successful_bid($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result;
    }

    public  function successRound1() {
        $sql = 'select * from successful_bid where round=1.0';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] =  new successful_bid($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result;
    }

    public  function successRound2() {
        $sql = 'select * from successful_bid where round=2.0';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] =  new successful_bid($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result;
    }

    public  function successRound2CourseSection($course_code, $section) {
        $sql = 'select * from successful_bid where round=2.0 && course_code=:course_code && section=:section';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] =  new successful_bid($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result;
    }


    #delete
    public function DropSpecificSection($userid,$section, $course_code) {
       $connMgr = new ConnectionManager();           
       $conn = $connMgr->getConnection();

       $sql = 'DELETE FROM successful_bid WHERE userid=:userid and section=:section and course_code=:course_code';      
       
      
       $stmt = $conn->prepare($sql);

       $stmt->bindParam(':userid',$userid, PDO::PARAM_STR);
       $stmt->bindParam(':section', $section, PDO::PARAM_STR);
       $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
       $isDeleteOk = False;
       if ($stmt->execute()) {
           $isDeleteOk = True;
       }
   
       return $isDeleteOk;
   }
   	
	 public function removeAll() {
        $sql = 'TRUNCATE TABLE successful_bid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    






}
?>