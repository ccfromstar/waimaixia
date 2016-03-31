<div class="form span-12 first">

	
<?php $form=$this->beginWidget('CActiveForm'); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('maxlength'=>255, 'class'=>'cusinput')); ?>
		<?php echo $form->error($model, 'name'); ?>
		<span class="hint"><?php echo Rights::t('core', 'Do not change the name unless you know what you are doing.'); ?></span>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textField($model, 'description', array('maxlength'=>255, 'class'=>'cusinput')); ?>
		<?php echo $form->error($model, 'description'); ?>
		<span class="hint"><?php echo Rights::t('core', 'A descriptive name for this item.'); ?></span>
	</div>

	<div class="row" style="display:none;">
		<?php echo $form->labelEx($model, 'lx'); ?>
		<?php echo $form->dropDownList($model, 'lx', array('sw' => '商委', 'dq' => '地区', 'yyb' => '营业部'), array('maxlength'=>50, 'class'=>'cusinput')); ?>
		<?php echo $form->error($model, 'lx'); ?>
	</div>

	<?php if( Rights::module()->enableBizRule===true ): ?>

		<div class="row">
			<?php echo $form->labelEx($model, 'bizRule'); ?>
			<?php echo $form->textField($model, 'bizRule', array('maxlength'=>255, 'class'=>'cusinput')); ?>
			<?php echo $form->error($model, 'bizRule'); ?>
			<span class="hint"><?php echo Rights::t('core', 'Code that will be executed when performing access checking.'); ?></span>
		</div>

	<?php endif; ?>

	<?php if( Rights::module()->enableBizRule===true && Rights::module()->enableBizRuleData ): ?>

		<div class="row">
			<?php echo $form->labelEx($model, 'data'); ?>
			<?php echo $form->textField($model, 'data', array('maxlength'=>255, 'class'=>'cusinput')); ?>
			<?php echo $form->error($model, 'data'); ?>
			<span class="hint"><?php echo Rights::t('core', 'Additional data available when executing the business rule.'); ?></span>
		</div>

	<?php endif; ?>
    
	<div class="row buttons">
        <?php echo CHtml::link(Rights::t('core', 'Cancel'), Yii::app()->user->rightsReturnUrl,array('class'=>'abutton','style'=>'float:left;margin-top:5px;margin-right:10px;')); ?>
		<?php echo CHtml::submitButton(Rights::t('core', 'Save'),array('style'=>'margin-top:5px;margin-right:10px;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>