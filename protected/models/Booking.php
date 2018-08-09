<?php

/**
 * This is the model class for table "booking".
 *
 * The followings are the available columns in table 'booking':
 * @property integer $booking_id
 * @property string $event_id
 * @property string $timeslot
 * @property string $username
 * @property string $email
 * @property string $mobile_number
 * @property string $building
 * @property string $street
 * @property string $city
 * @property string $region
 * @property string $country
 * @property string $postcode
 * @property string $user_id
 * @property string $price
 * @property string $coupon_code
 * @property string $status
 * @property string $number_of_people
 * @property string $checkindate
 * @property string $checkoutdate
 * @property string $id_file_1
 * @property string $id_file_2
 * @property integer $product_id
 * @property string $company_name
 * @property string $vat_number
 * @property string $orderid
 * @property string $created_at
 * @property string $modified_at
 */
class Booking extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'booking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, username, email, mobile_number, building, street, city, country, postcode, user_id, price, status, number_of_people, checkindate, checkoutdate, created_at', 'required'),
			array('product_id', 'numerical', 'integerOnly'=>true),
			array('event_id, timeslot, username, email, mobile_number, building, street, city, region, country, postcode, user_id, price, coupon_code, status, number_of_people, company_name, vat_number,orderid', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('booking_id, event_id, timeslot, username, email, mobile_number, building, street, city, region, country, postcode, user_id, price, coupon_code, status, number_of_people, checkindate, checkoutdate, id_file_1, id_file_2, product_id, company_name, vat_number,orderid, created_at, modified_at', 'safe', 'on'=>'search'),
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
			'booking_id' => 'Booking',
			'event_id' => 'Event',
			'timeslot' => 'Timeslot',
			'username' => 'Username',
			'email' => 'Email',
			'mobile_number' => 'Mobile Number',
			'building' => 'Building',
			'street' => 'Street',
			'city' => 'City',
			'region' => 'Region',
			'country' => 'Country',
			'postcode' => 'Postcode',
			'user_id' => 'User',
			'price' => 'Price',
			'coupon_code' => 'Coupon Code',
			'status' => 'Status',
			'number_of_people' => 'Number Of People',
			'checkindate' => 'Checkindate',
			'checkoutdate' => 'Checkoutdate',
			'id_file_1' => 'Id File 1',
			'id_file_2' => 'Id File 2',
			'product_id' => 'Product',
			'company_name' => 'Company Name',
			'vat_number' => 'Vat Number',
			'orderid' => 'orderId',
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

		$criteria->compare('booking_id',$this->booking_id);
		$criteria->compare('event_id',$this->event_id,true);
		$criteria->compare('timeslot',$this->timeslot,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile_number',$this->mobile_number,true);
		$criteria->compare('building',$this->building,true);
		$criteria->compare('street',$this->street,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('region',$this->region,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('postcode',$this->postcode,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('coupon_code',$this->coupon_code,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('number_of_people',$this->number_of_people,true);
		$criteria->compare('checkindate',$this->checkindate,true);
		$criteria->compare('checkoutdate',$this->checkoutdate,true);
		$criteria->compare('id_file_1',$this->id_file_1,true);
		$criteria->compare('id_file_2',$this->id_file_2,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('vat_number',$this->vat_number,true);
		$criteria->compare('orderid',$this->orderid,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Booking the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
