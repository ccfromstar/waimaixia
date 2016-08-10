<!--在这里编写你的代码-->
<header>
	<div class="i_location" style="display:none">
		<span class="am-icon-map-marker i_licon"></span>
		<span class="i_ltxt">
		<?php
			if(isset($curAddr)){
				echo $curAddr->address;
			}elseif(count($addr)){
				echo $addr[0]['address'];
			}else{
				echo '请设置收货地址';
			}
		?>
		</span>

		<span class="am-icon-angle-down i_jticon am-icon-sm <?php if(!count($addr)) echo 'no-addr';?>"></span>

	</div>
</header>
<section>
	<ul class="i_tab1" style="display:none">
		<li class="tab1_li1"><?php if($mid==4){ echo "减脂套餐";}else{ echo "白领套餐";}?></li>
		<li class="tab1_li2"><?php if($mid==4){ echo "白领套餐";}else{ echo "减脂套餐";}?></li>
	</ul>

	<ul class="i_tab2">
	

	<li>
		<!--精选套餐-->
		<?php
			if($mid==4){
				$menusSecond = $menusBl;
			}else{
				$menusSecond = $menusJk;
			}

			foreach($menusSecond as $v):
				$price = $v->price;
				if($_SERVER["QUERY_STRING"]=="path=HuaiHaiApp"){
					$price = $price - 1;
				}
		?>
		<div class="i_item">
			<input type="hidden" name="menuid" value="<?php echo $v->id;?>" />
			<div class="i_img">
				<img src="<?php echo $v->showpic;?>" width="100%" alt=""/>
			</div>
			<div class="i_content">
				<div class="i_ctop">
					<span class="i_cat">精选套餐</span>
					<span class="i_tit"><?php echo $v->name;?></span>
					<div class="cart_plus">
						<a data-mid="<?php echo $v->id;?>" href="javascript:void(0)" onClick="minute(this,<?php echo $price;?>)" class="cart_re">-</a>
						<p class="cart_number">0</p>
						<a data-mid="<?php echo $v->id;?>" href="javascript:void(0)" onClick="plus(this,<?php echo $price;?>)" class="cart_add">+</a>
					</div>
					<span class="i_price">￥<?php echo sprintf('%.2f',$price);?></span>
				</div>
				<div class="i_cbottom">
					<p>原料：<?php echo $v->material;?></p>
					<p>口感：<?php echo $v->taste;?></p>
					<p>特色：<?php echo $v->special;?></p>
				</div>
			</div>
		</div>
		<?php endforeach;?>
		<!--精选套餐结束-->
	</li>

	<li>
		<!--本周例汤-->
		<?php
			if($mid==4){
				$menusFirst = $menusJk;
			}else{
				$menusFirst = $menusBl;
			}

			foreach($menusFirst as $v):
				$price = $v->price;
				if($_SERVER["QUERY_STRING"]=="path=HuaiHaiApp"){
					$price = $price - 1;
				}
		?>
		<div class="i_item">
			<input type="hidden" name="menuid" value="<?php echo $v->id;?>" />
			<div class="i_img">
				<img src="<?php echo $v->showpic;?>" width="100%" alt=""/>
			</div>
			<div class="i_content">
				<div class="i_ctop">
					<span class="i_cat">本周例汤</span>
					<span class="i_tit"><?php echo $v->name;?></span>
					<div class="cart_plus">
						<a data-mid="<?php echo $v->id;?>" href="javascript:void(0)" onClick="minute(this,<?php echo $price;?>)" class="cart_re">-</a>
						<p class="cart_number">0</p>
						<a data-mid="<?php echo $v->id;?>" href="javascript:void(0)" onClick="plus(this,<?php echo $price;?>)" class="cart_add">+</a>
					</div>
					<span class="i_price">￥<?php echo sprintf('%.2f',$price);?></span>
				</div>
				<div class="i_cbottom">
					<p>原料：<?php echo $v->material;?></p>
					<p>口感：<?php echo $v->taste;?></p>
					<p>特色：<?php echo $v->special;?></p>
				</div>
			</div>
		</div>
		<?php endforeach;?>
		<!--本周例汤结束-->
	</li>

	<li>
		<!--稻香大米-->
		<?php
			$menusFouth = $menusDXDM;

			foreach($menusFouth as $v):
				$price = $v->price;
				if($_SERVER["QUERY_STRING"]=="path=HuaiHaiApp"){
					$price = $price - 1;
				}
		?>
		<div class="i_item">
			<input type="hidden" name="menuid" value="<?php echo $v->id;?>" />
			<div class="i_img">
				<img src="<?php echo $v->showpic;?>" width="100%" alt=""/>
			</div>
			<div class="i_content">
				<div class="i_ctop">
					<span class="i_cat">稻香大米</span>
					<span class="i_tit"><?php echo $v->name;?></span>
					<div class="cart_plus">
						<a data-mid="<?php echo $v->id;?>" href="javascript:void(0)" onClick="minute(this,<?php echo $price;?>)" class="cart_re">-</a>
						<p class="cart_number">0</p>
						<a data-mid="<?php echo $v->id;?>" href="javascript:void(0)" onClick="plus(this,<?php echo $price;?>)" class="cart_add">+</a>
					</div>
					<span class="i_price">￥<?php echo sprintf('%.2f',$price);?></span>
				</div>
				<div class="i_cbottom">
					<p>原料：<?php echo $v->material;?></p>
					<p>口感：<?php echo $v->taste;?></p>
					<p>特色：<?php echo $v->special;?></p>
				</div>
			</div>
		</div>
		<?php endforeach;?>
		<!--稻香大米结束-->
	</li>

	<li>
		<!--三明治精选-->
		<?php
			$menusThird = $menusSMZ;

			foreach($menusThird as $v):
				$price = $v->price;
				if($_SERVER["QUERY_STRING"]=="path=HuaiHaiApp"){
					$price = $price - 1;
				}
		?>
		<div class="i_item">
			<input type="hidden" name="menuid" value="<?php echo $v->id;?>" />
			<div class="i_img">
				<img src="<?php echo $v->showpic;?>" width="100%" alt=""/>
			</div>
			<div class="i_content">
				<div class="i_ctop">
					<span class="i_cat">三明治精选</span>
					<span class="i_tit"><?php echo $v->name;?></span>
					<div class="cart_plus">
						<a data-mid="<?php echo $v->id;?>" href="javascript:void(0)" onClick="minute(this,<?php echo $price;?>)" class="cart_re">-</a>
						<p class="cart_number">0</p>
						<a data-mid="<?php echo $v->id;?>" href="javascript:void(0)" onClick="plus(this,<?php echo $price;?>)" class="cart_add">+</a>
					</div>
					<span class="i_price">￥<?php echo sprintf('%.2f',$price);?></span>
				</div>
				<div class="i_cbottom">
					<p>原料：<?php echo $v->material;?></p>
					<p>口感：<?php echo $v->taste;?></p>
					<p>特色：<?php echo $v->special;?></p>
				</div>
			</div>
		</div>
		<?php endforeach;?>
		<!--三明治精选结束-->
	</li>
	</ul>

	<div class="zc_box">
		<div class="yk_box_center">
        <div class="yk_tit">
           <span>地址</span>
           <div class="yk_close">
              <img src="/resource/i/images/i_close.png" width="100%" alt=""/>
           </div>
        </div>
        <div class="yk_content" id="default_addr">
			<div class="yk_cc">
				<?php if(isset($curAddr)):?>
					<input type="hidden" id="curAddr" value="<?php echo $curAddr->id;?>" />
				<?php endif;?>
				<!--默认加载地址-->
				<div class="yk_item">
					<span class="yk_txt">收货人</span>
					<input type="text" name="uname" value="<?php if(isset($curAddr)){
						echo $curAddr->uname;
					}elseif(count($addr)){
						echo $addr[0]['uname'];
					}?>" class="yk_input">
					<div class="clear"></div>
				</div>
				<div class="yk_item">
					<span class="yk_txt">手机</span>
					<input type="text" name="mobile" value="<?php if(isset($curAddr)){
						echo $curAddr->mobile;
					}elseif(count($addr)){
						echo $addr[0]['mobile'];
					}?>" class="yk_input">
					<div class="clear"></div>
				</div>
				<div class="yk_item">
					<span class="yk_txt">详细地址</span>
					<input type="text" name="address" value="<?php if(isset($curAddr)){
						echo $curAddr->address;
					}elseif(count($addr)){
						echo $addr[0]['address'];
					}?>" class="yk_input">
					<div class="clear"></div>
				</div>

				<div class="yk_item">
					<span class="yk_txt">送餐日期</span>

					<div class="am-input-group date form_datetime-4" data-date="">
						<input size="16" type="text" value="<?php echo $curSendDate?>" class="am-form-field i_date" readonly>
						<span class="am-input-group-label add-on i_date2"><i class="icon-th am-icon-calendar"></i></span>
					</div>

					<div class="clear"></div>
				</div>
				<div class="yk_item">
					<span class="yk_txt">送餐时间</span>
					<select name="sendTime" data-am-selected>
						<?php
							foreach(Yii::app()->params['timeline'] as $k=>$vt):
						?>
							<option value="<?php echo $k;?>" <?php if($curSendTime==$k){
								echo "selected";
							}?>><?php echo $vt;?></option>
						<?php endforeach;?>
					</select>
					<div class="clear"></div>
				</div>
				<button type="button" class="am-btn yk-btn" data-addrtext='<?php if(isset($curAddr)){
					echo $curAddr->address;
				}elseif(count($addr)){
					echo $addr[0]['address'];
				}?>' data-addr='<?php if(isset($curAddr)){
					echo $curAddr->id;
				}elseif(count($addr)){
					echo $addr[0]['id'];
				}?>'>确认</button>
				<button type="button" class="am-btn zc-add-btn">切换地址</button>

				<!--默认加载地址结束-->
			</div>
        </div>
     </div>

	<div class="new_add_center">
        <div class="yk_tit">
			<span>地址</span>
			<div class="yk_close">
				<img src="/resource/i/images/i_close.png" width="100%" alt=""/>
			</div>
        </div>

		<!--已有地址展示段-->
		<?php
			foreach($addr as $v):
		?>
        <div class="yk_content">
			<div class="old_add">
				<div class="old_add_cc">
					<div class="new_add_item">
						<span class="yk_txt">收货人</span>
						<input type="text" name="uname" value="<?php echo $v->uname?>" class="new_add_input">
						<div class="clear"></div>
					</div>
					<div class="new_add_item">
						<span class="yk_txt">手机</span>
						<input type="text" name="mobile" value="<?php echo $v->mobile?>" class="new_add_input">
						<div class="clear"></div>
					</div>
					<div class="new_add_item">
						<span class="yk_txt">详细地址</span>
						<input type="text" name="address" value="<?php echo $v->address?>" class="new_add_input">
						<div class="clear"></div>
					</div>

					<div class="new_add_item">
						<div class="yk_item" style="border-bottom:none">
							<span class="yk_txt">送餐日期</span>

								<div class="am-input-group date form_datetime-4" data-date="">
									<input size="16" type="text" name="sendDate" class="am-form-field i_date" readonly>
									<span class="am-input-group-label add-on"><i class="icon-th am-icon-calendar"></i></span>
								</div>

							</span>
						</div>
						<div class="clear"></div>
					</div>

					<div class="yk_item">
						<span class="yk_txt">送餐时间</span>
						<select name="sendTime" data-am-selected>
						<?php
							foreach(Yii::app()->params['timeline'] as $k=>$vt):
						?>
							<option value="<?php echo $k;?>"><?php echo $vt;?></option>
						<?php endforeach;?>
						</select>
						<div class="clear"></div>
					</div>
				</div>

				<div class="odd_add_icon">
					<span class="am-icon-pencil-square-o old_add_bj"></span>
					<span class="am-icon-trash-o old_add_lj" data-addr='<?php echo $v->id;?>'></span>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<button type="button" class="am-btn yk-btn" style="display:none" data-addrtext='<?php echo $v->address;?>' data-addr='<?php echo $v->id;?>'>确认</button>
			</div>
		</div>
		<?php endforeach;?>
		<!--已有地址展示段结束-->

		<!--新增地址段-->
		<form id="newAddrForm">
		<div class="yk_cc">
			<div class="yk_item">
				<span class="yk_txt">收货人</span>
				<input type="text" name="uname" value="" class="yk_input">
				<div class="clear"></div>
			</div>
			<div class="yk_item">
				<span class="yk_txt">手机</span>
				<input type="text" name="mobile" value="" class="yk_input">
				<div class="clear"></div>
			</div>
			<div class="yk_item">
				<span class="yk_txt">详细地址</span>
				<input type="text" name="address" value="" class="yk_input">
				<div class="clear"></div>
			</div>

			<div class="yk_item">
				<span class="yk_txt">送餐日期</span>
					<div class="am-input-group date form_datetime-4" data-date="">
						<input size="16" type="text" name="sendDate" class="am-form-field i_date" readonly>
						<span class="am-input-group-label add-on"><i class="icon-th am-icon-calendar"></i></span>
					</div>
				</span>
				<div class="clear"></div>
			</div>

			<div class="yk_item">
				<span class="yk_txt">送餐时间</span>
				<select name="sendTime" data-am-selected>
				<?php
					foreach(Yii::app()->params['timeline'] as $k=>$vt):
				?>
					<option value="<?php echo $k;?>"><?php echo $vt;?></option>
				<?php endforeach;?>
				</select>
				<div class="clear"></div>
			</div>
			<button type="button" class="am-btn zc-add-btn zc-add-btn2">新增地址</button>
		</div>
		</form>
		<!--新增地址段结束-->
	</div>
	</div>
