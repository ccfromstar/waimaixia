<!doctype html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title><?php echo $this->pageTitle;?></title>

	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">

	<!-- No Baidu Siteapp-->
	<meta http-equiv="Cache-Control" content="no-siteapp"/>

	<!-- Add to homescreen for Chrome on Android -->
	<meta name="mobile-web-app-capable" content="yes">

	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="Amaze UI"/>

	<!-- Tile icon for Win8 (144x144 + tile color) -->
	<meta name="msapplication-TileColor" content="#0e90d2">

	<link rel="stylesheet" href="/resource/css/amazeui.min.css">
	<link rel="stylesheet" href="/resource/css/app.css">

	<!--[if (gte IE 9)|!(IE)]><!-->
	<script src="/resource/js/jquery.min.js"></script>
	<!--<![endif]-->
	<!--[if lte IE 8 ]>
	<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
	<script src="/resource/js/amazeui.ie8polyfill.min.js"></script>
	<![endif]-->
	<script src="/resource/js/amazeui.min.js"></script>
	<link rel="stylesheet" href="/resource/css/amazeui.datetimepicker.css">
	<script src="/resource/js/amazeui.datetimepicker.js"></script>
</head>
<body <?php
	$cname = Yii::app()->controller->id;
	$aname = $this->getAction()->getId();

	if($cname=='site'){
		if($aname=='login')
			echo 'class="login_bj"';
	}elseif($cname=='member'){
		switch($aname){
			case 'orderDetail':
				echo 'class="order_detail_bj"';
				break;

			case 'index':
			case 'orders':
			case 'tickets':
				echo 'class="userbg"';
				break;

			case 'addr':
				echo 'class="my_address_bj"';
				break;

			case 'getTicket':
				echo 'class="userbg2"';
				break;

			default:
				break;
		}
	}
?>>

<?php
	echo $content;
	Yii::app()->clientScript->registerScriptFile('/resource/js/dateutil.js',CClientScript::POS_END);
?>
</body>
</html>