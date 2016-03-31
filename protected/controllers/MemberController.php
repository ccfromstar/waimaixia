<?php

class MemberController extends Controller{
	
	public function init(){
		parent::init();
		date_default_timezone_set("Asia/Shanghai");
		
		if(Yii::app()->user->isGuest)
			$this->redirect($this->createUrl('/site/login'));
	}

	public function actionIndex(){
		$user = User::model()->findByPk(Yii::app()->user->id);
		if(!$user)
			throw new CHttpException(404,'用户不存在');

		$this->pageTitle = $this->pageTitle.' 用户中心';
		$this->render('index',array(
			'user'=>$user	
		));
	}
	
	//支付前的订单详情页
	public function actionOrderDetail(){
		$cf = Configs::model()->find('`key`="enableorder"');
		if($cf && $cf->value==0){
			throw new CHttpException(404,'抱歉，订餐系统暂时关闭了');
		}

		$selectedMenu = isset($_GET['menu'])?json_decode($_GET['menu'],true):null;

		if(!$selectedMenu && !isset(Yii::app()->session['orderMenu']))
			$this->redirect(Yii::app()->createUrl('/site/index'));

		$session = Yii::app()->session;
		
		$addrId = isset($session['selectedAddr'])?$session['selectedAddr']:0;
		$selectedAddr = Address::model()->findByPk($addrId);
		
		if(!$selectedAddr)
			$this->redirect(Yii::app()->createUrl('/member/addr'));

		$sendDate = $session['sendDate'];
		$sendTime = $session['sendTime'];

		$orderMenu = $selectedMenu?array():(isset(Yii::app()->session['orderMenu'])?Yii::app()->session['orderMenu']:array());
		
		$sumMenu = 0;
		$sumAmount = 0;
		
		if(!count($orderMenu)):
			foreach($selectedMenu as $k=>$v){
				$sumMenu += $v;
				$tmpArr = array();
				$tmpMenu = Menu::model()->findByPk(intval($k));
				$tmpArr['menu'] = $tmpMenu;
				$tmpArr['quantity'] = $v;
				$tmpArr['amount'] = round($tmpMenu->price*$v,2);
				$tmpArr['perPrice'] = round($tmpMenu->price,2);
				$sumAmount += $tmpArr['amount'];
				array_push($orderMenu, $tmpArr);
			}
			
			//已选菜品详情存入session
			Yii::app()->session['orderMenu'] = $orderMenu;
		else:
			foreach($orderMenu as $v){
				$sumMenu += $v['quantity'];
				$sumAmount += $v['amount'];
			}
		endif;
		
		//运费
		$freight = ($sumAmount<100)?6:0;
		//是否显示注册信息
		$showReg = Yii::app()->user->isGuest;
		
		//获取可用的卡券
		$mytickets = TicketUser::model()->findAll('uid='.Yii::app()->user->id.' and status=0 and deadline>"'.date("Y-m-d H:i:s").'" order by deadline');

		//获取地址列表
		$myaddresses = Address::model()->findAll('uid='.Yii::app()->user->id);

		$this->pageTitle = $this->pageTitle.' 订单详情';

		$err_msg = Yii::app()->user->getFlash('ordertimeerror','');

		$this->render('orderDetail',array(
			'orderMenu' => $orderMenu,
			'addr' => $selectedAddr,
			'sendDate' => $sendDate,
			'sendTime' => $sendTime,
			'sumMenu' => $sumMenu,
			'sumAmount' => $sumAmount,
			'showReg' => $showReg,
			'freight' => $freight,
			'mytickets' => $mytickets,
			'myaddresses' => $myaddresses,
			'msg' => $err_msg,
		));
	}
	
	//订单列表页
	public function actionOrders(){
		$orders = Order::model()->findAll('uid='.Yii::app()->user->id.' order by pay_status,order_time desc,pay_time desc');

		$this->pageTitle = $this->pageTitle.' 订单列表';
		$this->render('orders',array(
			'orders'=>$orders,	
		));
	}

	public function actionAddr(){
		$addres = Address::model()->findAll('uid='.Yii::app()->user->id.' order by id desc');

		$this->pageTitle = $this->pageTitle.' 收货地址';
		$this->render('addr',array(
			'addres' => $addres,	
		));
	}
	