</div>
</section>
<footer>
   <div class="i_foot">
    <span class="i_span1">共0份</span><span class="i_span2">￥ 0.00元</span>
    <a href="javascript:;" class="i_qrbtn_a"><button onclick='choosed(this)' class="am-btn i_qrbtn">选好了</button></a>
   </div>
</footer>

<?php
	Yii::app()->clientScript->registerCssFile('/js/layer.m/need/layer.css');
	Yii::app()->clientScript->registerScriptFile('/resource/js/main.js',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile('/js/layer.m/layer.m.js',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile('http://api.map.baidu.com/api?v=1.2',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile('http://api.map.baidu.com/library/GeoUtils/1.2/src/GeoUtils_min.js',CClientScript::POS_END);
?>

<script>
var menuSum = 0;
var priceSum = 0;

var curlng = '';
var curlat = '';

function plus(obj,price){
	var mid = $(obj).data('mid');
	if($(obj).hasClass('cart_add'))
		return false;

	var num = parseInt($(obj).parent().find('.cart_number').text());
	var targetNum = num+parseInt(1);
	if(checkStock(mid,targetNum,'')){
		$(obj).parent().find('.cart_number').text(++num);
		menuSum++;
		priceSum += price;
		$(".i_span1").text("共"+menuSum+"份");
		$(".i_span2").text("￥"+formatCurrency(priceSum)+"元");
	}else{
		layer.open({
			content: '抱歉，库存不够',
			style: 'background-color:#09C1FF; color:#fff; border:none;',
			time: 2
		});
		
		return false;
	}
}

function minute(obj,price){
	var mid = $(obj).data('mid');
	
	if($(obj).siblings('.cart_add').size()>0)
		return false;

	var num = parseInt($(obj).parent().find('.cart_number').text());
	var targetNum = num-parseInt(1);

	if(num>0){
		if(checkStock(mid,targetNum,'')){
			menuSum--;
			priceSum -= price;
			$(".i_span1").text("共"+menuSum+"份");
			$(".i_span2").text("￥"+formatCurrency(priceSum)+"元");
			$(obj).parent().find('.cart_number').text(--num);
		}else{
			layer.open({
				content: '抱歉，库存不够',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});

			return false;
		}
	}
}

function choosed(obj){
		$.ajax({
			url : '/site/ajaxIsLogin.html',
			success : function(res){
				if(res=='false'){
					location.href = "/site/login.html";
				}else{
					$(".zc_box").fadeIn(200);
					//没有地址的时候，直接显示添加地址
					if($(".i_jticon").hasClass('no-addr')){
						$(".yk_box_center").css('display', 'none');
						$(".new_add_center").fadeIn(200);
					}else{
						$(".yk_box_center").css('display', 'block');
						$(".new_add_center").fadeOut(200);
					}	
				}
			}
		});

	return false;

	if($(obj).hasClass('i_qrbtn'))
		return false;

	//保存菜品选择结果的JSON格式
	var jsonStr = '{';
	//总共选择的菜品数量
	var sum = 0;

	$(".i_item").each(function(){
		var tmpMenuId = $(this).find("input[name='menuid']").val();
		var tmpNum = parseInt($(this).find('.cart_number').text());

		if(tmpNum>0){
			sum += tmpNum;
			jsonStr += '"' + tmpMenuId + '":'+tmpNum+',';
		}
	});

	if(jsonStr.indexOf(",")>-1){
		jsonStr = jsonStr.substring(0,jsonStr.length-1);
	}

	jsonStr += '}';

	if(sum>0){
		//下单之前，先检查地址范围，黄浦区外至内环线10份起送
		$.ajax({
			url : '/site/getLngLat.html',
			dataType : 'json',
			async : false,
			success : function(res){
				checkHp(res.address,res.lng,res.lat,jsonStr,sum);
			}
		});
	}else{
		layer.open({
			content: '请先选餐',
			style: 'background-color:#09C1FF; color:#fff; border:none;',
			time: 2
		});
	}
}

function formatCurrency(num) {
    num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
    num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    cents = num%100;
    num = Math.floor(num/100).toString();
    if(cents<10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+','+
    num.substring(num.length-(4*i+3));
    return (((sign)?'':'-') + num + '.' + cents);
}

//检测是否在黄浦区
function checkHp(address,lng,lat,jsonStr,sum){
	var url = window.location.href;
	var redirectUrl = '/member/orderDetail.html?menu='+jsonStr+'&path=';
	if(url.indexOf("?path=HuaiHaiApp") != -1){
		redirectUrl += 'HuaiHaiApp';
	}
	if(checkSpec(address)){
		location.href = redirectUrl;
	}

	// 创建地理编码实例
	var myGeo = new BMap.Geocoder();
	// 根据坐标得到地址描述
	myGeo.getLocation(new BMap.Point(lng,lat), function(result){
		var addr = result.address;

		if(addr.indexOf("黄浦区")==-1 && !checkLJZ(lng,lat)){
			//不在黄埔区和陆家嘴
			if(sum<10){
				layer.open({
					content: '黄浦区和陆家嘴外内环线内10份起送',
					style: 'background-color:#09C1FF; color:#fff; border:none;',
					time: 2
				});
				return false;
			}else{
				location.href = redirectUrl;
			}
		}else{
			location.href = redirectUrl;
		}
	});
}

function checkSpec(address){
	var flag = false;
	$.ajax({
		async : false,
		url : '<?php echo $this->createUrl("site/ajaxCheckSpec");?>',
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