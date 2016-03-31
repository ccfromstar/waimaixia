<?php
class WxConfigs extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'wx_configs';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'WxConfigs|WxConfigs', $n);
	}

	public static function representingColumn() {
		return 'conf_key';
	}

	public function rules() {
		return array(
			array('conf_key, conf_value', 'required'),
			array('conf_key', 'length', 'max'=>100),
			array('id, conf_key, conf_value', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'conf_key' => Yii::t('app', 'Conf Key'),
			'conf_value' => Yii::t('app', 'Conf Value'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('conf_key', $this->conf_key, true);
		$criteria->compare('conf_value', $this->conf_value, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}