<div class="title">
    注册总用户数：<span id="total">0</span>
</div>

<script>
    
        $.ajax({
            type: "post",
            url: "http://www.4000191177.com:8080/service/getTotal",
            success: function(data) {
               $("#total").html(data[0].count);
            }
        });
    
</script>

<div id="assignments">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
	    'dataProvider'=>$dataProvider,
	    'template'=>"{items}{pager}",
        'pager' => array('header'=> ''),
	    'emptyText'=>Rights::t('core', 'No users found.'),
	    'htmlOptions'=>array('class'=>'grid-view assignment-table'),
	    'columns'=>array(
    		array(
    			'name'=>'name',
    			'header'=>'登录账号',
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'name-column','style'=>'width:160px;text-align:center;'),
    			'value'=>'$data->getAssignmentNameLink()',
    		),
    		array(
    			'header'=>'订餐次数',
    			'htmlOptions'=>array('class'=>'name-column','style'=>'text-align:center;width:100px;'),
    			'value'=>'$data->getOrderCount()',
    		),
    		array(
    			'header'=>'可用优惠券',
    			'htmlOptions'=>array('class'=>'name-column','style'=>'text-align:center;width:100px;'),
    			'value'=>'$data->getTickets()',
    		),
            array(
                'header'=>'注册时间',
                'htmlOptions'=>array('class'=>'name-column','style'=>'text-align:center;width:200px;'),
                'value'=>'$data->getTime()',
            ),
    		array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Roles'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'role-column','style'=>'text-align:center;'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
    		),
			array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Tasks'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'task-column','style'=>'text-align:center;'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
    		),
			array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Operations'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'operation-column','style'=>'text-align:center;'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
    		),

	    )
	)); ?>

</div>