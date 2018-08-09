<?php

class ResourcesController extends CController
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
        $model=new Resources;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Resources']))
        {
            $model->attributes=$_POST['Resources'];
            $model->created_at = date("Y-m-d H:i:s");
            $model->modified_at = date("Y-m-d H:i:s");
            if($model->is_available == "on"){
                $model->is_available = 1;
            }
            else{
                $model->is_available = 0;
            }
            if($model->save())
                $this->redirect(array('view','id'=>$model->resource_id));
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

        if(isset($_POST['Resources']))
        {
            $model->attributes=$_POST['Resources'];
            if($model->is_available == "on"){
                $model->is_available = 1;
            }
            else{
                $model->is_available = 0;
            }
            $model->modified_at = date("Y-m-d H:i:s");
            if($model->save())
                $this->redirect(array('view','id'=>$model->resource_id));
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
            $_SESSION['delete'] = "Resource deleted successfully";
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
        $TableID = CylTables::model()->findByAttributes(['table_name' => Resources::model()->tableSchema->name]);
        $model = new Resources('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Resources']))
            $model->attributes = $_GET['Resources'];
        $alldata = Resources::model()->findAll();

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
        $requestData = $_REQUEST;
        $model = new Resources;
        $primary_key = $model->tableSchema->primaryKey;
        $array_cols = Yii::app()->db->schema->getTable(Resources::model()->tableSchema->name)->columns;
        $array = array();
        $i = 0;
        foreach($array_cols as  $key=>$col){
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;

        $sql = "SELECT  * from ".Resources::model()->tableSchema->name." where 1=1";
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
     * @return Resources the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Resources::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Resources $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='resources-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
