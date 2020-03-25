<?php

class BidDAO {
    
    public  function retrieve($userid) {
        $sql = 'select * from bid where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] =  new Bid($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result;
    }

    public  function retrieveAll() {
        $sql = 'select * from bid ';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] =  new Bid($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result;
    }


    public function add($bid) {
        $sql = "INSERT IGNORE INTO bid (userid, amount, course_code, section) VALUES (:userid, :amount, :course_code, :section)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_STR);
        $stmt->bindParam(':course_code', $bid->course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }



    public function DropSpecificBid($userid,$section, $course_code) {
       $connMgr = new ConnectionManager();           
       $conn = $connMgr->getConnection();

       $sql = 'DELETE FROM bid WHERE userid=:userid and section=:section and course_code=:course_code';      
       
      
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

   public function DropBid($userid, $course_code) {
    $connMgr = new ConnectionManager();           
    $conn = $connMgr->getConnection();

    $sql = 'DELETE FROM bid WHERE userid=:userid and course_code=:course_code';      
    
   
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':userid',$userid, PDO::PARAM_STR);
    $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
    $isDeleteOk = False;
    if ($stmt->execute()) {
        $isDeleteOk = True;
    }

    return $isDeleteOk;
}

    //  public function update($user) {
    //     $sql = 'UPDATE user SET gender=:gender, password=:password, name=:name WHERE username=:username';      
        
    //     $connMgr = new ConnectionManager();           
    //     $conn = $connMgr->getConnection();
    //     $stmt = $conn->prepare($sql);
        
    //     $user->password = password_hash($user->password,PASSWORD_DEFAULT);

    //     $stmt->bindParam(':username', $user->username, PDO::PARAM_STR);
    //     $stmt->bindParam(':gender', $user->gender, PDO::PARAM_STR);
    //     $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);
    //     $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);

    //     $isUpdateOk = False;
    //     if ($stmt->execute()) {
    //         $isUpdateOk = True;
    //     }

    //     return $isUpdateOk;
    // }
	
	 public function removeAll() {
        $sql = 'TRUNCATE TABLE bid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
	public function retrieveAllClasses() {
    $sql = 'select course_code, section from bid';

    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

        
    $stmt = $conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $result = [];


    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $class = [$row['course_code'], $row['section']];

        if (!in_array($class,$result)){
            $result[] = [$row['course_code'], $row['section']];
        }
        
    }
    return $result;
}


public function retrieveAllBidsbyClasses($course_code, $section){


    $sql = 'select * from bid where course_code =:course_code && section =:section';

    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

        
    $stmt = $conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
    $stmt->bindParam(':section', $section, PDO::PARAM_STR);
    $stmt->execute();

    $result = [];


    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] =  new Bid($row['userid'], $row['amount'], $row['course_code'], $row['section']);
    }


    return $result;
    }


    public function count_no($section,$course_code) {
        $connMgr = new ConnectionManager();           
        $conn = $connMgr->getConnection();

        $sql = 'SELECT count(*) FROM bid WHERE section=:section AND course_code=:course_code';
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

    public  function retrieve_all() {
        $sql = 'SELECT *  FROM bid';
        
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

    public function retrieveAmountOfBid($username, $course_code, $section) {
        $sql = "Select amount from bid where userid=:username and course_code=:course_code and section=:section";
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
    public function updateStatusSuccess($userid, $course_code, $section) {
        $sql = "update bid set status='success' where userid=:userid and course_code=:course_code and section=:section";
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);

        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();



        $isUpdateOk = False;
        if ($stmt->execute()) {
           $isUpdateOk = True;
        }
   
       return $isUpdateOk;
    }

    public  function retrieveBidByUseridCourse($userid, $course_code) {
        $sql = 'select * from bid where userid=:userid && course_code=:course_code';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->execute();

 
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Bid($row['userid'], $row['amount'], $row['course_code'], $row['section']);
        }
        return $result;
    }
    public function updateStatusFail($userid, $course_code, $section) {
        $sql = "update bid set status='fail' where userid=:userid and course_code=:course_code and section=:section";
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);

        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);


        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();



        $isUpdateOk = False;
        if ($stmt->execute()) {
           $isUpdateOk = True;
        }
   
       return $isUpdateOk;
    }








    public function getStatus($username, $course_code, $section) {
        $sql = "Select status from bid where userid=:username and course_code=:course_code and section=:section";
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();



        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['status'];
        }
        
    }
    
}




