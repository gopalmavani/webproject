<?php

/**
 * This is the model class for table "testimonial".
 *
 * @author Yuvraj Jhala <png625@gmail.com>
 *
 * The followings are the available columns in table 'testimonial':
 * @property integer $id
 * @property string $Title
 * @property string $description
 * @property string $media
 * @property string $category
 * @property string $type
 * @property string $created_at
 * @property string $modified_at

 */
class Testimonial extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'testimonial';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('Title,description,media', 'required'),
            array('created_at, modified_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, Title, description, media, category,type, created_at, modified_at', 'safe', 'on'=>'search'),
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
            'Title' => 'Title',
            'description' => 'Description',
            'media' => 'Media',
            'category' => 'Category',
            'type,' => 'Type',
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
        $criteria->compare('Title',$this->Title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('media',$this->media,true);
        $criteria->compare('category',$this->category,true);
        $criteria->compare('type,',$this->type,true);
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
     * @return Testimonial the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
