<?php

class EventController extends Controller
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
        if (Yii::app()->user->isGuest && $action->id != "generatescript" && $action->id != "booking" && $action->id != "bookingconfirm" && $action->id != "checkcoupon" && $action->id != "eventwidget" && $action->id != "eventparticular" && $action->id != "bookingparticular" && $action->id != "bookingservice" && $action->id != "bookingserviceconfirm" && $action->id != "serviceparticular" && $action->id != "index" && $action->id != "checkseats" && $action->id != "step2" && $action->id != "step3" && $action->id != "checkuser"){
            $this->redirect(Yii::app()->createUrl('home/login'));
        }else{
            if($action->id != "generatescript" && $action->id != "booking" && $action->id != "bookingconfirm" && $action->id != "checkcoupon" && $action->id != "eventwidget" && $action->id != "eventparticular" && $action->id != "bookingparticular" && $action->id != "bookingservice" && $action->id != "bookingserviceconfirm" && $action->id != "serviceparticular" && $action->id != "index"  && $action->id != "checkseats" && $action->id != "step2" && $action->id != "step3"  && $action->id != "checkuser"){
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
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $events = Events::model()->findAll();

        if(isset(Yii::app()->user->id)){
            $myid = Yii::app()->user->id;
            $user = UserInfo::model()->findAllByPk($myid);
        }

            if(isset($_POST['booking'])){
                $checkintimestamp= strtotime($_POST['booking']['checkindate']);

                $checkindate = date("Y-m-d H:i:s",$checkintimestamp);
                $checkouttimestamp = date(strtotime($checkindate.'+3 days'));
                $checkoutdate = date("Y-m-d H:i:s",$checkouttimestamp);

                if(isset($myid)){
                    $myarray = array();
                    if(isset($_SESSION["myid".$myid])){
                        unset($_SESSION["myid".$myid]);
                    }
                    $myarray['checkindate'] = $checkindate;
                    $myarray['checkoutdate'] = $checkoutdate;
                    $myarray['firstname'] = $_POST['booking']['first_name'];
                    $myarray['lastname'] = $_POST['booking']['last_name'];
                    $myarray['email'] = $_POST['booking']['email'];
                    $myarray['numberofpeople'] = $_POST['booking']['numberofpeople'];

                    $_SESSION["myid".$myid] = $myarray;
                }
                else{
                    if(isset($_SESSION['myguestuser'])){
                        unset($_SESSION['myguestuser']);
                    }
                    $myguestarray = array();
                    $myguestarray['mycheckindate'] = $checkindate;
                    $myguestarray['mycheckoutdate'] = $checkoutdate;
                    $myguestarray['myfirstname'] = $_POST['booking']['first_name'];
                    $myguestarray['mylastname'] = $_POST['booking']['last_name'];
                    $myguestarray['myemail'] = $_POST['booking']['email'];
                    $myguestarray['mynumberofpeople'] = $_POST['booking']['numberofpeople'];

                    $_SESSION["myguestuser"] = $myguestarray;
                }
                /*echo"<pre>";
                print_r($_SESSION);die;*/

                $this->redirect(Yii::app()->createUrl('event/step2'));
            }

        if(isset($user)){
            $this->render('index', array('events' => $events,'user'=>$user[0]));
        }
        else{
            $this->render('index',array('events'=>$events));
        }
    }

    /**
     * This is ajax action to load event in popup
     */
    public function actionViewEvent()
    {

        $events = Events::model()->findByAttributes(['event_id' => $_POST['event_id'] ]);
        $userName1 = UserInfo::model()->findByAttributes(['user_id' => $events->event_host]);
        $events->event_host = $userName1['full_name'];
        if ($events) {
            $users = explode(',', $events->user_id);

            echo json_encode([
                'token' => 1,
                'data' => [
                    'title' => $events->event_title,
                    'host' => $events->event_host,
                    'start' => $events->event_start,
                    'end' => $events->event_end,
                    'description' => $events->event_description,
                    'location' => $events->event_location,
                    'users' => 'All user invited',
                ]
            ]);
        } else {
            echo json_encode([
                'token' => 0,
            ]);
        }

    }


    /**
     * Generates script for event booking.
     */
    public function actionGenerateScript(){
        $this->layout = "login";
        $events = Events::model()->findAll(array('group'=>'event_key'));

        $services = Services::model()->findAll();

        $sql = "SELECT user_id,full_name from user_info";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('script',array('events'=>$events,'services'=>$services,'from'=>"generatescript",'users'=>$result));
    }

    /**
     * This function does event booking.
     */
    public function actionBooking($id){
        $this->layout = "login";
        $sql = "SELECT * from events where event_id = ".$id;
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $event_key = $result[0]['event_key'];
        $events = Events::model()->findAll(array("condition"=>"event_key ="."'$event_key'"));

        $sql1 = "SELECT user_id,full_name from user_info";
        $result1 = Yii::app()->db->createCommand($sql1)->queryAll();

        $sql3 = "SELECT COUNT(*) as booking from booking where event_id = ".$id;
        $result3 = Yii::app()->db->createCommand($sql3)->queryAll();

        $stop = "";
        if($result3[0]['booking'] == $events[0]->total_tickets || $result3[0]['booking'] > $events[0]->total_tickets){
            $stop = "disabled";
        }

        $this->render('script',array(
            'events'=>$events,
            'services'=>'',
            'from'=>'booking',
            'users'=>$result1,
            'stop' => $stop,
        ));
    }

    /**
     * This function books the event
     */
    public function actionBookingConfirm($id){
        /*echo "<pre>";
        print_r($_POST);die;*/

        $this->layout = "login";
        $event = Events::model()->findAll(array("condition"=>"event_id =".$id));

        $url = Yii::app()->createUrl("/admin/events/eventview/")."/".$id;

        $sql1 = "SELECT user_id,full_name from user_info";
        $result1 = Yii::app()->db->createCommand($sql1)->queryAll();

        if(isset($_POST['booking'])){
            $newmodel = new Booking();
            $newmodel->event_id = $id;
            $newmodel->attributes = $_POST['booking'];
            $myuser = $newmodel->username;

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
                else{
                    $myuser = $_POST['booking']['username'];
                }
            }
            $newmodel->status = "pending";
            $newmodel->created_at = date("Y-m-d H:i:s");
            $newmodel->modified_at = date("Y-m-d H:i:s");
            if($newmodel->validate()){
                if($newmodel->save()){
                    $sql2 = "SELECT COUNT(*) as booking from booking where event_id = ".$id;
                    $result2 = Yii::app()->db->createCommand($sql2)->queryAll();

                    if($result2[0]['booking'] == 1){
                        if($event[0]->total_tickets == 1){
                            $firstbooking = "Completed";
                        }
                        else{
                            $firstbooking = "First";
                        }
                    }
                    else if($result2[0]['booking'] == $event[0]->total_tickets){
                        $firstbooking = "Completed";
                    }
                    else {
                        $firstbooking = "";
                    }


                    $title = $firstbooking." Booking of ".$event[0]->event_title;
                    $_SESSION['delete'] = "Your booking is confirmed for this event";
                    $body = "$myuser has booked $title.";
                    $bookerid = $newmodel->booking_id;
                    if($event[0]->is_notification){
                        NotificationHelper::AddNotitication($title,$body,'info',$bookerid,1,$url);
                    }

                    $emailsql = "SELECT value FROM settings where `module_name` = 'emailsettings' and `settings_key` = 'event'";
                    $emailresult = Yii::app()->db->createCommand($emailsql)->queryAll();
                    if(isset($emailresult[0]['value'])){
                        if($emailresult[0]['value'] == "eventmailchecked"){
                            $to = $newmodel->email;
                            $subject = "Booking of ".$event[0]->event_title;
                            $msgsql = "SELECT description from email_content where `module_name` = 'emailcontent' and `key` = 'oneventbooking'";
                            $msgresult = Yii::app()->db->createCommand($msgsql)->queryAll();
                            if(isset($msgresult[0]['description'])){
                                if($msgresult[0]['description'] != ""){
                                    $message = $msgresult[0]['description'];
                                }
                            }
                            else{
                                $message = "You have booked ".$event[0]->event_title." event and event start time is ".date('m/d/Y h:i A',strtotime(str_replace('/','-',$event[0]->event_start)));
                            }

                            $fromsql = "SELECT description from email_content where `module_name` = 'fromemail' and `key` = 'foreventbooking'";
                            $fromresult = Yii::app()->db->createCommand($fromsql)->queryAll();
                            if(isset($fromresult[0]['description'])){
                                if($fromresult[0]['description'] != ""){
                                    $from = $fromresult[0]['description'];
                                }
                            }
                            else{
                                $from = "event@cyclone.com";
                            }
                            Test::Email($to,$subject,$message,$from);
                        }
                    }

                    $this->redirect(Yii::app()->createurl('/event/generatescript'));
                }
            }
            else{
                echo '<pre>';
                print_r($newmodel->getErrors());die;
            }
        }

        $sql3 = "SELECT COUNT(*) as booking from booking where event_id = ".$id;
        $result3 = Yii::app()->db->createCommand($sql3)->queryAll();

        $stop = "";
        if($result3[0]['booking'] == $event[0]->total_tickets || $result3[0]['booking'] > $event[0]->total_tickets){
            $stop = "disabled";
        }

        $this->render('script',array(
            'event'=>$event[0],
            'from'=>'bookingconfirm',
            'users'=>$result1,
            'stop' => $stop,
        ));

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
     * shows widget for booking events
     */
    public function actionEventwidget(){
        $src = Yii::app()->params['siteUrl']."/".Yii::app()->params['applicationName']."/event/generatescript";
        header('Content-Type: application/javascript');
        echo "document.write(\"<iframe height='100%' width='100%' style='border:0px;' src='$src'></iframe>\");";
    }

    /**
     * Shows widget for booking a particular events
     */
    public function actionEventParticular($id){
        $src = Yii::app()->params['siteUrl']."/".Yii::app()->params['applicationName']."/event/bookingparticular/".$id;
        header('Content-Type: application/javascript');
        echo "document.write(\"<iframe height='100%' width='100%' style='border:0px;' src='$src'></iframe>\");";
    }

    /**
     * This function shows event for particular event booking
     */
    public function actionBookingParticular($id){
        $this->layout = "login";

        $events = Events::model()->findAll(array("condition"=>"event_id =".$id));

        $sql1 = "SELECT user_id,full_name from user_info";
        $result1 = Yii::app()->db->createCommand($sql1)->queryAll();

        $sql3 = "SELECT COUNT(*) as booking from booking where event_id = ".$id." AND (status= 'pending' or status = 'approved')";
        $result3 = Yii::app()->db->createCommand($sql3)->queryAll();
        $stop = "";
        if($result3[0]['booking'] == $events[0]->total_tickets || $result3[0]['booking'] > $events[0]->total_tickets){
            $stop = "disabled";
        }

        $this->render('script',array(
            'events'=>$events,
            'from'=>'bookingparticular',
            'users'=>$result1,
            'stop' =>$stop,
        ));
    }

    /**
     * @param $id
     * Displays page for service.
     */
    public function actionBookingservice($id){
        $this->layout = "login";

        $services = Services::model()->findAll(array("condition"=>"service_id="."'$id'"));

        $sql1 = "SELECT user_id,full_name from user_info";
        $result1 = Yii::app()->db->createCommand($sql1)->queryAll();

        $stop = "";

        $sql3 = "SELECT COUNT(*) as booking from booking where service_id = ".$id." AND (status= 'pending' or status = 'approved')";
        $result3 = Yii::app()->db->createCommand($sql3)->queryAll();
        $stop = "";
        if($result3[0]['booking'] == $services[0]->total_booking || $result3[0]['booking'] > $services[0]->total_booking){
            $stop = "disabled";
        }

        $this->render('script',array(
            'events'=>'',
            'services'=>$services,
            'from'=>'bookingservices',
            'users'=>$result1,
            'stop' => $stop,
        ));
    }

    /**
     * @param $id
     * Confirm booking of service.
     */
    public function actionBookingserviceconfirm($id){
        $error = "";
        $url = Yii::app()->createUrl("/admin/services/view/")."/".$id;
        $this->layout = "login";
        $service = Services::model()->findAll(array("condition"=>"service_id="."'$id'"));

        $sql1 = "SELECT user_id,full_name from user_info";
        $result1 = Yii::app()->db->createCommand($sql1)->queryAll();

        $newmodel = new Booking();

        if(isset($_POST['booking'])){
            $newmodel->service_id = $id;
            $newmodel->attributes = $_POST['booking'];
            $myuser = $newmodel->username;

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
                else{
                    $myuser = $_POST['booking']['username'];
                }
            }

            if(isset($_POST['timeslot'])){
                $maindate = $_POST['timeslot'];
                $myday = lcfirst(date("l",strtotime($maindate)));
                $sql = "SELECT * FROM business_openings_hours";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
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
            $newmodel->status = "pending";
            $newmodel->created_at = date("Y-m-d H:i:s");
            $newmodel->modified_at = date("Y-m-d H:i:s");

            if($error == ""){
                if($newmodel->validate()){
                    if($newmodel->save()){
                        $title = $service[0]->service_name;
                        $_SESSION['delete'] = "Your booking is confirmed for this service";
                        $body = "$myuser has booked $title.";
                        $bookerid = $newmodel->booking_id;
                        NotificationHelper::AddNotitication($title,$body,'info',$bookerid,1,$url);
                        $this->redirect(Yii::app()->createurl('/event/generatescript'));
                    }
                }
                else{
                    echo '<pre>';
                    print_r($newmodel->getErrors());die;
                }
            }
        }

        $this->render('script',array(
            'newmodel'=>$newmodel,
            'service'=>$service[0],
            'users'=>$result1,
            'error'=>$error,
            'from'=>'bookingserviceconfirm',
        ));
    }

    /**
     * Shows page for booking a particular service
     */
    public function actionServiceParticular($id){
        $src = Yii::app()->params['siteUrl']."/".Yii::app()->params['applicationName']."/event/bookingservice/".$id;
        header('Content-Type: application/javascript');
        echo "document.write(\"<iframe height='100%' width='100%' style='border:0px;' src='$src'></iframe>\");";
    }

    /**
     * cancels a booking of a booking.
     */
    public function actionCancelbooking($id){
        $sql = "SELECT event_id from booking where booking_id = ".$id;
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $userInfo = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId() ]);
        $eventid = $result[0]['event_id'];

        $event =  Events::model()->findByAttributes(['event_id' => $eventid]);

        $eventstarttime = strtotime($event->event_start);
        $todaytime = strtotime(date("Y-m-d H:i:s"));
