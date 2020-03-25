<?php
class Sort {
	function title($a, $b)
	{
	    return strcmp($a->title,$b->title);
	}

	function course_code($a, $b)
	{
	    return strcmp($a->course_code,$b->course_code);
	}

	function section($a, $b)
	{
	    return strcmp($a->section,$b->section);
	}

	function amount($a, $b)
	{
	    return strcmp($b->amount, $a->amount);
	}

	function prerequisite($a, $b)
	{
	    return strcmp($a->prerequisite,$b->prerequisite);
	}

	function userid($a, $b)
	{
	    return strcmp($a->userid,$b->userid);
	}

	function bootstrap($a, $b)
	{
		return strcmp(end($b), end($a));
	}

	function message($a, $b)
	{
		return strcmp($a, $b);
	}
	
	function end_message($a, $b)
	{
		$a = substr(strrchr($a, ' '), 1);
    	$b = substr(strrchr($b, ' '), 1);
		return strcmp($a, $b);
	}
	

	function sort_it($list,$sorttype)
	{
		usort($list,array($this,$sorttype));
		return $list;
	}
	function username($a, $b)
 	{
    	 return strcmp($a['userid'],$b['userid']);
 	}

 	function amount_arr($a, $b)
 	{
    	 return strcmp($a['amount'],$b['amount']);
 	}

}

?>