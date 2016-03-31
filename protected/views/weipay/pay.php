<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				if(res.err_msg != 'get_brand_wcpay_request:ok'){
                    alert("抱歉，支付失败");
                    window.location.href="<?php echo $this->createUrl('/member/orders')?>";
                }else{
                    window.location.href="<?php echo $this->createUrl('/weipay/success',array('id'=>$model->id))?>";
                }
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	window.onload = function(){
        callpay();
	};

	</script>

<div class="wrap">

</div>

