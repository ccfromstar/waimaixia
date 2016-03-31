<header>
	<div class="order_top">
		<div class="order_topc">
			<?php foreach($orderMenu as $v):?>
			<div class="order_topitem order_topitem2">
				<span class="order_l"><?php echo $v['menu']->name;?></span>
				<span class="order_r">￥<?php echo sprintf("%.2f",$v['amount']);?></span>
				<span class="order_c">×<?php echo $v['quantity'];?></span>
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
			<div class="am-input-group date form_datetime-4" data-date="" style="height: 46px;line-height: 46px">
				<?php echo $order->req_date;?>
			</div>
		</div>
         <div class="clear"></div>
      </div>
      <div class="order_line">
         <div class="order_k"></div>
          <div class="order_kk">
         <div class="order_txt1">送餐时间</div>
			<div class="am-input-group date form_datetime-4" style="height: 46px;line-height: 46px"><?php echo $order->req_time;?></div>
		</div>
         <div class="clear"></div>
      </div>
   </div>

	<div class="order_citem">
		<div class="order_tit">
			优惠信息
		</div>
		<div class="order_line">
			<div class="order_k"></div>

			<div class="order_txt"><a href="javascript:;">使用优惠券抵扣<span class="order_price">￥ <?php echo sprintf('%.2f',$order->ticketprice);?></span></a></div>
			<div class="clear"></div>
		</div>

   </div>

	<div class="order_citem">
		<div class="order_tit border_none">运费信息<span class="order_yfprice"><?php echo $order->bak;?></span></div>
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
		<div class="order_tit border_none">总价<span class="total_price">￥<?php echo $order->realpay;?></span></div>
	</div>
</section>
<footer>
  <div style="height: 60px"></div>
  <button type="button" class="am-btn order_foota">返回首页</button>
  <button type="button" class="am-btn order_footb">订单列表</button>
</footer>

<script>
$(function(){
	$(".order_foota").click(function(){
		location.href = "/site/index.html";
	});

	$(".order_footb").click(function(){
		location.href = "/member/orders.html";
	});
});
</script>