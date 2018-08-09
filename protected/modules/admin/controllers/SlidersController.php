<?php

class SlidersController extends CController
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

    protected function beforeAction($action)
    {
        $currentrole = Yii::app()->user->role;
        if ($currentrole != 'admin' ) {
            $this->redirect(Yii::app()->createUrl('home/login'));
        }
        return parent::beforeAction($action);
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Sliders;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Sliders'])) {
            $model->created_at = date('Y-m-d H:i:s');
            $model->attributes = $_POST['Sliders'];
            $model->image=CUploadedFile::getInstance($model,'image');
            if($model->validate())
            {
                $imageName = $this->getImageName($model->image);
                if (CUploadedFile::getInstance($model,'image') != ''){
                    $model->image = $imageName;
                }
                if($model->save())
                {
                    if(CUploadedFile::getInstance($model,'image') != '') {
                        $model->image = CUploadedFile::getInstance($model, 'image');
                        $model->image->saveAs(Yii::app()->basePath . '/..' . $imageName);
                    }
                    $this->redirect('admin',array(
                        'model'=>$model,
                    ));
                }
            }
        }
        $this->render('create',array(
            'model'=>$model,
        ));
    }

    protected  function getImageName($imageData){
        $imagePath = '/uploads/slider/';
        $date = date('Ymd');
        $time = time();
        return $imagePath . $date . $time . '.' . pathinfo($imageData, PATHINFO_EXTENSION);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = Sliders::model()->findAll();
        //echo "<pre>";print_r(count($model));die;
        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    public function actionDeleteImage(){
        $model = new Sliders('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Sliders']))
            $model->attributes = $_GET['Sliders'];
        $alldata = Sliders::model()->findAll();

        $this->render('deleteImage', array(
            'model' => $model,
            'alldata' => $alldata,
        ));
    }

    /**
     * Manages data for server side datatables.
     */
    public function actionServerdata(){
        $requestData = $_REQUEST;
        $model = new Sliders();
        $primary_key = $model->tableSchema->primaryKey;
        $array_cols = Yii::app()->db->schema->getTable(Sliders::model()->tableSchema->name)->columns;
        $array = array();
        $i = 0;
        foreach($array_cols as  $key=>$col){
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;

        $sql = "SELECT  * from ".Sliders::model()->tableSchema->name." where 1=1";
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

            if($requestData['columns'][$key]['search']['value'] != "" ){   //name
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
//        echo $sql;die;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $data = array();
        $i=1;


        foreach ($result as $key => $row)
        {
            $nestedData = array();
            $nestedData[] = $row[$primary_key];
            $row['image'] = "<image class='image' id='imagePreview' style = 'width: 175px;' src= ". Yii::app()->baseUrl . $row['image'] ." />";
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
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        $id = $_POST['id'];
        if($this->loadModel($id)->delete()){
            $_SESSION['delete'] = "Image deleted successfully";
            echo json_encode([
                'token' => 1,
            ]);
        }
        else{
            echo json_encode([
                'token' => 0,
            ]);
        }

        /*// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));*/
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Sliders the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Sliders::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Sliders $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='sliders-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
