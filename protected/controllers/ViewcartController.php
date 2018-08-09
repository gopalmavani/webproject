<?php

class ViewcartController extends Controller
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
        if (Yii::app()->user->isGuest){
            $this->redirect(Yii::app()->createUrl('home/login'));
        }else{
            $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->id]);
            if ($action->id != 'login'){
                if (Yii::app()->session['userid'] != $user->password){
                    $this->redirect(Yii::app()->createUrl('home/login'));
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
    public function actionIndex()
    {
        $cartItem = $sum =  [];
        $product = ProductInfo::model()->findAll();
        $userCheck = OrderInfo::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId()]);
        $cart = Cart::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId()]);
        foreach ($cart as $carts){
            $cartProduct = ProductInfo::model()->findByAttributes(['product_id' => $carts->product_id]);
            $sum[] = $carts->qty*$cartProduct->price;
            $cartItem[] = [
                'product_id' => $cartProduct->product_id,
                'product_name' => $cartProduct->name,
                'product_price' => $cartProduct->price,
                'product_image' => $cartProduct->image,
                'product_summary' => $cartProduct->short_description,
                'product_qty' => $carts->qty
            ];
        }


        $this->render('index',
            array(
                'products' => $product,
                'count' => count($cart),
                'cartItem' => $cartItem,
                'cartTotal' => array_sum($sum),
                'userCheck' => $userCheck
            ));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionSuccess()
    {
        if(isset($_GET['orderID'])){
            $userInfo = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId() ]);
            $model = OrderInfo::model()->findByAttributes(['order_id' => $_GET['orderID'] ]);
            $orderLineItem = OrderLineItem::model()->findByAttributes(['order_info_id' => $model->order_info_id]);
            $proDetail = ProductInfo::model()->findByAttributes(['product_id' => $orderLineItem->product_id]);
            $orderInfo = $model->attributes;
            $orderInfo['order_status'] = 1;

            $orderInfoStatus = OrderHelper::CreateOrderInfo($orderInfo);

            $orderPayment['created_at'] = date('Y-m-d H:i:s');
            $orderPayment['payment_date'] = date('Y-m-d H:i:s');
            $orderPayment['total'] = $model->netTotal;
            $orderPayment['payment_mode'] = 0;
            $orderPayment['transaction_mode'] = $_GET['PM'];
            $orderPayment['payment_status'] = 1;
            $orderPayment['payment_ref_id'] = $_GET['PAYID'];

            $userInfo->current_package = $proDetail->product_id;

            OrderHelper::AddDirectSalesBonus($userInfo->user_id, $proDetail->direct_sales);

            if ($orderInfoStatus){
                $userInfo->modified_at = date('Y-m-d H:i:s');
                $userInfo->save();

                $orderPayment['order_info_id'] =  $orderInfoStatus;
                $orderPaymentStatus = OrderHelper::CreateOrderPayment($orderPayment);

                $orderItem['order_info_id'] = $orderInfoStatus;
                $orderItem['product_id'] = $proDetail->product_id;
                $orderItem['item_qty'] = 1;
                $orderItem['item_disc'] = $proDetail->description;
                $orderItem['item_price'] = $_GET['amount'];
                $orderItem['created_at'] = date('Y-m-d H:i:s');

                $orderItemStatus = OrderHelper::CreateOrderItem($orderItem);

                $AddAffiliate = OrderHelper::AddAffiliateData($proDetail->product_id,$orderItem['item_qty'],$model->user_id);


                $buyProduct = $proDetail->product_id;

                $time = date('Y-m-d H:i:s');

                $user = Yii::app()->user->getId();

                if($buyProduct == 1 ){
                    $CheckUserInPool = PoolUserMapping::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId()]);
                    if($CheckUserInPool){
                        foreach ($CheckUserInPool as $poolUser){
                            Yii::app()->db->createCommand("DELETE FROM pool_user_mapping WHERE `id` = $poolUser->id")->execute();
                        }
                    }
                    Yii::app()->db->createCommand("INSERT INTO pool_user_mapping (`pool_id`, `user_id`,`created_at`) VALUES ('1', '$user', '$time')")->execute();
                }

                if($buyProduct == 2){
                    $CheckUserInPool = PoolUserMapping::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId()]);
                    if($CheckUserInPool){
                        foreach ($CheckUserInPool as $poolUser){
                            Yii::app()->db->createCommand("DELETE FROM pool_user_mapping WHERE `id` = $poolUser->id")->execute();
                        }
                    }
                    Yii::app()->db->createCommand("INSERT INTO pool_user_mapping (`pool_id`, `user_id`,`created_at`) VALUES ('1','$user', '$time')")->execute();
                    Yii::app()->db->createCommand("INSERT INTO pool_user_mapping (`pool_id`, `user_id`,`created_at`) VALUES ('2','$user', '$time')")->execute();
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

        $proDetail = ProductInfo::model()->findByAttributes(['sku' => $_POST['sku']]);
        $userDetail = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);

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
        //print_r($orderInfo); die;
        $orderInfoStatus = OrderHelper::CreateOrderInfo($orderInfo);
        if ($orderInfoStatus){
            $orderItem['order_info_id'] = $orderInfoStatus;
            $orderItem['product_id'] = $proDetail->product_id;
            $orderItem['item_qty'] = 1;
            $orderItem['item_disc'] = $proDetail->description;
            $orderItem['item_price'] = $_POST['amount'];
            $orderItem['created_at'] = date('Y-m-d H:i:s');

            if (OrderHelper::CreateOrderItem($orderItem)){
               echo json_encode([
                    'token' => 1,
                ]);
            }else{
                echo json_encode([
                    'token' => 2,
                ]);
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
        $this->render('cancel');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionException()
    {
        $this->render('exception');
    }

    public function actionDetail($id)
    {
        $product = ProductInfo::model()->findByAttributes(['product_id' => $id]);

        $this->render('detail',
            array(
                'product' => $product
            ));
    }

    public function actionAddToCart()
    {
        $product = ProductInfo::model()->findByAttributes(['product_id' => $_POST['id']]);
        $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
        $checkCart = Cart::model()->findByAttributes(['user_id' => Yii::app()->user->getId(), 'product_id' => $_POST['id']]);
        if ($checkCart){
            $checkCart->qty = $checkCart->qty+1;
            if($checkCart->save()){
                $cartItem = Cart::model()->findAllByAttributes(['user_id' => $user->user_id]);
                $count = count($cartItem);
                echo json_encode([
                    'token' => 1,
                    'cartCount' => $count,
                    'msg' => $product->name." added to cart"
                ]);
            }else{
                echo json_encode([
                    'token' => 0
                ]);
            }
        }else{
            $cart = new Cart();
            $cart->product_id = $product->product_id;
            $cart->user_id = $user->user_id;
            $cart->qty = 1;
            $cart->amount = $product->price;
            $cart->created_at = date('Y-m-d H:i:s');

            if($cart->save()){
                $cartItem = Cart::model()->findAllByAttributes(['user_id' => $user->user_id]);
                $count = count($cartItem);
                echo json_encode([
                    'token' => 1,
                    'cartCount' => $count,
                    'msg' => $product->name." added to cart"
                ]);
            }else{
                echo json_encode([
                    'token' => 0
                ]);
            }
        }


    }

    public function actionRemoveFromCart()
    {
        $sum = [];
        Yii::app()->db->createCommand("DELETE FROM cart WHERE `product_id` = ".$_POST['id']." AND `user_id` = " .Yii::app()->user->getId() )->execute();

        $cart = Cart::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId()]);
        foreach ($cart as $carts){
            $cartProduct = ProductInfo::model()->findByAttributes(['product_id' => $carts->product_id]);
            $sum[] = $carts->qty*$cartProduct->price;
        }
        $cartItem = Cart::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId()]);
        if(1 == 1){
            echo json_encode([
                'token'=> 1,
                'cartCount' => count($cartItem),
                'cartTotal' => array_sum($sum),
                'id'=> $_POST['id']
            ]);
        }else{
            echo json_encode([
                'token' => 0
            ]);
        }
    }

    public function actionPlaceOrder()
    {

        $carts = Cart::model()->findAllByAttributes(['user_id'=> Yii::app()->user->getId()]);
        $userDetail = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);

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
        //print_r($orderInfo); die;
        $orderInfoStatus = OrderHelper::CreateOrderInfo($orderInfo);
        if (1 == 1/*$orderInfoStatus*/){
        foreach ( $carts as $cart){
            $proDetail = ProductInfo::model()->findByAttributes(['product_id' => $cart->product_id]);
                $orderItem['order_info_id'] = $orderInfoStatus;
                $orderItem['product_id'] = $proDetail->product_id;
                $orderItem['item_qty'] = 1;
                $orderItem['item_disc'] = $proDetail->description;
                $orderItem['item_price'] = $proDetail->price;
                $orderItem['created_at'] = date('Y-m-d H:i:s');
                //print_r($orderItem);
                $token[] = OrderHelper::CreateOrderItem($orderItem);
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
            echo json_encode([
                'token' => 1,
            ]);
        }else{
            echo json_encode([
                'token' => 2,
            ]);
        }
    }
}