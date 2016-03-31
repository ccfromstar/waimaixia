<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/DatePicker/WdatePicker.js"></script>
<h3 class="title">
    <?php echo $title;?>
</h3>

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'ads',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
?>

<p class="formhead"><?php echo $title;?></p>

<?php foreach($models as $key => $model):?>
<?php if (is_array($options[$key])) :?>
	<div class="row" <?php if($options[$key]['type']!='number' && $options[$key]['type']!='list'):?>style="height:auto;"<?php endif;?>>
		<?php echo CHtml::label($options[$key]['title'], 'Configs_'.$key.'_value'); ?>
		<?php 
		if ($options[$key]['type'] == 'text'){
			echo $form->textArea($model, "[$key]value", array('style' => $options[$key]['style']));
			if(isset($options[$key]['tips'])){
				echo "<span style='font-weight:bold;color:red;margin-left:5px;'>(".$options[$key]['tips'].")</span>";
			}
		}else if ($options[$key]['type'] == 'list'){
			echo $form->dropDownList($model, "[$key]value", $options[$key]['data']);
		}else if($options[$key]['type'] == 'number'){
			echo $form->numberField($model, "[$key]value", array('style' => $options[$key]['style'],'class'=>'cusinput'));
		}
		?>
		<?php echo $form->error($model, "[$key]value"); ?>
	</div>

<?php else:?>
	<div class="row">
		<?php echo CHtml::label($options[$key], 'Configs_'.$key.'_value'); ?>
		<?php echo $form->textField($model, "[$key]value", array('class'=>'cusinput', 'style' => 'width: 250px')); ?>
		<?php if($key=='kf_code') echo "(多个QQ号之间以英文逗号','分隔)";?>
		<?php echo $form->error($model, "[$key]value"); ?>
	</div>
	
<?php endif;?>

<?php endforeach;?>

<div class="row buttons" style="height:40px;border:none;">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton('确认提交', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;')); ?>

	<?php if($title=='交易设置'):?>
	<?php echo CHtml::button('更新库存', array('id' => 'updateStock', 'style' => 'cursor:pointer;')); ?>
	<?php endif;?>
</div>
<?php $this->endWidget(); ?>

<script>
$(function(){
	$("#Configs_enableorder_value").bind('change',function(){
		if($(this).val()==0){
			if(!confirm("确定要关闭订餐吗")){
				$(this).find("option").eq(1).attr("selected",true);
			}
		}
	});

	$("#updateStock").bind('click',function(){
		if(confirm("确定要更新所有菜品的库存吗")){
			$.ajax({
				url : '<?php echo $this->createUrl("config/ajaxUpdateStock")?>',
				dataType : 'json',
				success : function(res){
					alert("操作成功，本次更新了 "+res.c+" 条数据")
				}
			});
		}
	});
});
</script>