//        echo $event->event_start."<br />".date("Y-m-d H:i:s");die;
        $hourdiff = round(($eventstarttime - $todaytime)/3600, 2);

        if($hourdiff > 0){
            if($hourdiff >= $event->hours_before_cancel){

                $walletsql = "UPDATE wallet SET transaction_status = 3 where reference_num = ".$id;
                Yii::app()->db->createCommand($walletsql)->execute();

                $bookingsql = "UPDATE booking set status = 'cancelled' where booking_id = ".$id;
                $bookingresult = Yii::app()->db->createCommand($bookingsql)->execute();
                if($bookingresult){
                    $notisql = "SELECT * FROM settings where module_name = 'notification' AND settings_key = 'eventcancel'";
                    $notiresult = Yii::app()->db->createCommand($notisql)->queryAll();
                    if(isset($notiresult[0]['value'])) {
                        if ($notiresult[0]['value'] == "eventcancelchecked") {
                            $url = Yii::app()->createUrl("/admin/booking/view")."/".$id;
                            NavCheck::Pushnotification($userInfo->first_name." has Cancelled booking of ".$event->event_title,"event",$url);
                            $title = "Cancellation of booking";
                            $myurl = Yii::app()->createUrl("/admin/userInfo/view")."/".$userInfo->user_id;
                            $body = "<a href='$myurl'>$userInfo->first_name</a> has Cancelled booking of  ".$event->event_title;
                            NotificationHelper::AddNotitication($title, $body, 'info', $id, 1, $url);
                        }

                        $emailsql = "SELECT value FROM settings where module_name = 'emailsettings' and settings_key = 'eventcancel'";
                        $emailresult = Yii::app()->db->createCommand($emailsql)->queryAll();
                        if(isset($emailresult[0]['value'])){
                            if($emailresult[0]['value'] == "eventcancelmailchecked"){
                                $to = $userInfo->email;
                                $subject = "Cancellation of booking  of ".$event->event_title;
                                $msgsql = "SELECT description from email_content where `module_name` = 'emailcontent' and `key` = 'oneventcancellation'";
                                $msgresult = Yii::app()->db->createCommand($msgsql)->queryAll();
                                if(isset($msgresult[0]['description'])){
                                    if($msgresult[0]['description'] != ""){
                                        $message = $msgresult[0]['description'];
                                    }
                                }
                                else{
                                    $message = "You have cancelled booking of ".$event->event_title;

                                }

                                $fromsql = "SELECT description from email_content where `module_name` = 'fromemail' and `key` = 'foreventcancellation'";
                                $fromresult = Yii::app()->db->createCommand($fromsql)->queryAll();
                                if(isset($fromresult[0]['description'])){
                                    if($fromresult[0]['description'] != ""){
                                        $from = $fromresult[0]['description'];
                                    }
                                }
                                else{
                                    $from = "event@cyclone.com";

                                }
                                Test::Email($to,$subject,$message,$from);
                            }
                        }


                    }
                    $_SESSION['delete'] = "Your booking is cancelled successfully.";
                }
            }
            else{
                $_SESSION['delete'] = "Booking cancellation time is gone.You can not cancel your booking now.";
            }
        }
        else{
            $_SESSION['delete'] = "This event is already started/done.";
        }

        $this->redirect(Yii::app()->createUrl("/event/bookingcalendarview"));
    }


    /**
     * step2 for booking in cryptotrain(Asks for information)
     */
    public function actionStep2(){
        if(isset(Yii::app()->user->id)){
            $myid = Yii::app()->user->id;
            $user = UserInfo::model()->findAllByPk($myid);
        }
            if(isset($_POST['step2'])){

                if(isset($myid)){
                    $_SESSION["myid".$myid]['firstname'] = $user[0]->first_name;
                    $_SESSION["myid".$myid]['lastname'] = $user[0]->last_name;
                    //$myarray = array();
                    $user[0]->business_name = $_SESSION["myid".$myid]['companyname'] = $_POST['step2']['companyname'];
                    $user[0]->vat_number = $_SESSION["myid".$myid]['vatnumber'] = $_POST['step2']['vatnumber'];
                    $user[0]->street = $_SESSION["myid".$myid]['street'] = $_POST['step2']['street'];
                    //$user[0]->region = $_SESSION["myid".$myid]['region'] = $_POST['step2']['region'];
                    $user[0]->building_num = $_SESSION["myid".$myid]['building_num'] = $_POST['step2']['building_num'];
                    $user[0]->city = $_SESSION["myid".$myid]['city'] = $_POST['step2']['city'];
                    $user[0]->postcode = $_SESSION["myid".$myid]['postcode'] = $_POST['step2']['postcode'];
                    $user[0]->country = $_SESSION["myid".$myid]['country'] = $_POST['step2']['country'];
                    $user[0]->phone = $_SESSION["myid".$myid]['phone'] = $_POST['step2']['phone'];
                    if($user[0]->validate()){
                        if($user[0]->save()){
                            $_SESSION['successtoast'] = "Uw adres is ingevoerd";
                        }
                    }
                }
                else{
                    $newguestarray = array();
                    $newguestarray['mybusiness_name'] = $_POST['step2']['companyname'];
                    $newguestarray['myvat_number'] = $_POST['step2']['vatnumber'];
                    $newguestarray['mystreet'] = $_POST['step2']['street'];
                    $newguestarray['mybuilding_num'] = $_POST['step2']['building_num'];
                    $newguestarray['mycity'] = $_POST['step2']['city'];
                    $newguestarray['mypostcode'] = $_POST['step2']['postcode'];
                    $newguestarray['mycountry'] = $_POST['step2']['country'];
                    $newguestarray['myphone'] = $_POST['step2']['phone'];

                    $_SESSION['myguestuser'] = array_merge($_SESSION['myguestuser'],$newguestarray);
                }
                // $this->redirect(Yii::app()->createUrl('event/step3'));

                //data save and go to the checkout page send

                if(isset(Yii::app()->user->id)){
                    $myid = Yii::app()->user->id;
                    $user = UserInfo::model()->findByPk($myid);
                    $checkindate = $_SESSION["myid".$myid]['checkindate'];
                }
                else{
                    $checkindate = $_SESSION['myguestuser']['mycheckindate'];
                }
                 // print_r($checkindate);
                //$events = Events::model()->findAllByAttributes(["event_start"=>"'".$checkindate."'"]);
                // $events = Yii::app()->db->createCommand("SELECT * from events where event_start = date('2018-08-27')")->queryAll();
                 $events = Yii::app()->db->createCommand()
            ->select('*')
            ->from('events')
            ->where("event_start = date('$checkindate')")
            ->queryAll();

                $_SESSION['myeventid']  = $events[0]['event_id'];
                $_SESSION['event_price'] = $events[0]['price'];

                if(!isset(Yii::app()->user->id)){
                        $model = UserInfo::model()->findByAttributes(array('email'=>$_SESSION['myguestuser']['myemail']));
                        if(empty($model)){
                            $model = new UserInfo();
                        }
                        $model->first_name = $_SESSION['myguestuser']['myfirstname'];
                        $model->last_name = $_SESSION['myguestuser']['mylastname'];
                        $model->full_name = $model->first_name." ".$model->last_name;
                        $model->email = $_SESSION['myguestuser']['myemail'];
                        $model->business_name = $_SESSION['myguestuser']['mybusiness_name'];
                        $model->vat_number = $_SESSION['myguestuser']['myvat_number'];
                        $model->street = $_SESSION['myguestuser']['mystreet'];
                        $model->building_num = $_SESSION['myguestuser']['mybuilding_num'];
                        $model->city = $_SESSION['myguestuser']['mycity'];
                        $model->postcode = $_SESSION['myguestuser']['mypostcode'];
                        $model->country = $_SESSION['myguestuser']['mycountry'];
                        $model->phone = $_SESSION['myguestuser']['myphone'];
                        $model->created_at = date("Y-m-d H:i:s");
                        $model->modified_at = date("Y-m-d H:i:s");
                        if($model->validate()){
                            if($model->save()){
                                $_SESSION['mynewid'] = $model->user_id;
                            }
                        }else{
                            $_SESSION['loginerror'] = "This email is already registered";
                            $this->redirect(Yii::app()->createUrl('home/'));
                            /*echo "<pre>";
                            print_r($model->getErrors());die;*/
                        }
                    }

                            $product = ProductInfo::model()->findByAttributes(['product_id' => 1]);
                            if(isset(Yii::app()->user->id)){
                                $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
                                $checkCart = Cart::model()->findByAttributes(['user_id' => Yii::app()->user->getId(), 'product_id' => 1]);
                            }
                            else{
                                $user = UserInfo::model()->findByAttributes(['user_id' => $model->user_id]);
                                $checkCart = Cart::model()->findByAttributes(['user_id' => $model->user_id, 'product_id' => 1]);
                            }

                            $sql = "SELECT SUM(number_of_people) from booking";
                            $result = Yii::app()->db->createCommand($sql)->queryAll();

                            if ($checkCart){
                                $checkCart->qty = $checkCart->qty+1;
                                if($checkCart->save()){
                                    $cartItem = Cart::model()->findAllByAttributes(['user_id' => $user->user_id]);
                                    $count = count($cartItem);
                                    $_SESSION['successtoast'] = "Het seminarie is toegevoegd aan uw winkelmandje";
                                }
                            }else{
                                $cart = new Cart();
                                $cart->product_id = $product->product_id;
                                $cart->user_id = $user->user_id;
                                $cart->qty = 1;
                                if($result[0]['SUM(number_of_people)'] < 50){
                                    $cart->amount = $product->sale_price;
                                }
                                else{
                                    $cart->amount = $product->price;
                                }
                                $cart->created_at = date('Y-m-d H:i:s');

                                if($cart->save()){
                                    $cartItem = Cart::model()->findAllByAttributes(['user_id' => $user->user_id]);
                                    $count = count($cartItem);
                                    $_SESSION['successtoast'] = "Het seminarie is toegevoegd aan uw winkelmandje";
                                }
                            }

                            $this->redirect(Yii::app()->createUrl("product/checkout"));


            }

        if(isset(Yii::app()->user->id)){
            $this->render('step2',array(
                'user'=>$user[0],
            ));
        }else{
            $this->render('step2');
        }
    }

    /**
     * step3 for booking asking for uploading id
     */
    public function actionStep3(){
        /*echo "<pre>";
        print_r($_SESSION['myguestuser']);die;*/
        if(isset(Yii::app()->user->id)){
            $myid = Yii::app()->user->id;
            $user = UserInfo::model()->findByPk($myid);
            $checkindate = $_SESSION["myid".$myid]['checkindate'];
        }
        else{
            $checkindate = $_SESSION['myguestuser']['mycheckindate'];
        }
        $events = Events::model()->findAll(["condition"=>"event_start = '$checkindate'"]);

        if(isset($_FILES['file-upload'])){
            /*echo "<pre>";
            print_r($_SESSION['myguestuser']);die;*/

            $target_dir = Yii::app()->params['basePath'].'/uploads/idproof/';

            $name = $_FILES["file-upload"]["name"];
            $ext = end((explode(".", $name)));

            if(isset(Yii::app()->user->id)){
                $imagename = $user->first_name."_".$user->last_name."_".$myid.".".$ext;
            }
            else{
                $imagename = $_SESSION['myguestuser']['myfirstname']."_".$_SESSION['myguestuser']['mylastname'].".".$ext;
            }
            $target_file = $target_dir . $imagename;

            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["file-upload"]["tmp_name"]);
            if($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
                if ($_FILES["file-upload"]["size"] > 500000) {
                    $_SESSION['loginerror'] = "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

            } else {
                $_SESSION['loginerror'] = "File is not an image.";
                $uploadOk = 0;
            }

            if($uploadOk == 1){
                if (move_uploaded_file($_FILES["file-upload"]["tmp_name"], $target_file)) {
                    //echo "The file ". basename( $_FILES["file-upload"]["name"]). " has been uploaded.";
                    $_SESSION['successtoast'] = "The file has been uploaded";
                    $_SESSION['myimagepath'] = "/uploads/idproof/".$imagename;
                    $_SESSION['myeventid']  = $events[0]->event_id;
                    $_SESSION['event_price'] = $events[0]->price;

                    if(!isset(Yii::app()->user->id)){
                        $model = UserInfo::model()->findByAttributes(array('email'=>$_SESSION['myguestuser']['myemail']));
                        if(empty($model)){
                            $model = new UserInfo();
                        }
                        $model->first_name = $_SESSION['myguestuser']['myfirstname'];
                        $model->last_name = $_SESSION['myguestuser']['mylastname'];
                        $model->full_name = $model->first_name." ".$model->last_name;
                        $model->email = $_SESSION['myguestuser']['myemail'];
                        $model->business_name = $_SESSION['myguestuser']['mybusiness_name'];
                        $model->vat_number = $_SESSION['myguestuser']['myvat_number'];
                        $model->street = $_SESSION['myguestuser']['mystreet'];
                        $model->building_num = $_SESSION['myguestuser']['mybuilding_num'];
                        $model->city = $_SESSION['myguestuser']['mycity'];
                        $model->postcode = $_SESSION['myguestuser']['mypostcode'];
                        $model->country = $_SESSION['myguestuser']['mycountry'];
                        $model->phone = $_SESSION['myguestuser']['myphone'];
                        $model->created_at = date("Y-m-d H:i:s");

                        if($model->validate()){
                            if($model->save()){
                                $_SESSION['mynewid'] = $model->user_id;
                            }
                        }else{
                            $_SESSION['loginerror'] = "This email is already registered";
                            $this->redirect(Yii::app()->createUrl('home/'));
                            /*echo "<pre>";
                            print_r($model->getErrors());die;*/
                        }
                    }

                            $product = ProductInfo::model()->findByAttributes(['product_id' => 1]);
                            if(isset(Yii::app()->user->id)){
                                $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
                                $checkCart = Cart::model()->findByAttributes(['user_id' => Yii::app()->user->getId(), 'product_id' => 1]);
                            }
                            else{
                                $user = UserInfo::model()->findByAttributes(['user_id' => $model->user_id]);
                                $checkCart = Cart::model()->findByAttributes(['user_id' => $model->user_id, 'product_id' => 1]);
                            }

                            $sql = "SELECT SUM(number_of_people) from booking";
                            $result = Yii::app()->db->createCommand($sql)->queryAll();

                            if ($checkCart){
                                $checkCart->qty = $checkCart->qty+1;
                                if($checkCart->save()){
                                    $cartItem = Cart::model()->findAllByAttributes(['user_id' => $user->user_id]);
                                    $count = count($cartItem);
                                    $_SESSION['successtoast'] = "Het seminarie is toegevoegd aan uw winkelmandje";
                                }
                            }else{
                                $cart = new Cart();
                                $cart->product_id = $product->product_id;
                                $cart->user_id = $user->user_id;
                                $cart->qty = 1;
                                if($result[0]['SUM(number_of_people)'] < 50){
                                    $cart->amount = $product->sale_price;
                                }
                                else{
                                    $cart->amount = $product->price;
                                }
                                $cart->created_at = date('Y-m-d H:i:s');

                                if($cart->save()){
                                    $cartItem = Cart::model()->findAllByAttributes(['user_id' => $user->user_id]);
                                    $count = count($cartItem);
                                    $_SESSION['successtoast'] = "Het seminarie is toegevoegd aan uw winkelmandje";
                                }
                            }

                            $this->redirect(Yii::app()->createUrl("product/checkout"));

                } else {
                    $_SESSION['loginerror'] = "Sorry, there was an error uploading your file.";
                }
            }

        }

        $this->render('uploadid');
    }

    /**
     * Checks availability for the seats of events
     */
    public function actionCheckseats(){
        if(isset($_POST['booking'])){
            $token = 1;
            $msg = "";
            $checkintimestamp= strtotime($_POST['booking']['checkindate']);
            $checkindate = date("Y-m-d H:i:s",$checkintimestamp);

            $event = Events::model()->findAll(["condition"=>"event_start = '$checkindate'"]);
            
            if(empty($event)){
                $token = 0;
                $msg = "This event doesn't exist! Please select different date";
                $seats = "No";
            }
            else{
                $eventid = $event[0]->event_id;
                $bookings = Booking::model()->findAll(["condition"=>"event_id = '$eventid'"]);
                if(empty($bookings)){
                    $seats = 7;
                }
                else{
                    $count = count($bookings);
                    $seats = 7-$count;

                    if($seats < 0  || $seats == 0){
                        $token = 0;
                        $msg = "All seats are booked";
                        $seats = "No";
                    }
                }
            }

            echo json_encode([
                'token' => $token,
                'seats' => $seats,
                'msg' => $msg,
            ]);
        }
    }

    /**
     * Checks if user exists or not
     */
    public function actionCheckuser(){
        $msg = "";
        $token = 1;

        if(isset($_POST['email'])){
            $email = $_POST['email'];
            $model = UserInfo::model()->findAll(["condition"=>"email= '$email'"]);
            if(empty($model)){

            }else{
                $token = 0;
                $msg = "Deze gebruiker bestaat reeds.";
            }
        }

        echo json_encode([
            'token' => $token,
            'msg' => $msg,
        ]);
    }

}