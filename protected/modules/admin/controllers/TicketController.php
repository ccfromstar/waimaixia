<?php

class TicketController extends AdminController{

	public function actionList(){
		$model = new Tickets;
		$model->unsetAttributes();

		$this->render('list',array('model'=>$model));
	}

	public function actionUpdate($id=0){
		if($id>0){
			$model = Tickets::model()->findByPk($id);
			$operate = '编辑卡券';
		}else{
			$model = new Tickets;
			$operate = '新增卡券';
		}

		if(isset($_POST['Tickets'])){
			$model->setAttributes($_POST['Tickets']);

			if($model->validate() && $model->save()){
				$model->refresh();
				Yii::app()->user->setFlash('success','保存成功');
				$this->redirect($this->createUrl('/admin/ticket/list'));
			}else{
				var_dump($model->getErrors());
			}
			Yii::app()->end();
		}

		$this->render('update',array(
			'model' => $model,	
			'operate' => $operate,
		));
	}

	public function actionView($id=0){
		$model = Tickets::model()->findByPk($id);

		$this->render('view',array(
			'model'=>$model,
		));
	}

	public function actionSendList(){
		$ticketLog = new TicketLog('search');
		$ticketLog->unsetAttributes();
		$ticketLog->opter = 0;

		$this->render('sendList',array(
			'model' => $ticketLog,	
		));
	}

	public function actionSend(){
		$model = new TicketLog;
		$model->usedtime = date('Y-m-d H:m:i');
		$model->order_id = 0;
		$model->optype = 4;
		$model->opway = 1;
		
		if(isset($_POST['TicketLog'])){
			$model->setAttributes($_POST['TicketLog']);
			$model->opter = Yii::app()->user->id;
			
			//发卡券
			//循环发放
			for ($x=1; $x<=intval($model->quantity); $x++) {
  				$tk = Yii::app()->ticket;
				$tid = $tk->addUserTicket($model->uid,$model->tid);
			} 
			
			if($tid){
				if($model->validate() && $model->save()){
					$model->refresh();

					Yii::app()->user->setFlash('success','恭喜，优惠券发放成功');
					$this->redirect(array('ticket/sendList'));
				}else{
					Yii::app()->user->setFlash('error','抱歉，保存发放记录失败');
				}
			}else{
				Yii::app()->user->setFlash('error','抱歉，优惠券发放失败');
			}
		}

		$this->render('send',array('model'=>$model));
	}

	public function actionGetUserByMobile(){
		$msg = array('success'=>false,'uid'=>'');
		$mobile = Yii::app()->request->getQuery('mobile','');
		$user = User::model()->find('username=:uname',array(':uname'=>$mobile));

		if($user){
			$msg = array('success'=>true,'uid'=>$user->id);
		}

		echo CJSON::encode($msg);exit;
	}
}