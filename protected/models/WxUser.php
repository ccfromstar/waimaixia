<?php

/**
 * This is the model class for table "wx_user".
 *
 * The followings are the available columns in table 'wx_user':
 * @property integer $id
 * @property integer $uid
 * @property string $openid
 * @property integer $wx_addtime
 * @property integer $wx_subscribe
 * @property integer $wx_quittime
 * @property string $wx_nickname
 * @property integer $wx_sex
 * @property string $wx_country
 * @property string $wx_province
 * @property string $wx_city
 */
class WxUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wx_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('openid', 'required'),
			array('uid, wx_addtime, wx_subscribe, wx_quittime, wx_sex', 'numerical', 'integerOnly'=>true),
			array('openid, wx_country, wx_province, wx_city', 'length', 'max'=>100),
			array('wx_nickname', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, openid, wx_addtime, wx_subscribe, wx_quittime, wx_nickname, wx_sex, wx_country, wx_province, wx_city', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => 'user表中的ID',
			'openid' => '用户识别名',
			'wx_addtime' => '关注时间',
			'wx_subscribe' => '是否关注',
			'wx_quittime' => '取消关注时间',
			'wx_nickname' => '微信昵称',
			'wx_sex' => '性别 1男 2女',
			'wx_country' => 'Wx Country',
			'wx_province' => 'Wx Province',
			'wx_city' => 'Wx City',
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
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('wx_addtime',$this->wx_addtime);
		$criteria->compare('wx_subscribe',$this->wx_subscribe);
		$criteria->compare('wx_quittime',$this->wx_quittime);
		$criteria->compare('wx_nickname',$this->wx_nickname,true);
		$criteria->compare('wx_sex',$this->wx_sex);
		$criteria->compare('wx_country',$this->wx_country,true);
		$criteria->compare('wx_province',$this->wx_province,true);
		$criteria->compare('wx_city',$this->wx_city,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WxUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
