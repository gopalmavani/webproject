<?php

class NotificationManagerController extends CController
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
        return UserIdentity::accessRules();
    }

    protected function beforeAction($action)
    {
        $currentrole = Yii::app()->user->role;
        if ($currentrole != "admin" ) {
            $this->redirect(Yii::app()->createUrl('home/login'));
        }
        return parent::beforeAction($action);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView()
    {
        $notifications = NotificationManager::model()->findAll(array('condition' => 'is_delete = 0','order'=>'id DESC'));
        $model = Settings::model()->findAll();

        $this->pageTitle = 'Notifications';
        $this->render('view',array('notifications' => $notifications,'settings'=>$model));
    }

    public function actionEmailView()
    {
        $model = Settings::model()->findAll();

        $newsql = "SELECT * from email_content";
        $newresult = Yii::app()->db->createCommand($newsql)->queryAll();

        $this->pageTitle = 'Notifications';
        $this->render('emailView',array('settings'=>$model,'emailcontent'=>$newresult));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        $model = NotificationManager::model()->findByAttributes(array('id' => $_POST['id']));
        $model->is_delete = 1;
        if ($model->save()){
            echo json_encode([
                'token' => 1
            ]);
        }else{
            echo json_encode([
                'token' => 0
            ]);
        }

    }
    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        /*$dataProvider=new CActiveDataProvider('NotificationManager');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));*/
        $this->redirect(array('view'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $this->render('admin');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return NotificationManager the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=NotificationManager::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param NotificationManager $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='NotificationManager-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    /**
     * Manages data for server side datatables.
     */
    public function actionServerdata(){
        /*$alldata = Yii::app()->db->createCommand("SELECT * FROM user_info")->queryAll();
        echo json_encode($alldata);*/

        $requestData = $_REQUEST;

        $model= new NotificationManager();
        $array_cols = Yii::app()->db->schema->getTable('notification_manager')->columns;
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

        if(isset($_COOKIE['portalId']) && $_COOKIE['portalId'] > 0) {
            $sql = "SELECT  * from notification_manager where `portal_id`= ".$_COOKIE['portalId'];
        }else{
            $sql = "SELECT  * from notification_manager where 1=1";
        }

        /*$sql = "SELECT  * from NotificationManager where 1=1";*/
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $totalFiltered = count($data);

        if (!empty($requestData['search']['value']))
        {
            $sql.=" AND ( category_id LIKE '%" . $requestData['search']['value'] . "%' ";
            foreach($array_cols as  $key=>$col){
                if($col->name != 'category_id')
                {
                    $sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
                }
            }
            $sql.=")";
//			$sql.=" OR employee_age LIKE '" . $requestData['search']['value'] . "%')";
        }

        /*echo "<pre>";
        print_r($requestData['columns']);die;*/

        $j = 0;
        // getting records as per search parameters
        foreach($columns as $key=>$column){
            if($requestData['columns'][$key]['search']['value'] != '' ){   //name
                $sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
            }
            $j++;
        }

//		echo $sql;die;
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        $totalData = count($data);
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
            $nestedData[] = $row['id'];
            /*$row['is_active'] = $row['is_active'] == 0? ('No') : ('Yes');*/
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

    public function actionRead(){
        $model = NotificationManager::model()->findByAttributes(['id' => $_POST['id']]);
        $model->is_unread = 0;
        if ($model->save()){
            echo json_encode([
                'token' => 1,
                'url'=>$model->url
            ]);
        }else{
            echo json_encode([
                'token' => 0
            ]);
        }
    }

    /**
     * saves notification settings to settings table.
     */
    public function actionSavesettings(){
        if(isset($_POST['Settings'])){
            $sql = "DELETE FROM settings where module_name = 'notification'";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($_POST['Settings'] as $key=>$value){
                $model = new Settings();
                $date  = date("Y-m-d H:i:s");
                $model->module_name = "notification";
                $model->created_date = $date;
                $model->modified_date = $date;
                $model->settings_key = $key;
                $model->value = $value;
                if($model->validate()){
                    $model->save();
                }
            }
        }

        $model = Settings::model()->findAll();

        $newsql = "SELECT * from email_content";
        $newresult = Yii::app()->db->createCommand($newsql)->queryAll();

        $notifications = NotificationManager::model()->findAll(array('condition' => 'is_delete = 0','order'=>'id DESC'));
        $this->render('view',array('notifications' => $notifications,'settings'=>$model,'emailcontent'=>$newresult));
    }

    /**
     * Saves settings for sending email.
     */
    public function actionSaveemailsettings(){
        if(isset($_POST['Settings'])){
            $sql = "DELETE FROM settings where module_name = 'emailsettings'";
            Yii::app()->db->createCommand($sql)->execute();
            foreach ($_POST['Settings'] as $key=>$value){
                $model = new Settings();
                $date  = date("Y-m-d H:i:s");
                $model->module_name = "emailsettings";
                $model->created_date = $date;
                $model->modified_date = $date;
                $model->settings_key = $key;
                $model->value = $value;
                if($model->validate()){
                    $model->save();
                }
            }
        }

        $model = Settings::model()->findAll();

        $newsql = "SELECT * from email_content";
        $newresult = Yii::app()->db->createCommand($newsql)->queryAll();

        $notifications = NotificationManager::model()->findAll(array('condition' => 'is_delete = 0','order'=>'id DESC'));
        $this->render('emailView',array('notifications' => $notifications,'settings'=>$model,'emailcontent'=>$newresult));
    }

    /**
     * saves content for all types of email
     */
    public function actionSaveemailcontent(){
        $sql = "SELECT * from email_content where module_name = 'emailcontent'";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $created_at = date("Y-m-d H:i:s");
        $modified_at = date("Y-m-d H:i:s");
        if(isset($_POST['emailcontent'])){
            $myemailcontent = $_POST['emailcontent'];
            if(empty($result)){
                foreach ($myemailcontent as $key=>$value){
                    $sql = "INSERT INTO email_content values('','emailcontent','$key','$value','$created_at','$modified_at')";
                    Yii::app()->db->createCommand($sql)->execute();
                }
            }
            else{
                $deletesql = "DELETE from email_content where module_name = 'emailcontent'";
                Yii::app()->db->createCommand($deletesql)->execute();
                foreach ($myemailcontent as $key=>$value){
                    $modified_at = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO email_content values('','emailcontent','$key','$value','$created_at','$modified_at')";
                    Yii::app()->db->createCommand($sql)->execute();
                }
            }
        }

        $fromsql = "SELECT * from email_content where module_name = 'fromemail'";
        $fromresult = Yii::app()->db->createCommand($fromsql)->queryAll();

        if(isset($_POST['fromemail'])){
            $fromemail = $_POST['fromemail'];
            if(empty($fromresult)){
                foreach($fromemail as $key=>$value){
                    $sql = "INSERT INTO email_content values('','fromemail','$key','$value','$created_at','$modified_at')";
                    Yii::app()->db->createCommand($sql)->execute();
                }
            }
            else{
                $deletesql = "DELETE from email_content where module_name = 'fromemail'";
                Yii::app()->db->createCommand($deletesql)->execute();
                foreach($fromemail as $key=>$value){
                    $modified_at = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO email_content values('','fromemail','$key','$value','$created_at','$modified_at')";
                    Yii::app()->db->createCommand($sql)->execute();
                }
            }
        }

        $newsql = "SELECT * from email_content";
        $newresult = Yii::app()->db->createCommand($newsql)->queryAll();

        $model = Settings::model()->findAll();

        $notifications = NotificationManager::model()->findAll(array('condition' => 'is_delete = 0','order'=>'id DESC'));
        $this->render('emailView',array('notifications' => $notifications,'settings'=>$model,'emailcontent'=>$newresult));
    }
}
