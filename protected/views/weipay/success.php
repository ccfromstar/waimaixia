<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>信息显示</title>

  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">

  <!-- No Baidu Siteapp-->
  <meta http-equiv="Cache-Control" content="no-siteapp"/>

  <link rel="icon" type="image/png" href="/resource/i/favicon.png">

  <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="icon" sizes="192x192" href="/resource/i/app-icon72x72@2x.png">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
  <link rel="apple-touch-icon-precomposed" href="/resource/i/app-icon72x72@2x.png">

  <!-- Tile icon for Win8 (144x144 + tile color) -->
  <meta name="msapplication-TileImage" content="/resource/i/app-icon72x72@2x.png">
  <meta name="msapplication-TileColor" content="#0e90d2">

  <link rel="stylesheet" href="/resource/css/amazeui.min.css">
  <link rel="stylesheet" href="/resource/css/app.css">
</head>
<body>
<!--在这里编写你的代码-->
<div class="ts_top">
	<a href="index.html" style="color:#fff"><span class="am-icon-angle-left am-icon-sm top_ret"></span></a>
	信息显示
	<!-- <a href="login.html" style="color:#fff"><span class="am-icon-user am-icon-sm top_right_more"></span></a> -->
	<div class="clear"></div>
</div>

<div class="ddwc_content">
    <div class="ddwc_img"><img src="/resource/i/images/success.png" width="100%" alt=""></div>
    <p>恭喜您支付成功</p>
    <!-- <a href="#">查看详情</a> -->
</div>

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="/resource/js/jquery.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/resource/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script src="/resource/js/amazeui.min.js"></script>
<script src="/resource/js/main.js"></script>
<script>
window.onload = function(){
	setTimeout(function(){
		location.href = "/member/index.html";
	},3000)
}
</script>
</body>
</html>