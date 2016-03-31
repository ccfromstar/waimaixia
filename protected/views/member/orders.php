<!--在这里编写你的代码-->
<section>
<?php 
if(count($orders)):
foreach($orders as $v):?>
	<div class="user_list user_list1">
	  <button data-oid="<?php echo $v->id;?>" type="button" class="am-btn am-btn-warning right <?php echo $v->order_status?'green':'yellow';?>" align="absmiddle"><?php echo (!$v->order_status)?'取消订单':'再来一单';?></button>
	  <span class='order-status'><?php echo Yii::app()->params['order_status'][$v->order_status];?></span>&nbsp;&nbsp;<?php echo date('Y-m-d H:i:s',$v->order_time)?>
	</div>

	<?php foreach($v->details as $goods):?>
	<div class="user_list user_list2" data-oid="<?php echo $v->id?>">
	  <img src="/resource/i/images/orderpic.jpg" align="absmiddle">
	  <span class="span1"><?php echo $goods->goods_name;?><br><i>￥<?php echo $goods->menu->price;?></i></span>
	  <span class="span2 right"> ×<?php echo $goods->quantity;?></span>
	</div>
	<?php endforeach;?>
<?php endforeach;
else:
?>
	<div class="user_list user_list1">
	暂无订单！<button type="button" class="am-btn am-btn-warning right" align="absmiddle">返回首页</button>
	</div>
<?php
endif;
?>
</section>

<?php
	Yii::app()->clientScript->registerCssFile('/js/layer.m/need/layer.css');
	Yii::app()->clientScript->registerScriptFile('/js/layer.m/layer.m.js',CClientScript::POS_END);
?>

<script>
$(function(){
	$(".am-btn").bind('click',function(){
		var orderid = $(this).data('oid');
		var obj = this;
		if($(this).hasClass('yellow')){
			layer.open({
				content: '确定要取消当前订单吗',
				btn: ['确认', '取消'],
				shadeClose: false,
				yes: function(){
					//异步取消订单
					$.ajax({
						url : '/member/ajaxCancelOrder.html',
						data : {id:orderid},
						dataType : 'json',
						success : function(res){
							layer.open({
								content: res.msg,
								style: 'background-color:#09C1FF; color:#fff; border:none;',
								time: 2
							});

							if(res.success){
								$(obj).removeClass("yellow").addClass("green");
								$(obj).siblings('.order-status').text('取消订单');
							}
						}
					});
				}, no: function(){
					return false;
				}
			});
		}else if($(this).hasClass('green')){
			location.href = "/member/sameOrder.html?id="+$(this).data('oid');
		}else{
			//勿删
			location.href = "/site/index.html";
		}
	});

	$(".user_list2").click(function(){
		location.href = "/member/showOrder.html?id="+$(this).data('oid');
	});
});
</script>