<h3 class="title">
    <?php echo CHtml::link('新增优惠券',array('/admin/ticket/update'));?>
    优惠券列表
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
		'name',
		array(
			'name'=>'worth',
			'value'=>'sprintf("%.2f",$data->worth)',
		),
		
		array(
			'name'=>'desc',
			'value'=>'mb_substr($data->desc,0,12)',
		),

		array(
			'name'=>'type',
			'value'=>'$data->type?"优惠码":"优惠券"',
		),

		array(
			'name'=>'effect_days',
		),
		array(
            'header' => '操作',
            'class' => 'CButtonColumn',
            'template' => '{view} {update} {delete}',
        	'htmlOptions' => array('style' => 'width:80px;text-align:center;'),
        	'buttons'=>array(
				'deleteConfirmation' => '确定删除该数据吗',
			)
        ),
	),
));
?>