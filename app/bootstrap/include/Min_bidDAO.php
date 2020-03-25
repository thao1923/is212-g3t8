<?php
class Min_bidDAO{
    public function add_min_bid($course_code, $section, $amount) {
        $sql = "INSERT IGNORE INTO min_bid (course_code, section, amount) VALUES (:course_code, :section, :amount)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
    public function update_min_bid($course_code, $section, $amount) {
       $connMgr = new ConnectionManager();           
       $conn = $connMgr->getConnection();

       $sql = 'UPDATE min_bid SET amount=:amount WHERE course_code=:course_code AND section=:section';      
       
      
       $stmt = $conn->prepare($sql);

       $stmt->bindParam(':amount',$amount, PDO::PARAM_STR);
       $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
       $stmt->bindParam(':section', $section, PDO::PARAM_STR);

       $isUpdateOk = False;
       if ($stmt->execute()) {
           $isUpdateOk = True;
       }
   
       return $isUpdateOk;
   }
   
   public  function retrieve_min_bid($course_code, $section) {
    $sql = 'SELECT course_code,section,amount  FROM min_bid WHERE course_code=:course_code AND section=:section' ;
    
    $connMgr = new ConnectionManager();      
    $conn = $connMgr->getConnection();
        
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
    $stmt->bindParam(':section', $section, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute();


    while($row = $stmt->fetch()){
        $return_value  = $row['amount'];
    }

    if (isset($return_value)){
        return $return_value;

    }else{
        return null;
    }
        
       

}
public function removeAll() {
    $sql = 'TRUNCATE TABLE min_bid';
    
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();
    
    $stmt = $conn->prepare($sql);
    
    $stmt->execute();
    $count = $stmt->rowCount();
}
public  function retrieveAllminBid() {
    $sql = 'SELECT *  FROM min_bid';
    
        
    $connMgr = new ConnectionManager();      
    $conn = $connMgr->getConnection();

    $stmt = $conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $result = array();

    while($row = $stmt->fetch()) {
        $result[] =  [$row['course_code'], $row['section'],$row['amount']];
    }
        
             
    return $result;
}
public  function retrieveMin_bidCodeAndSection($code, $section) {
    $sql = 'SELECT *  FROM min_bid WHERE `course_code` = :code && `section` = :section' ;
    
        
    $connMgr = new ConnectionManager();      
    $conn = $connMgr->getConnection();

    $stmt = $conn->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->bindParam(':section', $section, PDO::PARAM_STR);
    $stmt->execute();

    $result = 0;

    while($row = $stmt->fetch()) {
        $result =  $row['size'];
    }
    return $result;
        
             
}

   


 
}
?>