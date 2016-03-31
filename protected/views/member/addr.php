<!--在这里编写你的代码-->
<section>
<table id="attfile">
	<!--已有地址展示-->
	<?php foreach($addres as $v):?>
	<div class="add_ns">
		<div class="my_address_item">
			<div class="old_add_cc">
				<div class="new_add_item">
					<span class="yk_txt">收货人</span>
					<input type="text" name="uname" value="<?php echo $v->uname;?>" class="new_add_input">
					<div class="clear"></div>
				</div>
				<div class="new_add_item">
					<span class="yk_txt">手机</span>
					<input type="text" name="mobile" value="<?php echo $v->mobile;?>" class="new_add_input">
					<div class="clear"></div>
				</div>
				<div class="new_add_item">
					<span class="yk_txt">详细地址</span>
					<input type="text" name="address" value="<?php echo $v->address;?>" class="new_add_input">
					<div class="clear"></div>
				</div>
				<div class="odd_add_icon">
					<span class="am-icon-pencil-square-o my_add_bj" data-addr="<?php echo $v->id;?>"></span>
					<span class="am-icon-trash-o my_add_lj" data-addr="<?php echo $v->id;?>"></span>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<?php endforeach;?>
	<!--已有地址展示结束-->
</table>
	<a href="javascript:addrows();"><button type="button" class="am-btn my_add_btn">新增地址</button></a>
</section>

<?php
	Yii::app()->clientScript->registerCssFile('/js/layer.m/need/layer.css');
	Yii::app()->clientScript->registerScriptFile('/resource/js/main.js',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile('/js/layer.m/layer.m.js',CClientScript::POS_END);

	Yii::app()->clientScript->registerScriptFile('http://api.map.baidu.com/api?v=1.2',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile('http://api.map.baidu.com/library/GeoUtils/1.2/src/GeoUtils_min.js',CClientScript::POS_END);
?>

<script type="text/javascript">
	var attachfiles=1;        //附件的个数
	function addrows(){
		//给附件添加行
		attachfiles++;
		var mynewrow=attfile.insertRow();
		col1=mynewrow.insertCell(0);
		if(attachfiles<11){
			col1.innerHTML="<div class='add_ns'><div class='my_address_item'><div class='old_add_cc'><div class='new_add_item'><span class='yk_txt'>收货人</span><input type='text' name='uname' value='' class='new_add_input' placeholder='收货人姓名'><div class='clear'></div></div><div class='new_add_item'><span class='yk_txt'>手机</span><input type='text' name='mobile' value='' class='new_add_input' placeholder='收货人电话'><div class='clear'></div></div><div class='new_add_item'><span class='yk_txt'>详细地址</span><input type='text' name='address' class='new_add_input' placeholder='收货地址'><div class='clear'></div></div> <div class='odd_add_icon'><span class='am-icon-pencil-square-o my_add_bj' data-addr='0'></span><div class='clear'></div></div><div class='clear'></div></div></div></div>";
		}

		if(attachfiles>9){        
			layer.open({
				content: '地址数已经达到上限',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
		}
    }
</script>