	//卡券列表页
	public function actionTickets(){
		$curTime = date('Y-m-d H:i:s');
		//更新所有过期卡券的使用状态
		TicketUser::model()->updateAll(array('status'=>1),'deadline<="'.$curTime.'"');

		$tickets = TicketUser::model()->findAll('uid='.Yii::app()->user->id.' order by status desc,deadline desc');
		
		$this->pageTitle = $this->pageTitle.' 我的卡券';
		$this->render('tickets',array(
			'tickets' => $tickets,	
		));
	}
	
	//分享页面
	public function actionShare(){
		Yii::import("ext.wechat.Wechat");
		$options = array(
			'token'=>Yii::app()->params['wechat']['token'],
			'appid'=>Yii::app()->params['wechat']['appid'],
			'appsecret'=>Yii::app()->params['wechat']['appsecret'],
		);
		$weObj = new Wechat($options);
		$jsapi_ticket = $weObj->getJsTicket();
		
		$url = Yii::app()->request->hostInfo."/member/share.html";
		$arr = $weObj->getJsSign($url);

		$this->pageTitle = $this->pageTitle.' 卡券领取';
		$this->render('share',array(
			'arr' => $arr,	
		));
	}
	
	//创建订单并跳转支付
	public function actionCreateOrder(){
		$uid = Yii::app()->user->id;
		if (!$uid)
			Yii::app()->user->loginRequired();
		
		//$orderMenu = isset(Yii::app()->session['orderMenu'])?Yii::app()->session['orderMenu']:null;

		$orderMenu = array();

		foreach($_POST['orderNumber'] as $k=>$v){
			$tmpArr = array();
			$tmpMenu = Menu::model()->findByPk($_POST['menuId'][$k]);
			$tmpArr['menu'] = $tmpMenu;
			$tmpArr['quantity'] = $v;
			$tmpArr['amount'] = sprintf("%.2f",$tmpMenu->price*$v);
			array_push($orderMenu,$tmpArr);
		}

		if(!count($orderMenu))
			$this->redirect(Yii::app()->createUrl('/site/index'));

		$addr = $_POST['addr'];
		$sendDate = $_POST['sendDate'];
		$sendTime = Yii::app()->params['timeline'][$_POST['sendTime']];

		//对比用户请求的发货时间和当前时间
		if(!$this->checkOrderDate($sendDate)){
			Yii::app()->user->setFlash('ordertimeerror','超出10点不能订当天餐，请重新选择订餐时间');
			$this->redirect('/member/orderDetail');
		}

		$bak = $_POST['bak'];
		$sumAmount = $_POST['sumAmount'];

		$order = new Order;
		$order->uid = $uid;
		$order->order_sn = strtoupper(uniqid()).sprintf('%03d', rand(0, 999));
		$order->order_time = time();
		$order->req_date = $sendDate;
		$order->req_time = $sendTime;
		$order->ticketprice = isset($_POST['ticketprice'])?$_POST['ticketprice']:0;
		$order->ticketids = isset($_POST['ticketids'])?$_POST['ticketids']:'';
		//优惠前总价
		$order->amount = $sumAmount;
		//总价超过100免配送费
		$order->freight = $_POST['freight'];
		//优惠后的价格
		$order->realpay	= $sumAmount - $order->ticketprice + floatval($_POST['freight']);
		$order->address	= $addr;
		$order->bak	= $bak;
		
		$flag = false;
		if($order->validate() && $order->save()){
			$order->refresh();
			//记录订单详情
			foreach($orderMenu as $v){
				$orderDetail = new OrderDetail;
				$orderDetail->order_id = $order->id;
				$orderDetail->goods_name = $v['menu']->name;
				$orderDetail->gid = $v['menu']->id;
				$orderDetail->quantity = $v['quantity'];
				$orderDetail->amount = $v['amount'];

				if($orderDetail->validate() && $orderDetail->save()){
					//更新库存
					$menuStock = MenuStock::model()->find('mid=:mid and stock_date=:sdate',array(
						':mid' => $v['menu']->id,
						':sdate' => $order->req_date,
					));

					if(!$menuStock){
						$menuStock = new MenuStock;
						$menuStock->mid = $v['menu']->id;
						$menuStock->stock_date = $order->req_date;
						$menuStock->stock = $order->orgstock;

						if($menuStock->validate() && $menuStock->save()){
							$menuStock->refresh();
						}else{
							throw new CHttpException(404,'生成库存信息失败');
						}
					}

					$menuStock->stock = $menuStock->stock - $v['quantity'];
					if($menuStock->validate() && $menuStock->save()){
						$menuStock->refresh();
					}else{
						throw new CHttpException(404,'更新库存信息失败');
					}

					$flag = true;
				}else{
					$flag = false;
					Order::model()->deleteByPk($order->id);
					$this->redirect($_SERVER["HTTP_REFERER"]);
				}
			}

			if($flag)
				$this->redirect(Yii::app()->createAbsoluteUrl("/weipay/pay",array('id'=>$order->id)));
		}else{
			$this->redirect($_SERVER["HTTP_REFERER"]);
		}
	}
	
