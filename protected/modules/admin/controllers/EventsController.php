<?php
date_default_timezone_set("Asia/Kolkata");

class EventsController extends CController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/column1';

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
        return UserIdentity::accessRules();
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionServiceProvider()
    {
        $currentrole = Yii::app()->user->role;
        if ($currentrole != "admin" ) {
            $this->redirect(Yii::app()->createUrl('home/login'));
        }
        $model = ServiceProvider::model()->findByAttributes(array());
        /*echo "<pre>";print_r($model);die;*/
        if ($model == null){
            $model=new ServiceProvider;
        }
        $image = $model->image;
        if(isset($_POST['ServiceProvider']))
        {

            $model = ServiceProvider::model()->findByAttributes(array());
            if($model == null){
                $model = new ServiceProvider();
                $model->created_at = date('Y-m-d H:i:s');
            }

            $model->attributes=$_POST['ServiceProvider'];
            $model->image = CUploadedFile::getInstance($model, 'image');
            $model->modified_at = date('Y-m-d H:i:s');
            $imageName = $this->getImageName($model->image);
            if (CUploadedFile::getInstance($model, 'image') != '') {
                $model->image = $imageName;
            }
            if(empty($model->image)){
                $model->image = $image;
            }
            if($model->validate()){
                if($model->save()){
                    if (CUploadedFile::getInstance($model, 'image') != '') {
                        $model->image = CUploadedFile::getInstance($model, 'image');
                        $model->image->saveAs(Yii::app()->basePath . '/..' . $imageName);
                    }
                    $sql = "SELECT * from business_openings_hours where serviceprovider_id= ".$model->id;
                    $result = Yii::app()->db->createCommand($sql)->queryAll();

                    if(!empty($result)){
                        $sql2 = "DELETE from business_openings_hours";
                        Yii::app()->db->createCommand($sql2)->execute();
                    }
                    $days = ["monday","tuesday","wednesday","thursday","friday","saturday","sunday"];
                    foreach ($days as $key=>$day){
                        if(isset($_POST[$day])){
                            $timing = $_POST[$day.'_slider'];
                            $sql = "INSERT INTO business_openings_hours (`id`,`serviceprovider_id`,`day`,`timing`,`created_at`,`modified_at`) values ('','$model->id','$day','$timing','$model->created_at','$model->modified_at')";
                            Yii::app()->db->createCommand($sql)->execute();
                        }
                    }
                    $_SESSION['delete'] = "Service provider created/updated successfully";
                    $this->redirect(Yii::app()->createUrl("/admin/events/serviceProviderview"));
                }
            }
        }

        $this->render('serviceProvider',array(
            'model'=>$model,
        ));
    }


    public function actionServiceProviderView(){
        $currentrole = Yii::app()->user->role;
        if ($currentrole != "admin" ) {
            $this->redirect(Yii::app()->createUrl('home/login'));
        }
        $model = ServiceProvider::model()->findByAttributes(array());

        $sql = "SELECT * FROM business_openings_hours";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('serviceProviderview',array(
            'model'=>$model,
            'timings'=>$result,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView()
    {
        $TableID = CylTables::model()->findByAttributes(['table_name' => Events::model()->tableSchema->name]);
        $this->setPageTitle('EVENTS');
        $items = array();
        $model=Events::model()->findAll();
        foreach ($model as $value) {
            if ($value->event_type == 'regular' || $value->event_type == "specific"){
                $items[]=array(
                    'id' => $value->event_id,
                    'title' =>$value->event_title,
                    'start' =>$value->event_start,
                    //'end' =>$value->event_end,
                    'data' => ['user_id' => $value->user_id],
                    'allDay'=>true,
                );
            }else{
                $items[]=array(
                    'id' => $value->event_id,
                    'title' =>$value->event_title,
                    'start' =>$value->event_start,
                    'end' =>$value->event_end,
                    'data' => ['user_id' => $value->user_id],
                    //'color'=>'#CC0000',
                    //'allDay'=>true,
                    //'url'=>'http://anyurl.com'
                );
            }
        }

        $sql = "SELECT DISTINCT u.first_name,e.event_host FROM events e LEFT JOIN user_info u on e.event_host = u.user_id where 1=1";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('events', array(
            'events' => $items,
            'data' => $model,
            'TableID' => $TableID,
            'hosts'=>$result,
        ));

    }

    public function actionCalendarEvents()
    {
        $items = array();
        $model=UserInfo::model()->findAll();
        foreach ($model as $value) {
            $items[]=array(
                'title'=>$value->first_name,
                'start'=>$value->created_at,
                'end'=>date('Y-m-d', strtotime('+1 day', strtotime($value->created_at))),
                //'color'=>'#CC0000',
                //'allDay'=>true,
                'url'=>'http://anyurl.com'
            );
        }
        echo CJSON::encode($items);
        Yii::app()->end();
    }

    public function actionSelectEvent()
    {
        if (isset($_POST)) {
            $model = Events::model()->findByAttributes(['event_id' => $_POST['id']]);

            $users = explode(',', $model->user_id);
            $data = [
                'id' => $model->event_id,
                'title' => $model->event_title,
                'url' => $model->event_url,
                'desc' => $model->event_description,
                'users' => $users,
                'start' => date('Y-m-d g:i a', strtotime(str_replace('-', '/', $model->event_start))),
                'end' => date('Y-m-d g:i a', strtotime(str_replace('-', '/', $model->event_end))),
                'location' => $model->event_location,
            ];
            if ($model) {
                echo json_encode([
                    'token' => 1,
                    'data' => $data,
                ]);
            } else {
                echo json_encode([
                    'token' => 0,
                ]);
            }

        }
    }

    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        //echo "<pre>";print_r($_POST);die;
        //print_r($model->attributes);die;
        if (isset($_POST['Events'])) {
            if(isset($_POST['Events']['applyall'])){
                /*echo "<pre>";
                print_r($_POST['Events']);die;*/
                $sql = "SELECT * from events where event_key = "."'$model->event_key'";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                $i = 0;
                foreach ($result as $key=>$value){
                    $model1 = $this->loadModel($value['event_id']);
                    $mycreatedate = explode(" ",$_POST['Events']['event_start']);
                    $mystarttime= $mycreatedate[1];
                    $mystarttime =  date("H:i:s", strtotime($mystarttime));

                    $myenddate = explode(" ",$_POST['Events']['event_end']);
                    $myendtime = $myenddate[1];
                    $myendtime  =  date("H:i:s", strtotime($myendtime));


                    $temp_start = $_POST['Events']['event_start'];
                    $temp_end = $_POST['Events']['event_end'];

                    $_POST['Events']['event_start'] = $model1->event_start;
                    $_POST['Events']['event_end'] = $model1->event_end;

                    $model1->attributes = $_POST['Events'];

                    $_POST['Events']['event_start'] = $temp_start;
                    $_POST['Events']['event_end'] = $temp_end;

                    if(isset($_POST['Events']['user_id'])){
                        $model1->user_id = implode(",",$_POST['Events']['user_id']);
                    }

                    if(isset($_POST['user'])){
                        $model1->user_id = "all";
                    }
                    $model1->modified_at = date('Y-m-d H:i:s');

                    $originalstart = explode(" ",$model1->event_start);
                    $model1->event_start = date('Y-m-d',strtotime(str_replace('-','/',$originalstart[0])));


                    $originalend = explode(" ",$model1->event_end);
                    $model1->event_end = date('Y-m-d',strtotime(str_replace('-','/',$originalend[0])));

                    $model1->event_start .= " ".$mystarttime;
                    $model1->event_end .=  " ".$myendtime;


                    $model1->booking_start_date= date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['booking_start_date'])));
                    $model1->coupon_start_date = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['coupon_start_date'])));
                    $model1->coupon_end_date = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['coupon_end_date'])));
                    $imageFile = CUploadedFile::getInstance($model1,'event_image');
                    $model1->event_image = $imageFile;
                    $currentModel = $this->loadModel($value['event_id']);
                    if(empty($model1->event_image)){
                        $imageFile = CUploadedFile::getInstance($currentModel,'event_image');
                        $model1->event_image = $imageFile;
                    }

                    if ($model1->validate()){
                        $imageName = '';
                        if($model1->event_image != ''){
                            $imageName = $this->getImageName($model1->event_image);
                            $model1->event_image = $imageName;
                            $fileExists = is_file(Yii::app()->basePath . '/..' . $currentModel->event_image)?'yes':'no';
                            if($fileExists == 'yes'){
                                unlink(Yii::app()->basePath . '/..' . $currentModel->event_image);
                            }
                        }else{
                            $model1->event_image = $currentModel->event_image;
                        }

                        if(isset($_POST['Events']['is_cancel'])){
                            $model->is_cancel = 1;
                        }
                        else{
                            $model->is_cancel = 0;
                        }

                        /*if(isset($_POST['Events']['notification'])){
                            $model1->is_notification = 1;
                        }
                        else{
                            $model1->is_notification = 0;
                        }*/

                        if ($model1->save()) {
                            if (CUploadedFile::getInstance($model1, 'event_image') != '') {
                                $model1->event_image = CUploadedFile::getInstance($model1, 'event_image');
                                $model1->event_image->saveAs(Yii::app()->basePath . '/..' . $imageName);
                            }
                            $_SESSION['delete'] = "Events updated successfully";
                        }
                    } /*else {
                        echo "<pre>";
                        print_r($model1->getErrors());
                        die;
                    }*/
                    $i++;
                }
                $this->redirect(array('view'));
                //die;
            }
            else{
                $model->attributes = $_POST['Events'];
                if(isset($_POST['Events']['user_id'])){
                    $model->user_id = implode(",",$_POST['Events']['user_id']);
                }
                //echo "<pre>";
                //print_r($model->attributes); die;
                if(isset($_POST['user'])){
                    $model->user_id = "all";
                }

                if(isset($_POST['Events']['is_cancel'])){
                    $model->is_cancel = 1;
                }
                else{
                    $model->is_cancel = 0;
                }

                /*if(isset($_POST['Events']['notification'])){
                    $model->is_notification = 1;
                }
                else{
                    $model->is_notification = 0;
                }*/


                $model->modified_at = date('Y-m-d H:i:s');
                $model->event_start = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['event_start'])));
                $model->event_end = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['event_end'])));
                $model->booking_start_date = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['booking_start_date'])));
                $model->coupon_start_date = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['coupon_start_date'])));
                $model->coupon_end_date = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['coupon_end_date'])));

                if($model->type == "service"){
                    $model->event_start = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['duration_event_start'])));
                    $model->event_end = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['duration_event_end'])));

                    $hourminutes = 0;
                    if($_POST['Services']['hours'] != 0) {
                        $hourminutes = ($_POST['Services']['hours'] * 60);
                    }

                    $model->service_duration = $hourminutes + $_POST['Services']['minutes'];

                }

                $imageFile = CUploadedFile::getInstance($model,'event_image');

                $model->event_image = $imageFile;
                $currentModel = $this->loadModel($id);
                if(empty($model->event_image)){
                    $imageFile = CUploadedFile::getInstance($currentModel,'event_image');
                    $model->event_image = $imageFile;
                }

                if($model->validate()){
                    $imageName = '';
                    if($model->event_image != ''){
                        $imageName = $this->getImageName($model->event_image);
                        $model->event_image = $imageName;
                        $fileExists = is_file(Yii::app()->basePath . '/..' . $currentModel->event_image)?'yes':'no';
                        if($fileExists == 'yes'){
                            unlink(Yii::app()->basePath . '/..' . $currentModel->event_image);
                        }
                    }else{
                        $model->event_image = $currentModel->event_image;
                    }

                    /*echo "<pre>";
                    print_r($model);die;*/
                    if ($model->save()) {
                        if (CUploadedFile::getInstance($model, 'event_image') != '') {
                            $model->event_image = CUploadedFile::getInstance($model, 'event_image');
                            $model->event_image->saveAs(Yii::app()->basePath . '/..' . $imageName);
                        }
                        $_SESSION['delete'] = "Events updated successfully";
                        $this->redirect(array('view'));
                    }
                }/* else {
                    echo "<pre>";
                    print_r($model->getErrors());
                    die;
                }*/

            }
        }
        $this->render('create', array(
            'model' => $model,
            'from' => "update",
        ));
    }

    /*public function actionAddEvent()
    {

        if (isset($_POST['event_start']) &&  ($_POST['event_end'])) {
            $model = new Events();

            $model->event_title = $_POST['event_title'];
            $model->event_description = $_POST['description'];
            $model->event_location = $_POST['event_location'];
            $model->event_url= $_POST['url'];
            $model->event_start = date("Y-m-d H:i:s", strtotime($_POST['event_start']));
            $model->event_end = date("Y-m-d H:i:s", strtotime($_POST['event_end']));
            if ($_POST['users']){
                $model->user_id = implode(',',$_POST['users']);
            }else{
                $model->user_id = "all";
            }
            if ($model->validate()) {
                if ($model->save()) {
                    echo json_encode([
                        'token' => 1,
                        'id' => $model->event_id,
                    ]);
                }
            }else {
                echo json_encode([
                    'token' => 0,
                    'error' => $model->getError($model),
                ]);
            }
        }
    }*/
    public function actionCreate()
    {
        $model = new Events();
        if (isset($_POST['Events'])) {
            //echo "<pre>"; print_r($_POST['Events']);die;
            $model->created_at = date('Y-m-d H:i:s');
            $model->modified_at = date('Y-m-d H:i:s');
            $model->attributes = $_POST['Events'];
            if(isset($_POST['Events']['user_id'])) {
                $model->user_id = implode(",", $_POST['Events']['user_id']);
            }
            if(isset($_POST['user'])){
                $model->user_id = "all";
            }
            if($model->event_type=="single"){
                $model->recurring_span = 0;
                if($_POST['Events']['event_end'] == ""){
                    $hourminutes = 0;
                    if($_POST['Events']['hours'] != 0) {
                        $hourminutes = ($_POST['Events']['hours'] * 60);
                    }
                    $total_duration = $hourminutes + $_POST['Events']['minutes'];
                    $enddate = new DateTime($_POST['Events']['event_start']);

                    $enddate->modify("+{$total_duration} minutes");
                    $_POST['Events']['event_end'] = $enddate->format("Y-m-d H:i:s");
                }
            }
            $model->event_key = $model->event_title."-".date_timestamp_get(date_create());
            if($model->event_type == "specific"){
                $dates = explode(",",$_POST['Events']['specific']);
                $model->event_start = date('Y-m-d',strtotime(str_replace('-','/',$dates[0])));
                $time = $_POST['Events']['specific_time'];
                $model->event_start = $model->event_start." ".$time;
                $model->event_end = $model->event_start;
            }
            else{
                $model->event_start = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['event_start'])));
                $model->event_end = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['event_end'])));
            }
            $model->booking_start_date = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['booking_start_date'])));
            $model->coupon_start_date = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['coupon_start_date'])));
            $model->coupon_end_date = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['coupon_end_date'])));
            $model->event_image = CUploadedFile::getInstance($model, 'event_image');
            //echo $model->event_image;die;
            $imageName = $this->getImageName($model->event_image);
            if (CUploadedFile::getInstance($model, 'event_image') != '') {
                $model->event_image = $imageName;
            }

            if(isset($_POST['Events']['is_cancel'])){
                $model->is_cancel = 1;
            }
            else{
                $model->is_cancel = 0;
            }

            /*if(isset($_POST['Events']['notification'])){
                $model->is_notification = 1;
            }
            else{
                $model->is_notification = 0;
            }*/

            $hourminutes = 0;
            if($_POST['Services']['hours'] != 0) {
                $hourminutes = ($_POST['Services']['hours'] * 60);
            }

            $model->service_duration = $hourminutes + $_POST['Services']['minutes'];

            if($model->type == "service"){
                if(isset($_POST['Events']['duration_event_start'])){
                    $model->event_start = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['duration_event_start'])));
                    $model->event_end = date('Y-m-d H:i:s',strtotime(str_replace('-','/',$_POST['Events']['duration_event_end'])));
                }
            }


            if ($model->validate()){
                if ($model->save()) {
                    if($model->event_type == "regular"){
                        if($model->recurring_span == 1){
                            $diff = date_diff(date_create($model->event_start),date_create($model->event_end));
                            for($i = 1; $i<=$diff->days ; $i++){
                                $model1 = new Events();
                                $model1->attributes = $model->attributes;
                                $model1->event_start = strtotime("+$i day", strtotime($model->event_start));
                                $model1->event_start = date("Y-m-d H:i:s", $model1->event_start);
                                $model1->save();
                            }
                        }
                        else{
                            $dates = $this->calculate_diff($model->event_start,$model->event_end,$model->recurring_span);
                            /*array_shift($dates);*/
                            foreach ($dates as $key=>$value){
                                $model1 = new Events();
                                $model1->attributes = $model->attributes;
                                $model1->event_start = $value;
                                if($model->event_start != $value){
                                    $model1->save();
                                }
                            }
                        }
                    }

                    if($model->event_type == "specific"){
                        foreach($dates as $key=>$date){
                            if($key != 0){
                                $model1 = new Events();
                                $model1->attributes = $model->attributes;
                                $time = $_POST['Events']['specific_time'];
                                $model1->event_start = date('Y-m-d',strtotime(str_replace('-','/',$date)));
                                $model1->event_start = $model1->event_start." ".$time;
                                $model1->event_end = $model1->event_start;
                                $model1->save();
                            }
                        }
                    }

                    $_SESSION['delete'] = "Your event is created";

                    $sql = "SELECT value from settings where module_name = 'events' and settings_key = 'event_email_permission' ";
                    $result1 = Yii::app()->db->createCommand($sql)->queryAll();
                    if(!empty($result1)){
                        $value = $result1[0]['value'];
                        if($value == 'enabled') {
                            $to = array();
                            if($model->user_id != ""){
                                if($model->user_id == 'all'){
                                    $sqlall = "SELECT email from user_info";
                                    $resultall = Yii::app()->db->createCommand($sqlall)->queryAll();
                                    foreach ($resultall as $key=>$value){
                                        array_push($to,$value['email']);
                                    }
                                }
                                else{
                                    $users = explode(",", $model->user_id);
                                    foreach ($users as $key => $value) {
                                        $sql = "SELECT email from user_info where user_id = " . $value;
                                        $result = Yii::app()->db->createCommand($sql)->queryAll();
                                        $email = $result[0]['email'];
                                        array_push($to,$email);
                                    }
                                }
                            }
                            $to = implode(",",$to);
                            $subject = "Event Invitation";
                            $message = "<html>
                                           <body>
                                               <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
                                                        <tbody>
                                                               <tr>
                                                                   <td style=\"padding:25px 0;text-align:center; background-color:#f2f4f6\">
                                                                       <a style=\"font-family:Arial,Helvetica,'Helvetica Neue',sans-serif;font-size:16px;font-weight:bold;color:#2f3133;text-decoration:none\" href=\"http://cyc-01-dev.corecyclone.com\">
                                                                       <img src='http://cyclone.abptechnologies.com/admin/plugins/images/CYL-Logo.png' alt='Cyclone' width='16%'>
                                                                       </a>
                                                                   </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td>
                                                                        <table style=\"width:auto;max-width:570px;margin:0 auto;padding:0;text-align:center;font-weight:600;font-size:16px;\">
                                                                              <tbody>
                                                                                 <tr>
                                                                                    <td style=\"font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;color:#aeaeae;padding:35px;text-align:center\">
                                                                                        Event name
                                                                                    </td>
                                                                                    <td>
                                                                                        $model->event_title              
                                                                                    </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                    <td style=\"font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;color:#aeaeae;padding:35px;text-align:center;font-weight:600;font-size:16px;\">
                                                                                        Event description
                                                                                    </td>
                                                                                    <td>
                                                                                        $model->event_description
                                                                                    </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                     <td style=\"font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;color:#aeaeae;padding:35px;text-align:center;font-weight:600;font-size:16px;\">
                                                                                        Event location
                                                                                     </td>
                                                                                     <td>
                                                                                        $model->event_location
                                                                                     </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                    <td style=\"font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;color:#aeaeae;padding:35px;text-align:center;font-weight:600;font-size:16px;\">
                                                                                        Event start
                                                                                    </td>
                                                                                    <td>
                                                                                        $model->event_start
                                                                                    </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                    <td style=\"font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;color:#aeaeae;padding:35px;text-align:center;font-weight:600;font-size:16px;\">
                                                                                        Event end
                                                                                    </td>
                                                                                    <td>
                                                                                        $model->event_end
                                                                                    </td>
                                                                                 </tr>
                                                                                 <tr>
                                                                                    <td style=\"font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;color:#aeaeae;padding:35px;text-align:center;font-weight:600;font-size:16px;\">
                                                                                        Event Url
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href=\"$model->event_url\" style=\"font - family:Arial,'Helvetica Neue',Helvetica,sans - serif;display:block;display:inline - block;width:200px;min - height:20px;padding:10px;background - color:#3869d4;border-radius:3px;color:#ffffff;font-size:15px;line-height:25px;text-align:center;text-decoration:none;background-color:#3869d4\" class=\"m_1732025710485404230button\" target=\"_blank\" data-saferedirecturl=\"$model->event_url\">Click here to go event page</a>
                                                                                    </td>
                                                                                 </tr>
                                                                                 
                                                                                 
                                                                              </tbody>
                                                                          </table>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                     <td style=\"background-color:#f2f4f6\">
                                                                        <table style=\"width:auto;max-width:570px;margin:0 auto;padding:0;text-align:center\">
                                                                              <tbody>
                                                                                 <tr>
                                                                                    <td style=\"font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;color:#aeaeae;padding:35px;text-align:center\">
                                                                                        <p style=\"margin-top:0;color:#44484e;font-size:12px;line-height:1.5em\">
                                                                                           © 2018
                                                                                           <a style=\"color:#3869d4\" href=\"http://cyc-01-dev.corecyclone.com\">Cyclone</a>.
                                                                                           All rights reserved.
                                                                                        </p>
                                                                                        <p style=\"margin-top:0;color:#44484e;font-size:12px;line-height:1.5em\">
                                                                                           If you don't want to receive any emails,
                                                                                           <a style=\"color:#3869d4\" href=\"javascript:void(0);\">unsubscribe here</a>.
                                                                                        </p>
                                                                                    </td>
                                                                                 </tr>
                                                                              </tbody>
                                                                          </table>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                               </table>
                                           </body>
                                        </html>";

                            $url_array = explode("/",$_SERVER['REQUEST_URI']);
                            $from = "support@".$url_array[1];
                            Test::Email($to,$subject,$message,$from);

                        }
                    }

                    if (CUploadedFile::getInstance($model, 'event_image') != '') {
                        $model->event_image = CUploadedFile::getInstance($model, 'event_image');
                        $model->event_image->saveAs(Yii::app()->basePath . '/..' . $imageName);
                    }
                    Yii::app()->session['Csuccess'] = "Event created successfully";
                    $this->redirect(array('view'));
                }
            }
        }
        $this->render('create', array(
            'model' => $model,
            'from' => "create",
        ));
    }

    public function actionEventview($id)
    {
        $data = $this->loadModel($id);
        $attendees = "Select SUM(number_of_people) From booking  where event_id = ".$id;
        $attan = Yii::app()->db->createCommand($attendees)->queryAll();
        if(empty($attan)){
            $attan['0']['SUM(number_of_people)'] =0;
        }
        //echo "<pre>";print_r($attan);die;
        $userName = UserInfo::model()->findByAttributes(['user_id' => $data->event_host]);
        $data->event_host = $userName['first_name'];
        $this->render('eventview',array(
            'model'=>$data,
            'countofbooking' => $attan['0']['SUM(number_of_people)']));
    }

    public function actionDelete()
    {
        $model = Events::model()->findByAttributes(['event_id' => $_POST['id']]);

        $sql  = "DELETE FROM booking where event_id = ".$_POST['id'];
        Yii::app()->db->createCommand($sql)->execute();

        if(!empty($model)){
            if (Events::model()->deleteAll("event_id ='" .$model->event_id. "'")){
                $_SESSION['delete'] = "Event deleted successfully";
                echo json_encode([
                    'token' => 1,
                ]);
            }else{
                echo json_encode([
                    'token' => 0,
                ]);
            }
        }

    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SysUsers the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Events::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
    protected  function getImageName($imageData){
        $imagePath = '/uploads/events/';
        $date = date('Ymd');
        $time = time();
        return $imagePath . $date . $time . '.' . pathinfo($imageData, PATHINFO_EXTENSION);
    }

    /**
     * Performs the AJAX validation.
     * @param SysUsers $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='sys-users-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Opens settings page for event module.
     */
    public function actionSettings(){
        $events = Events::model()->findAll();
        $services = Services::model()->findAll();

        if(isset($_POST['hidden_email'])){
            $permission = $_POST['hidden_email'];
            $user_id = Yii::app()->user->id;
            $firstsql = "SELECT value from settings where module_name = 'events' and settings_key = 'event_email_permission'";
            $result = Yii::app()->db->createCommand($firstsql)->queryAll();
            if(!empty($result)){
                $modified_date = date('Y-m-d H:i:s');
                $sql = "UPDATE settings set value = "."'$permission'".", modified_date = "."'$modified_date'"." where module_name = 'events' and settings_key = 'event_email_permission'";
            }
            else{
                $sql = "INSERT INTO settings (`user_id`,`module_name`,`settings_key`,`value`) values ('$user_id','events','event_email_permission','$permission')";
            }
            Yii::app()->db->createCommand($sql)->execute();
            $_SESSION['delete'] = "Events settings saved successfully";
        }

        if(isset($_POST["host_role"])){
            $user_id = Yii::app()->user->id;
            $value = $_POST['host_role'];
            $modified_date = date('Y-m-d H:i:s');
            $firstsql = "SELECT * from settings where module_name='events' and settings_key = 'event_host_role'";
            $result = Yii::app()->db->createCommand($firstsql)->queryAll();
            if(!empty($result)){
                $sql = "UPDATE settings set value = "."'$value'".", modified_date = "."'$modified_date'"." where module_name = 'events' and settings_key = 'event_host_role'";
            }
            else{
                $sql = "INSERT INTO settings (`user_id`,`module_name`,`settings_key`,`value`) values ('$user_id','events','event_host_role','$value')";
            }
            Yii::app()->db->createCommand($sql)->execute();
            $_SESSION['delete'] = "Events settings saved successfully";
        }

        $sql = "SELECT role_title from roles";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('settings',array('events'=>$events,'services'=>$services,'roles'=>$result));
    }

    public function calculate_diff($startdate,$enddate,$recurring_span){
        $diff = date_diff(date_create($startdate),date_create($enddate));
        $diff = $diff->days;
        $sql = "SELECT recurring_span from recurring where id= $recurring_span";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $recur_span = $result[0]['recurring_span'];
        $dates = array();
        for($i=0;$i<=$diff;$i++){
            $timestamp = strtotime("+$i day", strtotime($startdate));
            switch ($recur_span){
                case "Weekdays only":
                    $day = date('D',$timestamp);
                    if($day != "Sun" && $day != 'Sat'){
                        $date = date("Y-m-d H:i:s", $timestamp);
                        array_push($dates,$date);
                    }
                    break;

                case "Weekends only":
                    $day = date('D',$timestamp);
                    if($day == "Sun" || $day == "Sat"){
                        $date = date("Y-m-d H:i:s", $timestamp);
                        array_push($dates,$date);
                    }
                    break;

                case "Once a week":
                    $timestamp1 = strtotime($startdate);
                    $daytocompare = date('D',$timestamp1);
                    $day = date('D',$timestamp);
                    if($day == $daytocompare){
                        $date = date("Y-m-d H:i:s", $timestamp);
                        array_push($dates,$date);
                    }
                    break;

                case "Once a fortnight":
                    $t = $i*14;
                    $mytimestamp = strtotime("+$t day", strtotime($startdate));
                    $endtimestamp = strtotime($enddate);
                    if($mytimestamp < $endtimestamp){
                        $date = date("Y-m-d H:i:s", $mytimestamp);
                        array_push($dates,$date);
                    }
                    break;

                case "Monthly(repeat by day)":
                    $date = date('Y-m-d H:i:s',$timestamp);
                    if($date < $enddate && date('d',$timestamp) == date('d',strtotime($startdate))){
                        array_push($dates,$date);
                    }
                    break;

                case "Every six weeks":
                    $t = $i * 42;
                    $date = date('Y-m-d H:i:s',strtotime("+$t days",$timestamp));
                    if($date < $enddate){
                        array_push($dates,$date);
                    }
                    break;

                case "Every two months(repeat by day)":
                    if($i != 0) {
                        $t = ($i * 2);
                        $timestamp = strtotime($startdate);
                        $date = new DateTime();
                        $date->setTimestamp($timestamp);
                        $date->modify("+$t months");
                        $mydate = $date->format('Y-m-d H:i:s');
                        $mytimestamp = $date->format('U');
                        if ($mytimestamp < strtotime($enddate)) {
                            array_push($dates, $mydate);
                        }
                    }
                    break;

                case "Every Quarter (repeat by day)":
                    if($i != 0){
                        $t = ($i*3);
                        $timestamp =  strtotime($startdate);
                        $date = new DateTime();
                        $date->setTimestamp($timestamp);
                        $date->modify("+$t months");
                        $mydate = $date->format('Y-m-d H:i:s');
                        $mytimestamp = $date->format('U');
                        if ($mytimestamp < strtotime($enddate)) {
                            array_push($dates, $mydate);
                        }
                    }
                    break;

                case "Twice a Year (repeat by day)":
                    if($i != 0){
                        $t = ($i * 6);
                        $timestamp = strtotime($startdate);
                        $date = new DateTime();
                        $date->setTimestamp($timestamp);
                        $date->modify("+$t months");
                        $mydate = $date->format('Y-m-d H:i:s');
                        $mytimestamp = $date->format('U');
                        if($mytimestamp < strtotime($enddate)){
                            array_push($dates,$mydate);
                        }
                    }
                    break;

            }

        }
        /*echo "<pre>";
        print_r($dates);die;*/

        switch ($recur_span){
            case "Once a week":
            case "Weekdays only":
            case "Once a fortnight":
            case "Monthly(repeat by day)":
            case "Every six weeks":
                array_shift($dates);
                break;
        }
        /*if($recur_span == "Once a week" || $recur_span == "Weekdays only" || $recur_span == "Once a fortnight" || $recur_span == ""){
            array_shift($dates);
        }*/

        return $dates;
    }

    public function actionBooking($id){
        $model = $this->loadModel($id);

        $url = Yii::app()->createUrl("/admin/events/eventview/")."/".$id;

        $sql = "SELECT user_id,full_name from user_info";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $newmodel = new Booking();
        $error = "";
        if(isset($_POST['booking'])){
            //echo "<pre>";print_r($_POST);die;
            $newmodel = new Booking();
            $myevent = Events::model()->findByAttributes(['event_id'=>$id]);
            $newmodel->checkindate = date('Y-m-d',strtotime($myevent->event_start));
            $newmodel->checkoutdate = date('Y-m-d', strtotime($myevent->event_start. ' + 3 days'));
            $newmodel->event_id = $id;
            $newmodel->number_of_people	 = 1;
            $newmodel->product_id = 1;
            $newmodel->id_file_1 = 1;
            $newmodel->id_file_2 = 1;
            $newmodel->product_id = 1;
            $newmodel->attributes = $_POST['booking'];
            $myuser = $newmodel->username;
            $newmodel->created_at = date("Y-m-d H:i:s");
            $newmodel->modified_at = date("Y-m-d H:i:s");
            //echo "<pre>";print_r($newmodel);die;

            if($myevent->type == 'event'){
                if($_POST['booking']['username'] != "" && $_POST['booking']['email'] != ""){
                    $newmodel->email = $_POST['booking']['email'];
                    $newmodel->username = $_POST['booking']['username'];
                    $newmodel->save();
                    $this->redirect(Yii::app()->createurl('/admin/booking/admin'));
                }
            }

            if($_POST['booking']['user_id'] != ""){
                $newsql = "SELECT * from user_info where user_id = ".$_POST['booking']['user_id'];
                $userresult = Yii::app()->db->createCommand($newsql)->queryAll();
                if(!empty($userresult)){
                    $newmodel->username = $userresult[0]['full_name'];
                    $newmodel->user_id = $userresult[0]['user_id'];
                    $newmodel->email  = $userresult[0]['email'];
                    $newmodel->mobile_number = $userresult[0]['phone'];
                    $newmodel->building = $userresult[0]['building_num'];
                    $newmodel->street = $userresult[0]['street'];
                    $newmodel->city = $userresult[0]['city'];
                    $newmodel->region = $userresult[0]['region'];
                    $newmodel->postcode = $userresult[0]['postcode'];
                    $newmodel->country = $userresult[0]['country'];
                    $newmodel->company_name = $userresult[0]['business_name'];
                    $newmodel->vat_number = $userresult[0]['vat_number'];
                    //$newmodel->address = $userresult[0]['street'].",".$userresult[0]['region'].",".$userresult[0]['city'];
                    $myuser = '<a href="'.Yii::app()->createUrl('admin/userInfo/view').'/'.$newmodel->user_id.'"> '.$newmodel->username.'</a>';
                }
                else{
                    $myuser = $_POST['booking']['username'];
                }
            }


            if($myevent->type == 'service'){
                $sql = "SELECT * FROM business_openings_hours";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
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
            }


            if($error == ''){
                //echo "<pre>";print_r($newmodel);die;
                if($newmodel->validate()){
                    //echo "heelo";die;
                    if ($newmodel->save()) {
                        $sql2 = "SELECT COUNT(*) as booking from booking where event_id = " . $id;
                        $result2 = Yii::app()->db->createCommand($sql2)->queryAll();

                        if ($result2[0]['booking'] == 1) {
                            if ($model->total_tickets == 1) {
                                $firstbooking = "Completed";
                            } else {
                                $firstbooking = "First";
                            }
                        } else if ($result2[0]['booking'] == $model->total_tickets) {
                            $firstbooking = "Completed";
                        } else {
                            $firstbooking = "";
                        }
                        $title = $firstbooking . " Booking of " . $model->event_title;
                        $_SESSION['delete'] = "Your booking is confirmed for this event";
                        $body = "$myuser has booked $title.";
                        $bookerid = $newmodel->booking_id;
                        NotificationHelper::AddNotitication($title, $body, 'info', $bookerid, 1, $url);
                        $this->redirect(Yii::app()->createurl('/admin/booking/admin'));
                    }
                }
            }
            else{
                $_SESSION['delete'] = "You choose the wrong timeslot please try again!";
                $this->redirect(Yii::app()->createurl('/admin/events/view'));
            }
        }

        $sql3 = "SELECT COUNT(*) as booking from booking where event_id = ".$id;
        $result3 = Yii::app()->db->createCommand($sql3)->queryAll();

        $stop = "";
        if($result3[0]['booking'] == $model->total_tickets || $result3[0]['booking'] > $model->total_tickets){
            $stop = "disabled";
        }

        if($model->type == 'service'){
            $view = 'bookingservice';
        }
        else{
            $view = 'bookingconfirmation';
        }

        $sql = "SELECT * FROM business_openings_hours";
        $result2 = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render($view,array(
            "model"=>$model,
            "users"=>$result,
            "stop" =>$stop,
            'timings' => $result2,
            'newmodel' => $newmodel,
            'error'=>$error,
        ));

    }

    public function actionMyevents($id){
        $this->setPageTitle('EVENTS');
        $today = date("Y-m-d H:i:s");
        $items = array();
        if($id == 1){
            $sql = "SELECT * FROM events where event_start > "."'$today'";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $model = (object)$result;

        }
        foreach ($model as $value) {
            $items[]=array(
                'id' => $value->event_id,
                'title' =>$value->event_title,
                'start' =>$value->event_start,
                'end' =>$value->event_end,
                'data' => ['user_id' => $value->user_id],
                //'color'=>'#CC0000',
                //'allDay'=>true,
                //'url'=>'http://anyurl.com'
            );
        }

        $this->render('events', array('events' => $items, 'data' => $model));
    }



    /**
     * Manages data for server side datatables.
     */
    public function actionServerdata(){

        $currentrole = Yii::app()->user->role;


        $requestData = $_REQUEST;
        $model = new Events;
        $primary_key = $model->tableSchema->primaryKey;
        $array_cols = Yii::app()->db->schema->getTable(Events::model()->tableSchema->name)->columns;
        $array = array();
        $i = 0;
        foreach($array_cols as  $key=>$col){
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;

        if($currentrole != "admin"){
            $userid = Yii::app()->user->id;
            $sql = "SELECT  * from ".Events::model()->tableSchema->name." where event_host = ".$userid;
        }
        else{
            $sql = "SELECT  * from ".Events::model()->tableSchema->name." where 1=1";
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
                if($column == 'event_start' && $requestData['event_start_min'] != '' && $requestData['event_start_max'] != ''){
                    $sql .= " AND cast(event_start as date) between '$requestData[event_start_min]' and '$requestData[event_start_max]'";
                }
                else if($column == 'event_end' && $requestData['event_end_min'] != '' && $requestData['event_end_max'] != ''){
                    $sql .= " AND cast(event_end as date) between '$requestData[event_end_min]' and '$requestData[event_end_max]'";
                }
                else{
                    $sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
                }
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
            $url = Yii::app()->createUrl("/admin/events/attendies")."/".$row[$primary_key];
            $sql = "SELECT count(*) as count from booking where event_id = ".$row[$primary_key];
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($result)){
                $count = $result[0]['count'];
            }
            else{
                $count = 0;
            }

            $row['total_tickets'] = "<a href=\"$url\">$count of $row[total_tickets] taken</a>";
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
     * checks if coupon is valid or not
     */
    public function actionCheckCoupon($id){
        $couponcode = $_POST['coupon'];
        $today = date('Y-m-d H:i:s');
        $sql = "SELECT * from events where event_id = ".$id." AND coupon_code = "."'$couponcode'"." AND coupon_start_date <= "."'$today'"." AND coupon_end_date >= "."'$today'";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        if(empty($result)){
            echo json_encode([
                'token' => 0,
            ]);
        }
        else{
            echo json_encode([
                'token' => 1,
            ]);
        }
    }

    /**
     * Opens a page for calendar view for events
     */
    public function actionCalendarview(){
        $items = array();
        $currentrole = Yii::app()->user->role;
        if($currentrole != "admin"){
            $userid = Yii::app()->user->id;
            $model=Events::model()->findAll(['condition'=>"event_host = ".$userid]);
        }
        else{
            $model=Events::model()->findAll();
        }
        foreach ($model as $value) {
            if ($value->event_type == 'regular' || $value->event_type == "specific"){
                $items[]=array(
                    'id' => $value->event_id,
                    'title' =>$value->event_title,
                    'start' =>$value->event_start,
                    //'end' =>$value->event_end,
                    'data' => ['user_id' => $value->user_id],
                    'allDay'=>true,
                    'color'=> ($value->color) ? "#".$value->color : '#10b8c7',
                );
            }else{
                $items[]=array(
                    'id' => $value->event_id,
                    'title' =>$value->event_title,
                    'start' =>$value->event_start,
                    'end' =>$value->event_end,
                    'data' => ['user_id' => $value->user_id],
                    'color'=> ($value->color) ? "#".$value->color : '#10b8c7',
                    //'color'=>'#CC0000',
                    //'allDay'=>true,
                    //'url'=>'http://anyurl.com'
                );
            }
        }

        $sql = "SELECT DISTINCT u.first_name,e.event_host from events e LEFT JOIN user_info u on e.event_host = u.user_id where 1=1";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('calendarview', array(
            'events' => $items,
            'hosts'=>$result,
        ));
    }


    /**
     * shows page for attendies datatable
     */
    public function actionAttendies($id){
        $this->render('attendies',array('id'=>$id));
    }

    /**
     * datatable action for attendies
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

        $sql = "SELECT  b.*,e.event_title FROM booking b LEFT JOIN events e ON b.event_id = e.event_id where b.event_id =".$id;
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
            $row['event_id'] = $row['event_title'];
            switch($row['status']){
                case "approved" :
                    $row['status'] = "<span class='m-badge  m-badge--primary m-badge--wide'>Approved</span>";
                    break;
                case "pending":
                    $row['status'] = "<span class='m-badge  m-badge--brand m-badge--wide'>Pending</span>";
                    break;
                case "rejected":
                    $row['status'] = "<span class='m-badge  m-badge--danger m-badge--wide'>Rejected</span>";
                    break;
                case "waitlist":
                    $row['status'] = "<span class='m-badge  m-badge--info m-badge--wide'>Waitlist</span>";
                    break;
                default:
                    break;
            }
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

    /**
     * shows specific events for particular event hosts.
     */
    public function actionEventhosts($id){
        $sql = "SELECT first_name from user_info WHERE user_id = ".$id;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $selectedhost = "";
        if(!empty($result)){
            $selectedhost = $result[0]['first_name'];
        }
        $currentrole = Yii::app()->user->role;
        if($currentrole == "admin"){
            $model=Events::model()->findAll(['condition'=>"event_host = ".$id]);
        }
        foreach ($model as $value) {
            if ($value->event_type == 'regular' || $value->event_type == "specific"){
                $items[]=array(
                    'id' => $value->event_id,
                    'title' =>$value->event_title,
                    'start' =>$value->event_start,
                    //'end' =>$value->event_end,
                    'data' => ['user_id' => $value->user_id],
                    'allDay'=>true,
                );
            }else{
                $items[]=array(
                    'id' => $value->event_id,
                    'title' =>$value->event_title,
                    'start' =>$value->event_start,
                    'end' =>$value->event_end,
                    'data' => ['user_id' => $value->user_id],
                    //'color'=>'#CC0000',
                    //'allDay'=>true,
                    //'url'=>'http://anyurl.com'
                );
            }
        }

        $sql = "SELECT u.first_name,e.event_host from events e LEFT JOIN user_info u on e.event_host = u.user_id where 1=1";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('calendarview', array(
            'events' => $items,
            'hosts'=>$result,
            'selectedhost'=>$selectedhost,
        ));
    }


}
