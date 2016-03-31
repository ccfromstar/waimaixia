<?php
class Ticket extends CApplicationComponent
{
	/**
	 * 更新卡券操作记录
	 * @param  integer  $uid      用户ID
	 * @param  integer  $tid      卡券ID
	 * @param  integer  $quantity 卡券数量
	 * @param  integer $orderid  订单ID(为0表示非购买操作)
	 * @param  integer $opter    操作人(为0表示系统自动更新)
	 * @param  integer  $optype   发放原因(注册、下单、分享等)
	 * @param  integer  $opway    操作类型(1.增;0.减)
	 * @param  string  $bak      操作备注
	 * @return boolean           操作成功与否
	 */
	public function updateLog($uid,$tid,$quantity,$orderid=0,$opter=0,$optype,$opway,$bak=''){
		$model = new TicketLog;
		$model->uid = $uid;
		$model->tid = $tid;
		$model->usedtime = date('Y-m-d H:i:s');
		$model->quantity = $quantity;
		$model->order_id = $orderid;
		$model->opter = $opter;
		$model->optype = $optype;
		$model->opway = $opway;
		$model->bak = $bak;

		if($model->validate() && $model->save()){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 用户新增一张卡券
	 * @param  [type] $uid   [description]
	 * @param  [type] $quantity   [description]
	 * @param  [type] $tid   [description]
	 * @return [type]        [description]
	 */
	public function addUserTicket($uid,$tid){
		$ticket = Tickets::model()->findByPk($tid);

		if(!$ticket)
			throw new CHttpException(404, '卡券不存在');

		$model = new TicketUser;
		$model->uid = $uid;
		$model->tid = $tid;
		//$model->deadline = date('Y-m-d H:i:s',time() + $ticket->effect_days*24*60*60+28800); 
		$timeStr = '2016-06-30 23:59:59';
		$model->deadline = date('Y-m-d H:i:s',strtotime($timeStr)); 
		$model->status = 0;
		$model->updtime = date('Y-m-d H:i:s');

		if($model->validate() && $model->save())
			return true;
		else
			return false;
	} 

	/**
	 * 用户使用一张卡券
	 * @param  int $id TicketUser表ID
	 * @return boolean    更新成功与否
	 */
	public function delUserTicket($id){
		return TicketUser::model()->updateByPk($id,array(
			'status' => 1,
			'updtime' => date('Y-m-d H:i:s'),
		));
	}
	
	/**
	 * 给指定用户发放指定面值的卡券
	 * @param  int $price 面值
	 * @param  int $uid 用户id
	 * @param int $quantity 发放数量
	 * @return int 发放的卡券id
	 */
	public function addTicketByValue($price,$uid,$quantity=1){
		$arr = array();
		$ticket = Tickets::model()->find('worth='.intval($price));

		if($ticket){
			for($i=0;$i<$quantity;$i++){
				if($this->addUserTicket($uid,$ticket->id)){
					array_push($arr,$ticket->id);
				}
			}
		}

		return $ticket->id;
	}
}