	//异步设置session
	public function actionAjaxSet(){
		if(count($_POST)){
			foreach($_POST as $k=>$v){
				Yii::app()->session[$k] = $v;
			}
		}
	}

	//异步取消订单
	public function actionAjaxCancelOrder(){
		$id = isset($_GET['id'])?$_GET['id']:0;
		$msg = array(
			'success' => false,
			'msg' => '抱歉，数据更新失败',
		);
		
		$order = Order::model()->findByPk($id);
		if(!$order)
			throw CHttpException(404,'抱歉，订单信息不存在');

		$order->order_status = 2;

		if($order->validate() && $order->save()){
			//库存回滚
			foreach($order->details as $v){
				$ms = MenuStock::model()->find('mid=:mid and stock_date=:sdate',array(
					':mid'=>$v->gid,
					':sdate'=>$order->req_date,
				));
				if($ms){
					$ms->stock += $v->quantity;
						if($ms->validate() && $ms->save()){
						$msg['success'] = true;
						$msg['msg'] = '订单取消成功';
					}else{
						$msg['success'] = true;
						$msg['msg'] = '订单取消成功，菜品库存更新失败';
						break;
					}
				}else{
					$msg['success'] = true;
					$msg['msg'] = '订单取消成功，菜品库存更新失败';
					break;
				}
			}
		}

		echo json_encode($msg);
	}

	//异步删除地址
	public function actionAjaxDelAddr($id=0){
		if(Yii::app()->user->isGuest)
			throw new CHttpException(404);

		$msg = array(
			'success' => false,
			'msg' => '抱歉，地址删除失败',
		);

		$model = Address::model()->findByPk($id,'uid=:uid',array(':uid'=>Yii::app()->user->id));

		if(!$model):
			$msg['msg'] = '地址信息不存在';
		else:
			if(Address::model()->deleteByPk($id)){
				$msg['success'] = true;
				$msg['msg'] = '操作成功';
			}
		endif;

		echo json_encode($msg);
	}

	public function actionAjaxUpdateTicket(){
		$uid = Yii::app()->user->id;

		$msg = array(
			'success' => false,
			'msg' => '操作失败',
		);
			
		$sharelog = ShareLog::model()->find('uid='.$uid);

		if(!$sharelog){
			$msg['success'] = true;
			//检查是否已分享过
			$ticket = Yii::app()->ticket;
			$tid = $ticket->addTicketByValue(5,$uid,10);
			
			if($ticket->updateLog($uid,$tid,2,0,0,3,1,$bak='分享送卡券')){
				$sharelog = new ShareLog;
				$sharelog->uid = $uid;
				$sharelog->shareid = 0;
				$sharelog->updtime = date('Y-m-d H:i:s');

				if($sharelog->validate() && $sharelog->save()){
					$msg['msg'] = '分享成功，卡券已赠送';
				}else{
					$msg['msg'] = '分享成功，卡券赠送成功，添加分享记录失败';
				}
			}else{
				$msg['msg'] = '分享成功，卡券赠送失败，请联系管理员';
			}
		}

		echo json_encode($msg);
		
	}
	
