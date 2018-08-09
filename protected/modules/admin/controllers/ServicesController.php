<?php

class ServicesController extends CController
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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

        $model = $this->loadModel($id);
        if($model->user_id != ""){
            $username =  UserInfo::model()->findByAttributes(array('user_id'=>$model->user_id));
            $model->user_id = $username->full_name;
        }
        if($model->resource_id != ""){
            $username =  Resources::model()->findByAttributes(array('resource_id'=>$model->resource_id));
            $model->resource_id = $username->resource_name;
        }
        $this->render('view',array(
            'model'=> $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Services;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Services']))
        {
            $hourminutes = 0;
            if($_POST['Services']['hours'] != 0) {
                $hourminutes = ($_POST['Services']['hours'] * 60);
            }

            $model->attributes=$_POST['Services'];
            $model->user_id = $_POST['Services']['user_id'];
            $model->resource_id = $_POST['Services']['resource_id'];

            if($model->is_display == "on"){
                $model->is_display = 1;
            }else{
                $model->is_display = 0;
            }
            $model->service_duration = $hourminutes + $_POST['Services']['minutes'];

            $model->service_image = CUploadedFile::getInstance($model, 'service_image');
            /*echo $model->service_image;die;*/
            $imageName = $this->getImageName($model->service_image);
            if (CUploadedFile::getInstance($model, 'service_image') != '') {
                $model->service_image = $imageName;
            }

            $model->created_at = date('Y-m-d H:i:s');
            $model->modified_at = date('Y-m-d H:i:s');

            if($model->save()){
                if (CUploadedFile::getInstance($model, 'service_image') != '') {
                    $model->service_image = CUploadedFile::getInstance($model, 'service_image');
                    $model->service_image->saveAs(Yii::app()->basePath . '/..' . $imageName);
                }

                $this->redirect(array('view','id'=>$model->service_id));
            }
        }

        $this->render('create',array(
            'model'=>$model,
            'from' => "create",
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Services']))
        {
            $hourminutes = 0;
            $model->attributes=$_POST['Services'];

            if($_POST['Services']['hours'] != 0) {
                $hourminutes = ($_POST['Services']['hours'] * 60);
            }

            /*echo "<pre>";
            print_r($_POST);die;*/
            $model->attributes = $_POST['Services'];
            $model->user_id = $_POST['Services']['user_id'];
            $model->resource_id = $_POST['Services']['resource_id'];
            if($model->is_display == "on"){
                $model->is_display = 1;
            }else{
                $model->is_display = 0;
            }
            $model->service_duration = $hourminutes + $_POST['Services']['minutes'];

            $model->service_image = CUploadedFile::getInstance($model, 'service_image');
            /*echo $model->service_image;die;*/
            $imageName = $this->getImageName($model->service_image);
            if (CUploadedFile::getInstance($model, 'service_image') != '') {
                $model->service_image = $imageName;
            }
            $currentModel = $this->loadModel($id);

            if(empty($model->service_image)){
                $imageFile = CUploadedFile::getInstance($currentModel,'service_image');
                $model->service_image = $imageFile;
            }

            $model->created_at = date('Y-m-d H:i:s');
            $model->modified_at = date('Y-m-d H:i:s');

            /*echo "<pre>";
            print_r($model);die;*/
            if($model->validate()){
                $imageName = '';
                if($model->service_image != ''){
                    $imageName = $this->getImageName($model->service_image);
                    $model->service_image = $imageName;
                    $fileExists = is_file(Yii::app()->basePath . '/..' . $currentModel->service_image)?'yes':'no';
                    if($fileExists == 'yes'){
                        unlink(Yii::app()->basePath . '/..' . $currentModel->service_image);
                    }
                }else{
                    $model->service_image = $currentModel->service_image;
                }

                if($model->save()){
                    if (CUploadedFile::getInstance($model, 'service_image') != '') {
                        $model->service_image = CUploadedFile::getInstance($model, 'service_image');
                        $model->service_image->saveAs(Yii::app()->basePath . '/..' . $imageName);
                    }
                    $this->redirect(array('view','id'=>$model->service_id));
                }
            }
        }

        $this->render('update',array(
            'model'=>$model,
            'from' => "update",
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        $id = $_POST['id'];

        if($this->loadModel($id)->delete()){
            $_SESSION['delete'] = "Service deleted successfully";
            echo json_encode([
                'token' => 1,
            ]);
        }
        else{
            echo json_encode([
                'token' => 0,
            ]);
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        /*if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));*/
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->redirect('admin');
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $TableID = CylTables::model()->findByAttributes(['table_name' => Services::model()->tableSchema->name]);
        $model = new Services('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Services']))
            $model->attributes = $_GET['Services'];
        $alldata = Services::model()->findAll();

        $sql = "SELECT DISTINCT u.full_name,s.user_id FROM services s LEFT JOIN user_info u on s.user_id = u.user_id where 1=1";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "SELECT DISTINCT r.resource_name,s.resource_id FROM services s LEFT JOIN resources r on s.resource_id = r.resource_id where 1=1";
        $result1 = Yii::app()->db->createCommand($sql1)->queryAll();

        $this->render('admin', array(
            'TableID' => $TableID,
            'model' => $model,
            'alldata' => $alldata,
            'staff' => $result,
            'resources'=>$result1,
        ));
    }

    /**
     * Manages data for server side datatables.
     */
    public function actionServerdata(){
        $requestData = $_REQUEST;
        $model = new Services;
        $primary_key = $model->tableSchema->primaryKey;
        $array_cols = Yii::app()->db->schema->getTable(Services::model()->tableSchema->name)->columns;
        $array = array();
        $i = 0;
        foreach($array_cols as  $key=>$col){
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;
        $currentrole = Yii::app()->user->role;
        if($currentrole != "admin"){
            $userid = Yii::app()->user->id;
            $sql = "SELECT  * from ".Services::model()->tableSchema->name." where user_id = ".$userid;
        }
        else{
            $sql = "SELECT  * from ".Services::model()->tableSchema->name." where 1=1";
        }

        if (!empty($requestData['search']['value']))
        {
            $sql.=" AND ( $primary_key LIKE '%" . $requestData['search']['value'] . "%' ";
            foreach($array_cols as  $key=>$col){
                if($col->name != $primary_key)
                {
                    $sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
                }
            }
            $sql.=")";
        }

        $j = 0;

        // getting records as per search parameters
        foreach($columns as $key=>$column){

            if( !empty($requestData['columns'][$key]['search']['value']) ){   //name
                $sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
            }
            $j++;
        }

        $count_sql = str_replace("*","count(*) as columncount",$sql);
        $data = Yii::app()->db->createCommand($count_sql)->queryAll();
        $totalData = $data[0]['columncount'];
        $totalFiltered = $totalData;

        $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
            $requestData['length'] . "   ";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $data = array();
        $i=1;


        foreach ($result as $key => $row)
        {
            $nestedData = array();
            $nestedData[] = $row[$primary_key];

            if(!empty($row['user_id'])){
                $sql = "SELECT full_name from user_info where user_id = ".$row['user_id'];
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($result)){
                    $row['user_id'] = $result[0]['full_name'];
                }
            }

            if(!empty($row['resource_id'])){
                $sql = "SELECT resource_name from resources where resource_id = ".$row['resource_id'];
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($result)){
                    $row['resource_id'] = $result[0]['resource_name'];
                }
            }

            foreach($array_cols as  $key=>$col){
                $nestedData[] = $row["$col->name"];
            }

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
     * @return Services the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Services::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Services $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='services-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected  function getImageName($imageData){
        $imagePath = '/uploads/events/';
        $date = date('Ymd');
        $time = time();
        return $imagePath . $date . $time . '.' . pathinfo($imageData, PATHINFO_EXTENSION);
    }

    public function actionBookingservice($id){
        /*echo "<pre>";
        print_r($_POST);die;*/
        $service = Services::model()->findAll(array("condition"=>"service_id  = '$id'"));

        $sql = "SELECT * FROM business_openings_hours";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT user_id,full_name from user_info";
        $newresult = Yii::app()->db->createCommand($sql)->queryAll();

        $url = Yii::app()->createUrl("/admin/services/view/")."/".$id;

        $error = "";

        $newmodel = new Booking();

        if(isset($_POST['booking'])){
            $newmodel->service_id = $id;
            $newmodel->attributes = $_POST['booking'];
            $newmodel->price = $service[0]->service_price;
            $myuser = $_POST['booking']['username'];

            if($_POST['booking']['user_id'] != ""){
                $newsql = "SELECT * from user_info where user_id = ".$_POST['booking']['user_id'];
                $userresult = Yii::app()->db->createCommand($newsql)->queryAll();
                if(!empty($userresult)){
                    $newmodel->username = $userresult[0]['full_name'];
                    $newmodel->user_id = $userresult[0]['user_id'];
                    $newmodel->email  = $userresult[0]['email'];
                    $newmodel->mobile_number = $userresult[0]['phone'];
                    $newmodel->address = $userresult[0]['street'].",".$userresult[0]['region'].",".$userresult[0]['city'];
                    $myuser = '<a href="'.Yii::app()->createUrl('admin/userInfo/view').'/'.$newmodel->user_id.'"> '.$newmodel->username.'</a>';
                }
            }


            if(isset($_POST['timeslot'])){
                $maindate = $_POST['timeslot'];
                $myday = lcfirst(date("l",strtotime($maindate)));
                $givendays = array();
                foreach($result as $key=>$value){
                    array_push($givendays,$value['day']);
                }
                if(in_array($myday,$givendays)){
                    foreach ($result as $key=>$value){
                        if($myday == $value['day']){
                            $hour = date("H",strtotime($maindate));
                            $mynewsql = "select timing from business_openings_hours where day = "."'$myday'";
                            $mynewresult = Yii::app()->db->createCommand($mynewsql)->queryAll();
                            $mytimings = explode(";",$mynewresult[0]['timing']);
                            $min = $mytimings[0];
                            $max = $mytimings[1];
                            if($hour >= $max || $hour <= $min){
                                $error = "has-error";
                            }
                        }
                    }
                }
                else{
                    $error = "has-error";
                }
            }
            $newmodel->timeslot = date("Y-m-d H:i:s",strtotime($_POST['timeslot']));
            $newmodel->created_at = date("Y-m-d H:i:s");
            $newmodel->modified_at = date("Y-m-d H:i:s");


            if($error == ""){
                if($newmodel->validate()) {
                    if ($newmodel->save()) {
                        $_SESSION['delete'] = "Your booking is confirmed for this service";
                        $title = $service[0]->service_name;
                        $body = "$myuser has booked $title.";

                        $bookerid = $newmodel->booking_id;

                        NotificationHelper::AddNotitication($title,$body,'info',$bookerid,1,$url);

                        $this->redirect(Yii::app()->createurl('/admin/services/admin'));

                    }
                }
                else{
                    echo "<pre>";
                    print_r($newmodel->getErrors());die;
                }
            }

        }

        $sql3 = "SELECT COUNT(*) as booking from booking where service_id = ".$id." AND (status= 'pending' or status = 'approved')";
        $result3 = Yii::app()->db->createCommand($sql3)->queryAll();
        $stop = "";
        if($result3[0]['booking'] == $service[0]->total_booking || $result3[0]['booking'] > $service[0]->total_booking){
            $stop = "stop";
        }

        $this->render("bookingservice",array(
            "stop" =>$stop,
            'newmodel' => $newmodel,
            'service'=>$service[0],
            'timings'=>$result,
            "users"=>$newresult,
            'error'=>$error,
        ));
    }

    public function actionNextstep(){
        echo '<pre>';
        print_r($_POST);die;
    }

    public function actionServicebookingnextstep($id){
        $model = $this->loadModel($id);
        $sql = "SELECT user_id,full_name from user_info";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $stop = "";
        $this->render('finalbooking',array(
            "model"=>$model,
            "users"=>$result,
            "stop" =>$stop,
        ));
    }

    /**
     * datatable action for attendies for services
     */
    public function actionAttendiestable($id){
        $requestData = $_REQUEST;
        $model = new Booking;
        $primary_key = $model->tableSchema->primaryKey;
        $array_cols = Yii::app()->db->schema->getTable(Booking::model()->tableSchema->name)->columns;
        $array = array();
        $i = 0;
        foreach($array_cols as  $key=>$col){
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;

        $sql = "SELECT  b.*,s.service_name FROM booking b LEFT JOIN services s ON b.service_id = s.service_id where s.service_id =".$id;
//        echo $sql;die;

        if (!empty($requestData['search']['value']))
        {
            $sql.=" AND ( $primary_key LIKE '%" . $requestData['search']['value'] . "%' ";
            foreach($array_cols as  $key=>$col){
                if($col->name != $primary_key)
                {
                    $sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
                }
            }
            $sql.=")";
        }

        $j = 0;

        // getting records as per search parameters
        foreach($columns as $key=>$column){

            if( !empty($requestData['columns'][$key]['search']['value']) ){   //name
                if($column == "event_id"){
                    $sql .= " AND e.event_title LIKE '%".$requestData['columns'][$key]['search']['value']."%'";
                }
                else{
                    $sql.=" AND b.$column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
                }
            }
            $j++;
        }

        $count_sql = str_replace("b.*","count(*) as columncount",$sql);
        $data = Yii::app()->db->createCommand($count_sql)->queryAll();
        $totalData = $data[0]['columncount'];
        $totalFiltered = $totalData;


        $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
            $requestData['length'] . "   ";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
//    echo "<pre>";
//    print_r($result);die;

        $data = array();
        $i=1;


        foreach ($result as $key => $row)
        {
            $nestedData = array();
            $nestedData[] = $row[$primary_key];
            $row['service_id'] = $row['service_name'];
            foreach($array_cols as  $key=>$col){
                $nestedData[] = $row["$col->name"];
            }

            $data[] = $nestedData;
            $i++;
        }

        /*if(empty($data[0][0])){
            $data = array();
        }*/


        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data   // total data array
        );

        echo json_encode($json_data);
    }
}
