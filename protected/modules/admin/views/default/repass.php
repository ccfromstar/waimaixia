<h3 class="title">
	修改密码
</h3>

<?php
$this->breadcrumbs=array(
	'系统管理',
	'修改密码'
);

$form = $this->beginWidget('CActiveForm', array(
            'id' => 'user',
        ));
?>

<p class="formhead">修改密码</p>

<div class="row">
    <label>账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号</label>
    <?php echo Yii::app()->user->name; ?>
</div>
<div class="row">
    <label>新密码</label>
    <?php echo CHtml::passwordField('password','',array('class'=>'cusinput','style'=>'width:150px;')); ?>
</div>
<div class="row">
    <label>确认新密码</label>
    <?php echo CHtml::passwordField('repassword','',array('class'=>'cusinput','style'=>'width:150px;')); ?>
</div>

<div class="row buttons" style="height:40px;border:none;">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton('确认修改', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;')); ?>
</div>

<?php $this->endWidget(); ?>