<style>
.sp img{ height:100px;}
</style>
<h3 class="title">
    <?php echo CHtml::link('新增分类',array('/admin/menu/updateCategory'));?>
    分类列表
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
            'header' => '操作',
            'class' => 'CButtonColumn',
            'template' => '{view}{update}{delete}',
        ),
    )
));
?>