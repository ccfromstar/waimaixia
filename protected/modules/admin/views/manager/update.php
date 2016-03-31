<h3 class="title">
    <?php echo CHtml::link('返回列表', array('/admin/manager/list')); ?>
    <?php echo $operate;?>
</h3>

<?php

$form = $this->beginWidget('CActiveForm', array(
            'id' => 'customer',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
?>

<p class="formhead"><?php echo $operate;?></p>

<div class="row">
    <?php echo $form->labelEx($umodel, 'username'); ?>
    <?php echo $form->textField($umodel,'username' ,array('class'=>'cusinput','style' => 'width:150px','readonly' => $isnew ? '' : 'readonly'));?>

    <?php echo $form->error($umodel,'username');?>
		<?php if($model->isNewRecord){?>注：如果不填写用户名，则密码默认为手机号码。<?php } ?>
</div>

<div class="row">
    <?php echo $form->labelEx($umodel, 'password'); ?>
    <?php echo $form->passwordField($umodel,'password' ,array('class'=>'cusinput','style' => 'width:150px'));?>
    <?php echo $form->error($umodel,'password');?>
		注：如果不填写密码，则密码默认为121314。

</div>


<div class="row">
    <?php echo $form->label($model,'role');?>
    <?php echo $form->dropDownList($model,'role',$roles);?>
</div>

<?php if(isAdmin()):?>
<div class="row">
    <?php echo $form->labelEx($model, 'province'); ?>
    <?php echo $form->dropDownList($model,'province', $provinceList ,array(
        'empty' => array('' => '请选择所在省份'),
        'ajax' => array(
            'type' => 'POST',
            'url' => $this->createUrl('manager/getCity'),
            'update' => '#Customer_city',
        ),
        ));?>
    <?php echo $form->error($model,'province');?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'city'); ?>
    <?php echo $form->dropDownList($model,'city',$cities ,array(
        'empty' => array('' => '请先选择省份'),
        'ajax' => array(
            'type' => 'POST',
            'url' => $this->createUrl('manager/getStore'),
            'update' => '#Customer_store_id',
        ),
    ));?>
    <?php echo $form->error($model,'city');?>
</div>
<?php endif;?>

<div class="row">
    <?php echo $form->labelEx($model, 'incode'); ?>
    <select class="cusinput" style="width:150px" name="Customer[incode]" id="Customer_incode" onchange="check_incode()">
		<option value="" selected="selected"></option>
		<?php
		$company_id=get_company_id();
		$sql="select worker_id,name from worker where company_id=".$company_id." and is_show=1";
		if($workers!='')
		  $sql.=" and worker_id not in (".$workers.")";
		$command=Yii::app()->db_talk->createCommand($sql);
		$re=$command->queryAll();
		if(is_array($re) && count($re)>0){
		  foreach($re as $v){
		?>
		<option value="<?php echo $v['worker_id'];?>"<?php if($v['worker_id']==$model->incode){ echo ' selected';}?>><?php echo $v['name'];?>[<?php echo $v['worker_id'];?>]</option>
		<?php } } ?>
		</select>
    <?php echo $form->error($model,'incode');?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model,'name' ,array('class'=>'cusinput','style' => 'width:150px'));?>
    <?php echo $form->error($model,'name');?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'tel'); ?>
    <?php echo $form->textField($model,'tel' ,array('class'=>'cusinput','style' => 'width:200px'));?>
    <?php echo $form->error($model,'tel');?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'mobile'); ?>
    <?php echo $form->textField($model,'mobile' ,array('class'=>'cusinput','style' => 'width:200px'));?>
    <?php echo $form->error($model,'mobile');?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'qq');?>
    <?php echo $form->textField($model,'qq' ,array('class'=>'cusinput','style'=>'width:120px;'));?>
    <?php echo $form->error($model,'qq');?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'email'); ?>
    <?php echo $form->textField($model,'email' ,array('class'=>'cusinput','style' => 'width:200px'));?>
    <?php echo $form->error($model,'email');?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'store_id'); ?>
    <?php echo $form->dropDownList($model,'store_id',$stores,array(
        'empty' => array(0 => '无指定门店'),
    ));?>
    <?php echo $form->error($model,'store_id');?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'address'); ?>
    <?php echo $form->textField($model,'address' ,array('class'=>'cusinput','style' => 'width:290px'));?>
    <?php echo $form->error($model,'address');?>
</div>

<div class="row" style="height:auto; padding-bottom:8px;  ">
    <?php echo $form->labelEx($model, 'remark'); ?>
    <?php echo $form->textArea($model,'remark' ,array('style'=>'width:290px;height:130px;'))  ?><br />
    <?php echo CHtml::label('&nbsp;',''). $form->error($model,'remark');?>
</div>

<div class="row buttons" style="height:40px;border:none;">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton('确认提交', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;')); ?>
</div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->clientScript;
$cs->registerScript('qmail',"
    $('#Customer_qq').change(function (){
        var mail = $('#Customer_email');
        var mval = mail.val();
        var qq = $(this);
        if(mval == ''){
            mail.val(qq.val() + '@qq.com').change();
        }
    });
    $('#Customer_mobile').change(function (){
        var mobile = $(this).val();
        var username = $('#User_username').val();
        if(!username){
            $('#User_username').val(mobile).blur();
        }
    });
");
?>
<script language="javascript">
var incode='<?php echo $model->incode;?>';
function check_incode()
{
  /*var inc=$("#Customer_incode").val();
	var cid='<?php echo $model->id;?>';
	if(inc!='')
	{
	  $.ajax({
				type: "GET",
				url: "/admin/manager/checkincode",
				data: {cid:cid,incode:inc},
				cache: false,
				dataType: "json",
				success: oper_suc
			});
	}*/
}
function oper_suc(data)
{
  if(data)
	{
	  $("#Customer_incode_em").html('此工号已被取用');
	  //$("#Customer_incode").val(incode);
	}else
	{
	  $("#Customer_incode_em").html('');
	}
}
$(document).ready(function(){
    $("#Customer_city").change(function() {
		var aid=$("#Customer_city").val();
		if(aid>0)
		{
			$("#Customer_incode").empty();
			$('<option value=""> </option>').appendTo("#Customer_incode");
			$.ajax({
				type: "POST",
				url: "/admin/manager/getbyarea",
				data: {area_id:aid},
				cache: false,
				dataType: "json",
				success: get_suc
			});
		}
   });
   
   function get_suc(data)
   {
     var ptitle=data.cate;
	 if(ptitle.length>0)
	 {
	   for(var i in ptitle)
	   {
	     $('<option value="'+ptitle[i]['worker_id']+'">'+ptitle[i]['name']+'</option>').appendTo("#Customer_incode");
	   }
	 }
   }
});
</script>