<?php

/**
 * This is the model class for table "ticket_log".
 *
 * The followings are the available columns in table 'ticket_log':
 * @property integer $id
 * @property integer $uid
 * @property integer $tid
 * @property string $usedtime
 * @property integer $quantity
 * @property integer $order_id
 * @property integer $opter
 * @property integer $optype
 * @property integer $opway
 * @property string $bak
 */
class TicketLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ticket_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, tid, usedtime, quantity, order_id, optype, opway', 'required'),
			array('uid, tid, quantity, order_id, opter, optype, opway', 'numerical', 'integerOnly'=>true),
			array('usedtime, bak', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, tid, usedtime, quantity, order_id, opter, optype, opway, bak', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'uid'),
			'ticket' => array(self::BELONGS_TO, 'Tickets', 'tid'),
			'operator' => array(self::BELONGS_TO, 'User', 'opter'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => '用户',
			'tid' => '优惠券',
			'usedtime' => '使用时间',
			'quantity' => '数量',
			'order_id' => '订单id',
			'opter' => '操作人',
			'optype' => '发放原因',
			'opway' => '操作类型',
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('usedtime',$this->usedtime,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('opter',$this->opter);
		$criteria->compare('optype',$this->optype);
		$criteria->compare('opway',$this->opway);
		$criteria->compare('bak',$this->bak,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function sendLogSearch()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('tid',$this->tid);
		$criteria->compare('usedtime',$this->usedtime,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('opter','>'.$this->opter);
		$criteria->compare('optype',$this->optype);
		$criteria->compare('opway',$this->opway);
		$criteria->compare('bak',$this->bak,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TicketLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
