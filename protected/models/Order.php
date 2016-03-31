<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $id
 * @property integer $uid
 * @property string $order_sn
 * @property integer $order_time
 * @property string $req_date
 * @property string $req_time
 * @property string $realpay
 * @property string $amount
 * @property integer $pay_status
 * @property string $deliver_time
 * @property integer $deliver_status
 * @property integer $order_status
 * @property string $pay_time
 * @property integer $address
 * @property string $bak
 */
class Order extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order';
	}

	public $nowtime;
	public $staticDetail;
	public $staticSum;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, order_sn, order_time, req_date, req_time, realpay, amount, address', 'required'),
			array('uid, order_time, pay_status, deliver_status, order_status, address, oprater, ordertype, paytype', 'numerical', 'integerOnly'=>true),
			array('order_sn, req_date, req_time, bak, ticketids, opbak', 'length', 'max'=>255),
			array('realpay, amount', 'length', 'max'=>10),
			array('deliver_time, pay_time', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, order_sn, order_time, req_date, req_time, realpay, amount, pay_status, deliver_time, deliver_status, order_status, pay_time, address, bak, freight, ticketprice, ticketids, oprater, ordertype, paytype, opbak', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'buyer' => array(self::BELONGS_TO,'User','uid'),
			'addr' => array(self::BELONGS_TO,'Address','address'),
			'details' => array(self::HAS_MANY,'OrderDetail','order_id'),
			'operator' => array(self::BELONGS_TO,'User','oprater'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => '用户ID',
			'order_sn' => '订单号',
			'order_time' => '下单时间',
			'req_date' => '要求发货日期',
			'req_time' => '要求发货时间',
			'realpay' => '实际支付金额',
			'amount' => '优惠前总金额',
			'pay_status' => '支付状态',
			'deliver_time' => '发货时间',
			'deliver_status' => '发货状态',
			'order_status' => '订单状态',
			'pay_time' => '支付时间',
			'address' => '收货地址',
			'bak' => '用户备注',
			'freight' => '配送费',
			'ticketprice' => '卡券抵扣金额',
			'ticketids' => '使用的卡券id(对应ticket_user表主键)',
			'oprater' => '操作人',
			'ordertype' => '订单类型',
			'paytype' => '支付类型',
			'opbak' => '操作人备注',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('order_sn',$this->order_sn,true);
		$criteria->compare('order_time',$this->order_time);
		$criteria->compare('req_date',$this->req_date,true);
		$criteria->compare('req_time',$this->req_time,true);
		$criteria->compare('realpay',$this->realpay,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('pay_status',$this->pay_status);
		$criteria->compare('deliver_time',$this->deliver_time,true);
		$criteria->compare('deliver_status',$this->deliver_status);
		$criteria->compare('order_status',$this->order_status);
		$criteria->compare('pay_time',$this->pay_time,true);
		$criteria->compare('address',$this->address);
		$criteria->compare('bak',$this->bak,true);
		$criteria->compare('freight',$this->freight,true);
		$criteria->compare('ticketprice',$this->ticketprice,true);
		$criteria->compare('ticketids',$this->ticketids,true);
		$criteria->compare('oprater',$this->oprater);
		$criteria->compare('ordertype',$this->ordertype);
		$criteria->compare('paytype',$this->paytype);
		$criteria->compare('opbak',$this->opbak, true);

		if(!empty($_GET['starttime'])){
            $sdate = $_GET['starttime'];
            $edate = $_GET['endtime'];
            if(!$edate) {
                $edate = date('Y-m-d');
            } else {
                $edate = date('Y-m-d', strtotime($edate));
            }
            $etime = $edate . ' 23:59:59';
            $etime_int = strtotime($etime);

            if(!$sdate) {
                $sdate = date('Y-m-d', strtotime('-365 days', $etime_int));

            } else {
                $sdate = date('Y-m-d', strtotime($sdate));

            }
            $stime = $sdate . ' 00:00:00';
            $stime_int = strtotime($stime);

            $days = floor(($etime_int - $stime_int) / (3600 * 24));
            if($days > 365) {
                $sdate = date('Y-m-d', strtotime('-365 days', $etime_int));
                $stime = $sdate . ' 00:00:00';
            }
			
            $criteria->addBetweenCondition('order_time', $stime_int, $etime_int);
        }
		
		$criteria->addCondition('order_status!=3');
		$criteria->order = 'order_time desc,pay_status desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	//日统计查询
	public function dailyStatic()
	{
		$orders = Order::model()->findAll('req_date="'.$this->nowtime.'" and pay_status=1 and (order_status=1 or order_status=4) order by pay_status desc');

		$arr = array();

		foreach($orders as $k=>&$v){
			$tmpArr = array();
			$tmpArr = $v->attributes;
			$tmpArr['order_time'] = isset($v->order_time)?date("Y-m-d H:i:s",$v->order_time):"";
			$tmpArr['buyer'] = isset($v->buyer)?$v->buyer->username:"用户已注销";
			$tmpArr['addr_mobile'] = isset($v->addr)?$v->addr->mobile:"地址已注销";
			$tmpArr['addr'] = isset($v->addr)?$v->addr->address:"地址已注销";

			$tmpSum = 0;
			$tmpDes = '';

			foreach($v->details as $dv){
				$tmpSum += $dv->quantity;
				$tmpDes .= $dv->menu->name." : ".$dv->quantity."份; \r\n";
			}

			$tmpArr['staticSum'] = $tmpSum;
			$tmpArr['staticDetail'] = $tmpDes;

			$arr[] = $tmpArr;
		}

		Yii::app()->cache->set('dailyStatic',$arr,3600);
		
		$dataProvider = new CArrayDataProvider($arr, array(
			'id'=>'orderstatic',
			
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
		
		return $dataProvider;
	} 

	//月统计查询
	public function monthStatic(){
		$sql = 'select DATE_FORMAT(req_date,"%Y-%m-%d") as id,sum(amount) as amount,sum(realpay) as realpay,count(1) as all_count from `order` where pay_status=1 and (order_status=1 or order_status=4) and DATE_FORMAT(req_date,"%Y-%m")="'.$this->nowtime.'" group by 
		DATE_FORMAT(req_date,"%Y-%m-%d") order by req_date desc';
		
		$arr = Yii::app()->db->createCommand($sql)->queryAll();

		$staticRow = array();
		$staticRow['id'] = '合计';
		$staticRow['amount'] = 0;
		$staticRow['realpay'] = 0;
		$staticRow['all_count'] = 0;

		foreach($arr as $v){
			$staticRow['amount'] += $v['amount'];
			$staticRow['realpay'] += $v['realpay'];
			$staticRow['all_count'] += $v['all_count'];
		}
		
		$staticRow['amount'] = sprintf("%.2f",$staticRow['amount']);
		$staticRow['realpay'] = sprintf("%.2f",$staticRow['realpay']);
		$arr[] = $staticRow;

		Yii::app()->cache->set('monthStatic',$arr,3600);
		
		$dataProvider = new CArrayDataProvider($arr, array(
			'id'=>'orderstatic',
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
		
		return $dataProvider;
	}

	//年统计查询
	public function yearStatic(){
		$sql = 'select DATE_FORMAT(req_date,"%Y-%m") as id,sum(amount) as amount,sum(realpay) as realpay,count(1) as all_count,count(case pay_status when 1 then 1 end) as pay_count from `order` where pay_status=1 and (order_status=1 or order_status=4) and DATE_FORMAT(req_date,"%Y")="'.$this->nowtime.'" group by DATE_FORMAT(req_date,"%Y-%m") order by order_time desc';
		
		$arr = Yii::app()->db->createCommand($sql)->queryAll();

		$staticRow = array();
		$staticRow['id'] = '合计';
		$staticRow['amount'] = 0;
		$staticRow['realpay'] = 0;
		$staticRow['all_count'] = 0;

		foreach($arr as $v){
			$staticRow['amount'] += $v['amount'];
			$staticRow['realpay'] += $v['realpay'];
			$staticRow['all_count'] += $v['all_count'];
		}
		
		$staticRow['amount'] = sprintf("%.2f",$staticRow['amount']);
		$staticRow['realpay'] = sprintf("%.2f",$staticRow['realpay']);
		$arr[] = $staticRow;
		
		Yii::app()->cache->set('yearStatic',$arr,3600);

		$dataProvider = new CArrayDataProvider($arr, array(
			'id'=>'orderstatic',
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
		
		return $dataProvider;
	}

	//自定义统计查询
	public function customStatic(){
		$crit = new CDbCriteria;

		if(!empty($_GET['starttime'])){
			$sdate = $_GET['starttime'];
			$edate = $_GET['endtime'];
			if(!$edate) {
				$edate = date('Y-m-d');
			} else {
				$edate = date('Y-m-d', strtotime($edate));
			}
			$etime = $edate . ' 23:59:59';
			$etime_int = strtotime($etime);

			if(!$sdate) {
				$sdate = date('Y-m-d', strtotime('-365 days', $etime_int));

			} else {
				$sdate = date('Y-m-d', strtotime($sdate));

			}
			$stime = $sdate . ' 00:00:00';
			$stime_int = strtotime($stime);

			$days = floor(($etime_int - $stime_int) / (3600 * 24));
			if($days > 365) {
				$sdate = date('Y-m-d', strtotime('-365 days', $etime_int));
				$stime = $sdate . ' 00:00:00';
			}
			
			$crit->addBetweenCondition('req_date', $sdate, $edate);
		}
		
		$crit->addCondition('pay_status=1 and (order_status=1 or order_status=4)');
		$crit->order = 'req_date desc,pay_status desc';

		$orders = Order::model()->findAll($crit);

		$arr = array();

		foreach($orders as $k=>&$v){
			$tmpArr = array();
			$tmpArr = $v->attributes;
			$tmpArr['order_time'] = isset($v->order_time)?date("Y-m-d H:i:s",$v->order_time):"";
			$tmpArr['buyer'] = isset($v->buyer)?$v->buyer->username:"用户已注销";
			$tmpArr['addr_mobile'] = isset($v->addr)?$v->addr->mobile:"地址已注销";
			$tmpArr['addr'] = isset($v->addr)?$v->addr->address:"地址已注销";

			$tmpSum = 0;
			$tmpDes = '';

			foreach($v->details as $dv){
				$tmpSum += $dv->quantity;
				$tmpDes .= $dv->menu->name." : ".$dv->quantity."份; \r\n";
			}

			$tmpArr['staticSum'] = $tmpSum;
			$tmpArr['staticDetail'] = $tmpDes;

			$arr[] = $tmpArr;
		}
		
		Yii::app()->cache->set('customStatic',$arr,3600);

		$dataProvider = new CArrayDataProvider($arr, array(
			'id'=>'orderstatic',
			
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
		
		return $dataProvider;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
