<h2 class="hidden">多学号批量查询</h2>
<div class="form"><form id="batchbox" name="gym2" action="<?php echo $_SERVER ['PHP_SELF'] . '?m=1&tab=2';?>" method="post">
	<input type="tel" name="num1" value="<?php if (isset($_POST ['num1'])) echo $_POST ['num1'];?>"	id="num1" placeholder="起始学号" pattern="(10|11|12)\d{7}" required="required" />
	<input type="tel" name="num2" value="<?php if (isset($_POST ['num2']))	echo $_POST ['num2'];?>" id="num2" placeholder="结束学号" pattern="(10|11|12)\d{7}" required="required" />
	<input class="submit" type="submit" name="submit" value="查询" />
</form></div>
<div class="loadingspinner">加载中</div>
<div class="result">
<?php

if (isset($_POST ['num1']) && isset($_POST ['num2'])) {
	$error = array ();
	$num1 = trim($_POST ['num1']);
	$num2 = trim($_POST ['num2']);
	if (preg_match('/(10)|(11)|(12)\d{7}/', $num1) == 0) {
		$error [] = '第一个学号输入有误！';
	}
	if (preg_match('/(10)|(11)|(12)\d{7}/', $num2) == 0 || $num2 < $num1) {
		$error [] = '第二个学号输入有误！';
	}
	if ($num2 - $num1 > 30) {
		$error [] = '学号范围过大，请修改至30以内！';
	}
	if (! count($error)) {
		echo '<ul>';
		for($i = $num1; $i <= $num2; $i ++) {
			if (strlen($i)==8) {
				$i = '0' . $i;
			}
			$results = query($i);
			echo "<li>学号：$i";
			show_list($results);
			echo '</li>';
		}
		echo '</ul>';
	} else {
		foreach ( $error as $e ) {
			echo $e . '<br />';
		}
	}
}

?>
<table id="batchdetails"></table>
<div id="pager2"></div>
</div>
