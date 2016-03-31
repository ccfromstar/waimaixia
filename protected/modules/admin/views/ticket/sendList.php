<h3 class="title">
    <?php echo CHtml::link('优惠券发放',array('/admin/ticket/send'));?>
    发放记录
</h3>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->sendLogSearch(),
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
			'name'=>'uid',
			'value'=>'$data->user==null?"已注销用户":$data->user->username',
			'htmlOptions' => array('style'=>'width:100px;text-align:center;'),
		),
		array(
			'name'=>'tid',
			'value'=>'$data->ticket==null?"优惠券已删除":$data->ticket->name',
			'htmlOptions' => array('style'=>'width:150px;text-align:center;'),
		),
		array(
			'header'=>'发放时间',
			'name'=>'usedtime',
			'htmlOptions' => array('style'=>'width:180px;text-align:center;'),
		),
		array(
			'name'=>'quantity',
			'htmlOptions' => array('style'=>'width:50px;text-align:center;'),
		),
		array(
			'name'=>'opter',
			'value'=>'$data->operator==null?"已注销用户":$data->operator->username',
			'htmlOptions' => array('style'=>'width:100px;text-align:center;'),
		),
		array(
			'name'=>'optype',
			'value'=>'Yii::app()->params["ticket_way"][$data->optype]',
			'htmlOptions' => array('style'=>'width:100px;text-align:center;'),
		),
		array(
			'name'=>'opway',
			'value'=>'$data->optype>0?"增加":"减少"',
			'htmlOptions' => array('style'=>'width:50px;text-align:center;'),
		),
		array(
			'name'=>'bak',
		),
	),
));
?>