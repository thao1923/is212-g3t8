<?php

class successful_bid {
    // property declaration
    public $userid;  
    public $amount;  
    public $course_code;
    public $section;
    
    public function __construct($userid='', $amount='', $course_code='', $section='') {
        $this->userid = $userid;
        $this->amount = $amount;
        $this->course_code = $course_code;
        $this->section = $section;
    }
}

?>