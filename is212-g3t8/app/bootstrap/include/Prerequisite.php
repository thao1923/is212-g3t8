<?php

class Prerequisite {
    // property declaration
    public $course_code;    
    public $prerequisite;
	
	
    public function __construct($course_code='', $prerequisite='') {
        $this->course_code = $course_code;
        $this->prerequisite = $prerequisite;
    }
}

?>