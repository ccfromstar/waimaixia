<script type="text/javascript" src="/js/layer2/layer.js"></script>
<link href="/js/layer2/skin/layer.css" rel="stylesheet" type="text/css" />
<style>
.wrap_p{display:none;width:500px;height:300px;text-align:left;padding:10px 0 5px 15px;}
.wrap_c{width:100%;text-align:center;}
</style>

<div id="baseinfo">
	<div class="row">
		<?php echo CHtml::label('联系电话', 'Address_mobile');?>
		<?php echo $form->textField($addr, 'mobile', array('class'=>'cusinput','style'=>'width:100px;','onblur'=>'js:checkMobile(this)'));?>
		<?php echo $form->error($addr, 'mobile');?>

		<span id="addrOpt" style="display:none;">
			<input id="chooseAddr" style='padding:2px;' type="button" value="选择已有地址" />
			<input id="addAddr" style='padding:2px;' type="button" value="自己填写地址" />
			<input id="addTicket" style='padding:2px;' type="button" value="选择优惠券" />
		</span>
	</div>
	
	<?php echo $form->hiddenField($addr,'id');?>
	<?php echo CHtml::hiddenField('curuid','');?>
	<?php echo CHtml::hiddenField('ticketids','');?>
	<?php echo CHtml::hiddenField('ticketprice','');?>

	<div class="row">
		<?php echo CHtml::label('联系人', 'Address_uname');?>
		<?php echo $form->textField($addr, 'uname', array('class'=>'cusinput','style'=>'width:100px;'));?>
		<?php echo $form->error($addr, 'uname');?>
	</div>

	<div class="row">
		<?php echo CHtml::label('送餐地址', 'Address_address');?>
		<?php echo $form->textField($addr, 'address', array('class'=>'cusinput','style'=>'width:250px;'));?>
		<?php echo $form->error($addr, 'address');?>
	</div>

	<div class="row">
		<?php echo CHtml::label('送达日期', 'Order_req_date');?>
		<?php echo $form->textField($model, 'req_date', array('class'=>'cusinput', 'style'=>'width:100px;', 'onfocus'=>"js:WdatePicker({readOnly:true,minDate:'%y-%M-%d',disabledDays:[0,6]})"));?>
		<?php echo $form->error($model, 'req_date');?>
	</div>

	<div class="row">
		<?php echo CHtml::label('送达时间', 'Order_req_time');?>
		<?php echo $form->dropDownList($model, 'req_time', Yii::app()->params['timeline'], array('style'=>'width:120px;'));?>
		<?php echo $form->error($model, 'req_time');?>
	</div>

	<div class="row" style="height:auto;padding-bottom:5px;">
		<?php echo CHtml::label('发票抬头', 'Order_bak');?>
		<?php echo $form->textArea($model, 'bak', array('style'=>'width:250px;height:50px;'));?>
		<?php echo $form->error($model, 'bak');?>
	</div>

	<div class="row" style="height:auto;padding-bottom:5px;">
		<?php echo CHtml::label('备注', 'Order_opbak');?>
		<?php echo $form->textArea($model, 'opbak', array('style'=>'width:250px;height:50px;'));?>
		<?php echo $form->error($model, 'opbak');?>
	</div>

	<div class="row">
		<?php echo CHtml::label('优惠券', 'Order_ticketids');?>
		<span id="ticket_info">无</span>
	</div>
</div>

<div id="org_addr" class="wrap_p">
	<div id="addrlist"></div>
	<div id='sureAddr' class="wrap_c"><input onclick="getAddr()" type='button' value='选定' /></div>
</div>

<div id="mytickets" class="wrap_p">
	<div id="ticklist"></div>
	<div id='sureTick' class="wrap_c"><input onclick="checkTick()" type='button' value='选定' /></div>
</div>

