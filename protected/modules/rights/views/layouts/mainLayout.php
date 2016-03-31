<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>中国国际航空公司海外人力资源管理系统</title>
<link href="<?php echo Yii::app()->baseUrl;?>/css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->baseUrl;?>/css/admin.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->baseUrl;?>/css/table_form.css" rel="stylesheet" type="text/css" />

<style type="text/css">
	html{_overflow-y:scroll}
</style>
</head>
<body>
    <div style="padding:10px;">
    <?php $this->renderPartial('/_flash'); ?>
    <?php echo $content;?>
    </div>

</body>
</html>
<?php
$this->widget('ext.selectmenu.selectMenu',array('style'=>'dropdown','maxHeight' => 150));

Yii::app()->clientScript->registerScript('flash','
    jQuery("div.flashes").animate({opacity: 0}, 5000).slideUp("slow");    
');
?>
