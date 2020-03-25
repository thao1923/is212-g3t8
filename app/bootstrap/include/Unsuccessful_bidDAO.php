<?php
class unsuccessful_bidDAO{
    public function add_unsuccessful($userid,$amount,$course_code,$section) {
        $sql = "INSERT IGNORE INTO unsuccessful_bid (userid, amount, course_code, section) VALUES (:userid, :amount, :course_code, :section)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
    public function drop_bid($bid){
        
        $sql = "DELETE from unsuccessful_bid where userid = :userid AND amount = :amount AND course_code = :course_code AND section = :section";
      

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
        $sql = 'SELECT userid,amount  FROM unsuccessful_bid';
        
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

        $sql = 'SELECT count(*) FROM unsuccessful_bid';
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
    public  function retrieve_unsuccessfulBid($userid) {
        $sql = 'SELECT *  FROM unsuccessful_bid WHERE userid = :userid' ;
        
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
        $sql = 'SELECT *  FROM unsuccessful_bid';
        
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
    public function removeAll() {
        $sql = 'TRUNCATE TABLE unsuccessful_bid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    

}
?>