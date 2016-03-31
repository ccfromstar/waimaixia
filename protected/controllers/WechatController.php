<?php

class WechatController extends RController{
	public $options;

	public function init(){
		Yii::import("ext.wechat.Wechat");

		$this->options = array(
			'token'=>Yii::app()->params['wechat']['token'],
			'appid'=>Yii::app()->params['wechat']['appid'],
			'appsecret'=>Yii::app()->params['wechat']['appsecret'],
		);
	}

	public function actionGate(){
		$weObj = new Wechat($this->options);
		$weObj->valid();
	}
}