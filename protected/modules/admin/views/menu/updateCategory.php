<h3 class="title">
    新增分类
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
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('class' => 'cusinput', 'style' => 'width:150px;')); ?>
    <?php echo $form->error($model, 'name'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'porder'); ?>
    <?php echo $form->numberField($model, 'porder', array('class' => 'cusinput', 'style' => 'width:50px;')); ?>
    <?php echo $form->error($model, 'porder'); ?>
</div>

<div class="row buttons" style="height:40px;border:none;">
    <label>&nbsp;</label>
<?php echo CHtml::submitButton('保存', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;')); ?>
</div>
<?php $this->endWidget();?>