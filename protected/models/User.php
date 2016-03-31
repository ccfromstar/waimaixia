<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property string $email
 * @property string $mobile
 * @property integer $status
 * @property string $regtime
 * @property integer $type
 */
class User extends CActiveRecord
{
	public $repassword;
    public $verifyCode;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	public function behaviors(){
        return array(
            'RegisterBehavior' => array(
                'class' => 'application.models.behaviors.RegisterBehavior',
            ),
        );
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username', 'required'),
			array('email', 'unique'),
			array('email', 'email'),
			array('status, type', 'numerical', 'integerOnly'=>true),
			array('username, nickname', 'length', 'max'=>50),
			array('password', 'length', 'max'=>32, 'min' => 6),
			array('email', 'length', 'max'=>150),
			array('mobile', 'length', 'max'=>20),
			array('avatar, bak', 'length', 'max' => 255),
			array('regtime', 'safe'),

			array('password, repassword', 'required', 'on' => 'reg, add'),
			array('repassword', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入的密码不一致', 'on' => 'reg, edit, add,'),
			/**array('verifyCode','required', 'on' => 'reg'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'reg'),**/

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, nickname, avatar, email, mobile, status, regtime, type, bak', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '账号',
			'password' => '密码',
			'nickname' => '昵称',
			'email' => '邮箱',
			'mobile' => '手机',
			'status' => '状态',
			'regtime' => 'Regtime',
			'type' => '用户类型',
			'repassword' => '重复密码',
			'verifyCode' => '验证码',
			'avatar' => '头像',
			'bak' => '备注',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('regtime',$this->regtime,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('bak',$this->bak);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
