<?php

class SectionDAO {
    
    public  function retrieveAllByCode($code) {
        $sql = 'SELECT *  FROM section WHERE `course_code` = :code';
        
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch()) {
            $result[] =  $row['section'];
        }
            
                 
        return $result;
    }
  
    public  function retrieveAll() {
        $sql = 'SELECT *  FROM section';
        
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch()) {
            $result[] = new Section($row['course_code'], $row['section'], $row['day'], $row['start'],
            $row['end'], $row['instructor'], $row['venue'], $row['size']);
        }
            
                 
        return $result;
    }

    public  function retrieveAllByCodeAndSection($code, $section) {
        $sql = 'SELECT *  FROM section WHERE `course_code` = :code && `section` = :section' ;
        
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();


        while($row = $stmt->fetch()) {
            return new Section($row['course_code'], $row['section'], $row['day'], $row['start'],
            $row['end'], $row['instructor'], $row['venue'], $row['size']);
        }
            
                 
    }
  
    public function add($section) {
        $sql = 'INSERT INTO section (course_code, section, day, start,
        end, instructor, venue, size) VALUES (:course_code, :section, :day, 
        :start, :end, :instructor, :venue, :size)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':course_code', $section->course_code, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section->section, PDO::PARAM_STR);
        $stmt->bindParam(':day', $section->day, PDO::PARAM_STR);
        $stmt->bindParam(':start', $section->start, PDO::PARAM_STR);
        $stmt->bindParam(':end', $section->end, PDO::PARAM_STR);
        $stmt->bindParam(':instructor', $section->instructor, PDO::PARAM_STR);
        $stmt->bindParam(':venue', $section->venue, PDO::PARAM_STR);
        $stmt->bindParam(':size', $section->size, PDO::PARAM_STR);

        
        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
        
    // public function remove($name) {
    //     $sql = 'DELETE FROM pokemon WHERE name = :name';
        
    //     $connMgr = new ConnectionManager();
    //     $conn = $connMgr->getConnection();
        
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        
    //     $stmt->execute();
    //     $count = $stmt->rowCount();
    // }

    public function removeAll() {
        $sql = 'TRUNCATE TABLE section';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
    public  function retrieveSizeByCodeAndSection($code, $section) {
        $sql = 'SELECT *  FROM section WHERE `course_code` = :code && `section` = :section' ;
        
            
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
    


        public function updateSize($section,$course_code,$size) {
        $connMgr = new ConnectionManager();           
        $conn = $connMgr->getConnection();

        $sql = 'UPDATE section SET size=:size WHERE section=:section AND course_code=:course_code' ;      
        
       
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':section',$section, PDO::PARAM_STR);
        $stmt->bindParam(':course_code',$course_code, PDO::PARAM_STR);
        $stmt->bindParam(':size',$size, PDO::PARAM_STR);

        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }
    
        return $isUpdateOk;
    }
    public function retrieveAllpossibleClasses() {
        $sql = 'select course_code, section from section';
    
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
    

}
