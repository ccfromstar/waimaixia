<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo Yii::app()->name; ?></title>
    <link href="<?php echo Yii::app()->baseUrl; ?>/css/table_form.css" rel="stylesheet" type="text/css">
    <style type="text/css">
	*{font:12px "Microsoft YaHei";}
	table, tr, td{border:none;border-collapse:collapse;padding: 0px;} 
	.formhead{background:#EEF3F7;color:#000;border-bottom:2px solid #82B9D7;height:30px;line-height:30px;text-indent:15px;}
	.g-mn{border:1px solid #ccc;}
	.g-sd{border-right:1px solid #ccc;}
	.g-sd .g-item{border-top:1px solid #ccc;}
	.g-itit{line-height:22px;padding:10px;}
	.g-sitem{height:24px;line-height:24px;padding:4px 30px;}
	.g-item .name{cursor:pointer}
	.g-itit:hover,.g-sitem:hover ,.curr{background-color:#EEF3F9;}
	.clear{clear:both;height:0px;}
	.g-wrap{width:100%;height:100%;}
	div.item{clear:both;display:none}
    div.show{display:block;}
    div.row{border-bottom: 1px solid #EEF3F9;margin-top:8px;padding-left: 15px; height: auto;}
    div.row label{width:85px;display:inline-block;}
	.g-tt{background-color:#E5E4E4;font-size:14px;padding:5px 10px;height:32px;line-height:32px;}
	.f-add {float:right;}
	.u-btn{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;display:inline-block;*display:inline;padding:4px 12px;margin-bottom:0;*margin-left:.3em;font-size:14px;line-height:20px;color:#333;text-align:center;text-shadow:0 1px 1px rgba(255,255,255,0.75);vertical-align:middle;cursor:pointer;background-color:#f5f5f5;*background-color:#e6e6e6;background-image:-moz-linear-gradient(top,#fff,#e6e6e6);background-image:-webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));background-image:-webkit-linear-gradient(top,#fff,#e6e6e6);background-image:-o-linear-gradient(top,#fff,#e6e6e6);background-image:linear-gradient(to bottom,#fff,#e6e6e6);background-repeat:repeat-x;border:1px solid #ccc;*border:0;border-color:#e6e6e6 #e6e6e6 #bfbfbf;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);border-bottom-color:#b3b3b3;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false);*zoom:1;-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);-moz-box-shadow:inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);box-shadow:inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05)}
	.u-btn:hover,.u-btn:focus,.u-btn:active,.u-btn.active,.u-btn.disabled,.u-btn[disabled]{color:#333;background-color:#e6e6e6;*background-color:#d9d9d9}
	.u-btn:hover,.u-btn:focus{color:#333;text-decoration:none;background-position:0 -15px;-webkit-transition:background-position .1s linear;-moz-transition:background-position .1s linear;-o-transition:background-position .1s linear;transition:background-position .1s linear}
	div.f-sadd,div.f-sdel,div.f-sedit{padding:0px 4px;font-size:10px;float:right;margin-right:6px;}
	.title{float:left;padding:3px 26px;text-align:center;text-indent:0px;height:24px;border-bottom:none;cursor:pointer;font-size:14px;}
	.g-item .f-sadd,.g-item .f-sdel,.g-item .f-sedit{display:none;}
	.curr .f-sadd,.curr .f-sdel,.curr .f-sedit{display:block;}
    .select{background-color:#FFFFFF;}
    #pager{height:30px;padding-top:10px;}
    .yiiPager a{display:block;height:20px;line-height:20px;float:left;font-size:12px;font-family:Arial, Helvetica, sans-serif;}
    ul.yiiPager a:link,ul.yiiPager a:visited{margin-right:3px;font-weight:normal;}
    ul.yiiPager .first,ul.yiiPager .last{float:left;display:inline!important;}
    ul.yiiPager .selected a{background:#ECF0FB;border-color:#069;font-weight:bold!important;color:#06f!important;}
    ul.yiiPager .hidden a{border:solid 1px #DEDEDE!important;	color:#888888!important;}
    </style>
    <?php Yii::app()->clientScript->registerCoreScript('jquery');?>
</head>
<body>
    <?php echo $content; ?>
    
</body>
</html>