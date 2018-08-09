<?php

/**
 * This is the model class for table "api_accounts".
 *
 * @author Yuvraj Jhala <png625@gmail.com>
 *
 * The followings are the available columns in table 'api_accounts':
 * @property integer $Login
 * @property string $Name
 * @property string $Currency
 * @property string $Balance
 * @property string $Equity
 * @property string $EmailAddress
 * @property string $Group
 * @property string $Agent
 * @property string $RegistrationDate
 * @property string $Leverage
 * @property string $Address
 * @property string $City
 * @property string $State
 * @property string $PostCode
 * @property string $Country
 * @property string $PhoneNumber
 * @property string $created_date
 * @property string $modified_at

 */
class ApiAccounts extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'api_accounts';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('Login, Equity', 'required'),
            array('Login', 'numerical', 'integerOnly'=>true),
            array('Name', 'length', 'max'=>200),
            array('Currency', 'length', 'max'=>10),
            array('Balance, Equity', 'length', 'max'=>100),
            array('EmailAddress, Address', 'length', 'max'=>300),
            array('Group, Agent, Leverage, City, State, PostCode, Country', 'length', 'max'=>50),
            array('PhoneNumber', 'length', 'max'=>20),
            array('RegistrationDate, created_date, modified_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('Login, Name, Currency, Balance, Equity, EmailAddress, Group, Agent, RegistrationDate, Leverage, Address, City, State, PostCode, Country, PhoneNumber, created_date, modified_at', 'safe', 'on'=>'search'),
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
            'Login' => 'Login',
            'Name' => 'Name',
            'Currency' => 'Currency',
            'Balance' => 'Balance',
            'Equity' => 'Equity',
            'EmailAddress' => 'Email Address',
            'Group' => 'Group',
            'Agent' => 'Agent',
            'RegistrationDate' => 'Registration Date',
            'Leverage' => 'Leverage',
            'Address' => 'Address',
            'City' => 'City',
            'State' => 'State',
            'PostCode' => 'Post Code',
            'Country' => 'Country',
            'PhoneNumber' => 'Phone Number',
            'created_date' => 'Created Date',
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

        $criteria->compare('Login',$this->Login);
        $criteria->compare('Name',$this->Name,true);
        $criteria->compare('Currency',$this->Currency,true);
        $criteria->compare('Balance',$this->Balance,true);
        $criteria->compare('Equity',$this->Equity,true);
        $criteria->compare('EmailAddress',$this->EmailAddress,true);
        $criteria->compare('Group',$this->Group,true);
        $criteria->compare('Agent',$this->Agent,true);
        $criteria->compare('RegistrationDate',$this->RegistrationDate,true);
        $criteria->compare('Leverage',$this->Leverage,true);
        $criteria->compare('Address',$this->Address,true);
        $criteria->compare('City',$this->City,true);
        $criteria->compare('State',$this->State,true);
        $criteria->compare('PostCode',$this->PostCode,true);
        $criteria->compare('Country',$this->Country,true);
        $criteria->compare('PhoneNumber',$this->PhoneNumber,true);
        $criteria->compare('created_date',$this->created_date,true);
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
     * @return ApiAccounts the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
