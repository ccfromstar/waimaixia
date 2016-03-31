<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title><?php echo Yii::app()->name;?></title>
<link href="<?php echo Yii::app()->baseUrl;?>/css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->baseUrl;?>/css/admin.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->baseUrl;?>/css/table_form.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	html{_overflow-y:scroll}
</style>
</head>
<body>
    <div style="padding:10px;">
        <?php $this->widget('application.extensions.flash.Flash', array(
            'keys'=> array('success','error'),
            'htmlOptions'=>array('class'=>'flash'),
        )); ?>

        <?php echo $content;?>
    </div>

</body>
</html>
<?php
//$this->widget('ext.selectmenu.selectMenu',array('style'=>'dropdown'));
?>

