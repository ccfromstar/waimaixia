<?php
	Yii::app()->clientScript->registerCssFile('/js/layer.m/need/layer.css');
	Yii::app()->clientScript->registerScriptFile('/js/layer.m/layer.m.js',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile('/resource/js/main.js',CClientScript::POS_END);

	Yii::app()->clientScript->registerScriptFile('http://api.map.baidu.com/api?v=1.2',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile('http://api.map.baidu.com/library/GeoUtils/1.2/src/GeoUtils_min.js',CClientScript::POS_END);
?>

<form id="orderForm" action="/member/createOrder.html" method="post">
<!--在这里编写你的代码-->
<header>
	<div class="order_top">
		<div class="order_topc">
			<?php foreach($orderMenu as $v):?>
			<div class="order_topitem order_topitem2">
				<span class="order_l"><?php echo $v['menu']->name;?></span>
				<span class="order_r">￥<?php echo sprintf("%.2f",$v['amount']);?></span>
				<span class="order_c">×<input type="text" value="<?php echo $v['quantity'];?>" name="orderNumber[]" data-mname="<?php echo $v['menu']->name;?>" data-mid="<?php echo $v['menu']->id;?>" class="order-number" style="ime-mode:disabled;" onpaste="return false;" onkeypress="keyPress(this)" data-price="<?php echo $v['perPrice'];?>"></span>

				<input type="hidden" name="menuId[]" value="<?php echo $v['menu']['id']?>" />
				<input type="hidden" id="sumMenu" value="<?php echo $sumMenu;?>" />
				<div class="clear"></div>
			</div>
			<?php endforeach;?>

			<div class="order_topitem order_toptotal">
				<span>共<?php echo $sumMenu;?>份 合计:￥<?php echo sprintf("%.2f",$sumAmount);?></span>
			</div>
		</div>
   </div>
</header>
<section>
   <div class="order_citem">
      <div class="order_tit">
        收货人信息
		<span class="message_change">信息修改</span>
      </div>
      <div class="order_line">
         <div class="order_k"></div>
         <div class="order_kk">
            <div class="order_txt1">收货人</div>
            <p id="show_uname"><?php echo $addr->uname;?></p>
         </div>
         <div class="clear"></div>
      </div>
      <div class="order_line">
         <div class="order_k"></div>
         <div class="order_kk">
           <div class="order_txt1">手机</div>
           <p id="show_mobile"><?php echo $addr->mobile;?></p>
         </div>
         <div class="clear"></div>
      </div>
      <div class="order_line">
         <div class="order_k"></div>
        <div class="order_kk">
         <div class="order_txt1">详细地址</div>
         <p id="show_address"><?php echo $addr->address;?></p>
       </div>
         <div class="clear"></div>
      </div>
      <div class="order_line">
         <div class="order_k"></div>
          <div class="order_kk">
         <div class="order_txt1">送餐日期</div>

			<div class="am-input-group date form_datetime-4" data-date="">
				<input size="12" type="text" id="sendDate" name="sendDate" class="am-form-field i_date" value="<?php echo $sendDate;?>" readonly>
				<span class="am-input-group-label add-on add-on2 order_aa"><i class="icon-th am-icon-calendar"></i></span>
			</div>
       </div>
         <div class="clear"></div>
      </div>
      <div class="order_line">
         <div class="order_k"></div>
          <div class="order_kk">
         <div class="order_txt1">送餐时间</div>
			<select name="sendTime" data-am-selected>
			<?php
				foreach(Yii::app()->params['timeline'] as $k=>$vt):
			?>
				<option value="<?php echo $k;?>" <?php if(isset($sendTime) && ($sendTime==$k)){
					echo "selected";
				}?>><?php echo $vt;?></option>
			<?php endforeach;?>
			</select>
       </div>
         <div class="clear"></div>
      </div>
   </div>

	<input type="hidden" name="addr" id="addr" value="<?php echo $addr->id;?>" />
	<input type="hidden" name="sumAmount" id="sumAmount" value="<?php echo $sumAmount;?>">
	<div class="order_citem">
		<div class="order_tit">
			优惠信息
		</div>
		<div class="order_line">
			<div class="order_k"></div>
			<input type="hidden" id="ticketprice" name="ticketprice" value="" />
			<input type="hidden" id="ticketids" name="ticketids" value="" />
			<input type="hidden" id="ticketnum" name="ticketnum" value="" />
			<div class="order_txt"><a href="javascript:;" onclick="showTickets()">使用优惠券抵扣<span class="order_price">￥ 0.00</span></a></div>
			<div class="clear"></div>
		</div>
		<div class="order_line">
			<div class="order_k"></div>
			<div class="order_txt">
				<span>使用优惠码抵扣</span>
				<div class="am-u-lg-6 order_yhm">
                <div class="am-input-group">
					<input type="text" class="am-form-field order_input">
					<span class="am-input-group-btn order_confirm">
					<button class="am-btn am-btn-default con_btn" type="button">确认</button>
					</span>
                </div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
   </div>

	<div class="order_citem">
		<input type="hidden" name="freight" id="freight" value="<?php echo $freight;?>" />
		<div class="order_tit border_none">运费信息
		<span class="freight_text">订单满100免运费</span>
		<span class="order_yfprice"><?php echo $freight>0?"￥".sprintf("%.2f",$freight):"免运费"?></span></div>
	</div>

	<div class="order_citem">
		<div class="order_tit">
			发票信息
		</div>
		<div class="order_line">
			<textarea name="bak" class="order_textarea" placeholder="在此输入您的发票抬头"></textarea>
		</div>
	</div>

	<div class="order_citem">
		<div class="order_tit border_none">总价<span class="total_price">￥<?php echo sprintf("%.2f",$sumAmount+$freight);?></span></div>
	</div>

	<?php if($showReg):?>
	<div class="order_ad">
		马上注册立享优惠！
		<a href="/site/login.html?refer=orderDetail" class="order_a"><button type="button" class="am-btn order_zcbtn">立即注册</button></a>
	</div>
	<?php endif;?>

	<!-- 我的可用卡券 -->
	<div style="display:none;" id="mytickets">
		<?php if(isset($mytickets)&&count($mytickets)){?>
			<div class="user_yhq">
			<?php foreach($mytickets as $v):?>
			<div class="user_yhq" onclick="">
				<img src="/resource/i/images/yhqbg1.png">
				<div class="user_yhq_price">

				<div class="cart_check cart_check2"><input type="checkbox" class="cart_c" value="<?php echo $v->id;?>" name="mytickets" data-worth="<?php echo $v->ticket->worth;?>"><span class="xuanzhong"></span></div>

				<div class="order_bb">
					<span>￥</span><?php echo $v->ticket->worth;?>
				</div></div>
				<div class="user_yhq_text">
					<span class="span1"><?php echo $v->ticket->name;?></span>
					<span class="span2"><i>·</i>有效期至：<?php echo $v->deadline;?></span>
				</div>
			</div>
			<?php endforeach;?>
			</div>

		<?php }else{?>
			<div style="text-align:center;padding:10px;font-weight:bold;">您还没有卡券！</div>
		<?php }?>

		<div style="padding:1em;text-align:center;">
			<button onclick="getTickets()" class="am-btn am-btn-default con_btn" type="button">确定</button>
		</div>
	</div>
	<!-- 我的可用卡券结束 -->

	<!--我的可选地址-->
	<div class="msg_zz">
		<div class="msg_center">
			<div class="msg_tit">
				<span>信息</span>
				<div class="msg_close">
					<img src="/resource/i/images/i_close.png" width="100%" alt=""/>
				</div>
			</div>

			<table id="attfile">
				<!--已有地址展示-->
				<?php foreach($myaddresses as $v):?>
				<div class="add_ns">
					<input type="hidden" name="addressid" value="<?php echo $v->id;?>" />
					<input type="hidden" name="addresstext" value="<?php echo $v->address;?>" />
					<input type="hidden" name="addressuname" value="<?php echo $v->uname;?>" />
					<input type="hidden" name="addressmobile" value="<?php echo $v->mobile;?>" />
					<div class="my_address_item">
						<div class="old_add_cc">
							<div class="new_add_item">
								<?php echo $v->address;?>&nbsp;&nbsp;
								<?php echo $v->uname;?>&nbsp;&nbsp;
								<?php echo $v->mobile;?>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<?php endforeach;?>
				<!--已有地址展示结束-->
			</table>
		</div>
	</div>
	<!--我的可选地址结束-->
</form>
</section>
<footer>
  <div style="height: 60px"></div>
  <button type="button" class="am-btn order_foota">返回订餐</button>
  <button type="button" class="am-btn order_footb">立即支付</button>
</footer>

<script type="text/javascript">
$(function(){
	var msg = "<?php echo isset($msg)?$msg:'';?>";

	if(msg!=''){
		layer.open({
			content: msg,
			style: 'background-color:#09C1FF; color:#fff; border:none;',
			time: 2
		});
	}

	$(".order_foota").click(function(){
		location.href = "/site/index.html";
	});

	$(".order_footb").click(function(){
		var stockFlag = true;
		var nostockMenu = '';
		var sendDate = $("#sendDate").val();
		//下单前再次检查库存
		$(".order-number").each(function(){
			var mid = $(this).data('mid');
			var orderNumb = $(this).val();
			
			if(!checkStock(mid,orderNumb,sendDate)){
				stockFlag = false;
				nostockMenu = $(this).data('mname');
				return false;
			}
		});

		if(!stockFlag){
			layer.open({
				content: '抱歉，'+nostockMenu+'库存不足',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});

			return false;
		}else{
			//检查购买数量是否小于使用卡券数量
			if(parseInt($("#sumMenu").val())<parseInt($("#ticketnum").val())){
				layer.open({
					content: '使用优惠券数量不能大于购买数量',
					style: 'background-color:#09C1FF; color:#fff; border:none;',
					time: 2
				});

				return false;
			}else{
				//提交前，检查购买数量是否符合区域性要求
				$.ajax({
					url : '/site/getLngLat.html',
					data : {addrId:$("#addr").val()},
					dataType : 'json',
					async : false,
					success : function(res){
						checkHp(res.address,res.lng,res.lat,$("#sumMenu").val());
					}
				});
			}
		}
	});

	$(".message_change").click(function(){
		$(".msg_zz").css('display', 'block');
	});

	$(".msg_close").click(function(){
		$(".msg_zz").css('display', 'none');
	});

	$(".order_top").delegate(".order-number","blur",function(){
		if(isNaN(this.value) || parseInt(this.value)<=0)
			$(this).val(1);
		
		var mid = $(this).data('mid');
		var targetNum = parseInt($(this).val());
		var sendDate = $("#sendDate").val();
		//检查库存
		if(!checkStock(mid,targetNum,sendDate)){
			layer.open({
				content: '抱歉，'+$(this).data("mname")+'库存不足',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
		}

		var newAmount = $(this).data('price')*$(this).val();
		newAmount = newAmount.toFixed(2);
		//更新当前菜品小合计的显示
		$(this).parent().siblings('.order_r').text("￥"+newAmount);

		var curSum = 0;
		var curAmount = 0;
		//更新总菜品合计的显示
		$(".order_topitem2").each(function(){
			curSum += parseInt($(this).find(".order-number").val());
			curAmount += $(this).find(".order-number").data("price")*$(this).find(".order-number").val();
		});

		$(".order_toptotal").find("span").text("共"+curSum+"份 合计：￥"+curAmount.toFixed(2));

		//使用优惠券抵价
		var ticketprice = $("#ticketprice").val()==""?0:$("#ticketprice").val();

		if((parseFloat(curAmount) - parseFloat(ticketprice)).toFixed(2)>100){
			$(".order_yfprice").text('免运费');
			$("#freight").val(0);
		}else{
			$(".order_yfprice").text('￥ 6.00');
			$("#freight").val(6);
		}
		//运费
		var freight = $("#freight").val();
		//实际需支付价格
		var realPrice = parseFloat(curAmount) - parseFloat(ticketprice) + parseFloat(freight);
		$(".total_price").text("￥"+realPrice.toFixed(2));

		$("#sumMenu").val(curSum);

		$("#sumAmount").val(curAmount.toFixed(2));
	});

	$(".add_ns").click(function(){
		$("#addr").val($(this).find('input[name="addressid"]').val());
		$("#show_uname").text($(this).find('input[name="addressuname"]').val());
		$("#show_mobile").text($(this).find('input[name="addressmobile"]').val());
		$("#show_address").text($(this).find('input[name="addresstext"]').val());
		$(".msg_zz").css('display', 'none');
	});
});

function showTickets(){
	var pageii = layer.open({
		type: 1,
		content: $("#mytickets").html(),
		style: 'overflow:auto; position:fixed; left:0; top:0; width:100%; height:100%; border:none;'
	});
}

function getTickets(){
	//总订购数
	var sum = 0;
	var curAmount = 0;
	//更新总菜品合计的显示
	$(".order_topitem2").each(function(){
		sum += parseInt($(this).find(".order-number").val());
		curAmount += $(this).find(".order-number").data("price")*parseInt($(this).find(".order-number").val());
	});

	if(!sum){
		layer.open({
			content: '购买数量为0，不能使用优惠券',
			style: 'background-color:#09C1FF; color:#fff; border:none;',
			time: 2
		});

		return false;
	}else{
		var checkedTickets = 0;		//选中卡券数量
		var checkedTicketPrice = 0;	//选中卡券面值总和
		var tids = '';		//选中卡券id集合
		$("input[name='mytickets']").each(function(){
			if($(this).prop("checked")){
				checkedTickets++;
				checkedTicketPrice += parseFloat($(this).data("worth"));
				tids += $(this).val()+",";
			}
		});

		if(tids.indexOf(",")>-1){
			tids = tids.substr(0,tids.length-1);
		}

		if(checkedTickets>sum){
			layer.open({
				content: '抱歉，使用的卡券数不能超过您的购买数量：'+sum,
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});

			return false;
		}else{
			if(tids.indexOf(",")>-1){
				tids = tids.substr(0,tids.length-1);
			}

			$("#ticketprice").val(checkedTicketPrice);
			$("#ticketids").val(tids);
			$("#ticketnum").val(checkedTickets);
			$(".order_price").text("￥ "+checkedTicketPrice);

			//购买总价减去优惠券的价格，以判断是否需要运费
			if(parseFloat(curAmount-checkedTicketPrice).toFixed(2)>100){
				$(".order_yfprice").text('免运费');
				$("#freight").val(0);
			}else{
				$(".order_yfprice").text('￥ 6.00');
				$("#freight").val(6);
			}

			//运费
			var freight = $("#freight").val();
			//实际需支付价格
			var realPrice = parseFloat(curAmount) - parseFloat(checkedTicketPrice) + parseFloat(freight);
			$(".total_price").text("￥"+realPrice.toFixed(2));
			layer.closeAll();
		}
	}
}

//检测是否在黄浦区
function checkHp(address,lng,lat,sum){
	if(checkSpec(address)){
		$("#orderForm").submit();
	}

	// 创建地理编码实例
	var myGeo = new BMap.Geocoder();
	// 根据坐标得到地址描述
	myGeo.getLocation(new BMap.Point(lng,lat), function(result){
		var addr = result.address;

		if(addr.indexOf("黄浦区")==-1){
			//不在黄埔区
			if(sum<10){
				layer.open({
					content: '黄浦区外内环线内10份起送',
					style: 'background-color:#09C1FF; color:#fff; border:none;',
					time: 2
				});
				return false;
			}else{
				$("#orderForm").submit();
			}
		}else{
			$("#orderForm").submit();
		}
	});
}

function checkSpec(address){
	var flag = false;
	$.ajax({
		async : false,
		url : '<?php echo $this->createUrl("/site/ajaxCheckSpec");?>',
		data : {addr:address},
		type : 'post',
		success : function(res){
			if(res=='success'){
				flag = true;
			}
		}
	});
	return flag;
}

function checkStock(mid,orderNumb,sendDate){
	var flag = true;
	$.ajax({
		url : '<?php echo $this->createUrl("site/ajaxCheckStock");?>',
		async : false,
		data : {mid:mid,orderNumb:orderNumb,sendDate:sendDate},
		success : function(res){
			if(res=='error')
				flag  = false;
		}
	});

	return flag;
}
</script>