<div id="menuinfo">
<input type='hidden' id="orderData" name='orderData' />
<table class='items'>
	<thead>
		<tr>
			<th>菜名</th>
			<th>单价</th>
			<th>数量</th>
			<th>小计</th>
		</tr>

		<tbody>
			<?php foreach($menu as $k=>$v):?>
			<tr class="<?php echo $k%2==0?'even':'odd';?>">
				<td style="text-align:center;"><?php echo $v->name;?></td>
				<td style="text-align:center;"><?php echo '￥'.sprintf('%.2f',$v->price);?></td>
				<td style="text-align:center;">
					<input class="minus" type="button" value="-" style="width:17px;height:22px;"/>
					<input data-mid="<?php echo $v->id;?>" data-price="<?php echo $v->price;?>" class="cusinput" style="margin-left:10px;margin-right:10px;width:30px;text-align:center;" value=0 style="ime-mode:disabled;" onpaste="return false;" onkeyup="getprice(this)" onkeypress="keyPress(this)"/>
					<input class="plus" type="button" value="+" style="width:17px;height:22px;"/>
				</td>
				<td style="text-align:center;width:250px;"></td>
			</tr/>
			<?php endforeach;?>
			<tr>
				<td colspan=4 style="text-align:right;color:red;">
					<span style="font-weight:bold;">合计数量：</span>
					<span id="ct_num">0份</span>
					<span style="margin-left:16px;font-weight:bold;">合计金额：</span>
					<span id="ct_cash">￥ 0.00</span>
					<span style="margin-left:16px;font-weight:bold;">结算金额：</span>
					<span id="all_cash" style="margin-right:20px;">￥ 0.00</span>
				</td>
			</tr>
		</tbody>
	</thead>
</table>
</div>

<script>
function keyPress(obj) {  
	var keyCode = event.keyCode;  
	if((keyCode >= 48 && keyCode <= 57)){
		event.returnValue = true;  
	}else{  
		event.returnValue = false;  
    }  
}

function getprice(o){
	var curNum = parseInt($(o).val());
	var price = parseFloat($(o).data('price'));
	$(o).parent().parent().find('td:eq(3)').text('￥'+(price*curNum).toFixed(2));
	getCount();
}

//获取总计
function getCount(){
	var orderData = '[';
	var quantity_count = 0;		//总数量
	var price_count = 0;		//总金额
	var amount = 0;				//结算金额

	$("#menuinfo .cusinput").each(function(){
		var tmpQua = parseInt($(this).val());
		quantity_count += tmpQua;
		price_count += parseFloat($(this).data('price'))*tmpQua;

		if(tmpQua>0){
			orderData += '{';
			orderData += '"mid":'+$(this).data('mid')+',';
			orderData += '"qua":'+tmpQua
			orderData += '},';
		}
	});
	
	if(orderData.indexOf(",")>0){
		orderData = orderData.substr(0,orderData.length-1);
	}

	orderData += ']';
	$("#orderData").val(orderData)
	
	if(price_count<100){
		amount = price_count + 6;
		$("#all_cash").text("￥ "+price_count.toFixed(2)+"(含6元配送费)");
	}else{
		amount = price_count;
		$("#all_cash").text("￥ "+price_count.toFixed(2));
	}
	
	$("#ct_num").text(quantity_count+"份");
	$("#ct_cash").text("￥ "+price_count.toFixed(2));
}

$(function(){
	$("#menuinfo").delegate('.minus','click',function(){
		var obj = $(this).parent().find('.cusinput');
		var price = parseFloat($(obj).data('price'));
		var curNum = parseInt($(obj).val());
		
		if(curNum==0)
			return false;
		else{
			$(obj).val(--curNum);
			$(this).parent().parent().find('td:eq(3)').text('￥'+(price*curNum).toFixed(2));
			getCount();
		}
	});

	$("#menuinfo").delegate('.plus','click',function(){
		var obj = $(this).parent().find('.cusinput');
		var price = parseFloat($(obj).data('price'));
		var curNum = parseInt($(obj).val());
		$(obj).val(++curNum);
		$(this).parent().parent().find('td:eq(3)').text('￥'+(price*curNum).toFixed(2));
		getCount();
	});
});
</script>