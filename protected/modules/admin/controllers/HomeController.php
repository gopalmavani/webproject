<?php

class HomeController extends CController
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

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
     * Allow only the owner to do the action
     * @return boolean whether or not the user is the owner
     */

    public function allowOnlyOwner()
    {
        if (Yii::app()->user->isAdmin) {
            return true;
        } else {
            $example = Example::model()->findByPk($_GET["id"]);
            return $example->uid === Yii::app()->user->id;
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $navigation = NavCheck::navigationCheck();
        $year = date('Y');
        $month = date('m');
        $t =  cal_days_in_month(CAL_GREGORIAN,$month,$year);

        $days = "";
        $daywiseevents = array();
        $result = array();
        $mybookings = array();

        if(Yii::app()->db->schema->getTable('events')){
            for($i = 1;$i<=$t;$i++){
                if($i == 1){
                    $days .= $i;
                }
                else{
                    $days .=",".$i;
                }

                $sql = "SELECT count(*) as eventcount from events where date(event_start) = "."'$year-$month-$i'";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                $daywiseevents[$i] = $result[0]['eventcount'];
            }

            $newsql = "SELECT * FROM booking order by booking_id desc LIMIT 10";
            $result = Yii::app()->db->createCommand($newsql)->queryAll();

            $myresult = Yii::app()->db->createCommand("SELECT * from booking")->queryAll();
            $today = date('Y-m-d');
            $later = date('Y-m-d', strtotime("+10 days"));


            foreach ($myresult as $key=>$item) {
                if(!empty($item['event_id'])){
                    $bookingsql = "SELECT * FROM events where event_id = ".$item['event_id']." AND date(event_start) between "."'$today'"." and "."'$later'";
                    $bookingresult = Yii::app()->db->createCommand($bookingsql)->queryAll();
                    if(!empty($bookingresult)) {
                        array_push($bookingresult[0],$item['username']);
                        array_push($mybookings, $bookingresult[0]);
                    }
                }
            }
        }


        $this->render('index', array('navigation' => $navigation,'days'=>$days,'daywiseevents'=>$daywiseevents,'bookings'=>$result,'upcomingbookings'=>$mybookings));

    }

    /*
     * for the dashboard Order Filter Date Wise ajax response.
     */
    public function actionOrderFilter()
    { //echo $_POST['enddate'];die;
        $startdate = $_POST['startdate'] . ' 00:00:00';
        $enddate = $_POST['enddate'] . ' 23:59:59';

        $query = "SELECT user_name,email,order_status,netTotal,orderTotal,invoice_number, created_date FROM order_info WHERE created_date BETWEEN '$startdate' AND '$enddate' order by created_date DESC limit 50 ";

        $orders = Yii::app()->db->createCommand($query)->queryAll();
        $countOrder = "SELECT count(*) as Total FROM order_info WHERE created_date BETWEEN '$startdate' AND '$enddate'";
        $response = '';
        if (!empty($orders)) {

            $no_of_order = Yii::app()->db->createCommand($countOrder)->queryRow();
            $countLicense = "SELECT sum(orderTotal) as total FROM order_info WHERE created_date BETWEEN '$startdate' AND '$enddate'";
            $no_of_license = Yii::app()->db->createCommand($countLicense)->queryRow();
            $total_license = ($no_of_license['total'] / 5);
            $pendingOrder = "SELECT count(*) as Pending FROM order_info WHERE created_date BETWEEN '$startdate' AND '$enddate' and order_status = 2";
            $pending_order = Yii::app()->db->createCommand($pendingOrder)->queryRow();

            $no_of_orders = $no_of_order['Total'];
            $pending_orders = $pending_order['Pending'];
            $response .= "
        	<table class='table table-striped m-table'>         
                 <thead>
                    <tr>
                        <th>UserName</th>
                        <th>Total</th>
                        <th>Invoice Number</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                 </thead>
                 <tbody style='height: 300px;overflow-y:scroll;'>";
            foreach ($orders as $key => $value) {
                if (empty($value['invoice_number'])) {
                    $invoice = "N/A";
                } else {
                    $invoice = $value['invoice_number'];
                }
                if ($value['order_status'] == 0) {
                    $status = '<span align="center" class="label label-table label-danger">Cancelled</span>';
                } else if ($value['order_status'] == 1) {
                    $status = '<span align="center" class="label label-table label-success">Success</span>';
                } else {
                    $status = '<span align="center" class="label label-table label-warning">Pending</span>';
                }
                $response .= "
    			<tr>
	    			<td>" . $value['user_name'] . "</td>
	    			<td>&euro;" . $value['netTotal'] . "</td>
	    			<td>" . $invoice . "</td>
	    			<td>" . $status . "</td>
	    			<td>" . $value['created_date'] . "</td>
    			</tr>";
            }
            $response .= "</tbody></table>";
            $status = true;
        } else {
            $status = false;
            $no_of_orders =0;
            $total_license = 0;
            $pending_orders = 0;
            $response .= "<table class='m-datatable__table'><tbody>
        		<tr>
	    			<td style='border-top:0px;'></td>
	    			<td style='border-top:0px;'><h4 align='center'>No Orders Found</h4></td>
	    			<td style='border-top:0px;'></td>
    			</tr></tbody></table>";
        }
        $result = [
            'status' => $status,
            'reponse_detail' => $response,
            'no_of_orders'  => $no_of_orders,
            'total_license' => $total_license,
            'pending_orders' => $pending_orders
        ];

        echo json_encode($result);

    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
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
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {

        $this->layout = 'login';
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $auth = Yii::app()->db->schema->getTable('address_mapping');
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $data = SysUsers::model()->findByAttributes(array('username' => $model->username));
                $_SESSION['user'] = $data->username;
                $this->redirect(['/admin/']);
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }


    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionEdit()
    {
        $this->pageTitle = 'Edit Profile';

        $data = SysUsers::model()->findByAttributes(array('username' => $_SESSION['user']));
        $this->render('edit', array('data' => $data));
    }

    public function actionUpdateEmail()
    {
        if (isset($_POST['email'])) {
            //echo "UPDATE sys_users SET email='".$_POST['email']."' WHERE username ='".$_SESSION['user']."';"; die;
            //Yii::$app->db->createCommand("UPDATE sys_users SET email='".$_POST['email']."' WHERE username ='".$_SESSION['user']."';")->execute();
            //if (Yii::app()->db->createCommand("UPDATE sys_users SET email='".$_POST['email']."' WHERE username ='".$_SESSION['user']."';")->execute()) {
            $user = SysUsers::model()->findByAttributes(['username' => $_SESSION['user']]);
            $user->email = $_POST['email'];
            $user->activekey = 1;
            $user->auth_level = 1;
            if ($user->save()) {
                echo json_encode([
                    'msg' => 'Success',
                ]);
            } else{
                print_r($user->Errors);
                echo json_encode([
                    'msg' => 'Fail',
                ]);
            }
        }
    }

    public function actionChangePass(){
        if (isset($_POST['current_pass']) && isset($_POST['New_Pass'])) {
            //print_r($_POST); die;
            $user = SysUsers::model()->findByAttributes(['username' => $_SESSION['user']]);
            if ($_POST['current_pass'] === $user->password){
                $user->password = $_POST['New_Pass'];
                if ($user->save()) {
                    echo json_encode([
                        'msg' => 'Success',
                    ]);
                } else{
                    echo json_encode([
                        'msg' => 'Fail',
                    ]);
                }
            }else{
                echo json_encode([
                    'token' => 1
                ]);
            }

        }
    }


    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        unset($_SESSION['user']);
        $this->redirect(Yii::app()->homeUrl . "admin/home/login/");
    }


    /**
     * shows popup for unauthorized actions.
     */
    public function actionExpired(){
        $this->layout = 'main';
        $denied = "denied";
        $this->render('expired', array('deny' => $denied));
    }

}