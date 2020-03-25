<?php

class PrerequisiteDAO {
    
    /**
     * Get all the possible pokemon types.
     * 
     * @return array of String
     */
    public function retrieveAll() {
        $sql = 'SELECT * FROM prerequisite ORDER BY course_code';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();


        $arr = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arr[] = new Prerequisite($row['course_code'], $row['prerequisite']);
        }
        return $arr;
    }

    public  function retrieveByCode($code) {
        $sql = 'select * from prerequisite where course_code=:code';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] =  $row['prerequisite'];
        }

        return $result;
    }
    
    public function add($prerequisite) {
        $sql = 'INSERT INTO prerequisite (course_code, prerequisite) VALUES (:course_code, :prerequisite)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':course_code', $prerequisite->course_code, PDO::PARAM_STR);
        $stmt->bindParam(':prerequisite', $prerequisite->prerequisite, PDO::PARAM_STR);
        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
    
    public function removeAll() {
        $sql = 'TRUNCATE TABLE prerequisite';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    

}
