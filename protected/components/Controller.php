<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $pageTitle = '';
	public $pageKeywords = '';
	public $pageDescription = '';

	public $cs = null;
	public $openid = '';
	public $userMobile = '';

	public function init() {
		parent::init();
		$this->pageTitle = '外卖侠';
		$this->cs = Yii::app()->clientScript;
		
		if(!$this->is_weixin()){
			//die("请在微信浏览器中访问本站");
		}
		
		/**if(Yii::app()->user->isGuest){
			Yii::import("ext.wechat.Wechat");

			$options = array(
				'token'=>Yii::app()->params['wechat']['token'],
				'appid'=>Yii::app()->params['wechat']['appid'],
				'appsecret'=>Yii::app()->params['wechat']['appsecret'],
			);

			$weObj = new Wechat($options);

			if(isset($_GET['code']) && isset($_GET['state'])){
				$returnData = $weObj->getOauthAccessToken();
				
				$this->openid = $returnData['openid'];
				$access_token = $returnData['access_token'];
				
				$userinfo = $weObj->getOauthUserinfo($access_token,$this->openid);
				
				//根据openid查询wxUser和user信息
				$wxUser = WxUser::model()->find('openid="'.$this->openid.'"');
				$user = null;

				if($wxUser){
					$user = User::model()->findByPk($wxUser->uid);
				}else{
					//加入到数据库
					$user = new User;
					$user->username = $user->nickname = $userinfo['nickname'];
					$user->password = 'waimaixia';
					$user->avatar = isset($userinfo['headimgurl'])?$userinfo['headimgurl']:'';
					$user->regtime = date('Y-m-d H:i:s');
					
					if($user->validate() && $user->save()){
						$user->refresh();

						$wxUser = new Wxuser;
						$wxUser->uid = $user->id;
						$wxUser->openid = $userinfo['openid'];
						$wxUser->wx_addtime = time();
						$wxUser->wx_subscribe = 1;
						$wxUser->wx_nickname = $userinfo['nickname'];
						$wxUser->wx_sex = $userinfo['sex'];
						$wxUser->wx_country = $userinfo['country'];
						$wxUser->wx_province = $userinfo['province'];
						$wxUser->wx_city = $userinfo['city'];

						if($wxUser->validate() && $wxUser->save()){
						}else{
							file_put_contents('/data/www/wm/waimaixia/log.txt',"引导新增微信用户失败". json_encode($wxUser->getErrors())." \n\r", FILE_APPEND);
						}
					}else{
						$this->redirect($weObj->getOauthRedirect(Yii::app()->request->hostInfo.'/site/index.html'));
					}
				}

				if($user){
					$this->userMobile = isset($user->mobile)?$user->mobile:'';

					$loginForm = new LoginForm;
					$loginForm->username = $user->username;
					$loginForm->password = 'waimaixia';
					$loginForm->rememberMe = true;

					$loginForm->login();
				}
			}else{
				$this->redirect($weObj->getOauthRedirect(Yii::app()->request->hostInfo.'/site/index.html'));
			}
		}**/

		//自动取消支付时间超过20分钟的订单
		date_default_timezone_set('PRC'); //默认时区 
		$timelimit = 20;
		$deadline = time()-intval($timelimit*60);
		$sql = "update `order` set order_status=2,opbak='订单超时".$timelimit."分钟未支付，系统自动取消' where pay_status=0 and deliver_status=0 and ordertype=0 and order_time<".$deadline;
		
		$count = Yii::app()->db->createCommand($sql)->execute();

		if($count && $count>0)
			file_put_contents($_SERVER['DOCUMENT_ROOT']."/log.txt", "【".date("Y-m-d H:i:s")."】系统自动取消".$count."条超时".$timelimit."分钟的订单\r\n",FILE_APPEND);
	}

	public function is_weixin(){ 
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}  
		return false;
	}

}