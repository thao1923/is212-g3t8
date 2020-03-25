<?php

class StudentDAO {
    
    public  function retrieve($userid) {
        $sql = 'SELECT *  FROM student WHERE `userid` = :userid';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->execute();

        while($row = $stmt->fetch()) {
            return new Student($row['userid'], $row['password'], $row['name'], 
            $row['school'], $row['edollar']);
        }

    }

    public  function retrieveAll() {
        $sql = 'select * from student';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Student ($row['userid'], $row['password'], $row['name'], 
                        $row['school'], $row['edollar']);
        }
        return $result;
    }

    public function add($student) {
        $sql = "INSERT IGNORE INTO student (userid, password, name, school, edollar) VALUES (:userid, :password, :name, :school, :edollar)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $student->userid, PDO::PARAM_STR);
        $stmt->bindParam(':password', $student->password, PDO::PARAM_STR);
        $stmt->bindParam(':name', $student->name, PDO::PARAM_STR);
        $stmt->bindParam(':school', $student->school, PDO::PARAM_STR);
        $stmt->bindParam(':edollar', $student->edollar, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    #update Edollar for deduction
    public function updateEdollar($userid,$edollar) {
             $connMgr = new ConnectionManager();           
            $conn = $connMgr->getConnection();

            $sql = 'UPDATE student SET edollar=:edollar WHERE userid=:userid';      
            
           
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':userid',$userid, PDO::PARAM_STR);
            $stmt->bindParam(':edollar', $edollar, PDO::PARAM_STR);
    
            $isUpdateOk = False;
            if ($stmt->execute()) {
                $isUpdateOk = True;
            }
        
            return $isUpdateOk;
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
        $sql = 'TRUNCATE TABLE student';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    

    public function retrieve_edollar ($userid){
        $sql = 'SELECT *  FROM student WHERE `userid` = :userid';
        
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->execute();

        $result = 0;

        while($row = $stmt->fetch()) {
            $result =  $row['edollar'];
        }
        return $result;
            
                 
    }
	
}


