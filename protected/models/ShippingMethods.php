<?php

/**
 * This is the model class for table "shipping_methods".
 *
 * @author Yuvraj Jhala <png625@gmail.com>
 *
 * The followings are the available columns in table 'shipping_methods':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $logo
 * @property string $price
 * @property integer $is_active
 * @property string $comment
 * @property string $created_at
 * @property string $modified_at

 */
class ShippingMethods extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shipping_methods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, logo, price, comment, created_at', 'required'),
			array('is_active', 'numerical', 'integerOnly'=>true),
			array('name, price', 'length', 'max'=>255),
			array('comment', 'length', 'max'=>1000),
			array('modified_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, logo, price, is_active, comment, created_at, modified_at', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'description' => 'Description',
			'logo' => 'Logo',
			'price' => 'Price',
			'is_active' => 'Is Active',
			'comment' => 'Comment',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
				'pagination' => array(
					'pagesize' => 50,
				),
				'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShippingMethods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
