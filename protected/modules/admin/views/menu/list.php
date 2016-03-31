<style>
.sp img{ height:100px;}
</style>
<h3 class="title">
    <?php echo CHtml::link('新增菜品',array('/admin/menu/update'));?>
    菜品列表
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
        array(
            'name' => 'name',
            'htmlOptions' => array('style' => 'text-align:center;'),
        ),

        array(
			'type' => 'image',
            'name' => 'showpic',
            'htmlOptions' => array('style' => 'text-align:center;','class'=>'sp'),
        ),

		array(
			'name' => 'price',
			'htmlOptions' => array('style'=>'text-align:center;'),
		),

		array(
			'type' => 'raw',
			'name' => 'status',
			'htmlOptions' => array('style'=>'text-align:center;'),
			'value' => '$data->status?"<span style=\"color:green\">上架</span>":"<span style=\"color:red\">下架</span>"',
		),

        array(
            'header' => '操作',
            'class' => 'CButtonColumn',
            'template' => '{update}&nbsp;{delete}',
        ),
    )
));
?>