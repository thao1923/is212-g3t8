<?php 
class Admin{
    private $userid;
    private $password;

    function __construct($userid,$password){
        $this->userid = $userid;
        $this->password = $password;

    }

    public function getUserID() {
        return $this->userid;
    }
    public function getPassword() {
        return $this->password;
    }
   
}
?>