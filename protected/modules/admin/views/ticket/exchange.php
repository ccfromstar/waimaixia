<h3 class="title">
    <?php echo CHtml::link('卡券列表',array('/admin/ticket/tickets'));?>
    卡券订单
</h3>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
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
		'id',
		array(
			'name'=>'tid',
			'htmlOptions'=>array('style'=>'text-align:center;'),
			'value'=>'(null==$data->ticket)?"卡券不存在":$data->ticket->name'
		),
		array(
			'name'=>'uid',
			'htmlOptions'=>array('style'=>'text-align:center;'),
			'value'=>'(null==$data->user)?"用户不存在":$data->user->username'
		),
		'extime',
		'coupon',
		'updtime',
		array(
			'type'=>'raw',
			'name'=>'sendstatus',
			'value'=>'$data->sendstatus?"<span style=color:green>已发放</span>":"<span style=color:red>未发放</span>"'
		),
	),
));
?>