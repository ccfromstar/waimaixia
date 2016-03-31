<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
        <title><?php echo Yii::app()->name;?>::管理登录</title>
        <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl;?>/js/jqtransformplugin/jqtransform.css" type="text/css" media="all" />
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/jqtransformplugin/jquery.jqtransform.js" ></script>

        <style>
        form{clear:both;}
        body{ background:url(<?php echo Yii::app()->baseUrl;?>/images/admin_img/boy.jpg);}
        *{padding:0px;margin:0px;border:none;font-size:12px;font-family:Arial,宋体;}
        #map{position:absolute;left:50%;top: 50%;margin:-280px 0px 0px -500px;overflow: hidden;width:959px;height:582px;z-index:1;background:url(<?php echo Yii::app()->baseUrl;?>/images/admin_img/login.jpg) no-repeat;}
        .shurukk{ height:20px; width:140px; line-height:20px; position:absolute; left:410px;}
        .yzm{position:absolute; left:420px; width:40px; top:296px;height:23px;line-height:23px; border:1px solid #CCC; padding-left:5px;}
        .dl{position:absolute; left:410px; top:353px;}
        .yzmtp{position:absolute; left:480px; top:299px;}
        .sfjzzh{position:absolute; left:332px; top:336px;}
        .wz{position:absolute; left:350px; top:316px; font-size:12px; color:#6C6C6C;}
        .error div,.errorMessage{height:20px;line-height:20px;color:red!important;}
        </style>
    </head>
    <body>
        <div id="map">
            <?php echo $content;?>
        </div>
    </body>
</html>