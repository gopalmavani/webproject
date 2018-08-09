<?php

class TicketingController extends Controller
{
    public $layout = 'main';

    /**
     * @param $id
     * This is action opens forum page for specific ticket.
     */
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $model = Ticketing::model()->findAll(Yii::app()->user->id);
        $model2 =new Ticketing;
        /*echo "<pre>";
        print_r($model);die;*/
        $this->render('index',
            array(
                'model' => $model,
                'model2' =>$model2
            ));
    }
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */

    public function actionDetail($id)
    {
        /*echo "<pre>";
        print_r($_POST['comment_description']);die;*/
        $model = $this->loadModel($id);
        if(isset($_POST['comment_description'])){
            $images = CUploadedFile::getInstancesByName('images');

            if (isset($images) && count($images) > 0) {
                foreach ($images as $image => $pic) {
                    $imageName = $this->getImageName($pic->name);
                    if ($pic->saveAs(Yii::app()->basePath . '/..'.$imageName)) {
                        $image_Name[] = $imageName; //it might be $img_add->name for you, filename is just what I chose to call it in my model
                    }
                    else{
                        echo "error";  die;
                    }
                }
                $attachment = json_encode($image_Name);
            }

            $user_id = Yii::app()->user->id;
            $ticket_id = $model->ticket_id;
            $created_at = date('Y-m-d H:i:s');
            $modified_at = date('Y-m-d H:i:s');
            $comment = $_POST['comment_description'];
            $sql = "INSERT into comment_mapping (`user_id`,`ticket_id`,`parent_id`,`comment`,`attachment`,`created_at`,`modified_at`) values ('$user_id','$ticket_id','0','$comment','$attachment','$created_at','$modified_at')";
            Yii::app()->db->createCommand($sql)->execute();
        }
        $this->render('detail',
            array(
                'model' => $model
            ));
    }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Ticketing;
        /*echo "<pre>";
        print_r($_POST);die;*/

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Ticketing']))
        {
            $images = CUploadedFile::getInstancesByName('images');

            if (isset($images) && count($images) > 0) {
                foreach ($images as $image => $pic) {
                    echo $pic->name.'<br />';
                    $imageName = $this->getImageName($pic->name);
                    if ($pic->saveAs(Yii::app()->basePath . '/..'.$imageName)) {
                        $image_Name[] = $imageName; //it might be $img_add->name for you, filename is just what I chose to call it in my model
                    }
                    else{
                        echo "error";  die;
                    }
                }
                $model->attachment = json_encode($image_Name);
            }

            $model->attributes=$_POST['Ticketing'];
            $model->ticket_detail = "detail";
            $model->status = "inprogress";
            $model->user_id = Yii::app()->user->id;
            $model->created_at = date('Y-m-d H:i:s');
            $model->modified_at = date('Y-m-d H:i:s');

            if($model->validate()){
                if($model->save()){
                    $this->redirect(Yii::app()->createUrl('/ticketing/index'));
                }
            }
            else{
                echo "<pre>";
                print_r($model->getErrors());die;
            }
        }

        $this->render('index',
            array(
                'model' => $model,
            ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Ticketing the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Ticketing::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
    /**
     * Make image name using this function
     * @param $imageData
     * @return string
     */
    protected  function getImageName($imageData){
        $imagePath = '/uploads/tickets/';
        $date = date('Ymd');
        $time = time();
        $random = rand(99,99999);
        return $imagePath . $date . $time . $random . '.' . pathinfo($imageData, PATHINFO_EXTENSION);
    }

    public function actionReplysave($id){
        $user_id = Yii::app()->user->id;
        if(isset($_POST['comment'])){
            $ticket_id = $_POST['ticket'];
            $parent_id =  $id;
            $created_at = date('Y-m-d H:i:s');
            $modified_at = date('Y-m-d H:i:s');
            $comment = $_POST['comment'];

            $sql = "INSERT into comment_mapping (`user_id`,`ticket_id`,`parent_id`,`comment`,`created_at`,`modified_at`) values ('$user_id','$ticket_id','$parent_id','$comment','$created_at','$modified_at')";
            Yii::app()->db->createCommand($sql)->execute();

            $this->redirect(Yii::app()->createUrl('/ticketing/detail/')."/".$ticket_id);
        }

    }
}
