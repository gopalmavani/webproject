<?php

class UserController extends Controller
{
    public $layout = 'main';
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    protected function beforeAction($action)
    {
        if (Yii::app()->user->isGuest && $action->id != "forgot" && $action->id != "resetpassword" && $action->id != "passwordreset"){
            $this->redirect(Yii::app()->createUrl('home/login'));
        }else{
            if($action->id != "forgot" && $action->id != "resetpassword" && $action->id != "passwordreset"){
                $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->id]);
                if ($action->id != 'login'){
                    if (Yii::app()->session['userid'] != $user->password){
                        $this->redirect(Yii::app()->createUrl('home/login'));
                    }
                }
            }
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
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('mijngegevens','view'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update'),
                'users'=>array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete'),
                'users'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionMijngegevens()
    {
        $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);

        /*if(!empty(CUploadedFile::getInstance($user,'image')))
        {
            $user->image=CUploadedFile::getInstance($user,'image');
            if($user->save())
            {
                $images_path = realpath(Yii::app()->basePath . '/../images');
                $user->image->saveAs($images_path . '/' . Yii::app()->user->getId().'.png');

            }
        }*/

        $this->render('index', array(
            'user' => $user
        ));
    }

    /**
     * This is the update user action
     */
    public function actionUpdate()
    {

        $model = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
        $model->full_name = $_POST['profile']['first_name'].' '.$_POST['profile']['last_name'];
        $model->attributes = $_POST['profile'];
        //print_r($model->attributes); die;
        if($model->validate()){
            if($model->save()) {
                echo json_encode([
                    'token' => 1
                ]);
            }else{
                echo json_encode([
                    'token' => 0
                ]);
            }
        }else{
            print_r($model->getErrors()); die;
        }


    }

    /**
     *change password of user
     */
    public function actionPaswoordveranderen()
    {
        $this->layout = "main";
        $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
        $this->render('changepassword', array(
            'user' => $user
        ));
    }

    /*
     * update password of user
     */
    public function actionUpdatePassword()
    {
        //print_r($_POST);die;
        $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);

