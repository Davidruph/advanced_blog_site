<?php 
date_default_timezone_set('Africa/Lagos');
$time = date("2021-12-20 24:52:16"); //now

function timeago($time, $tense='ago'){
	static $periods = array('year', 'month', 'day', 'hour', 'minute', 'second');

	if (!(strtotime($time)>0)) {
		return trigger_error("wrong time format: '$time'", E_USER_ERROR);
	}

	$now = new DateTime('now');
	$time = new DateTime($time);

	$diff = $now->diff($time)->format('%y %m %d %h %i %s');
	$diff = explode(' ', $diff);
	$diff = array_combine($periods, $diff);
	$diff = array_filter($diff);

	$period = key($diff);
	$value = current($diff);
	if (!$value) {
		$period = '';
		$tense = '';
		$value = 'just now';
	}else{
		if ($period == 'day' && $value >= 7) {
			$period = 'week';
			$value = floor($value/7);
		}if ($value > 1) {
			$period .='s';
		}
	}

	return "$value $period $tense";
}
$timeago = timeago($time);

echo $timeago;

// function get_time_ago_conversion($value)
// {	
// 	date_default_timezone_set('Africa/Lagos');
// 	list($date, $time) = explode(' ', $value);
// 	list($year, $month, $day) = explode('-', $date);
// 	list($hour, $minutes, $seconds) = explode(':', $time);

// 	$unit_timestamp = mktime($hour, $minutes, $seconds, $month, $day, $year);

// 	return $unit_timestamp;
// }

// function convert_ago_format($timestamp){
// 	date_default_timezone_set('Africa/Lagos');
// 	$difference_between_current_time_and_timestamp = time() - $timestamp;
// 	$arrayperiod = ["sec", "min", "hr", "day", "week", "month", "year", "decade"];
// 	$arraynumbers = ["60", "60", "24", "7", "4.35", "12", "10"];

// 	for($iterator = 0; $difference_between_current_time_and_timestamp >= $arraynumbers[$iterator]; $iterator++) { 
// 		$holdVal = $difference_between_current_time_and_timestamp / $arraynumbers[$iterator];
// 		$holdValround = round($holdVal);

// 		if($holdValround !== 1){
// 			$arrayperiod[$iterator].="s";
// 		}

// 		$finaloutput = "$holdValround $arrayperiod[$iterator]";

// 		return "Posted ".$finaloutput." ago";
			
// 	}

// }

// $unix_timestamp = get_time_ago_conversion($posted_at);

// echo convert_ago_format($unix_timestamp);
?>