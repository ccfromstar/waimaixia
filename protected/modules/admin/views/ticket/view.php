<?php
$this->breadcrumbs = array(
    '卡券详情',
);
?>

<h3 class="title">
    <?php echo CHtml::link('返回卡券列表', array('/admin/ticket/list')); ?>

    卡券资料详情
</h3>

<p class="formhead">卡券资料详情</p>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'name',
        array(
            'name' => 'worth',
            'value' => sprintf('%0.2f',$model->worth),
        ),
        'desc',
        array(
            'name' => 'type',
            'value' => $model->type?"优惠券":"优惠码",
        ),
        array(
            'name' => 'effect_days',
        ),
    ),
));
?>
