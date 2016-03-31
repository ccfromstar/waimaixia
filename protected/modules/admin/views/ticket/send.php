<h3 class="title">
	<?php echo CHtml::link('发放记录',array('/admin/ticket/sendList'));?>
    优惠券发放
</h3>

<p class="formhead">优惠券发放</p>

<?php
	$form = $this->beginWidget('CActiveForm',array(
		'enableClientValidation'=>true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
		),
	));
?>

<?php 
	echo $form->hiddenField($model,'usedtime');
	echo $form->hiddenField($model,'order_id');
	echo $form->hiddenField($model,'optype');
	echo $form->hiddenField($model,'opway');
?>

<div class="row">
    <?php echo $form->label($model,'uid');?>
	<?php echo $form->hiddenField($model,'uid');?>
    <?php echo CHtml::textField('user','',array('class'=>'cusinput','style'=>'width:120px;','onblur'=>'js:checkUser(this)'));?>
	(请输入用户手机号)
    <?php echo $form->error($model,'uid');?>
</div>

<div class="row">
    <?php echo $form->label($model,'tid');?>
    <?php echo $form->dropDownList($model,'tid',CHtml::listData(
		Tickets::model()->findAll(),'id','name'
	));?>
    <?php echo $form->error($model,'tid');?>
</div>

<div class="row">
    <?php echo $form->label($model,'quantity');?>
    <?php echo $form->numberField($model,'quantity',array('class'=>'cusinput','style'=>'width:50px;'));?>
    <?php echo $form->error($model,'quantity');?>
</div>

<div class="row" style='height:auto;'>
    <?php echo $form->label($model,'bak');?>
    <?php echo $form->textArea($model,'bak',array('class'=>'cusinput','style'=>'height:80px;width:300px'));?>
    <?php echo $form->error($model,'bak');?>
</div>

<div class="row buttons" style="height:40px;border:none;">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton('确认提交', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;')); ?>
</div>

<?php $this->endWidget(); ?>

<script>
function checkUser(o){
	var mobile = $.trim(o.value);
	if(mobile!=''){
		$.ajax({
			url : '<?php echo $this->createUrl("ticket/getUserByMobile");?>',
			data : {mobile:mobile},
			dataType : 'json',
			success : function(res){
				if(res.success){
					$("#TicketLog_uid").val(res.uid);
				}else{
					$("#TicketLog_uid").val('');
					alert('抱歉，该手机号用户不存在');
				}
			}
		});
	}else{
		$("#TicketLog_uid").val('');
	}
}
</script>