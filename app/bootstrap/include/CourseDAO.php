<?php

class CourseDAO {

    public  function retrieveAll() {
        $sql = 'SELECT * FROM course ORDER BY `course_code`';
        
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();

        while($row = $stmt->fetch()) {
            $result[] = new Course($row['course_code'], $row['school'],
            $row['title'], $row['description'], $row['exam_date'],  $row['exam_start'],
            $row['exam_end']);
        }
            
                 
        return $result;
    }
    
    public  function retrieveAllByCode($code) {
        $sql = 'SELECT *  FROM course WHERE `course_code` = :code';
        
            
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->execute();

        while($row = $stmt->fetch()) {
            return new Course($row['course_code'], $row['school'],
            $row['title'], $row['description'], $row['exam_date'],  $row['exam_start'],
            $row['exam_end']);
        }

    }
  
    public  function retrieve($title) {
        $sql = 'SELECT * FROM course WHERE title like :%title%';
        
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->execute();

        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Course($row['course_code'], $row['school'],
            $row['title'], $row['description'], $row['exam_date'],  $row['exam_start'],
            $row['exam_end']);
        }
        
        return $result;
    }
  
  
    public function add($course) {
        $sql = 'INSERT INTO course (course_code, school, title, description,
        exam_date, exam_start, exam_end) VALUES (:course_code, :school, :title, 
        :description, :exam_date, :exam_start, :exam_end)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
         
        $stmt = $conn->prepare($sql); 

        $stmt->bindParam(':course_code', $course->course_code, PDO::PARAM_STR);
        $stmt->bindParam(':school', $course->school, PDO::PARAM_STR);
        $stmt->bindParam(':title', $course->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $course->description, PDO::PARAM_STR);
        $stmt->bindParam(':exam_date', $course->exam_date, PDO::PARAM_STR);
        $stmt->bindParam(':exam_start', $course->exam_start, PDO::PARAM_STR);
        $stmt->bindParam(':exam_end', $course->exam_end, PDO::PARAM_STR);

        
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
        $sql = 'TRUNCATE TABLE course';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
}