        $user->password = md5($_POST['new-password']);
        $user->modified_at = date('Y-m-d H:i:s');
        if($user->validate()){
            if($user->save()) {
                echo json_encode([
                    'token' => 1
                ]);
            }else{
                echo json_encode([
                    'token' => 0
                ]);
            }
        }else{
            print_r($user->getErrors()); die;
        }
    }

    public function actionUploadImage(){
        /*header('Content-Type: image/jpeg');*/
        print_r($_FILES);
        print_r($_POST); die;
    }

    public function actionCreate()
    {
        $model=new Item;
        if(isset($_POST['Item']))
        {
            $model->attributes=$_POST['Item'];
            $model->image=CUploadedFile::getInstance($model,'image');
            if($model->save())
            {
                $model->image->saveAs('path/to/localFile');
                // redirect to success page
            }
        }
        $this->render('create', array('model'=>$model));
    }

    /**
     * Forgot password
     */
    public function actionForgot()
    {

        //$this->layout = 'login';
        if (isset($_POST['email'])) {
            if (!empty($_POST['email'])) {
                $this->forget($_POST['email']);
            }else{
                echo "we are in first else";die;
            }

        }else {
            echo "we are in seconde else";die;
        }
    }


    /**
     * Logs in a user using the provided username
     * @return boolean whether the user is logged in successfully
     */
    public function forget($email)
    {
        if($this->sendEmail($email)){
            return true;
        }
        return false;
    }

    /**
     * Send mail
     * This method will send email with a token
     * @returns boolean
     *
     */
    public function sendEmail($email){

        $model = UserInfo::model()->findByAttributes(['email' => $email]);
        if ($model) {
            $_SESSION['myemail'] = $email;
            $token = $model->user_id . mt_rand(10000, 999999);
            $_SESSION['mytoken'] = $token;
            /*$model->token = $token;
            $model->save(false);*/
            $appName = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", Yii::app()->params['applicationName']));
            $application = ucfirst($appName);
            $siteUrl = Yii::app()->getBaseUrl(true);
            $imgUrl = Yii::app()->getBasePath(true);
            $resetUrl = Yii::app()->getBaseUrl(true) . '/user/resetpassword/?token=' . $token;

            $to = $model->email;
            $subject = $application.' Password reset link';
            $from = 'support@'.$application.'com';

            // To send HTML mail, the Content-type header must be set
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            // Create email headers
            $headers .= 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            // Compose a simple HTML email message

            $message = "<html xmlns=\"http://www.w3.org/1999/xhtml\"><head>";
            $message .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
            $message .= "<title>".$application."com Emailer Email Verification</title>";
            $message .= "<style type=\"text/css\">";
            $message .= "body {margin: 0; padding: 0; min-width: 100%!important;font-family: Calibri, Arial;}";
            $message .= "</style></head>";
            $message .= "<body yahoo bgcolor=\"#fff\">";
            $message .= "<table width=\"703\" align=\"center\" bgcolor=\"#fff\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >";
            $message .= "<tr><td>";
            $message .= "<table class=\"content\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
            $message .= "<tr><td style=\"text-align: center;\">";
            $message .= "<img src=" . $imgUrl . "/img/imgpsh_fullsize.png\" alt=\"$application\"/>";
            $message .= "</td></tr>";
            $message .= "<tr><td style=\"text-align: center;\">";
            $message .= "<img src=" . $imgUrl . "/img/verify-email.jpg\" alt=\"$application\" Header\" />";
            $message .= "</td></tr>";
            $message .= "<tr><td style=\"padding: 10px 45px\">";
            $message .= "<table><tr><td>";
            $message .= "<p style=\"color:#105b8e;\"><strong>Dear $model->first_name,</strong></p>";
            $message .= "<p>Your request for password reset is almost complete. Please click the link below to reset your password.</p>";
            $message .= "<p><a href='$resetUrl'>$siteUrl/$token</a></p>";
            $message .= "<p>You will be redirected back to $application.com</p>";
            $message .= "<p>$application Team</p>";
            $message .= "</td></tr></table></td></tr>";

            $message .= "<tr><td style=\"border-top: 1px solid #e5e5e5; text-align: center; font-size: 13px; padding: 15px 0px;\">This is an automatically generated email.";
            $message .= "<br/>Please do not reply as your message will not be received and will be returned to you by the mail server.";
            $message .= "<br/>Copyright ".$application.".com <?php echo date('Y'); ?>";
            $message .= "</td></tr><tr><td><table width=\"100%\" style=\"background-color:#000000; font-size: 12px; color: #ffffff; padding: 10px;\"><tr>";
            $message .= "</tr></table></td></tr></table></td></tr></table></body></html>";
            //echo $message; die;
            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['successtoast'] = "We hebben een link naar u verstuurd om uw wachtwoord te resetten";
                $this->redirect(Yii::app()->createUrl('home/'));
            } else {
                $this->render('forgot-password', [
                    'error' => 'Oei! Het lijkt dat er iets is misgelopen.'
                ]);
            }
        }else{
            $_SESSION['forgotpasswordmsg'] = "Please enter correct email";
            $this->redirect(Yii::app()->createUrl('home/'));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect('/home/');
    }
    /*
     * Reset Password
     */
    public function actionResetPassword(){
        $this->layout = 'login';
        if (isset($_GET)){
            $user = UserInfo::model()->findByAttributes(['email' => $_SESSION['myemail']]);
            if ($user){
                /*$user->token = '';
                $user->save();*/
                $this->render('resetpassword',[
                    'user' => $user
                ]);
            }else{
                $this->render('linkbroken',[
                    'invalid' => 'De link die u tracht te openen is verbroken of verlopen.'
                ]);
            }
        }
    }

    public function actionPasswordReset(){
        if (isset($_POST['user-id']) && isset($_POST['new-password']) && isset($_POST['confirm-password'])){
            if ($_POST['new-password'] == $_POST['confirm-password']){
                $user = UserInfo::model()->findByAttributes(['user_id' => $_POST['user-id']]);
                $user->password = md5($_POST['new-password']);
                $user->save();
            }
            else{
                $_SESSION['loginerror'] = "Passwords doesn't match!!";
            }
            $this->redirect(Yii::app()->getBaseUrl(true).'/home/login');
        }
        else{
            $this->redirect(Yii::app()->getBaseUrl(true).'/home/login');
        }
    }

}