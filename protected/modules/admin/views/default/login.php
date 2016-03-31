
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">
                <?php echo $form->textField($model,'username',array('class' => 'shurukk','style'=>'top:226px;')); ?>
		<?php echo $form->error($model,'username',array('style'=>'position:absolute;top:226px;left:570px;width:190px;background:transparent;')); ?>
	</div>

	<div class="row">
		<?php echo $form->passwordField($model,'password',array('class' => 'shurukk','style'=>'top:265px;')); ?>
		<?php echo $form->error($model,'password',array('style'=>'position:absolute;top:265px;left:570px;width:190px;background:transparent;')); ?>
	</div>

        
	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe',array('class' => 'wz')); ?>
		<?php echo $form->label($model,'rememberMe',array('class' => 'shurukk','style'=>'top:310px;left:360px;width:300px;background:transparent;')); ?>
	</div>

	<div class="row buttons">
		<input type="image" class="dl" src="<?php echo Yii::app()->baseUrl;?>/images/admin_img/dl.jpg" name="Submit" value="提交" />
	</div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerScript('login',"
    $('form').jqTransform({imgPath:'".Yii::app()->baseUrl."/js/jqtransformplugin/img/'});
");
?>
