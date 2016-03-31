<?php
	Yii::app()->clientScript->registerCssFile('/js/layer.m/need/layer.css');
	Yii::app()->clientScript->registerScriptFile('/js/layer.m/layer.m.js',CClientScript::POS_END);
?>

<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<script>
wx.config({
    debug: false,
	appId: "<?php echo $arr['appid'];?>",
	timestamp: "<?php echo $arr['timestamp'];?>",
	nonceStr: "<?php echo $arr['noncestr'];?>",
	signature: "<?php echo $arr['signature'];?>",
    jsApiList: ['onMenuShareTimeline']
});

wx.ready(function(){
    wx.onMenuShareTimeline({
		link : "<?php echo Yii::app()->request->hostInfo.'/site/shared.html';?>",
		imgUrl : "<?php echo Yii::app()->request->hostInfo.'/resource/i/images/share.jpg';?>",
		success: function () { 
			$.ajax({
				url : '/member/ajaxUpdateTicket.html',
				dataType : 'json',
				success : function(res){
					if(res.success){
						layer.open({
							content: res.msg,
							style: 'background-color:#09C1FF; color:#fff; border:none;',
							time: 2
						});
					}
				}
			});
		},
		cancel: function () {}
	});
});

wx.error(function(res){
	layer.open({
		content: res.errMsg,
		style: 'background-color:#09C1FF; color:#fff; border:none;',
		time: 2
	});
});
</script>

<section>
  <div class="user_ly_banner"><img src="/resource/i/images/share.jpg"></div>
  <div class="user_ly_hb"><img src="/resource/i/images/hb.png"><span>点击右上角分享到朋友圈，再领<i>￥50</i> 元优惠券</span></div>
  <div class="user_input">
    <div class="clear"></div>
  </div>
  <div class="user_button">
    <!--<button type="button" class="am-btn am-btn-default am-round"> 立即领取 </button>-->
  </div>
</section>