<?php
require '../function.php';
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>南京大学体育锻炼刷卡查询</title>
</head>
<body>
<h1>南京大学体育锻炼刷卡查询 手机版</h1>

<!--[if lte IE 6]>
<p><strong>天哪，你居然还在使用IE6！你不怕被网页设计师砍死吗？快去<a href="http://www.microsoft.com/china/windows/internet-explorer/ie8howto.aspx">升级你的浏览器</a>吧！</strong></p>
<![endif]-->

<p>说明：此查询结果来自体育部官方，数据可能不全，仅供参考！</p>
<p>欲使用更多功能，获取更好效果，请使用电脑访问<a href="http://dayanjia.com/gym/?m=1">本站</a>。</p>

<h2>单学号查询</h2>
<form name="gym" action="<?php
echo $_SERVER ['PHP_SELF'];
?>"
	method="post">
<p><label for="num">学号</label><input type="text" name="num"
	value="<?php
	if (isset($_POST ['num']))
		echo $_POST ['num'];
	?>"
	id="num" /> <input type="submit" name='查询'></p>
</form>
<p>
<?php

if ($_POST ['num']) {
	$num = trim($_POST ['num']);
	if (preg_match('/(10)|(11)|(12)\d{7}/', $num) == 0) {
		$error = '学号格式有误！';
	}
	if (! isset($error)) {
		show_list(query($num));
	} else {
		echo $error;
	}
}

?>

</p>
<h2>批量查询</h2>
<form name="gym2" action="<?php
echo $_SERVER ['PHP_SELF'];
?>"
	method="post">
<p><label for="num1">学号从</label><input type="text" name="num1"
	value="<?php
	if (isset($_POST ['num1']))
		echo $_POST ['num1'];
	?>"
	id="num1" /> <label for="num2">到</label><input type="text" name="num2"
	value="<?php
	if (isset($_POST ['num2']))
		echo $_POST ['num2'];
	?>"
	id="num2" /> <input type="submit" name='查询'></p>
</form>
<p>
<?php

if (isset($_POST ['num1']) && isset($_POST ['num2'])) {
	$error = array ();
	$num1 = trim($_POST ['num1']);
	$num2 = trim($_POST ['num2']);
	if (preg_match('/(11)\(12)\d{7}/', $num1) == 0) {
		$error [] = '第一个学号输入有误！';
	}
	if (preg_match('/(11)|(12)\d{7}/', $num2) == 0 || $num2 < $num1) {
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
</p>
<p>&copy; Clippit @NJU <a href="http://dayanjia.com" target="_blank">大眼夹的鸟巢</a></p>

<?php

$GLOBALS['google']['client']='ca-mb-pub-8911375336850993';
$GLOBALS['google']['https']=read_global('HTTPS');
$GLOBALS['google']['ip']=read_global('REMOTE_ADDR');
$GLOBALS['google']['markup']='xhtml';
$GLOBALS['google']['output']='xhtml';
$GLOBALS['google']['ref']=read_global('HTTP_REFERER');
$GLOBALS['google']['slotname']='3436451288';
$GLOBALS['google']['url']=read_global('HTTP_HOST') . read_global('REQUEST_URI');
$GLOBALS['google']['useragent']=read_global('HTTP_USER_AGENT');
$google_dt = time();
google_set_screen_res();
google_set_muid();
google_set_via_and_accept();
function read_global($var) {
  return isset($_SERVER[$var]) ? $_SERVER[$var]: '';
}

function google_append_url(&$url, $param, $value) {
  $url .= '&' . $param . '=' . urlencode($value);
}

function google_append_globals(&$url, $param) {
  google_append_url($url, $param, $GLOBALS['google'][$param]);
}

function google_append_color(&$url, $param) {
  global $google_dt;
  $color_array = split(',', $GLOBALS['google'][$param]);
  google_append_url($url, $param,
                    $color_array[$google_dt % sizeof($color_array)]);
}

function google_set_screen_res() {
  $screen_res = read_global('HTTP_UA_PIXELS');
  if ($screen_res == '') {
    $screen_res = read_global('HTTP_X_UP_DEVCAP_SCREENPIXELS');
  }
  if ($screen_res == '') {
    $screen_res = read_global('HTTP_X_JPHONE_DISPLAY');
  }
  $res_array = split('[x,*]', $screen_res);
  if (sizeof($res_array) == 2) {
    $GLOBALS['google']['u_w']=$res_array[0];
    $GLOBALS['google']['u_h']=$res_array[1];
  }
}

function google_set_muid() {
  $muid = read_global('HTTP_X_DCMGUID');
  if ($muid != '') {
    $GLOBALS['google']['muid']=$muid;
     return;
  }
  $muid = read_global('HTTP_X_UP_SUBNO');
  if ($muid != '') {
    $GLOBALS['google']['muid']=$muid;
     return;
  }
  $muid = read_global('HTTP_X_JPHONE_UID');
  if ($muid != '') {
    $GLOBALS['google']['muid']=$muid;
     return;
  }
  $muid = read_global('HTTP_X_EM_UID');
  if ($muid != '') {
    $GLOBALS['google']['muid']=$muid;
     return;
  }
}

function google_set_via_and_accept() {
  $ua = read_global('HTTP_USER_AGENT');
  if ($ua == '') {
    $GLOBALS['google']['via']=read_global('HTTP_VIA');
    $GLOBALS['google']['accept']=read_global('HTTP_ACCEPT');
  }
}

function google_get_ad_url() {
  $google_ad_url = 'http://pagead2.googlesyndication.com/pagead/ads?';
  google_append_url($google_ad_url, 'dt',
                    round(1000 * array_sum(explode(' ', microtime()))));
  foreach ($GLOBALS['google'] as $param => $value) {
    if (strpos($param, 'color_') === 0) {
      google_append_color($google_ad_url, $param);
    } else if (strpos($param, 'url') === 0) {
      $google_scheme = ($GLOBALS['google']['https'] == 'on')
          ? 'https://' : 'http://';
      google_append_url($google_ad_url, $param,
                        $google_scheme . $GLOBALS['google'][$param]);
    } else {
      google_append_globals($google_ad_url, $param);
    }
  }
  return $google_ad_url;
}

$google_ad_handle = @fopen(google_get_ad_url(), 'r');
if ($google_ad_handle) {
  while (!feof($google_ad_handle)) {
    echo fread($google_ad_handle, 8192);
  }
  fclose($google_ad_handle);
}

?>

<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Feb41cc9c61f78bef304985e11825310f' type='text/javascript'%3E%3C/script%3E"));
</script>

</body>
</html>
