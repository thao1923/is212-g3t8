<?php

class CompleteDAO {
    
    public  function retrieve($userid) {
        $sql = 'select * from course_completed where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] =  $row['course_code'];
        }
        return $result;
    }

    public  function retrieveAll() {
        $sql = 'select * from course_completed';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Complete($row['userid'], $row['course_code']);
        }
        return $result;
    }

    public function add($course_completed) {
        $sql = "INSERT IGNORE INTO course_completed (userid, course_code) VALUES (:userid, :course_code)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $course_completed->userid, PDO::PARAM_STR);
        $stmt->bindParam(':course_code', $course_completed->course_code, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
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
        $sql = 'TRUNCATE TABLE course_completed';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


