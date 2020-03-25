<?php

class Section {
    // property declaration
    public $course_code;    
    public $section;
    public $day;
    public $start;
    public $end;
    public $instructor;
    public $venue;
    public $size;
	
	
    public function __construct($course_code='', $section='', $day='', $start='',
    $end='', $instructor='', $venue='', $size='') {
        $this->course_code = $course_code;
        $this->section = $section;
        $this->day = $day;
        $this->start = $start;
        $this->end = $end;
        $this->instructor = $instructor;
        $this->venue = $venue;
        $this->size = $size;
    }
}

?>