<?php
require 'function.php';

if ($_GET['sid']) {
	$num = trim($_GET['sid']);
	if (preg_match('/(09)|(10)|(11)\d{7}/', $num) == 0) {
		echo json_encode('Student ID Error!');
	} else {
		echo json_encode(query($num));
	}
}


?>