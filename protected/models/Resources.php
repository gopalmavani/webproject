<?php

/**
 * This is the model class for table "resources".
 *
 * @author Yuvraj Jhala <png625@gmail.com>
 *
 * The followings are the available columns in table 'resources':
 * @property integer $resource_id
 * @property string $resource_name
 * @property string $resource_description
 * @property string $resource_address
 * @property integer $is_available
 * @property string $created_at
 * @property string $modified_at

 */
class Resources extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'resources';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('resource_name, is_available', 'required'),
			array('is_available', 'numerical', 'integerOnly'=>true),
			array('resource_name', 'length', 'max'=>200),
			array('resource_address', 'length', 'max'=>400),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('resource_id, resource_name, resource_description, resource_address, is_available, created_at, modified_at', 'safe', 'on'=>'search'),
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
			'resource_id' => 'Resource',
			'resource_name' => 'Resource Name',
			'resource_description' => 'Resource Description',
			'resource_address' => 'Resource Address',
			'is_available' => 'Is Available',
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

		$criteria->compare('resource_id',$this->resource_id);
		$criteria->compare('resource_name',$this->resource_name,true);
		$criteria->compare('resource_description',$this->resource_description,true);
		$criteria->compare('resource_address',$this->resource_address,true);
		$criteria->compare('is_available',$this->is_available);
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
	 * @return Resources the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
