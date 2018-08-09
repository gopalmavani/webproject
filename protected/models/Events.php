<?php

/**
 * This is the model class for table "events".
 *
 * @author Yuvraj Jhala <png625@gmail.com>
 *
 * The followings are the available columns in table 'events':
 * @property integer $event_id
 * @property string $event_title
 * @property string $type
 * @property string $event_key
 * @property string $event_image
 * @property string $event_description
 * @property string $event_host
 * @property string $resource_id
 * @property string $event_location
 * @property string $event_start
 * @property string $event_end
 * @property string $event_url
 * @property string $user_id
 * @property string $event_type
 * @property string $recurring_span
 * @property string $booking_start_date
 * @property string $coupon_code
 * @property string $coupon_start_date
 * @property string $coupon_end_date
 * @property string $total_tickets
 * @property string $max_num_bookings
 * @property string $price
 * @property string $denomination_id
 * @property string $service_duration
 * @property string $is_cancel
 * @property string $hours_before_cancel
 * @property string $cancellation_rules
 * @property string $hours_before_registration
 * @property string $created_at
 * @property string $modified_at
 * @property string $color

 */
class Events extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'events';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('event_key, event_title,type, event_type,event_start, created_at,price,denomination_id,color', 'required'),
            array('event_title', 'length', 'max'=>50),
            array('event_key,type, event_image, event_host,resource_id, event_location, event_url, user_id, event_type, recurring_span, coupon_code, total_tickets, max_num_bookings, price,denomination_id,service_duration,color', 'length', 'max'=>255),
            array('event_start, event_end, booking_start_date, coupon_start_date,resource_id, coupon_end_date, modified_at,is_cancel,hours_before_cancel,cancellation_rules,hours_before_registration', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('event_id, event_title,type, event_key, event_image, event_description, event_host,resource_id, event_location, event_start, event_end, event_url, user_id, event_type, recurring_span, booking_start_date, coupon_code, coupon_start_date, coupon_end_date, total_tickets, max_num_bookings, price, denomination_id,service_duration,is_cancel,hours_before_cancel,cancellation_rules,hours_before_registration,created_at, modified_at', 'safe', 'on'=>'search'),
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
            'event_id' => 'Event',
            'event_title' => 'Event Title',
            'type' => 'Type',
            'event_key' => 'Event Key',
            'event_image' => 'Event Image',
            'event_description' => 'Event Description',
            'event_host' => 'Event Host',
            'resource_id' => 'Resource',
            'event_location' => 'Event Location',
            'event_start' => 'Event Start',
            'event_end' => 'Event End',
            'event_url' => 'Event Url',
            'user_id' => 'User',
            'event_type' => 'Event Type',
            'recurring_span' => 'Recurring Span',
            'booking_start_date' => 'Booking Start Date',
            'coupon_code' => 'Coupon code',
            'coupon_start_date' => 'Coupon Start Date',
            'coupon_end_date' => 'Coupon End Date',
            'total_tickets' => 'Total Bookings',
            'max_num_bookings' => 'Maximum number of bookings',
            'price' => 'Price',
            'denomination_id' => 'Denomination',
            'service_duration' => 'Service Duration',
            'is_cancel' => 'Is Cancel',
            'hours_before_cancel' => 'Hours Before Cancellation',
            'cancellation_rules' => 'Cancellation Rules',
            'hours_before_registration' => 'Hours Before Registration',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'color' => 'Color',
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

        $criteria->compare('event_id',$this->event_id);
        $criteria->compare('event_title',$this->event_title,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('event_key',$this->event_key,true);
        $criteria->compare('event_image',$this->event_image,true);
        $criteria->compare('event_description',$this->event_description,true);
        $criteria->compare('event_host',$this->event_host,true);
        $criteria->compare('resource_id',$this->resource_id,true);
        $criteria->compare('event_location',$this->event_location,true);
        $criteria->compare('event_start',$this->event_start,true);
        $criteria->compare('event_end',$this->event_end,true);
        $criteria->compare('event_url',$this->event_url,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('event_type',$this->event_type,true);
        $criteria->compare('recurring_span',$this->recurring_span,true);
        $criteria->compare('booking_start_date',$this->booking_start_date,true);
        $criteria->compare('coupon_code',$this->coupon_code,true);
        $criteria->compare('coupon_start_date',$this->coupon_start_date,true);
        $criteria->compare('coupon_end_date',$this->coupon_end_date,true);
        $criteria->compare('total_tickets',$this->total_tickets,true);
        $criteria->compare('max_num_bookings',$this->max_num_bookings,true);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('denomination_id',$this->denomination_id,true);
        $criteria->compare('service_duration',$this->service_duration,true);
        $criteria->compare('is_cancel',$this->is_cancel,true);
        $criteria->compare('hours_before_cancel',$this->hours_before_cancel,true);
        $criteria->compare('cancellation_rules',$this->cancellation_rules,true);
        $criteria->compare('hours_before_registration',$this->hours_before_registration,true);
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
     * @return Events the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
