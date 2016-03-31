<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/DatePicker/WdatePicker.js"></script>
<style>
.odd td,.even td{ text-align:center;}
</style>
<h3 class="title">
    年报表
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
		<input name="nowtime" class="cusinput" style="width:100px" onclick="WdatePicker({dateFmt:'yyyy'})" value="<?php echo $nowtime;?>" type="text" readonly>

		<input type="hidden" id="nowtime" value="<?php echo $nowtime;?>" />

		<?php echo CHtml::submitButton('提交查询', array('id' => 'btnSubmit', 'class' => 'btn btn-primary')); ?>

		<?php echo CHtml::button('导出年报表', array('id' => 'btnExport', 'class' => 'btn btn-primary')); ?>
       
        <?php $this->endWidget(); ?>
    </div>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->yearStatic(),
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
			'header' => '月份',
			'name' => 'id',
		),
		array(
			'header' => '订单总金额',
			'name' => 'amount',
		),
		array(
			'header' => '实际订单总金额',
			'name' => 'realpay',
		),
		array(
			'header' => '订单量',
			'name' => 'all_count',
		),
	),
));
?>

<script>
$(function(){
	$("#btnExport").click(function(){
		location.href = "/admin/finance/export.html?type=year&nowtime="+$("#nowtime").val();
	});
});
</script>