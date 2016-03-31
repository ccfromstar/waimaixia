<?php
$this->breadcrumbs = array(
    '员工资料详情',
);
?>

<h3 class="title">
    <?php echo CHtml::link('返回员工列表', array('/admin/manager/list')); ?>

    员工资料详情
</h3>

<p class="formhead">员工资料详情</p>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        array(
            'label' => '用户名',
            'value' => $model->user_model->username,
        ),
        'name',
        'tel',
        'mobile',
        'email',
        'qq',
        array(
            'label' => '授权角色',
            'value' => $role,
        ),
        'incode',
        array(
            'name' => 'province',
            'value' => $model->province_model->name,
        ),
        array(
            'name' => 'city',
            'value' => $model->city_model->name,
        ),
        'address',
        'remark',
    ),
));
?>
