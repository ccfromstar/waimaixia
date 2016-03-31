<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property integer $id
 * @property string $name
 * @property string $showpic
 * @property string $desc
 * @property string $price
 * @property integer $status
 * @property integer $updtime
 * @property integer $cid
 * @property string $material
 * @property string $taste
 * @property string $special
 */
class Menu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, price, updtime, cid, orgstock', 'required'),
			array('status, updtime, cid, orgstock', 'numerical', 'integerOnly'=>true),
			array('name, showpic, material, taste', 'length', 'max'=>255),
			array('price', 'length', 'max'=>10),
			array('desc, special', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, showpic, desc, price, status, updtime, cid, material, taste, special, orgstock', 'safe', 'on'=>'search'),
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
			'name' => '菜名',
			'showpic' => '展示图',
			'desc' => '详情',
			'price' => '单价',
			'status' => '状态',
			'updtime' => '更新时间',
			'cid' => '分类',
			'material' => '原料',
			'taste' => '口感',
			'special' => '特色',
			'orgstock' => '初始库存',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('showpic',$this->showpic,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('updtime',$this->updtime);
		$criteria->compare('cid',$this->cid);
		$criteria->compare('material',$this->material,true);
		$criteria->compare('taste',$this->taste,true);
		$criteria->compare('special',$this->special,true);
		$criteria->compare('orgstock',$this->orgstock);
		$criteria->order = 'status desc,updtime desc';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
