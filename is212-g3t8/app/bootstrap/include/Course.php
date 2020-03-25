<?php

class Course {
    // property declaration
    public $course_code;    
    public $school;
    public $title;
    public $description;
    public $exam_date;
    public $exam_start;
    public $exam_end;
	
	
    public function __construct($course_code='', $school='', $title='', $description='',
    $exam_date='', $exam_start='', $exam_end='') {
        $this->course_code = $course_code;
        $this->school = $school;
        $this->title = $title;
        $this->description = $description;
        $this->exam_date = $exam_date;
        $this->exam_start = $exam_start;
        $this->exam_end = $exam_end;
    }
}

?>