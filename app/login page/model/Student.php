<?php 
class Student {
    public $userid;
    public $password;
    public $name;
    public $school;
    public $edollar;
    
    function __construct($userid,$password,$stud_name,$school,$edollar){
    $this->userid = $userid;
      $this->password = $password;
      $this->stud_name = $stud_name;
      $this->school = $school;
      $this->edollar = $edollar;
    }

    public function getUserID() {
        return $this->userid;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getName() {
        return $this->stud_name;
    }
    public function getSchool() {
        return $this->school;
    }
    public function getEdollar() {
        return $this->edollar;
    }

   
}
?>