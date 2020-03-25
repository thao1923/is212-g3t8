<?php

class Round_noDAO {
    
    public function add_round($round_no) {
        $sql = "INSERT IGNORE INTO round_no (round_no) VALUES (:round_no)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':round_no', $round_no, PDO::PARAM_STR);
    

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }


    public function update_round($round_no){


        $sql = "UPDATE IGNORE round_no SET round_no =:round_no"  ;
        $connMgr = new ConnectionManager(); 
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':round_no', $round_no, PDO::PARAM_STR);



        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;


    }
        
    public  function retrieve() {
        $sql = 'SELECT *  FROM round_no';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
  
        $stmt->execute();

        while($row = $stmt->fetch()) {
         
            return  ($row['round_no']);
        }

    }

    public function removeAll() {
        $sql = 'TRUNCATE TABLE round_no';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
}

?>