<?php

class HomeController extends Controller
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
        if (Yii::app()->user->isGuest && $action->id != 'login' && $action->id != 'autologin' && $action->id != "index" && $action->id != "overons" && $action->id != "seminaries" && $action->id != "sprekers" && $action->id != "contact" && $action->id != "register" && $action->id != "subscribe" && $action->id != "cookiebeleid" && $action->id != "disclaimer" && $action->id != "privacybeleid" && $action->id != "locatie" && $action->id != "verifyaccount"){
            $this->redirect(Yii::app()->createUrl('home/login'));
        }else{

            if ($action->id != 'login' && $action->id != 'autologin' && $action->id != "index" && $action->id != "overons" && $action->id != "seminaries" && $action->id != "sprekers" && $action->id != "contact" && $action->id != "register" && $action->id != "subscribe"  && $action->id != "cookiebeleid" && $action->id != "disclaimer"  && $action->id != "privacybeleid" && $action->id != "locatie" && $action->id != "verifyaccount"){
                $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->id]);
                if (Yii::app()->session['userid'] != $user->password){
                    $this->redirect(Yii::app()->createUrl('home/login'));
                }
            }
        }
        return parent::beforeAction($action);

    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->layout = 'main';
        $users = array();
        $userInfo = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->id]);
        $testimonial = Testimonial::model()->findAll();
        $eventtable = Yii::app()->db->schema->getTable('events');

        $sql = "SELECT SUM(number_of_people) as count from booking";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $count = $result[0]['count'];
        $mycount = 50 - $count;
        $mystring = str_split($mycount);
        if(!empty($eventtable)){
            $events = Events::model()->findAll();
            $this->render('index', array(
                'user' => $userInfo,
                'events' => $events,
                'testimonial' => $testimonial,
                'mycount' => $mycount,
                'mystring' => $mystring
            ));
        }
        else{
            $this->render('index', array(
                'user' => $userInfo,
                'mycount' => $mycount,
                'mystring' => $mystring
            ));
        }

        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
    }

    /*
     * to show recent registered users.
     */
    public function actionNewChilds()
    {
        $NewUser = Yii::app()->db->createCommand()
            ->select('*')
            ->from('user_info')
            ->where('sponsor_id = '.Yii::app()->user->id)
            ->order('created_at desc')
            ->limit('5')
            ->queryAll();
        $this->renderPartial('new-user', array(
            'NewUser' => $NewUser
        ));
    }



    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        /*$model=new ContactForm;
        if(isset($_POST['ContactForm']))
        {
            $model->attributes=$_POST['ContactForm'];
            if($model->validate())
            {
                $name='=?UTF-8?B?'.base64_encode($model->name).'?=';
                $subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
                $headers="From: $name <{$model->email}>\r\n".
                    "Reply-To: {$model->email}\r\n".
                    "MIME-Version: 1.0\r\n".
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
                Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }*/
        $this->render('contact');
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $siteUrl = Yii::app()->params['siteUrl'];
        $file = file_exists(Yii::app()->params['basePath'].'/common/');
        if($file != ''){
            $currentApp = file_get_contents("../common/.current");
        }
        if (Yii::app()->db->schema->getTable('user_info')===null) {
            $this->redirect($siteUrl.'/admin/user/step/1');
        }

        $this->layout = 'login';
        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            /*print_r($_POST); die;*/
            $model->attributes=$_POST['LoginForm'];
            Yii::app()->session['userid'] = md5($model->password);
            // validate user input and redirect to the previous page if valid
            if($model->validate()){
                $user = UserInfo::model()->findByAttributes(array("email"=>$model->username));
                if($user->is_active == 1){
                    if($model->login()){
                        $this->redirect(Yii::app()->createUrl('/home/'));
                    }
                }
                else{
                    $_SESSION['loginerror'] = "Your account is not verified yet !";
                }

            }
            else{
                $_SESSION['incorrectusernameerror'] = "De gebruikersnaam of het wachtwoord is niet correct.";
            }
        }
        elseif (!isset(Yii::app()->user->id)){
            $_SESSION['loginerror'] = "Gelieve eerst in te loggen !";
        }
        // display the login form
        $this->redirect(Yii::app()->createUrl('/home/'));
    }


    /**
     * Auto logs in superadmin after creating new application.
     */
    public function actionAutologin(){

        $sql = "SELECT email from user_info";
        $result = Yii::app()->db->CreateCommand($sql)->queryAll();

        $email = $result[0]['email'];
//        echo $email;die;

        Yii::app()->session['adminLogin'] = 0;
        if (isset(Yii::app()->session['userid'])) {
            unset(Yii::app()->session['userid']);
        }
        /*Yii::app()->user->logout();*/
        $model = new AutoLoginForm;
        $model->email = $email;
        $user = UserInfo::model()->findByAttributes(['email' => $email]);
        Yii::app()->session['userid'] = $user->password;
        Yii::app()->session['adminLogin'] = 1;
        if ($model->validate()) {
            $appname = Yii::app()->params['applicationName'];
            $folder = Yii::app()->params['basePath'];
            if (strpos($folder, 'cyclone') !== false) {
                $todaydate = strtotime(date('Y-m-d H:i:s'));
                $expdate = '';
                $expsql = "SELECT expiring_at from app_management where app_name = " . "'$appname'";
                $expresult = Yii::app()->sitedb->createCommand($expsql)->queryAll();
                if (!empty($expresult)) {
                    $expdate = strtotime($expresult[0]['expiring_at']);
                }
                if ($todaydate > $expdate) {
                    $this->redirect(Yii::app()->createUrl('/../admin/demo/expired'));
                }
                else{
                    if ($model->login()) {
                        $last_logged_in = date('Y-m-d H:i:s');
                        $appname = Yii::app()->params['applicationName'];
                        $sql = "UPDATE app_management set last_logged_in = " . "'$last_logged_in'" . " where app_name = " . "'$appname'";
                        Yii::app()->sitedb->createCommand($sql)->execute();
                        $this->redirect(array('/admin/home/index/'));
                    }
                }
            }

        } else {
            $this->redirect(array('login'));
        }

    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->createUrl('home/'));

    }

    public function actionEventSearch(){
        //print_r($_POST); die;
        $date_start = date('Y-m-d H:i:s', strtotime($_POST['start']));
        $date_end = date('Y-m-d H:i:s', strtotime($_POST['end']));
        $events =array();
        $criteria=new CDbCriteria;
        $criteria->addBetweenCondition("event_start",$date_start,$date_end,'AND');
        $model = Events::model()-> findAll($criteria);
        foreach($model as $data){
            $events[] = [
                'event_name' => $data->event_title,
                'event_start' => date('d/m/Y',strtotime($data->event_start)),
                'event_end' => date('d/m/Y',strtotime($data->event_end)),
                'event_location' => $data->event_location,
                'event_desc' => $data->event_description,
                'event_time' => date('h:i a',strtotime($data->event_start)),
                'event_url' => $data->event_url,
            ];
        }
        if ($events){
            echo json_encode([
                'token' => 1,
                'data' => $events
            ]);
        }else{
            echo json_encode([
                'token' => 0,
            ]);
        }

    }
    public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    /**
     * Display the signup page
     * only put for email change no any use currently
     */
    public function actionSignup(){
        $this->layout = "login";
        $model = new UserInfo();
        if(isset($_POST['UserInfo'])){
            $model->attributes =$_POST['UserInfo'];
            if($_POST['UserInfo']['password'] == ""){
                $model->password = "";
            }
            else{
                $model->password = md5($model->password);
            }
            $model->full_name = $model->first_name." ".$model->last_name;
            $model->role = 'user';
            $model->created_at = date("Y-m-d H:i:s");
            $model->modified_at = date("Y-m-d H:i:s");

            if($model->validate()){
                if($model->save()){
                    $notisql = "SELECT * FROM settings where module_name = 'notification' AND settings_key = 'signup'";
                    $notiresult = Yii::app()->db->createCommand($notisql)->queryAll();
                    if(isset($notiresult[0]['value'])) {
                        if ($notiresult[0]['value'] == "usersignupchecked") {
                            $url = Yii::app()->createUrl("/admin/userInfo/view")."/".$model->user_id;
                            NavCheck::Pushnotification($model->first_name." has registered on The Functional barn","alert",$url);
                            $title = "New user registration";
                            $body = "<a href='$url'>$model->first_name</a> has registered on The Functional barn";
                            NotificationHelper::AddNotitication($title, $body, 'info', $model->user_id, 1, $url);
                        }
                    }

                    $emailsql = "SELECT value FROM settings where module_name = 'emailsettings' and settings_key = 'signup'";
                    $emailresult = Yii::app()->db->createCommand($emailsql)->queryAll();
                    if(isset($emailresult[0]['value'])){
                        if($emailresult[0]['value'] == "usersignupmailchecked"){
                            $to = $model->email;
                            $subject = "Registration of ".$model->first_name;
                            $msgsql = "SELECT description from email_content where `module_name` = 'emailcontent' and `key` = 'onusersignup'";
                            $msgresult = Yii::app()->db->createCommand($msgsql)->queryAll();
                            if(isset($msgresult[0]['description'])){
                                if($msgresult[0]['description'] != ""){
                                    $message = $msgresult[0]['description'];
                                }
                            }
                            else{
                                $message = "U bent geregistreerd bij Cryptotrain.";

                            }

                            $fromsql = "SELECT description from email_content where `module_name` = 'fromemail' and `key` = 'forusersignup'";
                            $fromresult = Yii::app()->db->createCommand($fromsql)->queryAll();
                            if(isset($fromresult[0]['description'])){
                                if($fromresult[0]['description'] != ""){
                                    $from = $fromresult[0]['description'];
                                }
                            }
                            else{
                                $from = "registration@cyclone.com";

                            }
                            Test::Email($to,$subject,$message,$from);
                        }
                    }

                    $this->redirect(Yii::app()->createUrl("home/autologin/")."/".$model->user_id);
                }
            }else{
                echo "<pre>";
                print_r($model->getErrors());die;
            }
        }
        $this->render('signup',array('model'=>$model));
    }

    /**
     * opens about us page.
     */
    public function actionOverons(){
        $this->render('aboutus');
    }

    /**
     * opens seminars page
     */
    public function actionSeminaries(){
        $this->render('seminars');
    }

    /**
     * opens Speakers page
     */
    public function actionSprekers(){
        $this->render('speakers');
    }

    /**
     * Registers a new User
     */
    public function actionRegister(){
        if(isset($_POST['register'])){
            $model = new UserInfo();
            $model->attributes = $_POST['register'];
            $model->full_name = $model->first_name." ".$model->last_name;
            $model->created_at = date("Y-m-d H:i:s");
            $model->password = md5($model->password);
            $model->is_active = 0;

            if($model->validate()){
                if($model->save()){
                    $to = $model->email;
                    $subject =  "Signup Crytotrain";
                    $logosrc = "https://www.cryptotrain.eu/plugins/imgs/logo.png";
                    $action = Yii::app()->getBaseUrl(true)."/home/verifyaccount";
                    $id = $model->user_id;
                    $message = "

<!DOCTYPE html> 
<html class='html'>
   <head>
      <meta charset='utf-8'>
      <meta http-equiv='X-UA-Compatible' content='IE=edge'>
      <meta name='viewport' content='width=device-width, initial-scale=1'>
      <link rel='shortcut icon' type='image/png' href='imgs/favicon.png' />
      <title>Cryptotrain | Verify Email</title>
      <link href='https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i' rel='stylesheet'>
      <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
      <style type='text/css'> body { 	margin: 0 !important; 	padding: 0 !important; 	color: #000; 	font-family: 'Roboto', sans-serif; 	font-size: 14px; 	line-height: 20px; 	background:#fafafa; } a { 	color: #ef963f; 	outline: none !important; 	text-decoration: none !important; } .container { 	width: 700px; 	margin: 30px auto !important; 	border: 1px solid #e0e0e0; 	background:#fff; 	-webkit-box-shadow: 0 0 100px rgba(0,0,0,.1); 	box-shadow: 0 0 100px rgba(0,0,0,.1) } .header { 	padding: 30px 0; 	text-align: center; } .logo img { 	margin: 0 auto; } .body { 	padding: 0 30px 30px 30px; } .body h1 { 	font-size: 48px; 	line-height: 54px; 	text-align: center; 	margin-bottom: 30px; } .split { 	display: inline-block; 	width: 40px; 	height: 2px; 	background-color: #000; 	margin-bottom: 30px; } .text-center { 	text-align: center; } .text-bold { 	font-weight:bold; } .content { 	margin-bottom:30px; } .btn { 	position: relative; 	display: inline-block; 	padding: 10px 40px; 	font-size: 20px; 	line-height: 41px; 	border-radius: 5px; } .btn-primary { 	-webkit-transition: none 172ms ease-out; 	transition: none 172ms ease-out; 	-webkit-transition-property: color, border-color, background-color; 	transition-property: color, border-color, background-color; 	color: #fff; 	border-color: #ef963f; 	background-color: #ef963f; } .btn-primary.focus, .btn-primary:active:focus, .btn-primary:active:hover, .btn-primary:focus, .btn-primary:hover { 	color: #fff; 	border-color: #959595; 	background-color: #959595 } .btn-primary.active, .btn-primary:active, .btn-primary.disabled, .btn-primary.disabled:hover { 	color: #fff; 	border-color: #959595; 	background-color: #959595 } .footer { 	padding: 20px; 	text-align: center; 	color: #999; 	background: #222222; 	font-size: 13px; } .footer p { 	margin: 0; 	padding: 0; } .footer a { 	color: #999; } @media screen and (max-width:480px) { .container { 	width: 320px; 	margin: 0 auto; } .btn { 	padding: 10px 30px !important; 	font-size: 16px; } } </style>
   </head>
   <body>
      <div class='container'>
         <div class='header'>
            <div class='logo'><img src='$logosrc' alt=''></div>
         </div>
         <div class='body text-center'>
            <h1>Verifieer je e-mailadres</h1>
            <div class='split'></div>
            <div class='content'>Bevestig dat u dit als uw e-mailadres voor uw Cryptotrain-account wilt gebruiken</div>
            <div class='content'>
                <form method='post'action='$action'>
                    <input type='hidden' name='userid' value='$id'>
                    <button style='cursor:pointer !important;' type='submit' class='btn btn-primary'>Verifieer mijn e-mail</button>
                </form> 
            </div>
            <div class='content'>of plak deze link in uw browser:<br>
                <form method='post' action='$action'>
                    <input type='hidden' name='userid' value='$id'>
                    <button type='submit' style='border: none;cursor: pointer !important;background: #ffff;color: blue;'  >$action</button>
                </form>
            </div>
            <div class='content text-bold'>     	Bedankt, <br> Team Cryptotrain     </div>
         </div>
         <div class='footer'>
            <p>Copyright 2018 &copy; Cryptotrain.</p>
            <p><a href='https://www.cryptotrain.eu/home/cookiebeleid'>Cookie beleid</a> | <a href='https://www.cryptotrain.eu/home/disclaimer'>Disclaimer/Legale informatie</a> | <a href='https://www.cryptotrain.eu/home/privacybeleid'>Privacybeleid</a></p>
         </div>
      </div>
   </body>
</html>

";
                    $from = "cryptotrain@crypto.com";
                    Test::Email($to,$subject,$message,$from);
                    $_SESSION['successtoast'] = "You have successfully registerd, to login please verify your account";
                    echo json_encode([
                        'token' => 1
                    ]);
                    exit;
                }
            }
            else{
                echo json_encode([
                    'token' =>0
                ]);
                exit;
            }
        }

        $this->render('register');

    }

    /**
     * action for subscription of users to newsletter or news about website
     * Integration of mailchimp api
     */
    public function actionSubscribe(){
        if(isset($_POST['email_address'])){
            if(isset(Yii::app()->user->id)){
                $model = UserInfo::model()->findAllByPk(Yii::app()->user->id);
                $myarray['merge_fields']['FNAME'] =  $model[0]->first_name;
                $myarray['merge_fields']['LNAME'] =  $model[0]->last_name;
            }
            $myarray = array();
            $myarray['email_address'] = $_POST['email_address'];
            $myarray['status'] = 'subscribed';

            $mydata = json_encode($myarray);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://us18.api.mailchimp.com/3.0/lists/4c923f9880/members/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "$mydata",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer 654d8be72f8a63ebcd069f4a7ad05af0-us18",
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Postman-Token: e77dafe0-a398-485f-947a-4e42f31f5748"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $myresponse = json_decode($response);
                if($myresponse->status == 'subscribed'){
                    $_SESSION['successtoast'] = "U bent succesvol ingeschreven om de nieuwsbrieven te ontvangen.";
                    $this->redirect(Yii::app()->createUrl("/home/"));
                }
                else if($myresponse->status == 400){
//                    $_SESSION['loginerror'] = $myresponse->detail;
                    $_SESSION['loginerror'] = "Deze gebruiker bestaat reeds!!";
                    $this->redirect(Yii::app()->createUrl("/home/"));
                }
                else{
                    echo "<pre>";
                    print_r($myresponse);die;
                }
            }
        }
    }

    /**
     * opens location page
     */
    public function actionLocatie(){
        $this->render('location');
    }

    /**
     * opens cookie policy
     */
    public function actionCookiebeleid(){
        $this->render('cookiebeleid');
    }

    /**
     * opens disclaimer page
     */
    public function actionDisclaimer(){
        $this->render('disclaimer');
    }

    /**
     * opens privacy policy.
     */
    public function actionPrivacybeleid(){
        $this->render("privacybeleid");
    }

    /**
     * verifies registered user
     */
    public function actionVerifyaccount(){
        if(isset($_POST['userid'])){
            $model = UserInfo::model()->findByPk($_POST['userid']);
            $model->is_active = 1;
            if($model->validate()){
                if($model->save()){
                    $_SESSION['successtoast'] = "Congratulations! Your account is verified.";
                    $this->redirect(Yii::app()->createUrl("home/"));
                }
            }
            else{
                echo "<pre>";
                print_r($model->getErrors());die;
            }
        }
    }
}