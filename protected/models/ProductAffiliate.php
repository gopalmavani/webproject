<?php

/**
 * This is the model class for table "product_affiliate".
 *
 * @author Yuvraj Jhala <png625@gmail.com>
 *
 * The followings are the available columns in table 'product_affiliate':
 * @property integer $affiliate_id
 * @property integer $product_id
 * @property integer $aff_level
 * @property string $amount
 * @property string $type_User_FAN
 * @property string $created_at
 * @property string $modified_at
 * @property integer $is_delete

 *
 * The followings are the available model relations:
 * @property ProductInfo $product
 */
class ProductAffiliate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_affiliate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, aff_level, amount', 'required'),
			array('product_id, aff_level, is_delete', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>10),
			array('type_User_FAN', 'length', 'max'=>80),
			array('created_at, modified_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('affiliate_id, product_id, aff_level, amount, type_User_FAN, created_at, modified_at, is_delete', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'ProductInfo', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'affiliate_id' => 'Affiliate',
			'product_id' => 'Product',
			'aff_level' => 'Aff Level',
			'amount' => 'Amount',
			'type_User_FAN' => 'Type User Fan',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
			'is_delete' => 'Is Delete',
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

		$criteria->compare('affiliate_id',$this->affiliate_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('aff_level',$this->aff_level);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('type_User_FAN',$this->type_User_FAN,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);
		$criteria->compare('is_delete',$this->is_delete);

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
	 * @return ProductAffiliate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
