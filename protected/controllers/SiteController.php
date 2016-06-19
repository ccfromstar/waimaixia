<?php

class SiteController extends Controller{
	public $options;

	public function init() {
		parent::init();
	}

	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
				'minLength' => 4,
				'maxLength' => 4
			),

		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->layout = false;
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error',array(
					'error'=>$error['message'],	
				));
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout(false);
		$this->redirect(Yii::app()->homeUrl);
	}


	public function actionAjaxlogin() {
		$this->layout = false;
		$model = new LoginForm;

		$returnUrl = Yii::app()->request->urlReferrer;
		if ($returnUrl && false === strpos($returnUrl, 'login')) {
			Yii::app()->user->setState('returnUrl', $returnUrl);
		} else {
			Yii::app()->user->setState('returnUrl', Yii::app()->user->returnUrl);
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$ret = array('success' => false, 'message' => '');
			$model->attributes=$_POST['LoginForm'];
			if($model->validate() && $model->login()) {
				$ret = array('success' => true, 'message' => Yii::app()->user->getState('returnUrl'));
			} else {
				$errors = $model->getErrors();
                $errmsg = '';
                foreach($errors as $e) {
                    $errmsg .= $e[0]."\n";
                }
                $ret['message'] = $errmsg;
			}

			echo CJSON::encode($ret);
			Yii::app()->end();
		}

		$this->render('ajaxlogin', array('model' => $model));
	}

	public function actionIndex(){
		$mid = isset($_GET['mid'])?$_GET['mid']:0;
		
		$session = Yii::app()->session;

		//当前选中地址
		$curAddr = null;
		if(isset($session['selectedAddr'])){
			$curAddr = Address::model()->findByPk($session['selectedAddr']);
		}

		$curSendDate = isset($session['sendDate'])?$session['sendDate']:'';
		$curSendTime = isset($session['sendTime'])?$session['sendTime']:'a';

		//地址列表
		$addr = array();
		if(!Yii::app()->user->isGuest):
			$addr = Address::model()->findAll('uid='.Yii::app()->user->id);
		endif;
		
		$menusBl = Yii::app()->cache->get('menusBl');
		if(!$menusBl){
			$crit = new CDbCriteria;
			$crit->order = 'price asc,updtime desc';
			$crit->condition = "cid=6 and status=1";
			$menusBl = Menu::model()->findAll($crit);
		}
		
		$menusJk = Yii::app()->cache->get('menusJk');
		if(!$menusJk){
			$crit = new CDbCriteria;
			$crit->order = 'price asc,updtime desc';
			$crit->condition = "cid=5 and status=1";
			$menusJk = Menu::model()->findAll($crit);
			Yii::app()->cache->set('menusJk', $menusJk, 60*60);
		}

		$menusSMZ = Yii::app()->cache->get('menusSMZ');
		if(!$menusSMZ){
			$crit = new CDbCriteria;
			$crit->order = 'price asc,updtime desc';
			$crit->condition = "cid=7 and status=1";
			$menusSMZ = Menu::model()->findAll($crit);
			Yii::app()->cache->set('menusSMZ', $menusSMZ, 60*60);
		}

		$this->pageTitle = $this->pageTitle.' 首页';
		$this->render('index',array(
			'addr' => $addr,
			'menusBl' => $menusBl,
			'menusJk' => $menusJk,
			'menusSMZ' => $menusSMZ,
			'mid' => $mid,
			'curAddr' => $curAddr,
			'curSendDate' => $curSendDate,
			'curSendTime' => $curSendTime,
		));
	}

	public function actionLogin(){
		if(!Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->createUrl('/site/index'));

		$refer = isset($_GET['refer'])?$_GET['refer']:'';

		if($refer=='orderDetail'){
			$refer = Yii::app()->createAbsoluteUrl('/member/orderDetail');
		}

		$this->pageTitle = $this->pageTitle.' 手机验证';
		$this->render('login',array(
			'refer' => $refer,
		));
	}

	public function actionAjaxAddAddr(){
		$id = isset($_POST['id'])?$_POST['id']:0;

		$msg = array(
			'success' => false,
			'msg' => '数据提交失败',
		);
		
		if($id==0):
			$model = new Address;
		else:
			$model = Address::model()->findByPk($id);
		endif;

		$model->uid = Yii::app()->user->id;
		$model->uname = $_POST['uname'];
		$model->mobile = $_POST['mobile'];
		$model->address = $_POST['address'];
		$model->lng = $_POST['curlng'];
		$model->lat = $_POST['curlat'];

		if($model->validate() && $model->save()){
			$model->refresh();
			$session = Yii::app()->session;
			$session['selectedAddr'] = $model->id;
			$session['sendDate'] = $_POST['sendDate'];
			$session['sendTime'] = $_POST['sendTime'];

			$msg['success'] = true;
			$msg['msg'] = '数据提交成功';
		}

		echo json_encode($msg);
		exit;
	}

	public function actionSendYzm(){
		$mobile = $_POST['mobile'];
		Yii::app()->session['yzm_'.$mobile]= $yzm = rand(100000,999999);
		$target = Yii::app()->params['sms_config']['addr'];
		$username = Yii::app()->params['sms_config']['username'];
		$pwd = Yii::app()->params['sms_config']['pwd'];

		if(empty($mobile)){
			exit('手机号码不能为空');
		}

		$post_data = "account=$username&password=$pwd&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$yzm."。请不要把验证码泄露给其他人。");
		
		$gets =  xml_to_array(Post($post_data, $target));
		
		error_log($gets['SubmitResult']['code']);
		//if($gets['SubmitResult']['code']!=2){
			file_put_contents('/var/www/sms_log.txt', $mobile." ".$gets['SubmitResult']['msg']."【".date('Y-m-d H:i:s')."】 \n\r", FILE_APPEND);
		//}
	}

	public function actionCheckYzm(){
		$msg = array(
			'success' => false,
			'msg' => '您输入的验证码有误，请重新输入',
		);

		$mobile = $_POST['mobile'];
		$yzm = $_POST['yzm'];
		$yzmInSession = Yii::app()->session['yzm_'.$mobile];
		
		if(isset($yzmInSession)){
			if($yzm==$yzmInSession){
				$msg['success'] = true;
				$msg['msg'] = '手机验证成功';
				unset(Yii::app()->session['yzm_'.$mobile]);
				//根据手机号查找用户
				$user = User::model()->find('username="'.$mobile.'"');

				if(!$user){		//用户不存在，则自动注册
					$user = new User;
					$user->username = $user->mobile = $mobile;
					$user->password = 'waimaixia';
					$user->regtime = date('Y-m-d H:i:s');

					if($user->validate() && $user->save()){
						$user->refresh();
						
						file_put_contents('/var/www/log.txt', "新注册用户:".$mobile." ;开始赠送卡券 \n\r", FILE_APPEND);
						//卡券发放
						$tk = Yii::app()->ticket;
						$tid = $tk->addTicketByValue(10,$user->id,1);
						
						if(!$tid){
							file_put_contents('/var/www/log.txt', "新注册用户:".$mobile." ;赠送1张10元卡券失败 \n\r", FILE_APPEND);
						}else{
							$tk->updateLog($user->id,$tid,1,0,0,1,1,'注册赠送卡券');
				
							$tid = $tk->addTicketByValue(5,$user->id,8);
							
							if(!$tid){
								file_put_contents('/var/www/log.txt', "新注册用户:".$mobile." ;赠送8张5元卡券失败 \n\r", FILE_APPEND);
							}else{
								$tk->updateLog($user->id,$tid,8,0,0,1,1,'注册赠送卡券');
							}
						}
					}
				}
				
				//登录
				$loginForm = new LoginForm;
				$loginForm->username = $user->username;
				$loginForm->password = 'waimaixia';
				$loginForm->rememberMe = true;

				$loginForm->login();
			}
		}else{
			$msg['msg'] = '验证码已过期，请重新获取';
		}

		echo json_encode($msg);
	}
	
	//异步获取百度经纬度
	public function actionAddrToLngAndLat(){
		$msg = array(
			'success' => false,
			'lng' => '',
			'lat' => '',
		);
		$address = isset($_GET['address'])?$_GET['address']:'';

		if(!strstr($address,"上海市")){
			$address = "上海市".$address;
		}
		
		$address = urlencode($address);	//地址编码成 UTF-8 字符的二字符十六进制值

		$res = $this->http_get("http://api.map.baidu.com/geocoder/v2/?address=$address&output=json&ak=6iOpZf2A3UiosgkLKDDLE2bA");

		$msgArr = json_decode($res,true);

		if(!$msgArr['status']){
			$lng = $msgArr['result']['location']['lng'];  //百度地图经度
			$lat = $msgArr['result']['location']['lat'];  //百度地图纬度
			
			$msg['success'] = true;
			$msg['lng'] = $lng;
			$msg['lat'] = $lat;
		}

		echo json_encode($msg);
	}

	private function http_get($url){
		$ch = curl_init($url) ;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
		$output = curl_exec($ch) ; 

		return $output;
	}

	public function actionGetLngLat(){
		$msg = array(
			'success' => false,
			'lng' => '',
			'lat' => '',
		);
		$session = Yii::app()->session;

		if(isset($_GET['addrId'])){
			$addrId = $_GET['addrId'];
		}else{
			$addrId = isset($session['selectedAddr'])?$session['selectedAddr']:0;
		}

		$model = Address::model()->findByPk($addrId);

		if($model){
			$msg = array(
				'success' => true,
				'lng' => $model->lng,
				'lat' => $model->lat,
				'address' => $model->address,
			);
		}

		echo json_encode($msg);
	}

	//异步判断是否登录
	public function actionAjaxIsLogin(){
		if(Yii::app()->user->isGuest){
			echo "false";
		}else{
			echo "true";
		}
	}

	public function actionShared(){
		$this->render('shared');
	}

	//检查特殊地址
	public function actionAjaxCheckSpec(){
		$addr = isset($_POST['addr'])?$_POST['addr']:"";
		$configModel = Configs::model()->find("`key`='spaddr'");
		$arr = explode("\r\n",$configModel->value);

		$arr = array_map(function($v){ return trim($v);},$arr);
		
		$flag = false;
		foreach($arr as $v){
			if(strpos($addr,$v)!==false){
				$flag = true;
				break;
			}
		}

		if($flag)
			echo 'success';
		else
			echo 'error';
		exit;
	}
	
	//异步检查库存
	public function actionAjaxCheckStock(){
		$sendDate = Yii::app()->request->getQuery('sendDate','');
		$mid = Yii::app()->request->getQuery('mid',0);
		$orderNumb = Yii::app()->request->getQuery('orderNumb',0);
		
		if($sendDate==''){
			//获取订餐日期
			$session = Yii::app()->session;
			$sendDate = $session['sendDate'];
		}
		
		if(!$sendDate || $sendDate==''){
			throw CHttpException(404,'请选择订餐日期');	
		}

		$m = Menu::model()->findByPk($mid,'status=1');

		if(!$m){
			throw new CHttpException(404,'菜品不存在或已下架');
		}
		
		//查询订餐日期对应的菜品库存量
		$menuStock = MenuStock::model()->find('mid=:mid and stock_date=:sdate',array(
			':mid'=>$mid,
			':sdate'=>$sendDate,
		));

		if(!$menuStock){
			$menuStock = new MenuStock;
			$menuStock->mid = $mid;
			$menuStock->stock_date = $sendDate;
			$menuStock->stock = $m->orgstock;

			if($menuStock->validate() && $menuStock->save()){
				$menuStock->refresh();
			}else{
				throw new CHttpException(404,'生成库存信息失败');
			}
		}

		if($menuStock->stock<$orderNumb)
			echo "error";
		else
			echo "success";

		exit;
	}
}