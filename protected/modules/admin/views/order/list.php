<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/DatePicker/WdatePicker.js"></script>
<style>
    .red {color:red; cursor:pointer;}
    .green {color:green; cursor: pointer;}
    .print img {width:16px; height: 16px;}
    .tip {display: inline-block; width: 16px; height: 16px; margin-bottom: -3px; background-image: url(<?php echo Yii::app()->baseUrl;?>/images/msg_icon.png); background-repeat: no-repeat; text-indent: -100000px;}
    .btip {background-position: 0 -34px;}
    .stip {background-position: 0 -18px;}
	.pager{height:30px;}
</style>
<h3 class="title">
    订单管理
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
        <p>
            <?php echo $form->labelEx($model,'order_sn');?>
            <?php echo $form->textField($model,'order_sn', array('class' => 'cusinput', 'style' => 'width:140px;'));?>
			
            <?php echo $form->labelEx($model, 'pay_status');?>

            <?php echo $form->dropDownList($model, 'pay_status', Yii::app()->params['pay_status'], array('empty' => '全部','class' => 'cusinput','style'=>'width:90px;'));?>
            
            <?php echo $form->labelEx($model, 'order_status');?>
            <?php echo $form->dropDownList($model, 'order_status', Yii::app()->params['order_status'], array('empty' => '全部','class' => 'cusinput','style'=>'width:80px;'));?>

			<input readonly="" class="cusinput" style="width:100px" onfocus="js:WdatePicker({readOnly:true, maxDate:&quot;#F{$dp.$D('order_endtime')}&quot;})" name="starttime" id="order_starttime" maxlength="255" value="<?php echo @$_GET['starttime']  ?>" type="text"> - <input class="cusinput" style="width:100px" onfocus="js:WdatePicker({readOnly:true, minDate:&quot;#F{$dp.$D('order_starttime')}&quot;})" name="endtime" id="order_endtime" maxlength="255" value="<?php echo @$_GET['endtime']  ?>" type="text">    
		   
            <?php echo CHtml::submitButton('提交查询', array('id' => 'btnSubmit', 'class' => 'btn btn-primary')); ?>
        </p>
        <?php $this->endWidget(); ?>
    </div>
</div>
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$model->search(),
    'id' => 'orderlist',
    'ajaxUpdate' => true,
    'template' => '{items}{pager}{summary}',
	'itemView'=>'_order',
	'itemsTagName' => 'table', 
	'emptyText' => '<li class="item">暂无信息~</li>',
	'summaryText' => '&nbsp;',
	'emptyText' => '<li class="item">暂无信息~</li>',
    'pager' => array(
        'header' => '',
        'firstPageLabel' => '首页',
        'lastPageLabel' => '末页',
        'prevPageLabel' => '上一页',
        'nextPageLabel' => '下一页',
        'maxButtonCount' => 13
    ),
));
?>

<?php
Yii::app()->clientScript->registerScript('delete',"
		$('.Order_delete').live('click',function(){
			if(confirm('确定要删除该条数据吗?'))
			{
				return true;
			}else{
				return false;
			}
		});
	",0);

Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js/miniTip/minitip.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/miniTip/miniTip.js');

Yii::app()->clientScript->registerScript('tips', "
    $('.tip').miniTip({
        className: 'green',
        position: 'left'
    });
");