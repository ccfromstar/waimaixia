<h3 class="title">
    <?php echo CHtml::link('新增员工',array('/admin/manager/update'));?>
    员工资料
</h3>
<div class="summary">
<form name="o" action="" method="get" class="count_form">
	用户名：<input type="text" name="Customer[username]" style="width:120px;" class="cusinput" value="<?php echo $customer['username'];?>" />
	姓名：<input type="text" name="Customer[name]" style="width:120px;" class="cusinput" value="<?php echo $customer['name'];?>" />
	客服工号：<input type="text" name="Customer[incode]" style="width:120px;" class="cusinput" value="<?php echo $customer['incode'];?>" />
	手机：<input type="text" name="Customer[mobile]" style="width:120px;" class="cusinput" value="<?php echo $customer['mobile'];?>" />

    <?php if(isAdmin()):?>
    <label for="">分站：</label>
    <?php echo CHtml::dropDownList('Customer[city]',$customer['city'],CHtml::listData(Area::model()->findAll('level=2 and status=1'),'id','name'),array('empty' => '所有'));?>
    <?php endif;?>

    <?php if(isAdmin()):?>
    <label for="">门店：</label>
    <?php echo CHtml::dropDownList('Customer[store_id]',$customer['store_id'], $stories, array('empty' => array('' => '所有', '0' => '未分配')));?>
    <?php endif;?>

    <label for="">角色：</label>

    <?php echo CHtml::dropDownList('Customer[role]',$customer['role'],Rights::getAuthItemSelectOptions(2,array('Admin','Authenticated','Guest')),array('empty' => '所有'));?>

    <span class="buttons">
	<input type="submit" name="ss" value="搜索"/>
    </span>
</form>
</div>
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
            'name' => 'userid',
            'header' => '员工帐号',
            'htmlOptions' => array('style' => 'text-align:center;'),
			'value'  => '$data->user_model->username',
        ),
				array(
            'name' => 'name',
            'header' => '员工姓名',
            'htmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'header' => '<a href="#df">角色</a>',
            'value' => 'getUserRole($data->userid)',
            'htmlOptions' => array('style' => 'text-align:center;'),
        ),
        array(
            'name' => 'incode',
            'htmlOptions' => array('style' => 'width:100px;text-align:center;'),
        ),
        array(
            'name' => 'tel'
        ),
        array(
            'name' => 'mobile',
        ),
        array(
            'name' => 'email',
        ),
        array(
            'filter' => false,
            'name' => 'province',
            'value' => '$data->province_model->name',
            'htmlOptions' => array('style' => 'width:80px;text-align:center;')
        ),
        array(
            'filter' => false,
            'name' => 'city',
            'value' => '$data->city_model->name',
            'htmlOptions' => array('style' => 'width:80px;text-align:center;')
        ),

        array(
            'header' => '操作',
            'class' => 'CButtonColumn',
            'template' => '{view} &nbsp; {update} &nbsp; {resetpw} &nbsp; {delete}',
        	'htmlOptions' => array('style' => 'width:120px;'),
            //'viewButtonUrl' => 'array("scape/album","id"=>$data->id)',
        	'buttons'=>array(
        		'resetpw' =>array(
        			'label'=> '重置密码',
					'url'=> 'array("resetpw","id"=>$data->id)',
        			'imageUrl' => Yii::app()->baseUrl.'/images/resetpw.png',
        			'options' => array('class'=>'resetpw'),
					'click'=>'function(){if(!confirm("确定要重置此员工的密码吗?")) return false;}'
				),
				'delete' =>array(
					'url'=> 'array("del","id"=>$data->userid)',
				),
			)
        ),
    )
));
?>
<?php
Yii::app()->clientScript->registerScript('store',"
    $('#Customer_city').change(function (){
        var cityid = $(this).val();
        $.get('".$this->createUrl('getsearchstore')."',{'city':cityid},function (data){
            $('#Customer_store_id').html(data);
        });
    });
");
?>