<h3 class="title">
    <?php echo CHtml::link('返回列表', array('/admin/ticket/list')); ?>
    <?php echo $operate;?>
</h3>

<p class="formhead"><?php echo $operate;?></p>

<?php
	$form = $this->beginWidget('CActiveForm',array(
		'enableClientValidation'=>true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
		),
	));
?>
<div class="row">
    <?php echo $form->label($model,'name');?>
    <?php echo $form->textField($model,'name');?>
    <?php echo $form->error($model,'name');?>
</div>

<div class="row">
    <?php echo $form->label($model,'worth');?>
    <?php echo $form->textField($model,'worth',array('style'=>'width:50px;'));?> 元
    <?php echo $form->error($model,'worth');?>
</div>

<div class="row" style='height:auto;'>
    <?php echo $form->label($model,'desc');?>
    <?php echo $form->textArea($model,'desc',array('style'=>'width:350px;height:80px;'));?>
</div>

<div class="row">
    <?php echo $form->label($model,'type');?>
	<?php echo $form->dropDownList($model,'type',array('优惠码','优惠券'));?>
</div>

<div class="row">
    <?php echo $form->label($model,'effect_days');?>
    <?php echo $form->numberField($model,'effect_days',array('style'=>'width:50px;'));?>
</div>

<div class="row buttons" style="height:40px;border:none;">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton('确认提交', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;')); ?>
</div>

<?php $this->endWidget(); ?>