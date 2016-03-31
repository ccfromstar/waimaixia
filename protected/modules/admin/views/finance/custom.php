<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/DatePicker/WdatePicker.js"></script>
<h3 class="title">
    自定义报表
</h3>

<div class="search-form" id="filterForm">
    <div class="condition">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'get',
            'htmlOptions' => array(
                'class' => 'search_form',
            ),
        )); ?>
        
		日期范围：<input id="starttime" name="starttime" class="Wdate" type="text" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'endtime\')}'})" value="<?php if(isset($_GET['starttime'])) echo $_GET['starttime']?>" readonly/> -
		<input id="endtime" name="endtime" class="Wdate" type="text" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'starttime\')}'})" value="<?php if(isset($_GET['endtime'])) echo $_GET['endtime']?>" readonly/>
		
		<input type="hidden" id="starttime" value="<?php if(isset($_GET['starttime'])) echo $_GET['starttime']?>" />
		<input type="hidden" id="endtime" value="<?php if(isset($_GET['endtime'])) echo $_GET['endtime']?>" />

		<?php echo CHtml::submitButton('提交查询', array('id' => 'btnSubmit', 'class' => 'btn btn-primary')); ?>

		<?php echo CHtml::button('导出报表', array('id' => 'btnExport', 'class' => 'btn btn-primary')); ?>
       
        <?php $this->endWidget(); ?>
    </div>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->customStatic(),
    'ajaxUpdate' => false,
    'template' => '{summary}{items}{pager}',
    'pager' => array(
        'header' => '',
        'firstPageLabel' => '首页',
        'lastPageLabel' => '末页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'maxButtonCount' => 13
    ),
	'columns' => array(
		array(
			'header' => '订单号',
			'name' => 'order_sn',
		),
		array(
			'header' => '客户',
			'name' => 'buyer',
		),
		array(
			'header' => '联系方式',
			'name' => 'addr_mobile',
		),
		array(
			'header' => '数量',
			'name' => 'staticSum',
		),
		array(
			'header' => '明细',
			'name' => 'staticDetail',
			'htmlOptions' => array('style' => 'width:200px;')
		),
		array(
			'header' => '售价',
			'name' => 'amount',
		),
		array(
			'header' => '实收金额',
			'name' => 'realpay',
		),
		array(
			'header' => '订单状态',
			'name' => 'order_status',
			'value' => 'Yii::app()->params["order_status"][$data["order_status"]]',
		),
		array(
			'header' => '配送地址',
			'name' => 'addr',
			'htmlOptions' => array('style' => 'width:200px;')
		),
		array(
			'header' => '抬头',
			'name' => 'bak',
			'htmlOptions' => array('style' => 'width:120px;')
		),
		array(
			'header'=>'下单日期',
			'name' => 'order_time',
			'htmlOptions' => array('style' => 'width:120px;')
		),
		array(
			'header'=>'发货日期',
			'name' => 'deliver_time',
			'htmlOptions' => array('style' => 'width:120px;')
		),
	),
));
?>

<script>
$(function(){
	$("#btnExport").click(function(){
		location.href = "/admin/finance/export.html?type=custom&starttime="+$("#starttime").val()+"&endtime="+$("#endtime").val();
	});
});
</script>