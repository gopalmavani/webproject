<?php

class ProductController extends Controller
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
        if (Yii::app()->user->isGuest && $action->id != "checkout" && $action->id != "RemoveFromCart"){
            $this->redirect(Yii::app()->createUrl('home/login'));
        }else{
            if($action->id != "checkout"  && $action->id != "RemoveFromCart"){
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
    public function actionIndex()
    {
        $product = ProductInfo::model()->findAll();
        $this->render('index',
            array(
                'products' => $product
            ));
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
                $_SESSION['addalCart'] = $product->name." added to cart";
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
                $_SESSION['addCart'] = $product->name." added to cart";
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

    public function actionAddToWishlist()
    {

        $product = ProductInfo::model()->findByAttributes(['product_id' => $_POST['id']]);
        $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
        $checkWishlist = Wishlist::model()->findByAttributes(['user_id' => Yii::app()->user->getId(), 'product_id' => $_POST['id']]);
        if ($checkWishlist){
            $_SESSION['allWish'] = $product->name." is already in wishlist";
            echo json_encode([
                'token' => 0,
                'msg' => $product->name." is already in wishlist"
            ]);

        }else{
            $wishlist = new Wishlist();
            $wishlist->product_id = $product->product_id;
            $wishlist->user_id = $user->user_id;
            $wishlist->created_at = date('Y-m-d H:i:s');

            if($wishlist->save()){
                $_SESSION['addWish'] = $product->name." added to wishlist successfully";
                echo json_encode([
                    'token' => 1,
                    'msg' => $product->name." added to wishlist"
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
        if(isset(Yii::app()->user->id)){
            $myid = Yii::app()->user->id;
        }else{
            $myid = $_SESSION['mynewid'];
        }
        $sum = [];
        Yii::app()->db->createCommand("DELETE FROM cart WHERE `product_id` = ".$_POST['id']." AND `user_id` = " .$myid )->execute();

        $cart = Cart::model()->findAllByAttributes(['user_id' => $myid]);
        foreach ($cart as $carts){
            $cartProduct = ProductInfo::model()->findByAttributes(['product_id' => $carts->product_id]);
            $sum[] = $carts->qty*$cartProduct->price;
        }
        $cartItem = Cart::model()->findAllByAttributes(['user_id' => $myid]);
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

    public function actionResetCart()
    {
        Yii::app()->db->createCommand()->truncateTable('cart');
        $cart = Cart::model()->findAll();
        if (!$cart){
            echo json_encode([
                'token' => 1
            ]);
        }else{
            echo json_encode([
                'token' =>0
            ]);
        }
    }

    /**
     * opens cart page
     */
    public function actionCheckout(){
        if(isset(Yii::app()->user->id)){
            $myid = Yii::app()->user->id;
        }
        else{
            $myid = $_SESSION['mynewid'];
        }
        $cart= Cart::model()->findAllByAttributes(['user_id' => $myid]);
        $cartItem = $sum =  [];
        foreach ($cart as $carts) {
            $cartProduct = ProductInfo::model()->findByAttributes(['product_id' => $carts->product_id]);
            $sum[] = $carts->qty * $cartProduct->price;
            $cartItem[] = [
                'product_id' => $cartProduct->product_id,
                'product_name' => $cartProduct->name,
                'original_price' => $cartProduct->price,
                'product_price' => $carts->amount,
                'product_image' => $cartProduct->image,
                'product_summary' => $cartProduct->short_description,
                'product_qty' => $carts->qty
            ];
        }
        $count = count($cart);
        $cartTotal = array_sum($sum);
        $this->render('checkout',array(
            'cartItem' => $cartItem,
            'count' => $count,
            'cartTotal' => $cartTotal
        ));
    }

}