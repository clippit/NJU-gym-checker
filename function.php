<?php
require 'simple_html_dom.php';
date_default_timezone_set ( 'Asia/Shanghai' );

function query($num) {
	$html = @file_get_html('http://202.119.37.145:8080/SportWeb/gym/gymExercise/gymExercise_query_result_2.jsp?xh=' . $num);
	$result_set = $html->find('table table table tbody tr');
	array_shift($result_set);
	array_shift($result_set);
	if (count($result_set) == 0) {
		return array ();
	}
	$results = array ();
	foreach ( $result_set as $data ) {
		switch ($data->find('td', 2)->plaintext) {
			case '701' :
				$type = '早上刷卡';
				break;
			case '702' :
				$type = '下午刷卡';
				break;
		}
		$time = str_replace('.', ':', sprintf('%05.2f',$data->find('td', 4)->plaintext));
		//$datetime = strtotime($data->find('td', 3)->plaintext . ' ' . $time);
		$datetime = $data->find('td', 3)->plaintext . ' ' . $time;
		$results[] = array('datetime'=>$datetime, 'type'=>$type);
		//$results [] = $data->find('td', 3)->plaintext . " $type " . str_replace('.', ':', $data->find('td', 4)->plaintext);
	}
	usort($results, "sort_helper");
	$count = count($result_set);
	store_db($num, $count);
	return array('list'=>$results,'count'=>$count,'student_id'=>$num);
}

function sort_helper($a, $b) {
	return strtotime($a ['datetime']) - strtotime($b ['datetime']);
}

function store_db($num, $count) {
	$mysqli = conn();
	$ip = getRealIpAddr();
	$mysqli->query("INSERT INTO record (student_id, count, ip) VALUES ('$num', $count, '$ip')");
	$mysqli->close();
}

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function get_statics() {
	$mysqli = conn();
	$statics = array();
	$last24_sql = "SELECT count(*) AS last24_total, count(DISTINCT student_id) AS last24_people FROM record WHERE TIMESTAMPDIFF(SECOND,search_time,now())<=86400";
	if ( $last24 = $mysqli->query($last24_sql) ) {
		$last24_row = $last24->fetch_array(MYSQLI_ASSOC);
		$last24->close();
	}
	$global_sql = "SELECT sum(student_counts) AS global_total, max(counts) AS global_max_counts, count(counts) AS global_people, sum(counts) AS global_total_counts, left(avg(counts),4) AS global_average_counts, max(student_counts) AS global_max_student FROM (SELECT max(count) as counts, count(student_id) AS student_counts FROM record GROUP BY student_id) AS r";
	if ( $global = $mysqli->query($global_sql) ) {
		$global_row = $global->fetch_array(MYSQLI_ASSOC);
		$global->close();
	}
	$statics = array_merge($last24_row, $global_row);
	$mysqli->close();
	//print_r($statics);
	return $statics;
}

function conn() {
	$mysqli = new mysqli('localhost', 'root', 'root', 'gym');
	if (mysqli_connect_error())
		die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
	return $mysqli;
}

function show_list($results) {
	echo '<ul>';
	if (count($results)>0) {
		foreach ( $results['list'] as $data ) {
			echo '<li>' . $data['datetime'] . ' ' . $data['type'] . '</li>';
		}
		echo '<li>共有 ' . $results['count'] . ' 条记录！</li>';
	} else {
		echo '<li>没有该学号的相关记录！</li>';
	}
	echo '</ul>';
}
?>