<?php

class UserInfoController extends CController
{
    /**
     * @return array action filters
     */
    public function filters()
{
    return array(
        'accessControl', // perform access control for CRUD operations
    );
}

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */

    public function accessRules()
{
    return UserIdentity::newaccessRules();
}

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
{
    $model = UserInfo::model()->findByPk(['user_id' => $id]);
    if(isset($_POST['UserInfo'])){
        try {
            $model->password = md5($_POST['UserInfo']['newPassword']);
            $model->modified_at = date('Y-m-d H:i:s');
            if ($model->validate()) {
                if($model->save())
                    $result = [
                        'result' => true
                    ];
            }
        }catch (Exception $e){
            $result = [
                'result' => false,
                'error' => $e
            ];
        }
        echo CJSON::encode($result);
    }else {
        /*$this->render('changePassword', array(
            'model' => $model,
        ));*/

        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));

    }
}

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
{
    $model = new UserInfo;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['UserInfo'])) {
        if (isset($_POST['UserInfo']['checkbox'])) {
            if ($_POST['UserInfo']['checkbox']) {
                foreach ($_POST['UserInfo']['checkbox'] as $key => $val) {
                    if ($val){
                        $model->$key = implode(",", $val);
                    }
                }
            }
        }

        $model->attributes = $_POST['UserInfo'];
        // $model->auth_level = 'user';
        $model->created_at = date('Y-m-d H:i:s');
        $model->modified_at = date('Y-m-d H:i:s');
        $model->password = md5($_POST['UserInfo']['password']);
        if($model->business_name){
            if(!($model->busAddress_building_num)){
                $model->busAddress_building_num = $model->building_num;
            }
            if(!($model->busAddress_street)){
                $model->busAddress_street = $model->street;
            }
            if(!($model->busAddress_region)){
                $model->busAddress_region = $model->region;
            }
            if(!($model->busAddress_city)){
                $model->busAddress_city = $model->city;
            }
            if(!($model->busAddress_postcode)){
                $model->busAddress_postcode = $model->postcode;
            }
            if(!($model->busAddress_country)){
                $model->busAddress_country = $model->country;
            }
            if(!($model->business_phone)){
                $model->business_phone = $model->phone;
            }
        }
        if ($model->validate()) {
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->user_id));
            }
        }
    }
    $this->render('create', array(
        'model' => $model,
    ));
}

    protected function validateUser($model, $address, $addressMap)
{
    $user = UserInfo::model()->findAllByAttributes(['email' => $model->email]);
    if (count($user) > 0):
        $model->addError('email', 'Email Already Exist in System');
        $flag = 0;
        return $flag;
    endif;

    if ($model->validate() && $address->validate()) {
        $model->save(false);
        $address->save(false);
        $addressMap->address_id = $address->address_id;
        $addressMap->user_id = $model->user_id;
        $addressMap->created_at = date('Y-m-d H:i:s');
        $addressMap->modified_at = date('Y-m-d H:i:s');
        $model->created_at = date('Y-m-d H:i:s');
        $addressMap->save();
        $flag = 1;
    } else {
        $flag = 0;
    }
    return $flag;
}

    protected function validateBusiness($model, $address, $addressMap, $business)
{
    $user = UserInfo::model()->findAllByAttributes(['email' => $model->email]);
    if (count($user) > 0):
        $model->addError('email', 'Email Already Exist in System');
        $flag = 0;
        return $flag;
    endif;

    $business->user_id = rand(9, 9999);
    if ($model->validate() && $address->validate() && $business->validate()) {
        if ($model->save()) {
            $business->user_id = $model->user_id;
            $business->save();
            $address->save(false);
            $addressMap->user_id = $business->business_id;
            $addressMap->address_id = $address->address_id;
            $addressMap->user_id = $model->user_id;
            $addressMap->created_at = date('Y-m-d H:i:s');
            $addressMap->modified_at = date('Y-m-d H:i:s');
            $model->created_at = date('Y-m-d H:i:s');
            $addressMap->save();
            $flag = 1;
        }
    } else {
        $flag = 0;
    }
    return $flag;
}

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
{

    $model = $this->loadModel($id);
    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['UserInfo'])) {
        if (isset($_POST['UserInfo']['checkbox'])) {
            if ($_POST['UserInfo']['checkbox']) {
                foreach ($_POST['UserInfo']['checkbox'] as $key => $val) {
                    if ($val) {
                        $model->$key = implode(",", $val);
                    } else {
                        $model->$key = "";
                    }
                }
            }
        }
        $model->attributes = $_POST['UserInfo'];
        $model->modified_at = date('Y-m-d H:i:s');

        if($model->business_name){
            if(!($model->busAddress_building_num)){
                $model->busAddress_building_num = $model->building_num;
            }
            if(!($model->busAddress_street)){
                $model->busAddress_street = $model->street;
            }
            if(!($model->busAddress_region)){
                $model->busAddress_region = $model->region;
            }
            if(!($model->busAddress_city)){
                $model->busAddress_city = $model->city;
            }
            if(!($model->busAddress_postcode)){
                $model->busAddress_postcode = $model->postcode;
            }
            if(!($model->busAddress_country)){
                $model->busAddress_country = $model->country;
            }
            if(!($model->business_phone)){
                $model->business_phone = $model->phone;
            }
        }

        /*            echo "<pre>";
                    print_r($model);die;*/
        if ($model->save())
            $this->redirect(array('view', 'id' => $model->user_id));
    }
    foreach ($model->getMetadata()->columns as $temp) {
        $arr = json_decode($temp->comment);
        $fieldType[$arr->name] = $arr->field_input_type;
    }
    foreach ($fieldType as $key => $val) {
        if ($val == 'check') {
            $model->$key = explode(',', $model->$key);
        }
    }
    $this->render('update', array(
        'model' => $model,
    ));
}

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
{
    $user = UserInfo::model()->findByAttributes(['user_id' => $_POST['id']]);
    $ordertable = Yii::app()->db->schema->getTable('order_info');
    $wallettable = Yii::app()->db->schema->getTable('wallet');

    if($ordertable != ''){
        $orderinfo = OrderInfo::model()->findByAttributes(['user_id' => $_POST['id']]);
        if($orderinfo != ''){
            $orderLineItem = OrderLineItem::model()->findByAttributes(['order_info_id' => $orderinfo->order_info_id]);
            $orderPayment = OrderPayment::model()->findByAttributes(['order_info_id' => $orderinfo->order_info_id]);
            $creditMemo = OrderCreditMemo::model()->findByAttributes(['order_info_id' => $orderinfo->order_info_id]);

            if($orderLineItem != ''){
                OrderLineItem::model()->deleteAll("order_info_id ='" . $orderinfo->order_info_id . "'");
            }

            if($orderPayment != ''){
                OrderPayment::model()->deleteAll("order_info_id ='" . $orderinfo->order_info_id . "'");
            }

            if($creditMemo != ''){
                OrderCreditMemo::model()->deleteAll("order_info_id ='" . $orderinfo->order_info_id . "'");
            }
        }
        if($orderinfo != ''){
            OrderInfo::model()->deleteAll("user_id ='" . $_POST['id'] . "'");
        }
    }
    if($wallettable != ''){
        $wallet = Wallet::model()->findByAttributes(['user_id' => $_POST['id']]);
        $userlicencecount = UserLicenseCount::model()->findByAttributes(['user_id' => $_POST['id']]);

        if($wallet != ''){
            Wallet::model()->deleteAll("user_id ='" . $_POST['id'] . "'");
        }

        if($userlicencecount != ''){
            UserLicenseCount::model()->deleteAll("user_id ='" . $_POST['id'] . "'");
        }
    }

    /*$this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if (!isset($_GET['ajax']))
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));*/

    if($user->delete()){
        echo json_encode([
            'token' => 1,
        ]);
    }
    else{
        echo json_encode([
            'token' => 0,
        ]);
    }
}

    /**
     * Lists all models.
     */
    public function actionIndex()
{
    $dataProvider = new CActiveDataProvider('UserInfo');
    $this->render('index', array(
        'dataProvider' => $dataProvider,
    ));
}

    /**
     * Manages all models.
     */
    public function actionAdmin()
{
    $TableID = CylTables::model()->findByAttributes(['table_name' => UserInfo::model()->tableSchema->name]);
    $model = new UserInfo('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['UserInfo']))
        $model->attributes = $_GET['UserInfo'];
    $alldata = UserInfo::model()->findAll();
    $this->render('admin', array(
        'TableID' => $TableID,
        'model' => $model,
        'alldata' => $alldata,
    ));
}

    /**
     * Manages data for server side datatables.
     */
    public function actionServerdata(){
    /*$alldata = Yii::app()->db->createCommand("SELECT * FROM user_info")->queryAll();
    echo json_encode($alldata);*/

    $requestData = $_REQUEST;


//		$model= new UserInfo();
    $array_cols = Yii::app()->db->schema->getTable('user_info')->columns;
    $array = array();
    $i = 0;
    foreach($array_cols as  $key=>$col){
        $array[$i] = $col->name;
        $i++;
    }
    /*$columns = array(
        0 => 'user_id',
        1 => 'full_name'
    );*/
    $columns = $array;

    $sql = "SELECT  * from user_info where 1=1";
//		$data = Yii::app()->db->createCommand($sql)->queryAll();
//		$totalFiltered = count($data);


    if (!empty($requestData['search']['value']))
    {
        $sql.=" AND ( user_id LIKE '%" . $requestData['search']['value'] . "%' ";
        foreach($array_cols as  $key=>$col){
            if($col->name != 'user_id')
            {
                $sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
            }
        }
        $sql.=")";
    }

    $j = 0;
    // getting records as per search parameters
    foreach($columns as $key=>$column){
        /*if($column == 'country')
        {

            if(!empty($requestData['columns'][28]['search']['value'])){
                $countryname = $requestData['columns'][28]['search']['value'];
                $codesql = "select country_code from countries where country_name LIKE "."'%$countryname%'";
                $country_code = Yii::app()->db->createCommand($codesql)->queryAll();
                if(!empty($country_code)){
                    $requestData['columns'][28]['search']['value'] = $country_code[0]['country_code'];
                }
            }
        }*/
        if( !empty($requestData['columns'][$key]['search']['value']) ){   //name
            $sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
        }
        $j++;
    }

//		echo $sql;die;

    $count_sql = str_replace("*","count(*) as columncount",$sql);
    $data = Yii::app()->db->createCommand($count_sql)->queryAll();
    $totalData = $data[0]['columncount'];
    $totalFiltered = $totalData;

    $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
        $requestData['length'] . "   ";

    $result = Yii::app()->db->createCommand($sql)->queryAll();

    $data = array();
    $i=1;

    /*echo "<pre>";
    print_r($result);die;*/
    foreach ($result as $key => $row)
    {
        $nestedData = array();
        $nestedData[] = $row['user_id'];
        if(ctype_alpha($row['country'])){
            $countrycode = $row['country'];
            $country_sql = "select country_name from countries where country_code = "."'$countrycode'";
            $country_name = Yii::app()->db->createCommand($country_sql)->queryAll();
            if(!empty($country_name)){
                $row['country'] = $country_name[0]['country_name'];
            }
        }
        else if(is_numeric($row['country'])){
            $countryid = $row['country'];
            $country_sql = "select country_name from countries where id = "."'$countryid'";
            $country_name = Yii::app()->db->createCommand($country_sql)->queryAll();
            $row['country'] = $country_name[0]['country_name'];
        }
        switch($row['gender']){
            case 1 :
                $row['gender'] = "Male";
                break;
            case 2:
                $row['gender'] = "Female";
                break;
            default:
                break;
        }
        foreach($array_cols as  $key=>$col){
            $nestedData[] = $row["$col->name"];
        }
//			$nestedData[] = $row["employee_age"];
//			$nestedData[] = '<a href="'.$url.'"><span class="glyphicon glyphicon-pencil"></span></a>';
        $data[] = $nestedData;
        $i++;
    }


    $json_data = array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data" => $data   // total data array
    );

    echo json_encode($json_data);
}



    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return UserInfo the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
{
    $model = UserInfo::model()->findByPk($id);
    if ($model === null)
        throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
}

    /**
     * Performs the AJAX validation.
     * @param UserInfo $model the model to be validated
     */
    protected function performAjaxValidation($model)
{
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-info-form') {
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }
}

    /**
     * this action change user password
     * @param $id
     * @return json array
     */
    public function actionChangePassword($id){
    $model = UserInfo::model()->findByPk(['user_id' => $id]);

    if(isset($_POST['UserInfo'])){
        try {
            $model->password = md5($_POST['UserInfo']['newPassword']);
            $model->modified_at = date('Y-m-d H:i:s');
            if ($model->validate()) {
                if($model->save())
                    $result = [
                        'result' => true
                    ];
            }
        }catch (Exception $e){
            $result = [
                'result' => false,
                'error' => $e
            ];
        }
        echo CJSON::encode($result);
    }else {
        $this->render('changePassword', array(
            'model' => $model,
        ));
    }
}

    /**
     * this action active or inactive
     * @param $id
     */
    public function actionUserActive($id){
    $model = UserInfo::model()->findByPk(['user_id' => $id]);

    if(isset($_GET['is_active'])){
        if($_GET['is_active'] == 0){
            $model->is_active = 1;
        }else{
            $model->is_active = 0;
        }
        $model->modified_at = date('Y-m-d H:i:s');
        if($model->save()) {
            $this->redirect(['view','id' => $model->user_id ]);
        }
    }
}

    /**
     * this action render genealogy view
     * @param $id
     */
    public function actionGenealogy($id){
    $model = UserInfo::model()->findByAttributes(['user_id'=>$id]);
    $orders = OrderInfo::model()->findAllByAttributes(['user_id' => $id]);
    $wallets = Wallet::model()->findAll([
        'select' => 'SUM(amount) as amount,wallet_id,wallet_type_id,reference_id,updated_balance',
        'condition' => "user_id = $id"
    ]);

    $this->renderPartial('genealogy',[
        'model' => $model,
        'orders' => $orders,
        'wallets' => $wallets
    ]);
}


    public function actionUserorders($id){


    /*$alldata = Yii::app()->db->createCommand("SELECT * FROM user_info")->queryAll();
    echo json_encode($alldata);*/

    $requestData = $_REQUEST;

//        $model= new OrderInfo();
    $array_cols = Yii::app()->db->schema->getTable('order_info')->columns;
    $array = array();
    $i = 0;
    foreach($array_cols as  $key=>$col){
        $array[$i] = $col->name;
        $i++;
    }
    /*$columns = array(
        0 => 'user_id',
        1 => 'full_name'
    );*/
    $columns = $array;

    $sql = "SELECT  * from order_info where user_id=".$id;
//		$data = Yii::app()->db->createCommand($sql)->queryAll();
//		$totalFiltered = count($data);

    if (!empty($requestData['search']['value']))
    {
        $sql.=" AND ( order_info_id LIKE '%" . $requestData['search']['value'] . "%' ";
        foreach($array_cols as  $key=>$col){
            if($col->name != 'order_info_id')
            {
                $sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
            }
        }
        $sql.=")";
//			$sql.=" OR employee_age LIKE '" . $requestData['search']['value'] . "%')";

    }

    $j = 0;
    // getting records as per search parameters
    foreach($columns as $key=>$column){
        if($requestData['columns'][$key]['search']['value'] != ''){   //name
            if($column == 'user_id'){
                $sql.=" AND  user_id = " . $requestData['columns'][$key]['search']['value'] . " ";
            }
            else{
                $sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
            }
        }
        $j++;
    }

//		echo $sql;die;

    $count_sql = str_replace("*","count(*) as columncount",$sql);
    $data = Yii::app()->db->createCommand($count_sql)->queryAll();
    $totalData = $data[0]['columncount'];
    $totalFiltered = $totalData;

    $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
        $requestData['length'] . "   ";

    $result = Yii::app()->db->createCommand($sql)->queryAll();

    $data = array();
    $i=1;

    /*echo "<pre>";
    print_r($result);die;*/
    foreach ($result as $key => $row)
    {
        $nestedData = array();
        $nestedData[] = $row['order_info_id'];
        if(ctype_alpha($row['country'])){
            $countrycode = $row['country'];
            $country_sql = "select country_name from countries where country_code = "."'$countrycode'";
            $country_name = Yii::app()->db->createCommand($country_sql)->queryAll();
            $row['country'] = $country_name[0]['country_name'];
        }
        else if(is_numeric($row['country'])){
            $countryid = $row['country'];
            $country_sql = "select country_name from countries where id = "."'$countryid'";
            $country_name = Yii::app()->db->createCommand($country_sql)->queryAll();
            $row['country'] = $country_name[0]['country_name'];
        }
        $row['is_subscription_enabled'] = $row['is_subscription_enabled'] == 0? ('No') : ('Yes');


        switch($row['order_status']){
            case 0 :
                $row['order_status'] = "Canceled";
                break;
            case 1:
                $row['order_status'] = "Success";
                break;
            default:
                break;
        }

        foreach($array_cols as  $key=>$col){
            $nestedData[] = $row["$col->name"];
        }
//			$nestedData[] = $row["employee_age"];
//			$nestedData[] = '<a href="'.$url.'"><span class="glyphicon glyphicon-pencil"></span></a>';
        $data[] = $nestedData;
        $i++;
    }
    /*echo "<pre>";
    print_r($data);die;*/

    $json_data = array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data" => $data   // total data array
    );

    echo json_encode($json_data);
}



    /**
     * Manages data for server side datatables.
     */
    public function actionUserwallet($id){
//        echo $id;die;
    /*$alldata = Yii::app()->db->createCommand("SELECT * FROM user_info")->queryAll();
    echo json_encode($alldata);*/

    $requestData = $_REQUEST;

//		$model= new wallet();
    $array_cols = Yii::app()->db->schema->getTable('wallet')->columns;
    $array = array();
    $i = 0;
    foreach($array_cols as  $key=>$col){
        $array[$i] = $col->name;
        $i++;
    }
    /*$columns = array(
        0 => 'user_id',
        1 => 'full_name'
    );*/
    $columns = $array;

    $sql = "SELECT  * from wallet where user_id=".$id;
//		$data = Yii::app()->db->createCommand($sql)->queryAll();
//		$totalFiltered = count($data);

    if (!empty($requestData['search']['value']))
    {
        $sql.=" AND ( wallet_id LIKE '%" . $requestData['search']['value'] . "%' ";
        foreach($array_cols as  $key=>$col){
            if($col->name != 'id')
            {
                $sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
            }
        }
        $sql.=")";
//			$sql.=" OR employee_age LIKE '" . $requestData['search']['value'] . "%')";

    }

    $j = 0;
    // getting records as per search parameters
    foreach($columns as $key=>$column){
        if($requestData['columns'][$key]['search']['value'] != '' ){   //name
            if($column == 'user_id'){
                $sql.=" AND  user_id = " . $requestData['columns'][$key]['search']['value'] . " ";
            }
            else {
                $sql .= " AND $column LIKE '%" . $requestData['columns'][$key]['search']['value'] . "%' ";
            }
        }
        $j++;
    }

//		echo $sql;die;

    $count_sql = str_replace("*","count(*) as columncount",$sql);
    $data = Yii::app()->db->createCommand($count_sql)->queryAll();
    $totalData = $data[0]['columncount'];
    $totalFiltered = $totalData;

    $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
        $requestData['length'] . "   ";

    $result = Yii::app()->db->createCommand($sql)->queryAll();

    $data = array();
    $i=1;

    /*echo "<pre>";
    print_r($result);die;*/
    foreach ($result as $key => $row)
    {
        $nestedData = array();
        $nestedData[] = $row['wallet_id'];

        $wallettypesql = "select wallet_type from wallet_type_entity where wallet_type_id ="."'$row[wallet_type_id]'";
        $wallettypenames = Yii::app()->db->createCommand($wallettypesql)->queryAll();

        $denominationsql = "select denomination_type from denomination where denomination_id="."'$row[denomination_id]'";
        $denominations = Yii::app()->db->createCommand($denominationsql)->queryAll();

        $row['wallet_type_id'] = $wallettypenames[0]['wallet_type'];
        $row['denomination_id'] = $denominations[0]['denomination_type'];

        switch($row['transaction_status']){
            case 0 :
                $row['transaction_status'] = "Pending";
                break;
            case 1:
                $row['transaction_status'] = "On Hold";
                break;
            case 2:
                $row['transaction_status'] = "Approved";
                break;
            case 3:
                $row['transaction_status'] = "Rejected";
                break;
            default:
                break;
        }

        switch($row['transaction_type']){
            case 0:
                $row['transaction_type'] = 'Credit';
                break;
            case 1:
                $row['transaction_type'] = 'Debit';
                break;
            default:break;
        }

        foreach($array_cols as  $key=>$col){
            $nestedData[] = $row["$col->name"];
        }
//			$nestedData[] = $row["employee_age"];
//			$nestedData[] = '<a href="'.$url.'"><span class="glyphicon glyphicon-pencil"></span></a>';
        $data[] = $nestedData;
        $i++;
    }
    /*echo "<pre>";
    print_r($data);die;*/

    $json_data = array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data" => $data   // total data array
    );

    echo json_encode($json_data);
}

    public function actionAddpermission(){
        $created_at = date('Y-m-d H:i:s');
        $modified_at = date('Y-m-d H:i:s');
        $controllerarray  =explode("/",$this->uniqueId);
        $controller = $controllerarray[1];

        if(isset($_POST['data'])){
            Yii::app()->db->createCommand("DELETE  FROM role_mapping WHERE `controller` = '$controller'")->execute();
            $data = explode("&",$_POST['data']);
            foreach($data as $key=>$value){
                $role = explode('=', $value);
                //print_r($role);die;
                    $sql = "INSERT into role_mapping (`controller`,`role`,`allowed_actions`,`created_at`,`modified_at`) VALUES('$controller','$role[0]','$role[1]','$created_at','$modified_at')";
                    Yii::app()->db->createCommand($sql)->execute();

            }

        }
    }
}