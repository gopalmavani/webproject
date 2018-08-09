<?php
require __DIR__  . '/../../vendor/autoload.php';
class OrderController extends Controller
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
        if (Yii::app()->user->isGuest && $action->id != "PlaceOrder" && $action->id != "thankyou" && $action->id != "success" && $action->id != "paypalintegration" && $action->id != "paypalpayment"){
            $this->redirect(Yii::app()->createUrl('home/login'));
        }else{
            if($action->id != "PlaceOrder"  && $action->id != "thankyou" && $action->id != "success" && $action->id != "paypalintegration" && $action->id != "paypalpayment"){
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
                'actions'=>array('index','view'),
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
    public function actionIndex(){

        $id = Yii::app()->user->id;
        if($id == 1)
        {
            $orders = OrderInfo::model()->findAll();
        }
        else{
            $Criteria = new CDbCriteria();
            $Criteria->condition = "user_id = $id";
            $orders = OrderInfo::model()->findAll($Criteria);
        }

        $this->render('index',
            array(
                'orders' => $orders
            ));

    }

    /* invoice list in front end */
    public function actionInvoice(){
        $this->layout = "main";
        $id = Yii::app()->user->id;
        $Criteria = new CDbCriteria();
        $Criteria->order = "order_info_id DESC";
        if($id == 1)
        {
            $orders = OrderInfo::model()->findAll($Criteria);
        }
        else{
            $Criteria->condition = "user_id = $id";
            $orders = OrderInfo::model()->findAll($Criteria);
        }
        $this->render('invoice',
            array(
                'orders' => $orders
            ));

    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionSuccess()
    {
        echo "<pre>";
        print_r($_GET);DIE;
        if(isset($_GET['orderID'])){
            $model = OrderInfo::model()->findByAttributes(['order_id' => $_GET['orderID'] ]);

            $payment = OrderPayment::model()->findByAttributes(['order_info_id' => $model->order_info_id]);

            $userInfo = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId() ]);
            $orderInfo = $model->attributes;
            $orderInfo['order_status'] = 1;

            $orderInfoStatus = OrderHelper::CreateOrderInfo($orderInfo);

            $orderPayment =  $payment->attributes;
            $orderPayment['order_info_id'] =  $orderInfoStatus;
            $orderPayment['created_at'] = date('Y-m-d H:i:s');
            $orderPayment['payment_date'] = date('Y-m-d H:i:s');
            $orderPayment['total'] = $model->netTotal;
            $orderPayment['payment_mode'] = 0;
            $orderPayment['transaction_mode'] = $_GET['PM'];
            $orderPayment['payment_status'] = 1;
            $orderPayment['payment_ref_id'] = $_GET['PAYID'];
            /*echo "<pre>";
            print_r($orderPayment); die;*/
            $orderPaymentStatus = OrderHelper::CreateOrderPayment($orderPayment);


            if ($orderInfoStatus){
                $userInfo->modified_at = date('Y-m-d H:i:s');
                $userInfo->save();

                $orderItem = OrderLineItem::model()->findAllByAttributes(['order_info_id' => $model->order_info_id]);
                foreach ($orderItem as $item) {

                    $proDetail = ProductInfo::model()->findByAttributes(['name' => $item->product_name]);

//                    OrderHelper::AddDirectSalesBonus($userInfo->user_id, $proDetail->direct_sales);

//                    $AddAffiliate = OrderHelper::AddAffiliateData($proDetail->product_id, $item->item_qty, $model->user_id, 0);

                    Yii::app()->db->createCommand("DELETE FROM cart WHERE `user_id` = " .Yii::app()->user->getId() )->execute();

                    $notisql = "SELECT * FROM settings where module_name = 'notification' AND settings_key = 'productorder'";
                    $notiresult = Yii::app()->db->createCommand($notisql)->queryAll();
                    if(isset($notiresult[0]['value'])) {
                        if ($notiresult[0]['value'] == "productorderchecked") {
                            $url = Yii::app()->createUrl("/admin/productInfo/view")."/".$proDetail->product_id;
                            $title = "Order of ".$proDetail->name;
                            $myurl = Yii::app()->createUrl("/admin/userInfo/view")."/".$userInfo->user_id;
                            $body = "<a href='$myurl'>$userInfo->first_name</a> has order ".$proDetail->name;
                            NotificationHelper::AddNotitication($title, $body, 'info', $model->order_id, 1, $url);
                        }
                    }


                    $emailsql = "SELECT value FROM settings where module_name = 'emailsettings' and settings_key = 'productorder'";
                    $emailresult = Yii::app()->db->createCommand($emailsql)->queryAll();
                    if(isset($emailresult[0]['value'])){
                        if($emailresult[0]['value'] == "productordermailchecked"){
                            $to = $userInfo->email;
                            $subject = "Order Placed for ".$proDetail->name;
                            $msgsql = "SELECT description from email_content where `module_name` = 'emailcontent' and `key` = 'onproductorder'";
                            $msgresult = Yii::app()->db->createCommand($msgsql)->queryAll();
                            if(isset($msgresult[0]['description'])){
                                if($msgresult[0]['description'] != ""){
                                    $message = $msgresult[0]['description'];
                                }
                            }
                            else{
                                $message = "You have placed an order for ".$proDetail->name;

                            }

                            $fromsql = "SELECT description from email_content where `module_name` = 'fromemail' and `key` = 'forproductorder'";
                            $fromresult = Yii::app()->db->createCommand($fromsql)->queryAll();
                            if(isset($fromresult[0]['description'])){
                                if($fromresult[0]['description'] != ""){
                                    $from = $fromresult[0]['description'];
                                }
                            }
                            else{
                                $from = "orders@cyclone.com";

                            }
                            Test::Email($to,$subject,$message,$from);
                        }
                    }
                }

                $this->render('success');
            }
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionAddOrder()
    {
        /*echo"<pre>";
        print_r($_POST);die;*/

        $proDetail = ProductInfo::model()->findByAttributes(['sku' => $_POST['sku']]);
        $userDetail = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
        $orderInfo['user_name'] = $userDetail['full_name'];
        $orderInfo['email'] = $userDetail['email'];
        $orderInfo['vat'] = $_POST['vat-Amount'];
        $orderInfo['invoice_number'] = $_POST['order_id'];
        $orderInfo['order_id'] = $_POST['order_id'];
        $orderInfo['order_status'] = 0;
        $orderInfo['user_id'] = $userDetail->user_id;
        $orderInfo['building'] = $userDetail->building_num;
        $orderInfo['street'] = $userDetail->street;
        $orderInfo['city'] = $userDetail->city;
        $orderInfo['country'] = $userDetail->country;
        $orderInfo['region'] = $userDetail->region;
        $orderInfo['postcode'] = $userDetail->postcode;
        $orderInfo['orderTotal'] = $_POST['amount'];
        $orderInfo['discount'] = 0;
        $orderInfo['netTotal'] = $_POST['amount'];
        $orderInfo['invoice_date'] = date('Y-m-d H:i:s');
        $orderInfo['company'] = $userDetail->business_name;
        $orderInfo['created_date']= date('Y-m-d H:i:s');

//        print_r($orderInfo); die;
        $orderInfoStatus = OrderHelper::CreateOrderInfo($orderInfo);

        if ($orderInfoStatus){
            $orderItem['order_info_id'] = $orderInfoStatus;
            $orderItem['product_name'] = $proDetail->name;
            $orderItem['product_id'] = $proDetail->product_id;
            $orderItem['product_sku'] = $proDetail->sku;
            $orderItem['item_qty'] = 1;
            $orderItem['item_disc'] = $proDetail->description;
            $orderItem['item_price'] = $_POST['amount'];
            $orderItem['created_at'] = date('Y-m-d H:i:s');

            if (OrderHelper::CreateOrderItem($orderItem)) {
                $orderPayment['order_info_id'] = $orderInfoStatus;
                $orderPayment['created_at'] = date('Y-m-d H:i:s');
                $orderPayment['payment_date'] = date('Y-m-d H:i:s');
                $orderPayment['total'] = $_POST['amount'];
                $orderPayment['payment_mode'] = 1;
                $orderPayment['transaction_mode'] = 'n/a';
                $orderPayment['payment_status'] = 0;
                $orderPayment['payment_ref_id'] = 'n/a';
                if (OrderHelper::CreateOrderPayment($orderPayment)) {
                    Yii::app()->db->createCommand("DELETE FROM cart WHERE `product_id` = ".$proDetail->product_id." AND `user_id` = " .Yii::app()->user->getId() )->execute();
                    echo json_encode([
                        'token' => 1,
                    ]);
                }else{
                    echo json_encode([
                        'token' => 2,
                    ]);
                }

                $sql = "SELECT value from settings where module_name = 'order_email' and settings_key = 'email_permission' ";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($result)){
                    $value = $result[0]['value'];
                    if($value == 'enabled'){
                        $to = $userDetail['email'];
                        $subject = "Order Confirmation";
                        $total = $orderInfo['vat']+$orderInfo['orderTotal'];
                        $mPDF = Yii::app()->ePdf->mpdf('utf-8', 'A4', 9, 'dejavusans');
                        $html = file_get_contents(Yii::app()->createAbsoluteUrl('invoice/view/'.$orderInfoStatus));
                        $mPDF->writeHTML($html);
                        $content = $mPDF->Output('invoice.pdf', 'S');
                        $message = "<html>
<body>
<table width='100%' cellpadding='0' cellspacing='0'>
    <tbody>
    <tr>
        <td style='padding:25px;text-align:center; background-color:#f2f4f6'>
        <a style='font-family:Arial,Helvetica,Helvetica Neue,sans-serif;font-size:16px;font-weight:bold;color:#2f3133;text-decoration:none' href='http://cyc-01-dev.corecyclone.com'>
        <img src='http://cyclone.abptechnologies.com/admin/plugins/images/CYL-Logo.png' alt='Cyclone' width='16%'>
        </a><br><br>
            <span style='font-family:Arial,Helvetica,Helvetica Neue,sans-serif;font-size:16px;font-weight:bold;color:#2f3133;text-decoration:none'>Order Confirmation</span>
        </td>
    </tr>

    <tr>
        <td style='font-family:Arial,Helvetica,Helvetica Neue,sans-serif;font-size:16px;font-weight:normal;color:#2f3133;text-decoration:none;padding:10px;'>
            <hr>
            Thank you for placing your order with cyclone. This email is to confirm your order has been placed successfully, and will be processed & shipped to you soon.<br />
            Your order id is $_POST[order_id].
        </td>
    </tr>
    </tbody>
</table>
<table width='100%' border='1px solid #ccc' style='border-collapse:collapse;margin-top:20px;margin-bottom:20px;padding:15px;font-family:Arial,Helvetica,Helvetica Neue,sans-serif;font-size:16px;color:#2f3133;text-decoration:none'>
    <thead style='font-weight:bold;background-color:#ccc'>
    <td style='padding:10px'>Product Name</td>
    <td style='padding:10px'>Product Details</td>
    <td style='text-align: center;padding:10px'>Quantity</td>
    <td style='text-align: right;padding:10px'>Price</td>
    </thead>
    <tr>
        <td style='padding-left:5px'>$proDetail->name</td>
        <td style='padding-left:5px'>$proDetail->description</td>
        <td style='text-align: center;padding-left:5px'>1</td>
        <td style='text-align: right;padding-left:5px'>$_POST[amount]</td>
    </tr>
</table>

<table align='right' style='margin-top:20px;margin-bottom:20px;padding:15px;border:1px;font-family:Arial,Helvetica,Helvetica Neue,sans-serif;font-size:18px;color:#2f3133;text-decoration:none'>
    <tr>
        <td style='font-weight:bold'>Vat :</td>
        <td style='font-size:15px;'>&euro; $orderInfo[vat]</td>
    </tr>
    <tr>
        <td style='font-weight:bold'>Total :</td>
        <td style='font-weight:bold'>&euro; $total</td>
    </tr>
</table>

<table style='background-color:#f2f4f6;width:100%;margin:0 auto;padding:0;text-align:center'>
    <tbody>
    <tr>
        <td style='font-family:Arial,Helvetica Neue,Helvetica,sans-serif;color:#aeaeae;padding:35px;text-align:center'>
            <p style='margin-top:0;color:#44484e;font-size:12px;line-height:1.5em'>
                © 2018
                <a style='color:#3869d4' href='http://cyc-01-dev.corecyclone.com'>Cyclone</a>.
                All rights reserved.
            </p>
            <p style='margin-top:0;color:#44484e;font-size:12px;line-height:1.5em'>
                If you don't want to receive any emails,
                <a style='color:#3869d4' href='javascript:void(0);'>unsubscribe here</a>.
            </p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>";
                        $url_array = explode("/",$_SERVER['REQUEST_URI']);
                        $from = "support@".$url_array[1];
                        Test::EmailAttachment($to,$subject,$message,$from,$content);
                    }
                }

            }
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionDecline()
    {
        $this->render('decline');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionCancel()
    {
        if(isset($_GET['orderID'])){
            $model = OrderInfo::model()->findByAttributes(['order_id' => $_GET['orderID'] ]);

            $userInfo = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId() ]);
            $orderInfo = $model->attributes;
            $orderInfo['order_status'] = 0;

            $orderInfoStatus = OrderHelper::CreateOrderInfo($orderInfo);

            $orderPayment['order_info_id'] =  $orderInfoStatus;
            $orderPayment['created_at'] = date('Y-m-d H:i:s');
            $orderPayment['payment_date'] = date('Y-m-d H:i:s');
            $orderPayment['total'] = $model->netTotal;
            $orderPayment['payment_mode'] = 0;
            $orderPayment['transaction_mode'] = $_GET['PM'];
            $orderPayment['payment_status'] = 1;
            $orderPayment['payment_ref_id'] = $_GET['PAYID'];

            $orderPaymentStatus = OrderHelper::CreateOrderPayment($orderPayment);


            if ($orderInfoStatus){
                $userInfo->modified_at = date('Y-m-d H:i:s');
                $userInfo->save();

                $orderItem = OrderLineItem::model()->findAllByAttributes(['order_info_id' => $model->order_info_id]);
                foreach ($orderItem as $item) {


//                    $proDetail = ProductInfo::model()->findByAttributes(['product_id' => $item->product_id]);

                    //OrderHelper::AddDirectSalesBonus($userInfo->user_id, $proDetail->direct_sales);
                    //$AddAffiliate = OrderHelper::AddAffiliateData($proDetail->product_id, $item->item_qty, $model->user_id);
                    //Yii::app()->db->createCommand("DELETE FROM cart WHERE `user_id` = " .Yii::app()->user->getId() )->execute();

                }
                $this->render('cancel');
            }
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionException()
    {
        $this->render('exception');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionDetail($id)
    {
        $order = OrderInfo::model()->findByAttributes(['order_id' => $id]);
//        $orderStatus = CylFieldValues::model()->findByAttributes(['field_id' => 155, 'predefined_value' => $order->order_status]);
        $orderLineItem = OrderLineItem::model()->findAllByAttributes(['order_info_id' => $order->order_info_id]);

        $orderPayment = OrderPayment::model()->findByAttributes(['order_info_id' => $order->order_info_id]);
        $orderStatus = CylFieldValues::model()->findByAttributes(['field_id' => 101, 'predefined_value' => $order->order_status]);


        $this->render('detail',
            array(
                'order' => $order,
                'orderStatus' => $orderStatus,
                'orderlineitem' => $orderLineItem,
                'orderpayment' => $orderPayment,
                'status' => $orderStatus,
            ));
    }

    public function actionPlaceOrder()
    {

        if(isset(Yii::app()->user->id)){
            $myid = Yii::app()->user->id;
        }
        else{
            $myid = $_SESSION['mynewid'];
        }

        $userDetail = UserInfo::model()->findByAttributes(['user_id' => $myid]);

        $bookingmodel = new Booking();
        // $bookingmodel->id_file_1 = $_SESSION['myimagepath'];
        $bookingmodel->id_file_1 = "";
        $bookingmodel->event_id = $_SESSION['myeventid'];
        $bookingmodel->username = $userDetail->full_name;
        $bookingmodel->email = $userDetail->email;
        $bookingmodel->mobile_number = $userDetail->phone;
        $bookingmodel->building = $userDetail->building_num;
        $bookingmodel->street = $userDetail->street;
        $bookingmodel->city = $userDetail->city;
        $bookingmodel->region = "";
        $bookingmodel->country = $userDetail->country;
        $bookingmodel->postcode = $userDetail->postcode;
        $bookingmodel->user_id = $userDetail->user_id;
        $bookingmodel->company_name = $userDetail->business_name;
        $bookingmodel->vat_number = $userDetail->vat_number;
        if(isset(Yii::app()->user->id)){
            $bookingmodel->checkindate = $_SESSION["myid".$myid]['checkindate'];
            $bookingmodel->checkoutdate = $_SESSION["myid".$myid]['checkoutdate'];
            $bookingmodel->number_of_people = $_SESSION["myid".$myid]['numberofpeople'];
        }
        else{
            $bookingmodel->checkindate = $_SESSION['myguestuser']['mycheckindate'];
            $bookingmodel->checkoutdate = $_SESSION['myguestuser']['mycheckoutdate'];
            $bookingmodel->number_of_people = $_SESSION['myguestuser']['mynumberofpeople'];
        }

        $bookingmodel->product_id = 1;
        $bookingmodel->status = "pending";
        $bookingmodel->price = $_SESSION['event_price'];
        $bookingmodel->created_at = date("Y-m-d H:i:s");
        $bookingmodel->modified_at = date("Y-m-d H:i:s");

        if($bookingmodel->validate()) {
            if ($bookingmodel->save()) {
                $_SESSION["booking".$myid] = $bookingmodel->booking_id;
            }
        }
        else{
            echo "<pre>";
            print_r($bookingmodel->getErrors());die;
        }
        $carts = Cart::model()->findAllByAttributes(['user_id'=> $myid ]);
        /*echo "<Pre>";
        print_r($_POST); die;*/
        $orderInfo['user_name'] = $userDetail->full_name;
        $orderInfo['email'] = $userDetail->email;
        $orderInfo['vat'] = $_POST['vat-Amount'];
        $orderInfo['order_id'] = $_POST['order_id'];
        $orderInfo['order_status'] = 2;
        if($orderInfo['order_status'] == 1){
            $orderInfo['invoice_number'] = $_POST['order_id'];
        }
        $orderInfo['user_id'] = $userDetail->user_id;
        $orderInfo['vat_number'] = $userDetail->vat_number;
        $orderInfo['building'] = $userDetail->building_num;
        $orderInfo['street'] = $userDetail->street;
        $orderInfo['city'] = $userDetail->city;
        $orderInfo['country'] = $userDetail->country;
        $orderInfo['region'] = "";
        $orderInfo['postcode'] = $userDetail->postcode;
        $orderInfo['orderTotal'] = $_POST['amount'];
        $orderInfo['discount'] = 0;
        $orderInfo['netTotal'] = $_POST['amount'];
        $orderInfo['invoice_date'] = date('Y-m-d H:i:s');
        $orderInfo['company'] = $userDetail->business_name;
        $orderInfo['created_date']= date('Y-m-d H:i:s');
        $orderInfo['shipping_id'] = 1;
        $orderInfo['shipping_method_name'] = "Standard delivery";
        $orderInfo['shipping_cost'] = 5;
        $orderInfo['shipment_tracking_number'] = 123554;
        if(isset(Yii::app()->user->id)){
            $orderInfo['order_comment'] = $_SESSION["myid".$myid]['checkindate']."_".$_SESSION["myid".$myid]['checkoutdate'];
        }
        else{
            $orderInfo['order_comment'] = $_SESSION["myguestuser"]['mycheckindate']."_".$_SESSION["myguestuser"]['mycheckoutdate'];
        }

        /*echo "<pre>";
        print_r($orderInfo); die;*/
        $orderInfoStatus = OrderHelper::CreateOrderInfo($orderInfo);
        if ($orderInfoStatus){
            $string="";
            foreach ( $carts as $cart){
                $proDetail = ProductInfo::model()->findByAttributes(['product_id' => $cart->product_id]);
                $orderItem['order_info_id'] = $orderInfoStatus;
                $orderItem['product_name'] = $proDetail->name;
                $orderItem['product_id'] = $proDetail->product_id;
                $orderItem['product_sku'] = $proDetail->sku;
                $orderItem['item_qty'] = $cart->qty;
                $orderItem['item_disc'] = $proDetail->description;
                $orderItem['item_price'] = $proDetail->price;
                $orderItem['created_at'] = date('Y-m-d H:i:s');
                $token[] = OrderHelper::CreateOrderItem($orderItem);
                $string .= "<tr><td style='padding-left:5px'>$proDetail->name</td>
            <td style='padding-left:5px'>$proDetail->description</td>
            <td style='text-align: center;padding-left:5px'>$cart->qty</td>
            <td style='text-align: right;padding-left:5px'>$proDetail->price</td></tr>";
            }
        }

        foreach ($token as $val){
            if ($val){
                $return[] = 'true';
            }else{
                $return[] = 'false';
            }
        }

        if (!in_array('false', $return)){
            $orderPayment['order_info_id'] =  $orderInfoStatus;
            $orderPayment['created_at'] = date('Y-m-d H:i:s');
            $orderPayment['payment_date'] = date('Y-m-d H:i:s');
            $orderPayment['total'] = $_POST['amount'];
            $orderPayment['payment_mode'] = 1;
            $orderPayment['transaction_mode'] = 'n/a';
            $orderPayment['payment_status'] = 2;
            $orderPayment['payment_ref_id'] = 'n/a';
            $orderPayment['payment_method_ref_id'] = 1;
            if (OrderHelper::CreateOrderPayment($orderPayment)){
                $bookingid = $_SESSION["booking".$myid];
                $booking = Booking::model()->findAll(["condition"=>"booking_id='$bookingid'"]);
                $booking[0]->orderid = $_POST['order_id'];
                if($booking[0]->validate()){
                    $booking[0]->save();
                }
                echo json_encode([
                    'token' => 1,
                ]);
            }else{
                echo json_encode([
                    'token' => 2,
                ]);
            }

        }

        $sql = "DELETE FROM cart where user_id = ".$myid;
        Yii::app()->db->createCommand($sql)->execute();

        $_SESSION['successtoast'] = "Uw reservatie is geslaagd";

        $_SESSION["orderid".$myid] = $_POST['order_id'];



        $emailsql = "SELECT value FROM settings where module_name = 'emailsettings' and settings_key = 'productorder'";
        $emailresult = Yii::app()->db->createCommand($emailsql)->queryAll();
        if(isset($emailresult[0]['value'])){
            if($emailresult[0]['value'] == "productordermailchecked"){
                $model = UserInfo::model()->findByPk($myid);
                $to = $model->email;
                $subject = "Registration of ".$model->first_name;
                $msgsql = "SELECT description from email_content where `module_name` = 'emailcontent' and `key` = 'onproductorder'";
                $msgresult = Yii::app()->db->createCommand($msgsql)->queryAll();
                if(isset($msgresult[0]['description'])){
                    if($msgresult[0]['description'] != ""){
                        $message = $msgresult[0]['description'];
                    }
                }
                else{
                    $message = "U bent geregistreerd bij Cryptotrain.";

                }

                $fromsql = "SELECT description from email_content where `module_name` = 'fromemail' and `key` = 'forproductorder'";
                $fromresult = Yii::app()->db->createCommand($fromsql)->queryAll();
                if(isset($fromresult[0]['description'])){
                    if($fromresult[0]['description'] != ""){
                        $from = $fromresult[0]['description'];
                    }
                }
                else{
                    $from = "orders@cryptotrain.com";

                }
                Test::Email($to,$subject,$message,$from);
            }
        }
    }

    /**
     * Opens thank you page after order
     */
    public function actionThankyou(){
        if(isset($_SESSION['myguestuser'])){
            unset($_SESSION['myguestuser']);
        }
        $this->render('thankyou');
    }

    /**
     * paypal integration
     */
    public function actionPaypalintegration($id){
        $order = OrderInfo::model()->findByAttributes(["order_id" => $id]);

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'Abj7NKJ2PR2X0cY3UsgFP7tgHARR10QC1p3DcBGAxvid4eNf7wyCZmaJ1pZg2wBZOT9d_Ry9l4cVPWel',     // ClientID
                'EFSXzDsvYhuQE_mrocXQDIpIk-a_j9jmJ9n1zUb5Yk5wMiwfxuajJ9Tja9jPpvAwQHIJF6hApJq4UYQ1'      // ClientSecret
            )
        );

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($order->orderTotal);
        $amount->setCurrency('EUR');

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $baseUrl = Yii::app()->getBaseUrl(true);
        $return = $baseUrl . "/order/paypalpayment";
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl($return)
            ->setCancelUrl("https://example.com/your_cancel_url.html");

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);



        try {
            $payment->create($apiContext);
            echo "<pre>";
            print_r($payment);

            echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
            $this->redirect($payment->getApprovalLink());




        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            echo $ex->getData();
        }

    }

    /**
     * paypal payment execution
     */
    public function actionPaypalpayment(){
        $url = "https://api.sandbox.paypal.com/v1/payments/payment/".$_GET['paymentId']."/execute";

        $myarray = array();
        $myarray['payer_id'] = $_GET["PayerID"];

        $data = json_encode($myarray);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer A21AAHt0rjhpeZPDns0IR37YMX0KDxPNsfqdeWl_WWcAMg5QG2DxCqQ2ritlggs25joYMZmrU-ga2mgZEWJhXSuRFkgVherfg",
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "Postman-Token: 129e8234-eda5-49fc-a26c-a3a99aeed0db"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo "<pre>";
            print_r($response);die;
            $myresponse = json_decode();
            if($myresponse->state == "approved"){
                echo "<pre>";
                print_r($myresponse);die;
            }
        }
    }
}