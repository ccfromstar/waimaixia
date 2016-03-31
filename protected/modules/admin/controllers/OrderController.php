<?php

class OrderController extends AdminController{

	public function actionList(){
		$model = new Order('search');
        $model->unsetAttributes();

		$model->ordertype = 0;

        if(isset($_GET['Order'])) {
            $model->setAttributes($_GET['Order']);
        }

        $this->render('list', array('model' => $model));
	}

	public function actionView($id=0){
		$order = Order::model()->findByPk($id);
		
		$detail = OrderDetail::model()->findAll('order_id='.$id);

		$this->render('view',array(
			'order'=>$order,
			'detail'=>$detail,
		));
	}

	public function actionDeliver(){
		$oid = isset($_GET['orderid'])?$_GET['orderid']:0;

		if(Order::model()->updateByPk($oid,array(
			'deliver_time' => date('Y-m-d H:i:s'),
			'deliver_status' => 1,
			'order_status' => 4,
			'oprater' => Yii::app()->user->id,
		))){
			Yii::app()->user->setFlash('success','更新发货信息成功');
		}else{
			Yii::app()->user->setFlash('error','更新发货信息失败');
		}

		$this->redirect($this->createUrl('order/list'));
	}

	public function actionDelete($id=0){
		$order = Order::model()->findByPk($id);
		if(!$order)
			throw new CHttpException(404);

		$order->order_status = 3;
		if($order->validate() && $order->save()){
			Yii::app()->user->setFlash('success','订单删除成功');
		}else{
			Yii::app()->user->setFlash('error','订单删除失败');
		}

		$this->redirect(Yii::app()->createUrl('/admin/order/list'));
	}

	public function actionMandelete($id=0){
		$order = Order::model()->findByPk($id);
		if(!$order)
			throw new CHttpException(404);

		$order->order_status = 3;
		if($order->validate() && $order->save()){
			Yii::app()->user->setFlash('success','订单删除成功');
		}else{
			Yii::app()->user->setFlash('error','订单删除失败');
		}

		$this->redirect(Yii::app()->createUrl('/admin/order/manlist'));
	}

	//400订单列表
	public function actionManlist(){
		$model = new Order('search');
        $model->unsetAttributes();

		$model->ordertype = 1;

        if(isset($_GET['Order'])) {
            $model->setAttributes($_GET['Order']);
        }

        $this->render('manlist', array('model' => $model));
	}

	//新增400订单
	public function actionAddorder($id=0){
		$operate = '新增订单';

		$model = Order::model()->findByPk($id);

		$menu = Menu::model()->findAll('status=1 order by updtime desc');

		if(!$model){
			$model = new Order;
			$addr = new Address;
		}else{
			$addr = Address::model()->findByPk($model->address);
		}

		$req = Yii::app()->request;
		if($req->getIsPostRequest()){
			$addrModel = new Address;
			$addrModel->setAttributes($req->getPost('Address'));

			$orderModel = new Order;
			$orderModel->setAttributes($req->getPost('Order'));
			$orderData = $req->getPost('orderData');
			
			//根据地址传递过来的手机号，查询用户是否存在
			$user = User::model()->find('username=:uname',array(':uname'=>$addrModel['mobile']));
			if(!$user){
				//用户不存在，新增
				$user = new User;
				$user->username = $user->mobile = $addrModel['mobile'];
				$user->password = 'waimaixia';
				$user->regtime = date('Y-m-d H:i:s');
				//新增成功，赠送卡券
				if($user->validate() && $user->save()){
					$user->refresh();
					
					file_put_contents('/data/www/wm/waimaixia/log.txt', "400下单，新注册用户:".$mobile." ;开始赠送卡券 \n\r", FILE_APPEND);
					//卡券发放
					$tk = Yii::app()->ticket;
					$tid = $tk->addTicketByValue(10,$user->id,1);
					
					if(!$tid){
						file_put_contents('/data/www/wm/waimaixia/log.txt', "400下单，新注册用户:".$mobile." ;赠送1张10元卡券失败 \n\r", FILE_APPEND);
					}else{
						$tk->updateLog($user->id,$tid,1,0,0,1,1,'400下单，后台自动注册用户赠送卡券');
			
						$tid = $tk->addTicketByValue(5,$user->id,8);
						
						if(!$tid){
							file_put_contents('/data/www/wm/waimaixia/log.txt', "400下单，新注册用户:".$mobile." ;赠送8张5元卡券失败 \n\r", FILE_APPEND);
						}else{
							$tk->updateLog($user->id,$tid,8,0,0,1,1,'400下单，后台自动注册用户赠送卡券');
						}
					}
				}else{
					Yii::app()->user->setFlash('error','用户自动注册失败');
					$this->redirect(array('order/addorder'));
				}
			}

			//地址入库
			if(!isset($addrModel->id) || $addrModel->id==0){
				$addrModel->uid = $user->id;
				if($addrModel->validate() && $addrModel->save()){
					$addrModel->refresh();
				}else{
					Yii::app()->user->setFlash('error','地址自动入库失败');
					$this->redirect(array('order/addorder'));
				}
			}
			
			$orderData = CJSON::decode($orderData);

			$orderModel->uid = $user->id;
			$orderModel->order_sn = strtoupper(uniqid()).sprintf('%03d', rand(0, 999));
			$orderModel->order_time = time();
			$orderModel->ticketprice = isset($_POST['ticketprice'])?$_POST['ticketprice']:0;
			$orderModel->ticketids = isset($_POST['ticketids'])?$_POST['ticketids']:'';
			$orderModel->req_time = Yii::app()->params['timeline'][$orderModel->req_time];
			
			//优惠前总计
			$amount = 0;
			
			foreach($orderData as $k=>$v){
				$tmpMenu = Menu::model()->findByPk($v['mid']);
				$amount += $tmpMenu->price*$v['qua'];
			}

			$orderModel->amount = sprintf('%.2f',$amount);
			$orderModel->address = $addrModel->id;
			$orderModel->freight = $amount<100?6:0;
			$orderModel->realpay = $orderModel->amount - $orderModel->ticketprice + $orderModel->freight;
			$orderModel->oprater = Yii::app()->user->id;
			$orderModel->ordertype = 1;
			$orderModel->paytype = 1;

			if($orderModel->validate() && $orderModel->save()){
				$orderModel->refresh();

				//记录订单详情
				foreach($orderData as $v){
					$tmpamount = 0;
					$tmpMenu = Menu::model()->findByPk($v['mid']);
					$tmpamount += $tmpMenu->price*$v['qua'];

					$orderDetail = new OrderDetail;
					$orderDetail->order_id = $orderModel->id;
					$orderDetail->goods_name = $tmpMenu->name;
					$orderDetail->gid = $tmpMenu->id;
					$orderDetail->quantity = $v['qua'];
					$orderDetail->amount = $tmpamount;

					if($orderDetail->validate() && $orderDetail->save()){
						Yii::app()->user->setFlash('success','恭喜，下单成功');
						$this->redirect(array('order/manlist'));
					}else{
						Order::model()->deleteByPk($orderModel->id);
						Yii::app()->user->setFlash('error','抱歉，生成订单详情失败');
						$this->redirect(array('order/addorder'));
					}
				}
			}else{
				Yii::app()->user->setFlash('error','抱歉，订单生成失败');
				$this->redirect(array('order/addorder'));
			}
		}
		
		$this->render('addorder',array(
			'operate' => $operate,
			'addr' => $addr,
			'model' => $model,
			'menu' =>$menu,
		));
	}
	
