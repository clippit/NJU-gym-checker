<?php
require 'function.php';

/*if (strpos($_SERVER['HTTP_REFERER'], "dayanjia.com") === FALSE) {
	header('HTTP/1.1 403 Forbidden');
	die("Unauthorized access forbidden!");
}*/

if (isset($_POST['num'])) {
	$num = trim($_POST['num']);
	if (preg_match('/(10)|(11)|(12)\d{7}/', $num) == 0) {
		echo json_encode('Student ID Error!');
	} else {
		echo json_encode(query($num));
	}
}

if (isset($_POST['secrets'])) {
	echo json_encode(get_statics());
}


?>