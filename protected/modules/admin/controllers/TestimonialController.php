<?php

class TestimonialController extends CController
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

    protected function beforeAction($action)
    {
        $currentrole = Yii::app()->user->role;
        if ($currentrole != "admin" ) {
            $this->redirect(Yii::app()->createUrl('home/login'));
        }
        return parent::beforeAction($action);
    }

    public function actionAdmin()
    {
        $model = new Testimonial('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Testimonial']))
            $model->attributes = $_GET['Testimonial'];
        $alldata = Testimonial::model()->findAll();

        $this->render('admin', array(
            'model' => $model,
            'alldata' => $alldata,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Testimonial the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Testimonial::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Testimonial $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='exercise-detail-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Make image name using this function
     * @param $imageData
     * @return string
     */
    protected  function getImageName($imageData){
        $imagePath = '/uploads/testimonial/';
        $date = date('Ymd');
        $time = time();
        $random = rand(99,99999);
        return $imagePath . $date . $time . $random . '.' . pathinfo($imageData, PATHINFO_EXTENSION);
    }

    /**
     * Shows datatable for testimonial of media.
     */
    public function actionServerdata()
    {
        $requestData = $_REQUEST;
        $model = new Testimonial;
        $primary_key = $model->tableSchema->primaryKey;
        $array_cols = Yii::app()->db->schema->getTable(Testimonial::model()->tableSchema->name)->columns;
        $array = array();
        $i = 0;
        foreach ($array_cols as $key => $col) {
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;

        $sql = "SELECT  * from " . Testimonial::model()->tableSchema->name;
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND ( $primary_key LIKE '%" . $requestData['search']['value'] . "%' ";
            foreach ($array_cols as $key => $col) {
                if ($col->name != $primary_key) {
                    $sql .= " OR " . $col->name . " LIKE '%" . $requestData['search']['value'] . "%'";
                }
            }
            $sql .= ")";
        }

        $j = 0;

        // getting records as per search parameters
        foreach ($columns as $key => $column) {

            if (!empty($requestData['columns'][$key]['search']['value'])) {   //name
                $sql .= " AND $column LIKE '%" . $requestData['columns'][$key]['search']['value'] . "%' ";
            }
            $j++;
        }

        $count_sql = str_replace("*", "count(*) as columncount", $sql);
        $data = Yii::app()->db->createCommand($count_sql)->queryAll();
        $totalData = $data[0]['columncount'];
        $totalFiltered = $totalData;

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
            $requestData['length'] . "   ";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $data = array();
        $i = 1;


        foreach ($result as $key => $row) {
            $nestedData = array();
            $nestedData[] = $row[$primary_key];
            foreach ($array_cols as $key => $col) {
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
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Testimonial;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Testimonial']))
        {
            $model->attributes=$_POST['Testimonial'];

            $model->media = CUploadedFile::getInstance($model, 'media');
            //echo $model->event_image;die;
            $imageName = $this->getImageName($model->media);
            if (CUploadedFile::getInstance($model, 'media') != '') {
                $model->media = $imageName;
            }
            $model->category = "Image/Video";
            $model->created_at = date("Y-m-d H:i:s");
            $model->modified_at = date("Y-m-d H:i:s");
            if($model->save()){
                if (CUploadedFile::getInstance($model, 'media') != '') {
                    $model->media = CUploadedFile::getInstance($model, 'media');
                    $model->media->saveAs(Yii::app()->basePath . '/..' . $imageName);
                }
                $_SESSION['delete'] = "This Image/Video  created successfully in Testimonial";
                $this->redirect(array('view','id'=>$model->id));
            }
        }

        $this->render('create',array(
            'model'=>$model,
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

        if(isset($_POST['Testimonial']))
        {
            $model->attributes=$_POST['Testimonial'];

            $imageFile = CUploadedFile::getInstance($model,'media');
            $model->media = $imageFile;
            $currentModel = $this->loadModel($id);

            if(empty($model->media)){
                $imageFile = CUploadedFile::getInstance($currentModel,'media');
                $model->media = $imageFile;
            }

            $imageName = '';
            if($model->media != ''){
                $imageName = $this->getImageName($model->media);
                $model->media = $imageName;
                $fileExists = is_file(Yii::app()->basePath . '/..' . $currentModel->media)?'yes':'no';
                if($fileExists == 'yes'){
                    unlink(Yii::app()->basePath . '/..' . $currentModel->media);
                }
            }else{
                $model->media = $currentModel->media;
            }

            if($model->save()){
                if (CUploadedFile::getInstance($model, 'media') != '') {
                    $model->media = CUploadedFile::getInstance($model, 'media');
                    $model->media->saveAs(Yii::app()->basePath . '/..' . $imageName);
                }
                $_SESSION['delete'] = "This Image/Video updated successfully in Testimonial";
                $this->redirect(array('view','id'=>$model->id));
            }

        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        $id = "";
        if(isset($_POST['id'])){
            $id = $_POST['id'];
        }
        if($this->loadModel($id)->delete()){
            $_SESSION['delete'] = "This Image/Video deleted successfully from Testimonial";
            echo json_encode(['token' => 1]);
        }
        else{
            echo json_encode(['token' => 0,]);
        }
    }
}