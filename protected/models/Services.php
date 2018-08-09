<?php

/**
 * This is the model class for table "services".
 *
 * @author Yuvraj Jhala <png625@gmail.com>
 *
 * The followings are the available columns in table 'services':
 * @property integer $service_id
 * @property string $service_name
 * @property string $service_description
 * @property string $service_image
 * @property integer $is_display
 * @property string $service_price
 * @property string $service_duration
 * @property string $category
 * @property string $user_id
 * @property string $resource_id
 * @property string $total_booking
 * @property string $created_at
 * @property string $modified_at

 */
class Services extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'services';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('service_name, service_description, is_display,total_booking', 'required'),
            array('is_display', 'numerical', 'integerOnly'=>true),
            array('service_name, service_image, service_price, category,total_booking', 'length', 'max'=>255),
            array('service_duration', 'length', 'max'=>300),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('service_id, service_name, service_description, service_image, is_display, service_price, service_duration, category, created_at, modified_at', 'safe', 'on'=>'search'),
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
            'service_id' => 'Service',
            'service_name' => 'Service Name',
            'service_description' => 'Service Description',
            'service_image' => 'Service Image',
            'is_display' => 'Is Display',
            'service_price' => 'Service Price',
            'service_duration' => 'Service Duration',
            'category' => 'Category',
            'user_id' => 'User',
            'resource_id' => 'Resource',
            'total_booking' => 'Total Booking',
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

        $criteria->compare('service_id',$this->service_id);
        $criteria->compare('service_name',$this->service_name,true);
        $criteria->compare('service_description',$this->service_description,true);
        $criteria->compare('service_image',$this->service_image,true);
        $criteria->compare('is_display',$this->is_display);
        $criteria->compare('service_price',$this->service_price,true);
        $criteria->compare('service_duration',$this->service_duration,true);
        $criteria->compare('category',$this->category,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('resource_id',$this->resource_id,true);
        $criteria->compare('total_booking',$this->total_booking,true);
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
     * @return Services the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