	//根据手机号码异步查询用户及用户地址信息
	public function actionAjaxGetUserByMobile(){
		$res = array('success'=>false,'data'=>'');

		$mobile = Yii::app()->request->getQuery('mobile');
		$user = User::model()->find('username=:uname',array(':uname'=>$mobile));

		if($user){
			//老用户
			$res['success'] = true;
			$res['uid'] = $user->id;

			$addresses = Address::model()->findAll('uid=:uid',array(":uid"=>$user->id));
			if($addresses && count($addresses)):
				$res['hasAddr'] = true;
				$res['data'] = $addresses;
			endif;
		}

		echo CJSON::encode($res);
		exit;
	}

	public function actionManview($id=0){
		$order = Order::model()->findByPk($id);
		
		$detail = OrderDetail::model()->findAll('order_id='.$id);

		$this->render('manview',array(
			'order'=>$order,
			'detail'=>$detail,
		));
	}

	//修改400订单的发货状态
	public function actionManDeliver(){
		$oid = isset($_GET['orderid'])?$_GET['orderid']:0;

		if(Order::model()->updateByPk($oid,array(
			'deliver_time' => date('Y-m-d H:i:s'),
			'deliver_status' => 1,
			'order_status' => 4,
			'oprater' => Yii::app()->user->id,
		))){
			Yii::app()->user->setFlash('success','更新发货信息成功');
		}else{
			Yii::app()->user->setFlash('error','更新发货信息失败');
		}

		$this->redirect($this->createUrl('order/manlist'));
	}
	
	//修改400订单的收款状态
	public function actionManReceive(){
		$oid = isset($_GET['orderid'])?$_GET['orderid']:0;
		$model = Order::model()->findByPk($oid,'pay_status=0');

		if(!$model){
			Yii::app()->user->setFlash('error','订单不存在或已付款');
			$this->redirect(array('order/manlist'));
		}

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

		Yii::app()->user->setFlash('success','更新付款状态成功');
		$this->redirect($this->createUrl('order/manlist'));
	}

	//异步查询可用优惠券
	public function actionAjaxGetTick(){
		$res = array('success'=>false,'data'=>'');
		$uid = Yii::app()->request->getQuery("uid",0);

		$ticketUsers = TicketUser::model()->findAll('uid=:uid and deadline>:dl and status=:st',array(
			':uid' => $uid,
			':dl' => date("Y-m-d H:i:s"),
			':st' => 0,
		));

		if($ticketUsers && count($ticketUsers)){
			$res['success'] = true;
			$htmlContent = '';

			foreach($ticketUsers as $v){
				$htmlContent .= "<div style='margin-bottom:5px;'><input name='tickchk' value='".$v->id."' type='checkbox' data-price='".$v->ticket->worth."' />".$v->ticket->name." 【折价：".$v->ticket->worth."; 截止日期:".$v->deadline."】</div>";
			}

			$res['data'] = $htmlContent;
		}

		echo CJSON::encode($res);exit;
	}
}