<?php
if(!isset($_GET['m'])) {
	require_once('mobile_device_detect.php');
	mobile_device_detect(false,false,false,true,true,true,true,'http://dayanjia.com/gym/m/',false);
}

require 'function.php';
$isSingle = true;
$isBatch = $isSecrets = false;
if (isset($_GET['tab']) && $_GET['tab'] == 2) {
	$isBatch = true;
	$isSingle = false;
}
if (isset($_GET['tab']) && $_GET['tab'] == 3) {
	$isSecrets = true;
	$isSingle = false;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name="description" content="本站提供南京大学学生课外体育锻炼刷卡次数查询服务" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen">
<link rel="stylesheet" href="css/jquery-ui-1.8.11.custom.css" type="text/css" media="screen">
<link rel="stylesheet" href="css/ui.jqgrid.css" type="text/css" media="screen">
<link href="http://fonts.googleapis.com/css?family=Slackey:regular" rel="stylesheet" type="text/css" >
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
<![endif]-->
<title>南京大学体育锻炼刷卡查询 v2.0</title>
</head>
<body>
<div id="bg">
<div id="wrapper">
<header role="banner">
<h1 id="title">南京大学体育锻炼刷卡查询</h1>
<div id="ver">2.0</div>
<nav role="navigation">
<div id="menu" class="clearfix">
<ul class="clearfix">
	<li><a href="<?php echo $_SERVER ['PHP_SELF'] . '?tab=1';?>" class="<?php echo $isSingle?'active':''; ?>"><span>单学号查询</span></a></li>
	<li><a href="<?php echo $_SERVER ['PHP_SELF'] . '?tab=2';?>" class="<?php echo $isBatch?'active':''; ?>"><span>多学号批量查询</span></a></li>
	<li><a href="<?php echo $_SERVER ['PHP_SELF'] . '?tab=3';?>" class="<?php echo $isSecrets?'active':''; ?>"><span>不为人知的小秘密</span></a></li>
</ul>
</div>
</nav>
</header>

<div id="content">
<section><div id="single">
<?php include 'single.php'; ?>
</div></section>
<section><div id="batch">
<?php include 'batch.php'; ?>
</div></section>
<section><div id="secrets">
<?php include 'secrets.php'; ?>
</div></section>

</div> <!-- end of #content -->
<script type="text/javascript" src="js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="js/modernizr-1.7.min.js"></script>
<script type="text/javascript" src="js/grid.locale-cn.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>

</div> <!-- end of #wrapper -->
<footer role="contentinfo"><div id="footer">
<?php //<xn:share-button type="button_count_right" label="分享到人人"></xn:share-button> ?>
<a name="xn_share" type="button_count_right" href="#">分享到人人</a><script src="http://static.connect.renren.com/js/share.js" type="text/javascript"></script>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-8911375336850993";
/* 体育刷卡 */
google_ad_slot = "5943856160";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<div id="copyright">&copy; Clippit @NJU <a href="http://dayanjia.com" target="_blank" id="website">大眼夹的鸟巢</a></div>
<div id="hint">本站查询结果仅供参考，最终次数请以体育部数据为准。建议使用Chrome, Firefox, Safari等浏览器访问本站。</div>
</div></footer>
</div> <!-- end of #bg -->
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Feb41cc9c61f78bef304985e11825310f' type='text/javascript'%3E%3C/script%3E"));
</script>
<?php /*<script type="text/javascript" src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
  <script type="text/javascript">
    XN_RequireFeatures(["EXNML"], function()
    {
      XN.Main.init("9663e9e6ada24d25bff5abd0fc551cd2", "./xd_receiver.html");
    });
</script>*/ ?>
</body>
</html>
