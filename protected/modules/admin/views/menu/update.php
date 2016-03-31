<h3 class="title">
    新增菜品
</h3>

<?php

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
));
?>
<div class="row">
    <?php echo $form->labelEx($model, 'cid'); ?>
    <?php echo $form->dropDownList($model, 'cid', CHtml::listData(MenuCategory::model()->findAll(),'id','name')); ?>
    <?php echo $form->error($model, 'cid'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('class' => 'cusinput', 'style' => 'width:150px;')); ?>
    <?php echo $form->error($model, 'name'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'price'); ?>
    <?php echo $form->textField($model, 'price', array('class' => 'cusinput', 'style' => 'width:50px;')); ?> 元
    <?php echo $form->error($model, 'price'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'orgstock'); ?>
    <?php echo $form->numberField($model, 'orgstock', array('class' => 'cusinput', 'style' => 'width:50px;')); ?>
    <?php echo $form->error($model, 'orgstock'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'material'); ?>
    <?php echo $form->textField($model, 'material', array('class' => 'cusinput', 'style' => 'width:300px;')); ?>
    <?php echo $form->error($model, 'material'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'taste'); ?>
    <?php echo $form->textField($model, 'taste', array('class' => 'cusinput', 'style' => 'width:300px;')); ?>
    <?php echo $form->error($model, 'taste'); ?>
</div>

<div class="row" style="height: auto;">
	<?php echo $form->labelEx($model, 'showpic'); ?>
	<?php echo $form->textField($model, 'showpic', array('class' => 'cusinput','readonly'=>'readonly','style' => 'width:250px;background:#eee;float:left;')); ?>

	<div style="margin:1px 0 0 8px;float:left;text-align:center;color:#069;width: 80px; height: 18px; border:1px solid #ddd;background:#EEF3F7;border-right:1px solid #555;border-bottom:1px solid #555; padding:2px;">
		<?php
		$this->widget('application.extensions.uploadify.EuploadifyWidget',
			array(
				'name'=>'pic',
				'options'=> array(
					'uploader' => $this->createUrl("{$this->id}/pic"),
					'width' => 80,
					'height' => 18,
					'auto' => true,
					'multi' => false,
					//'queueID' => 'queue',
					'postData' => array('sessionid' => session_id()),
					'buttonText' => '上传文件',
					'fileTypeExts' => '*.png;*.jpg;*.jpeg;*.gif',
					'fileTypeDesc' => '图片文件',
					'method' => 'post',
				),
				'callbacks' => array(
					'onUploadError' => 'function(file,errorCode,errorMsg,errorString,swfuploadifyQueue) {
						   alert(errorMsg);
					   }',
					'onUploadSuccess' => 'function(file,data,response){
							var json = $.parseJSON(data);
							if (json.success) {
								$("#Menu_showpic").val(json.thumbs[0]);
								$("#s_img").html("<a href=\"" + json.source + "\" target=\"_blank\"><img width=\"100\" src=\"" + json.thumbs[0] + "\"></a>");
							} else {
								alert(json.message);
							}
					   }',
				)
			));
		?>
	</div>
	<?php echo $form->error($model, 'showpic'); ?>
	<span> 最佳尺寸 ： 750px X 410px</span>
	<div id="s_img" style="margin-left:120px; margin-bottom:8px;"><?php if($model->showpic!=''){?><a href="<?php echo $model->showpic;?>" target="_blank"><img src="<?php echo $model->showpic;?>" width="100" /></a><?php } ?></div>
</div>

<div class="row" style="height:auto;">
    <?php echo $form->labelEx($model, 'special'); ?>
    <?php echo $form->textArea($model, 'special', array('class' => 'cusinput', 'style' => 'width:500px;height:80px;')); ?>
    <?php echo $form->error($model, 'special'); ?>
</div>

<div class="row" style="height:auto;padding-bottom:10px;">
    <?php echo $form->labelEx($model, 'desc'); ?>

    <?php
        $this->widget('ext.widgets.KEditor',array(
				'model' => $model,
				'name'=>'desc',
				'properties' => array(
					'width' => '95%',
					'minHeight' => '200px;',
				)
			)
		);
    ?>

    <?php echo $form->error($model, 'desc'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'status'); ?>
    <?php echo $form->radioButtonList($model, 'status', array(1=>'上架',0=>'下架'), array('separator'=>' ', 'style'=>'height:30px;line-height:30px;float:left;','labelOptions'=>array(
		'style'=>'width:45px;margin-left:3px;'
	))); ?>
    <?php echo $form->error($model, 'status'); ?>
</div>

<div class="row buttons" style="height:40px;border:none;">
    <label>&nbsp;</label>
<?php echo CHtml::submitButton('保存', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;')); ?>
</div>
<?php $this->endWidget();?>