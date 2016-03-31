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
		'order_no',
		'amount',
		'paytime',
		'ordertime',
		'updtime',
		array(
			'type'=>'raw',
			'name'=>'paystatus',
			'value'=>'$data->paystatus?"<span style=color:green>已支付</span>":"<span style=color:red>未支付</span>"'
		),
		array(
			'type'=>'raw',
			'name'=>'sendstatus',
			'value'=>'$data->sendstatus?"<span style=color:green>已发货</span>":"<span style=color:red>未发货</span>"'
		),
		array(
            'header' => '操作',
            'class' => 'CButtonColumn',
            'template' => '{deliver}',
        	'htmlOptions' => array('style' => 'width:80px;text-align:center;'),
        	'buttons'=>array(
				'deliver' => array(
					'label' => '发货',
					'url' => 'Yii::app()->createUrl("/admin/ticket/deliver",array("oid"=>$data->id))',
					'imageUrl' => '/images/deliver.jpg',
					'visible' => '$data->paystatus',
					'click' => 'function(){if(!confirm("确定发货吗")) return false;}',
				),
			)
        ),
	),
));
?>