<script>
//根据手机号获取用户信息
function checkMobile(o){
	var mobile = $.trim(o.value);
	if(mobile!=''){
		$.ajax({
			url : "<?php echo $this->createUrl('order/ajaxGetUserByMobile')?>",
			data : {mobile:mobile},
			dataType : 'json',
			success : function(res){
				$("#ticketids").val('');
				$("#ticketprice").val(0);
				$("#ticket_info").html('无');
				if(res.success){
					$("#addrOpt").show()
					
					if(res.hasAddr){
						var content = '';
						var allAddre = res.data;
						for(var i=0,addlen=allAddre.length;i<addlen;i++){
							content += "<div style='margin-bottom:5px;'>"+allAddre[i].address;
							content += " "+allAddre[i].uname+" "+allAddre[i].mobile;
							content += "<input style='margin-left:5px;' name='orgaddre' type='radio' value='";
							content += allAddre[i].id+"' data-uid='"+allAddre[i].uid+"' data-uname='"+allAddre[i].uname+"' data-addr='"+allAddre[i].address+"' /></div>";
						}

						$("#addrlist").html(content);
					}else{
						$("#addrlist").html('该用户还没有提交地址信息');
					}
					
					$("#curuid").val(res.uid);
				}else{
					//如果不存在用户和地址信息
					$("#addrOpt").hide()
					$("#addrlist").html('')
					$("#Address_uname").val('').prop('readonly',false);
					$("#Address_address").val('').prop('readonly',false);
					$("#Address_id").val('');
					$("#curuid").val('');
				}
			}
		});
	}else{
		$("#addrOpt").hide()
		$("#addrlist").html('')
		$("#Address_uname").val('').prop('readonly',false);
		$("#Address_address").val('').prop('readonly',false);
		$("#Address_id").val('');
		$("#curuid").val('');

		$("#ticketids").val('');
		$("#ticketprice").val(0);
		$("#ticket_info").html('无');
	}
}

function getAddr(){
	var selectedAddr = 0;
	var $selected;
	$("#addrlist").find("input[type='radio']").each(function(){
		if($(this).prop('checked')){
			selectedAddr = $(this).val();
			$selected = $(this);
			return false;
		}
	});
	
	if(selectedAddr>0){
		$("#Address_uname").val($selected.data('uname')).prop('readonly',true);
		$("#Address_address").val($selected.data('addr')).prop('readonly',true);
		
		layer.closeAll();
		$("#Address_id").val(selectedAddr);
	}else{
		layer.msg('请选择地址',{time:1500});
	}
}

//选中优惠券事件
function checkTick(){
	//已选菜品数
	var quantity_count = 0;
	$("#menuinfo .cusinput").each(function(){
		var tmpQua = parseInt($(this).val());
		quantity_count += tmpQua;
	});

	//已选优惠券数
	var ticket_count = 0;

	var ticketsIds = '';
	var ticketsPrice = 0;

	$("#ticklist").find("input[type='checkbox']").each(function(){
		if($(this).prop('checked')){
			ticket_count += 1;

			ticketsIds += $(this).val()+",";
			ticketsPrice += parseFloat($(this).data('price'));
		}
	});

	if(ticketsIds.indexOf(',')>-1){
		ticketsIds = ticketsIds.substr(0,ticketsIds.length-1);
	}

	if(quantity_count<ticket_count){
		layer.msg('优惠券数不能大于菜品数');
		return false;
	}else{
		$("#ticket_info").html("已选择："+ticket_count+"张优惠券，共折价："+ticketsPrice.toFixed(2)+"元");
		$("#ticketids").val(ticketsIds);
		$("#ticketprice").val(ticketsPrice.toFixed(2));
		layer.closeAll();
	}
}

$(function(){
	//选择已有地址
	$("#chooseAddr").click(function(){
		layer.open({
			type: 1,
			title: '地址列表',
			area: ['550px', '370px'],
			closeBtn: 1,
			shadeClose: true,
			content: $("#org_addr")
		});
	});

	//自己填写地址
	$("#addAddr").click(function(){
		$("#Address_uname").val('').prop('readonly',false);
		$("#Address_address").val('').prop('readonly',false);
		$("#Address_uname").focus();
		$("#Address_id").val('');
	});

	//查询可用的优惠券
	$("#addTicket").click(function(){
		var uid = $("#curuid").val();
		if(uid!=''){
			$.ajax({
				url : "<?php echo $this->createUrl('order/ajaxGetTick');?>",
				data : {uid:uid},
				dataType : 'json',
				success : function(res){
					if(res.success){
						$("#ticklist").html(res.data);
					}else{
						$("#ticklist").html('暂无卡券可用');
					}

					layer.open({
						type: 1,
						title: '卡券列表',
						area: ['550px', '370px'],
						closeBtn: 1,
						shadeClose: true,
						content: $("#mytickets")
					});
				}
			});
		}else{
			$("#ticketids").val('');
			$("#ticketprice").val(0);
			$("#ticket_info").html('无');

			layer.msg('抱歉，获取用户信息失败');
			return false;
		}
	});
});
</script>