<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/DatePicker/WdatePicker.js"></script>

<style>
.items tr td{ text-align:center;}
</style>

<div class="search-form" id="filterForm">
    <div class="condition">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'get',
            'htmlOptions' => array(
                'class' => 'search_form',
            ),
        )); ?>
		<input name="nowtime" class="cusinput" style="width:100px" onclick="WdatePicker()" value="<?php echo $nowtime;?>" type="text" readonly>
			
		<input type="hidden" id="nowtime" value="<?php echo $nowtime;?>" />

		<?php echo CHtml::submitButton('提交查询', array('id' => 'btnSubmit', 'class' => 'btn btn-primary')); ?>

		<?php echo CHtml::button('导出报表', array('id' => 'btnExport', 'class' => 'btn btn-primary')); ?>
       
        <?php $this->endWidget(); ?>
    </div>
</div>

<div class="grid-view">
	<table class="items" id="menutable" style="border-width: 1px;border-color: #666666;border-collapse: collapse;">
		<caption style="font-size:14px;font-weight:bold;padding-bottom:5px;">外卖侠-菜单明细报表</caption>
		<thead>
			<tr/>
				<th style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;">&nbsp;</th>
				<?php foreach($dateArr as $v):?>
				<th style="border-width: 1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #dedede;"><?php echo $v;?></th>
				<?php endforeach;?>
			</tr>
		</thead>
			
		<tbody>
			<?php foreach($menus as $v):?>
			<tr>
				<td style="width:120px;border-width:1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;"><?php echo $v->name?></td>
				<?php 
					foreach($arr as $ak=>$av):
				?>
				<td style="text-align:center;border-width:1px;padding: 8px;border-style: solid;border-color: #666666;background-color: #ffffff;"><?php echo empty($av[$v->id])?"-":$av[$v->id]['count'];?></td>
				<?php endforeach;?>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>

<form action='<?php echo $this->createUrl("finance/ee");?>' id='eeform' method='post'>
<input type="hidden" name="toee" id="toee" />
<input type="hidden" name="staticDate" value="<?php echo $dateArr[0]."-".$dateArr[6];?>" />
</form>

<script>
$(function(){
	$("#btnExport").click(function(){
		$("#toee").val($('.grid-view').html());
		$("#eeform").submit();
	});
});
</script>