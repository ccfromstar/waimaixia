<?php
/**
 * 微信支付页面
 */

class WeipayController extends RController
{
    public function init()
    {
        parent::init();
        ini_set('date.timezone','Asia/Shanghai');
        Yii::import('application.vendor.weipay.*');
    }

    /**
     * 付款页面
     * params int $Id 订单id
     */
    public function actionPay($id=0){
		if(Yii::app()->user->isGuest){
			$this->redirect("/site/login");
		}

		//检查是否已经关闭订餐
		$cf = Configs::model()->find('`key`="enableorder"');
		if($cf && $cf->value==0){
			throw new CHttpException(404,'抱歉，订餐系统暂时关闭了');
		}

		$model = Order::model()->findByPk($id,'order_status = 0');
		if(!$model)
			throw new CHttpException(404,'要支付的订单没有找到');
		
		$goodsName='外卖侠';
		$tools = new JsApiPay();
		//①、获取用户openid
		$openId = $tools->GetOpenid();
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody($goodsName);
		$input->SetAttach($goodsName);
		$input->SetOut_trade_no($model->order_sn);
		$input->SetTotal_fee($model->realpay*100);
		//$input->SetTotal_fee(1);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($goodsName);
		$input->SetNotify_url($this->createAbsoluteUrl("/weipay/notify"));
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		
		$jsApiParameters = $tools->GetJsApiParameters($order);
		$this->render("pay",array("jsApiParameters"=>$jsApiParameters,'model'=>$model));
		
    }

    /*
     * 微支付的异步回调地址
     * 结果已异步通知为准
     */
    public function actionNotify()
    {
        $notify = new PayNotifyCallBack();
        $notify->Handle(false);
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $result = WxPayResults::Init($xml);
        $out_trade_no = $result['out_trade_no'];
        $result_code = $result['result_code'];
        $return_code = $result['return_code'];
		$openid = $result['openid'];
        $transaction_id =$result['transaction_id'];
		
		$model = Order::model()->find("order_sn=:no",array(":no"=>$out_trade_no));
        
		if($result_code == 'SUCCESS') {
			if($model->order_status == 1){
				exit("SUCCESS");
			}else{
				//更新订单状态
				$now =date('Y-m-d H:i:s');
				$model->pay_status = 1;
				$model->order_status = 1;
				$model->pay_time = $now;
				$model->save();
				
				if(isset($model->ticketids) && $model->ticketids!=''){
					file_put_contents('/data/www/wm/waimaixia/log.txt', "使用卡券 \n\r", FILE_APPEND);
					$idsArr = explode(",",$model->ticketids);
					file_put_contents('/data/www/wm/waimaixia/log.txt', "卡券：".json_encode($idsArr)." \n\r", FILE_APPEND);

					foreach($idsArr as $v){
						$tu = TicketUser::model()->findByPk($v);
						//如果使用了卡券，更新用户卡券状态，并添加使用记录
						$tk = Yii::app()->ticket;
						$tk->delUserTicket($v);
						$tk->updateLog($model->uid,$tu->tid,1,$model->id,0,2,0,$bak='购买消费');
					}
				}

				exit("SUCCESS");
			}
		}else{
			exit('FAIL');
		}
    }
    /*
     * params $Id int
     * 支付成功通知页面 z只是做显示，不做业务逻辑
     */
    public function actionSuccess($id){
		$this->layout = false;
        $this->render("success",array("id"=>$id));
    }

    //微支付告警地址
    public function actionNotice(){
        exit("success");
    }
}


