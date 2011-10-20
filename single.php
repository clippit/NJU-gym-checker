<h2 class="hidden">单学号查询</h2>
<div class="form"><form name="gym" id="singlebox" action="<?php echo $_SERVER ['PHP_SELF'] . '?m=1&tab=1';?>" method="post">
<input id="num" type="tel" name="num" value="<?php if (isset($_POST ['num']))	echo $_POST ['num'];?>" placeholder="请输入学号" pattern="(09|10|11)\d{7}" required="required" />
<input class="submit" type="submit" name="submit" value="查询" />
</form></div>
<div class="loadingspinner">加载中</div>
<div class="result">
<?php
if (isset($_POST ['num'])) {
	$num = trim($_POST ['num']);
	if (preg_match('/(09)|(10)|(11)\d{7}/', $num) == 0) {
		$error = '学号格式有误！';
	}
	if (! isset($error)) {
		show_list(query($num));
	} else {
		echo $error;
	}
}
?>
<div class="summary">截至<span class="date"></span>，你已完成<span class="count"></span>次刷卡！</div>
<table id="singledetails"></table>
<div id="pager1"></div>
</div>
