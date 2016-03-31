<?php
class ApiController extends Controller{
	private $errorCode = array(
		'0001' => '缺少token',
		'0002' => 'token验证失败',
		'0003' => '请求参数错误',
		'0004' => '数据提交失败',
		'0005' => '非法请求',
	);

	//响应数组
	public $rspArr = array(
		'status'=>true,
		'errorcode'=>'',
		'errormsg'=>'',
		'response'=>array(
		),
	);

	//接收到的json数组
	public $json = array();

	public function init(){
		if(!Yii::app()->request->isPostRequest){
			$this->returnError('0005');
		}

		$jsonData = file_get_contents('php://input', 'r');
		$data = json_decode($jsonData ,true);
		
		$token = !empty($data['token'])?$data['token']:null;
        $this->json = isset($data['json'])?$data['json']:array();

		if(!$token){
			$this->returnError('0001');
		}elseif($token!=='waimaixia'){
			$this->returnError('0002');
		}
    }

	private function returnError($errorcode){
		$this->rspArr['status'] = false;
		$this->rspArr['errorcode'] = $errorcode;
		$this->rspArr['errormsg'] = $this->errorCode[$errorcode];
		die(json_encode($this->rspArr));
	}
	
	//获取所有订单列表
	public function actionList(){
		$orders = Yii::app()->db->createCommand("select * from `order` order by order_time desc limit 2")->queryAll();
		
		foreach($orders as &$v){
			$addressDetail = Yii::app()->db->createCommand("select uname,address,mobile from address where id=".$v['address'])->queryRow();
			
			$v['addressDetail'] = $addressDetail;

			$orderDetails = Yii::app()->db->createCommand("select goods_name,quantity,amount from order_detail where order_id=".$v['id'])->queryAll();

			$v['orderDetails'] = $orderDetails;
		}
		$this->rspArr['response'] = $orders;
	
		echo json_encode($this->rspArr);
	}
	
	//更新某个订单的发货状态
	public function actionUpdate(){
		if(!isset($this->json['oid'])){
			$this->returnError('0003');
		}
		
		if(Order::model()->updateByPk($this->json['oid'],array(
			'deliver_time' => date('Y-m-d H:i:s'),
			'deliver_status' => 1,
		))){
			$this->rspArr['response']['orderid'] = $this->json['oid'];
			echo json_encode($this->rspArr);
		}else{
			echo $this->returnError('0004');
		}
	}
	
	//根据订单号
	public function actionGetOrderDetail(){
		if(!isset($this->json['osn'])){
			$this->returnError('0003');
		}

		$orderDetails = Yii::app()->db->createCommand("select goods_name,quantity,amount from order_detail where order_id=(select id from `order` where order_sn='".$this->json['osn']."')")->queryAll();

		var_dump($orderDetails);
	}
}