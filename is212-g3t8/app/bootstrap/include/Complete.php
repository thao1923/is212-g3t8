<?php

class Complete {
    // property declaration
    public $userid;    
    public $course_code;
    
    
    public function __construct($userid='', $course_code='') {
        $this->userid = $userid;
        $this->course_code = $course_code;
    }
}

?>