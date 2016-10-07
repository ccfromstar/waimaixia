<section>
  <div class="login_logo">
     <div class="login_logo1"><img src="/resource/i/images/logo.png" width="100%" alt=""/></div>
     <div class="login_logo2"><img src="/resource/i/images/login_title.png" width="100%" alt=""/></div>
  </div>
  <div class="login_main">
     <div class="login_item">
	 <input type="hidden" id="refer" value="<?php echo $refer;?>" />
     <div class="login_item_aa"><span>手机号</span><input type="text" id="mobile" name="" value="" placeholder=""></div>
     </div>
     <div class="login_item login_item2">
         <div class="login_item_aa login_item_bb">
            <span class="yzm_txt">验证码</span><input type="text" id="yzm" name="" value="" placeholder="" class="login_yzm">
         </div>
         <button class="am-btn get_yzm">获取验证码</button>
         <div class="clear"></div>
     </div>
     <a href="javascript:;" class="login_a"><button type="button" class="am-btn login_btn">登录</button></a>
  </div>

</section>
<footer>
   <div class="login_ad">
      <div class="login_adl"></div>
      <div class="login_adc">
         <span class="login_wzl">注册立享</span>
         <div class="login_wzc">
            <div class="login_img">
                <img src="/resource/i/images/login_icon2.png" width="100%" alt=""/>
            </div>

         </div>
         <span class="login_wzr">现金红包 !</span>
        <div class="clear"></div>
      </div>
      <div class="login_adr"></div>
      <div class="clear"></div>
   </div>
   <div style="height: 49px"></div>
   <!--<button type="button" class="am-btn am-btn-block login_zf_btn">立即支付</button>-->
</footer>

<?php
	Yii::app()->clientScript->registerCssFile('/js/layer.m/need/layer.css');
	Yii::app()->clientScript->registerScriptFile('/js/layer.m/layer.m.js',CClientScript::POS_END);
?>
<script>
$(function(){
	$(".get_yzm").bind('click',function(){
		var mobile = $("#mobile").val();
		if(mobile==''){
			layer.open({
				content: '请输入手机号',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
		}else if(!mobile.match(/^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8]))\d{8}$/)){
			layer.open({
				content: '请输入正确的手机号',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
		}else{
			sendMessage(this);
		}
	});

	$(".login_a").bind('click',function(){
		var yzm = $("#yzm").val();
		var mobile = $("#mobile").val();

		if($.trim(yzm)==''){
			layer.open({
				content: '请输入验证码',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
		}else if(mobile==''){
			layer.open({
				content: '请输入手机号',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
		}else if(!mobile.match(/^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8]))\d{8}$/)){
			layer.open({
				content: '请输入正确的手机号',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
		}else{
			$.ajax({
				type: "POST",
				dataType: "json",
				url: '/site/checkYzm.html', //目标地址
				data: {mobile:mobile,yzm:yzm},
				success: function(res){
					layer.open({
						content: res.msg,
						style: 'background-color:#09C1FF; color:#fff; border:none;',
						time: 2
					});

					if(res.success){
						//跳转到来源页
						var refer = $("#refer").val();
						if(refer!==''){
							location.href = refer;
						}else{
							var url = window.location.href;
							if(url.indexOf("?path=HuaiHaiApp") != -1){
								location.href = "/site/index.html?path=HuaiHaiApp";
							}else{
								location.href = "/member/index.html";
							}
						}
					}
				}
			});
		}
	});
});

var InterValObj; //timer变量，控制时间
var count = 60; //间隔函数，1秒执行
var curCount;//当前剩余秒数

function sendMessage(obj) {
	curCount = count;
　　//设置button效果，开始计时
	$(obj).attr("disabled", "true");
	$(obj).text("(" + curCount + ")秒");
	InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
	
	//向后台发送处理数据
	var mobile = $("#mobile").val();

	$.ajax({
		type: "POST",
		dataType: "text",
		url: '/site/sendYzm.html', //目标地址
		data: {mobile:mobile},
		success: function(msg){}
	});
}

//timer处理函数
function SetRemainTime() {
	if (curCount == 0) {                
		window.clearInterval(InterValObj);//停止计时器
		$(".get_yzm").removeAttr("disabled");//启用按钮
		$(".get_yzm").text("获取验证码");
	}else {
		curCount--;
		$(".get_yzm").text("(" + curCount + ")秒");
	}
}
</script>