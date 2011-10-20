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
		$datetime = strtotime($data->find('td', 3)->plaintext . ' ' . $time);
		$results[] = array('datetime'=>$datetime, 'type'=>$type);
		//$results [] = $data->find('td', 3)->plaintext . " $type " . str_replace('.', ':', $data->find('td', 4)->plaintext);
	}
	usort($results, "sort_helper");
	$count = count($result_set);
	return array('list'=>$results,'count'=>$count);
}

function sort_helper($a, $b) {
	return $a ['datetime'] - $b ['datetime'];
}
function show_list($results) {
	echo '<ul>';
	if (count($results)>0) {
		foreach ( $results['list'] as $data ) {
			echo '<li>' . date('n月j日 H:i', $data['datetime']) . ' ' . $data['type'] . '</li>';
		}
		echo '<li>共有 ' . $results['count'] . ' 条记录！</li>';
	} else {
		echo '<li>没有该学号的相关记录！</li>';
	}
	echo '</ul>';
}
?>