	//再来一单
	public function actionSameOrder($id=0){
		$order = Order::model()->findByPk($id);
		if(!$order)
			throw new CHttpException(404);

		$orderDetail = OrderDetail::model()->findAll('order_id='.$id);

		if(!$orderDetail || !count($orderDetail))
			throw new CHttpException(404);
		
		$selectedAddr = Address::model()->findByPk($order->address);

		$sendDate = date('Y-m-d');

		$orderMenu = array();
		$sumMenu = 0;
		$sumAmount = 0;

		foreach($orderDetail as $v){
			$sumMenu += $v->quantity;
			$tmpArr = array();
			$tmpArr['menu'] = $v->menu;
			$tmpArr['quantity'] = $v->quantity;
			$tmpArr['amount'] = $v->amount;
			$tmpArr['perPrice'] = $v->menu->price;
			$sumAmount += $tmpArr['amount'];
			array_push($orderMenu, $tmpArr);
		}

		//已选菜品详情存入session
		Yii::app()->session['orderMenu'] = $orderMenu;
		
		//运费
		$freight = ($sumAmount<100)?6:0;
		//是否显示注册信息
		$showReg = Yii::app()->user->isGuest;
		
		//获取可用的卡券
		$mytickets = TicketUser::model()->findAll('uid='.Yii::app()->user->id.' and status=0 and deadline>"'.date("Y-m-d H:i:s").'" order by deadline');

		//获取地址列表
		$myaddresses = Address::model()->findAll('uid='.Yii::app()->user->id);

		$this->pageTitle = $this->pageTitle.' 订单详情';

		$this->render('orderDetail',array(
			'orderMenu' => $orderMenu,
			'addr' => $selectedAddr,
			'sendDate' => $sendDate,
			'sumMenu' => $sumMenu,
			'sumAmount' => $sumAmount,
			'showReg' => $showReg,
			'freight' => $freight,
			'mytickets' => $mytickets,
			'myaddresses' => $myaddresses,
		));
	}
	
	//查看订单详情
	public function actionShowOrder($id=0){
		$order = Order::model()->findByPk($id);
		if(!$order)
			throw new CHttpException(404);

		$orderDetail = OrderDetail::model()->findAll('order_id='.$id);

		if(!$orderDetail || !count($orderDetail))
			throw new CHttpException(404);
		
		$selectedAddr = Address::model()->findByPk($order->address);
		$orderMenu = array();
		$sumMenu = 0;
		$sumAmount = 0;

		foreach($orderDetail as $v){
			$sumMenu += $v->quantity;
			$tmpArr = array();
			$tmpArr['menu'] = $v->menu;
			$tmpArr['quantity'] = $v->quantity;
			$tmpArr['amount'] = $v->amount;
			$tmpArr['perPrice'] = $v->menu->price;
			$sumAmount += $tmpArr['amount'];
			array_push($orderMenu, $tmpArr);
		}

		$this->pageTitle = $this->pageTitle.' 订单详情';

		$this->render('showOrderDetail',array(
			'orderMenu' => $orderMenu,
			'addr' => $selectedAddr,
			'sumMenu' => $sumMenu,
			'sumAmount' => $sumAmount,
			'order' => $order,
		));
	}

	//本周一
	private function weekStartDate(){
		return date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600));
	}

	//本周五
	private function weekEndDate(){
		return date('Y-m-d',(time()+(7-(date('w')==0?7:date('w')))*24*3600)-2*24*3600);
	}

	//下周一
	private function nextWeekStartDate(){
		if(date('w')==1)
			return date('Y-m-d',strtotime('+2 monday', time()));
		
		return date('Y-m-d',strtotime('+1 monday', time()));
	}

	//下周五
	private function nextWeekEndDate(){
		return date('Y-m-d',strtotime('+2 sunday', time())-2*24*3600);
	}
	
	//判断请求发货的日期是否正确
	private function checkOrderDate($req_date){
		$today = date('Y-m-d');

		if(date('w')>=1 && date('w')<=4){
			if(date('H')<10){
				//当天及本周的日期
				if($req_date>=$today && $req_date<=$this->weekEndDate()){
					return true;
				}
			}else{
				//第二天及本周内、当天以后的日期
				if($req_date>$today && $req_date<=$this->weekEndDate()){
					return true;
				}
			}
		}elseif(date('w')==5){
			if(date('H')<10){
				//当天
				if($req_date==$today){
					return true;
				}
			}else{
				//下周一到下周五
				if($req_date>=$this->nextWeekStartDate() && $req_date<=$this->nextWeekEndDate()){
					return true;
				}
			}
		}else{
			//下周一到下周五
			if($req_date>=$this->nextWeekStartDate() && $req_date<=$this->nextWeekEndDate()){
				return true;
			}
		}

		return false;
	}
}