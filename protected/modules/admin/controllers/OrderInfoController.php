<?php

class OrderInfoController extends CController
{
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
* Displays a particular model.
* @param integer $id the ID of the model to be displayed
*/
public function actionView($id)
{
$this->render('view',array(
'model'=>$this->loadModel($id),
'itemModel' => OrderLineItem::model()->findAllByAttributes(['order_info_id' => $id]),
));
}

/**
* Creates a new model.
* If creation is successful, the browser will be redirected to the 'view' page.
*/
public function actionCreate()
{
$model=new OrderInfo;
$orderItem =new OrderLineItem();
$orderPayment =new OrderPayment();
$productSubscription = new ProductSubscription();

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

//echo "<pre>";print_r($_POST);die;
if(isset($_POST['OrderInfo']) && isset($_POST['OrderLineItem'])) {
$userInfo = UserInfo::model()->findByAttributes(['user_id' => $_POST['OrderInfo']['user_id']]);
if($_POST['OrderInfo']['company'] == ''){
$model->country = $userInfo->country;
}else{
$model->country = $userInfo->busAddress_country;
}
$proDetail = ProductInfo::model()->findByAttributes(['name' => $_POST['OrderLineItem']['product_name']['0']]);
$invoiceNo = 1;
$lastInvoiceNo = OrderInfo::model()->find(array('order'=>'order_info_id DESC'));
$model->attributes = $_POST['OrderInfo'];
$shipping = ShippingMethods::model()->findByAttributes(['name' =>$_POST['OrderInfo']['shipping_method_name']]);
$model->shipping_id = $shipping->id;
$model->shipping_cost = $shipping->price;
   // echo "<pre>";print_r($model);die;

if($lastInvoiceNo == ''){
$model->invoice_number = $invoiceNo;
$model->order_id = $invoiceNo;
}else{
$model->invoice_number = $lastInvoiceNo['invoice_number']+1;
$model->order_id = $lastInvoiceNo['invoice_number']+1;
}/*echo $model->order_id;die;*/
if($_POST['ProductSubscription']['duration']!='')
{
$productSubscription->attributes = $_POST['ProductSubscription'];
$productSubscription->user_id = $userInfo->user_id;
$productSubscription->user_name  = $userInfo->full_name;
$productSubscription->email  = $userInfo->email;
$productSubscription->product_name = $_POST['OrderLineItem']['product_name']['0'];
$productSubscription->product_details = $proDetail->short_description;
$productSubscription->subscription_price = $proDetail->price;
$productSubscription->starts_at= $_POST['ProductSubscription']['starts_at'];
$productSubscription->payment_mode = $_POST['OrderPayment']['payment_mode'];
$productSubscription->subscription_status = 0;

$renewaldate = '';

if ($_POST['ProductSubscription']['duration_denomination'] == 1 ){
$renewaldate = date('Y-m-d H:i:s', time() + $_POST['ProductSubscription']['duration']*24 * 60 * 60);
}elseif ($_POST['ProductSubscription']['duration_denomination'] == 2 ){
$week = "+".$_POST['ProductSubscription']['duration']." week";
$renewaldate = date('Y-m-d H:i:s', strtotime($week, strtotime(date("Y-m-d H:i:s"))));
}elseif ($_POST['ProductSubscription']['duration_denomination'] == 3 ){
$month = "+".$_POST['ProductSubscription']['duration']." months";
$renewaldate = date('Y-m-d H:i:s', strtotime($month, strtotime(date("Y-m-d H:i:s"))));
}elseif ($_POST['ProductSubscription']['duration_denomination'] == 4 ){
$year = "+".$_POST['ProductSubscription']['duration']." years";
$renewaldate = date('Y-m-d H:i:s', strtotime($year, strtotime(date("Y-m-d H:i:s"))));
}

$productSubscription->next_renewal_date = $renewaldate;
$productSubscription->created_at = date('Y-m-d H:i:s');

if($productSubscription->validate()){
if ($productSubscription->save(false)){
$OrderSubMap = new OrderSubscriptionMapping;
$OrderSubMap->subscription_id = $productSubscription->s_id;
$OrderSubMap->order_id = $model->order_id;
$OrderSubMap->created_at  = date('Y-m-d H:i:s');
$OrderSubMap->save();
}
}else{
echo "<pre>";
                print_r($productSubscription->getErrors()); die;
            }

		}

		$userId = $_POST['OrderInfo']['user_id'];
		$usernamesql = "SELECT full_name from user_info WHERE user_id = "."'$userId'";
		$model->user_name = Yii::app()->db->createCommand($usernamesql)->queryScalar();

		$emailsql = "SELECT email from user_info WHERE user_id = "."'$userId'";
		$model->email = Yii::app()->db->createCommand($emailsql)->queryScalar();

		$model->created_date = date('Y-m-d H:i:s');
		$model->modified_date = date('Y-m-d H:i:s');
		$model->invoice_date = date('Y-m-d H:i:s');


		// Order All item price,discount,total,net total
		$totalArray = $this->getOrderAllTotal($_POST['OrderLineItem']);
		$model->orderTotal = $totalArray['orderTotal'];
		$model->discount = $totalArray['orderDiscount'];
		$model->netTotal = $totalArray['netTotal'] + $model->vat;

		$orderPayment->attributes = $_POST['OrderPayment'];
		$orderPayment->created_at = date('Y-m-d H:i:s');
		$orderPayment->modified_at = date('Y-m-d H:i:s');
		$orderPayment->transaction_mode = "Bank Transfer";
		$orderPayment->total = $model->netTotal;
		$paymentmethod = PaymentMethods::model()->findByAttributes(['payment_method_id' =>1]);
		$orderPayment->payment_method_ref_id =$paymentmethod->payment_method_id;
    //echo "<pre>";print_r($orderPayment);die;
		$model->order_origin = 'admin';

    if($model->validate() && $orderPayment->validate()) {

			if ($model->save()){
                //echo "hi";die;
				$orderPayment->order_info_id =  $model->order_info_id;
				$orderPayment->save();
				$this->saveOrderItem($_POST['OrderLineItem'],$model->order_info_id);

				// add user affiliate level amount
				foreach ($_POST['OrderLineItem']['product_name'] as $index => $orderProductName){

					$productname_sql = "select product_id from product_info where name = "."'$orderProductName'";
					$productname = Yii::app()->db->createCommand($productname_sql)->queryAll();

					$orderProductId = $productname[0]['product_id'];

					$affiliateData = ProductAffiliate::model()->findAllByAttributes(['product_id' => $orderProductId]);
					$product_qty = $_POST['OrderLineItem']['item_qty'][$index];

					if(!empty($affiliateData)) {
						$affLevel = [];
						foreach ($affiliateData as $affKey => $affiliate){
							$affLevel[$affKey] = $affiliate->aff_level;
						}
						$maxValue = max($affLevel);
						$userParents = CJSON::decode(BinaryTreeHelper::GetParentTrace($model->user_id,$maxValue));
						foreach ($userParents as $parent){
							if(in_array($parent['level'],$affLevel)){

								// get transaction type
								$field = 'Credit';
								$fieldId = CylFields::model()->findByAttributes(['field_name' => 'transaction_type']);
								$fieldLabel = CylFieldValues::model()->findByAttributes(['field_id' => $fieldId->field_id,'field_label' => $field]);

								//get Wallet type
								$walletTypeId = WalletTypeEntity::model()->findByAttributes(['wallet_type' => 'User']);

								// get reference id
								$reference = WalletMetaEntity::model()->findByAttributes(['reference_key' => 'AFFILIATE']);

								// wallet transaction comment
								$comment = "From UserId-".$model->user_id.", Level-".$parent['level'];

								// get denomination id
								$tableId = CylTables::model()->findByAttributes(['table_name' => 'denomination']);
								$denomFieldId = CylFields::model()->findByAttributes(['field_name' => 'denomination_type', 'table_id' => $tableId->table_id]);
								$paymentTableId = CylTables::model()->findByAttributes(['table_name' => 'order_payment']);//get Payment Mode
								$paymentFieldId = CylFields::model()->findByAttributes(['field_name' => 'payment_mode', 'table_id' => $paymentTableId->table_id]);
								$paymentMode = CylFieldValues::model()->findByAttributes(['field_id' => $paymentFieldId->field_id, 'predefined_value' => $orderPayment->payment_mode]);
								$denominationData = CylFieldValues::model()->findByAttributes(['field_id' => $denomFieldId->field_id, 'field_label' => $paymentMode->field_label]);
								$denominationId = Denomination::model()->findByAttributes(['denomination_id' => 1]);

								// get transaction status
								$walletTable = CylTables::model()->findByAttributes(['table_name' => 'wallet']);
								$transactionField = CylFields::model()->findByAttributes(['field_name' => 'transaction_status', 'table_id' => $walletTable->table_id]);
								$transactionValue = CylFieldValues::model()->findByAttributes(['field_id' => $transactionField->field_id, 'field_label' => 'Approved']);

								//get Portal id
								$portal = Portals::model()->findByAttributes(['portal_name' => 'IrisCall']);

								// get affiliate
								$affiliateDetails = ProductAffiliate::model()->findByAttributes(['product_id' => $orderProductId, 'aff_level' => $parent['level']]);
								$affAmount = $affiliateDetails->amount * $product_qty;

								$wallet = new Wallet();
								$wallet->user_id = $parent['userId'];
								$wallet->wallet_type_id = $walletTypeId->wallet_type_id;
								$wallet->transaction_type = $fieldLabel->predefined_value;
								$wallet->reference_id = $reference->reference_id;
								$wallet->reference_num = $model->user_id;
								$wallet->transaction_comment = $comment;
								$wallet->denomination_id = $denominationId->denomination_id;
								$wallet->transaction_status = $transactionValue->predefined_value;
								$wallet->portal_id = $portal->portal_id;
								$wallet->amount = $affAmount;
								$wallet->created_at = date('Y-m-d H:i:s');
								$wallet->modified_at = date('Y-m-d H:i:s');

								if($wallet->validate()){
									$wallet->save();
								}
							}
						}
					}
				}

				//$this->saveUserLicenseCount($_POST['OrderLineItem'],$model->user_id);
				// add user product licenses in user-license-count table
				foreach ($_POST['OrderLineItem']['product_name'] as $key => $productId) {

					$productname_sql = "select product_id from product_info where name = "."'$orderProductName'";
					$productname = Yii::app()->db->createCommand($productname_sql)->queryAll();

					$productId = $productname[0]['product_id'];

//                        echo $productId;die;

					$product_ids = ProductLicenses::model()->findAllByAttributes(['purchase_product_id' => $productId]);
					$product_qty = $_POST['OrderLineItem']['item_qty'][$key];

					if(!empty($product_ids)) {
						foreach ($product_ids as $key1 => $licenseProductId) {
							// license multiply by quantity
							$totalLicense = $licenseProductId->license_no * $product_qty;

							//$userLicenseCount = UserLicenseCount::model()->findByAttributes(['product_id' => $licenseProductId->product_id, 'user_id' => $_POST['OrderLineItem']]);
							$userLicenseCount = new UserLicenseCount();
							$userLicenseCount->product_id = $licenseProductId->product_id;
							$userLicenseCount->total_licenses = $totalLicense;
							$userLicenseCount->available_licenses = $totalLicense;
							$userLicenseCount->user_id = $model->user_id;
							$userLicenseCount->modified_at = date('Y-m-d H:i:s');
							$userLicenseCount->created_at = date('Y-m-d H:i:s');
							if($userLicenseCount->validate()){
								$userLicenseCount->save();
							}
						}
					}
				}

                $sql = "SELECT value from settings where module_name = 'order_email' and settings_key = 'email_permission' ";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($result)){
                $value = $result[0]['value'];
                if($value == 'enabled'){
                $to = $model->email;
                $subject = "Order Confirmation";
                $total = $_POST['OrderInfo']['vat']+$model->orderTotal;
                $productname = $_POST['OrderLineItem']['product_name'][0];
                $productdesc = $_POST['OrderLineItem']['item_disc'][0];
                $productqty = $_POST['OrderLineItem']['item_qty'][0];
                $productprice = $_POST['OrderLineItem']['item_price'][0];
                $vat = $_POST['OrderInfo']['vat'];
                $mPDF = Yii::app()->ePdf->mpdf('utf-8', 'A4', 9, 'dejavusans');
                $html = file_get_contents(Yii::app()->createAbsoluteUrl('invoice/view/'.$model->order_info_id));
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
                            Thank you for placing your order with cyclone. This email is to confirm your order has been placed successfully, and will be processed & shipped to you soon.
                            <p>Your order id is $model->order_id.</p>
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
                        <td style='padding:10px'>$productname</td>
                        <td style='padding:10px'>$productdesc</td>
                        <td style='text-align: center;padding:10px'>$productqty</td>
                        <td style='text-align: right;padding:10px'>$productprice</td>
                    </tr>
                </table>

                <table align='right' style='margin-top:20px;margin-bottom:20px;padding:15px;border:1px;font-family:Arial,Helvetica,Helvetica Neue,sans-serif;font-size:18px;color:#2f3133;text-decoration:none'>
                    <tr>
                        <td style='font-weight:bold'>Vat :</td>
                        <td style='font-size:15px;'>&euro; $vat</td>
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

				$this->redirect(array('view', 'id' => $model->order_info_id));
			}
		}
	}


	$this->render('create',array(
		'model'=>$model,
		'orderItem' => $orderItem,
		'orderPayment' => $orderPayment,
		'productSubscription' => $productSubscription
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
	$orderItem = OrderLineItem::model()->findAllByAttributes(['order_info_id' => $model->order_info_id]);
	$orderPayment = OrderPayment::model()->find('order_info_id ='. $model->order_info_id);
	$userInfo = OrderInfo::model()->findByAttributes(['order_info_id' => $id]);

	// Uncomment the following line if AJAX validation is needed
	// $this->performAjaxValidation($model);

    //echo "<pre>";print_r($_POST);die;
	if(isset($_POST['OrderInfo']) /*&& isset($_POST['OrderLineItem'])*/ && isset($_POST['OrderPayment']))
	{
		$model->attributes = $_POST['OrderInfo'];
		if($model->order_status == 1){
            $model->invoice_number = $model->order_id;
        }
		$model->modified_date = date('Y-m-d H:i:s');
		if($model->validate()) {
			$model->attributes = $_POST['OrderInfo'];

			/*// Delete Order Item
			$oldItems = OrderLineItem::model()->findAllByAttributes(['order_info_id' => $model->order_info_id]);
			if(!empty($oldItems)){
				foreach ($oldItems as $key => $value) {
					if (!in_array($value['product_id'], $_POST['OrderLineItem']['product_id'])) {
						OrderLineItem::model()->deleteAllByAttributes(['order_info_id' => $model->order_info_id, 'product_id' => $value['product_id']]);
					}
				}
			}

			// Update or create Order Line Item
			$orderItemArray = $_POST['OrderLineItem'];
			$this->saveOrderItem($_POST['OrderLineItem'],$model->order_info_id);

			// Update Order All item price,discount,total,net total
			$totalArray = $this->getOrderAllTotal($_POST['OrderLineItem']);
			$model->orderTotal = $totalArray['orderTotal'];
			$model->discount = $totalArray['orderDiscount'];
			$model->netTotal = $totalArray['netTotal'];*/

			// Update Order Payment
			$orderPayment->attributes = $_POST['OrderPayment'];
			$orderPayment->modified_at = date('Y-m-d H:i:s');
			$orderPayment->total = $model->netTotal;
			if($orderPayment->validate()){
				$orderPayment->save();
			}

			if ($model->save()) {

				$this->redirect(array('view', 'id' => $model->order_info_id));
			}
		}
	}

	$this->render('update',array(
		'model'=>$model,
		'orderItem' => $orderItem,
		'orderPayment' => $orderPayment,
	));
}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
{
	$orders = OrderLineItem::model()->findByAttributes(['order_info_id' => $id]);
	if(!empty($orders)){
		OrderLineItem::model()->deleteAll("order_info_id ='" . $id . "'");
	}
	$orderPayment = OrderPayment::model()->findByAttributes(['order_info_id' => $id]);
	if(!empty($orderPayment)){
		OrderPayment::model()->deleteAll("order_info_id ='" . $id . "'");
	}
	$creditMemo = OrderCreditMemo::model()->findByAttributes(['order_info_id' => $id]);
	if(!empty($creditMemo)){
		OrderCreditMemo::model()->deleteAll("order_info_id ='" . $id . "'");
	}
	$this->loadModel($id)->delete();

	// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
	if(!isset($_GET['ajax']))
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
{
	$this->redirect(['admin']);
	//		$dataProvider=new CActiveDataProvider('OrderInfo');
	//		$this->render('index',array(
	//			'dataProvider'=>$dataProvider,
	//		));
}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
{
	$model=new OrderInfo('search');
	$model->unsetAttributes();  // clear any default values
	if(isset($_GET['OrderInfo']))
		$model->attributes=$_GET['OrderInfo'];

	$this->render('admin',array(
		'model'=>$model,
	));
}

	/*
    * Credit Memo
    */
	    public function actionCreditMemo($id){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $order = OrderInfo::model()->findByPk($_POST['order_info_id']);

            $creditMemo = new OrderCreditMemo();
            $creditMemo->order_info_id = $order->order_info_id;
            $creditMemo->invoice_number = $order->invoice_number;
            $creditMemo->vat = $order->vat;
            $creditMemo->order_total = $_POST['refund_amount'];
            $creditMemo->refund_amount = $order->netTotal;
            $creditMemo->created_at = date('Y-m-d H:i:s');

            if($creditMemo->save()){
                foreach ($_POST['OrderCreditMemo']['order_line_item_id'] as $key=>$value){
                    if($_POST['OrderCreditMemo']['qty_refund'][$key] >= 0){
                        $orderCreditItem = new OrderCreditItems();
                        $orderCreditItem->credit_memo_id = $creditMemo->credit_memo_id;
                        $lineItem = OrderLineItem::model()->findByPk($value);
                        $orderCreditItem->attributes = $lineItem->attributes;
                        $orderCreditItem->product_price = $lineItem->item_price;
                        $orderCreditItem->order_item_qty = $lineItem->item_qty;
                        $orderCreditItem->refund_item_qty = $_POST['OrderCreditMemo']['qty_refund'][$key];
                        $orderCreditItem->save(false);
                    }
                }
                $url = Yii::app()->createUrl('admin/orderCreditMemo');
                $this->redirect($url);
                //Put Order in cancelled state
                $order->order_status = 0;
                $order->modified_date = date('Y-m-d H:i:s');
                $order->save(false);
            } else {
                print('<pre>');
                print_r($creditMemo->errors);
                exit;
            }
        }
        $model=$this->loadModel($id);
        $orderItem = OrderLineItem::model()->findAll('order_info_id =' . $model->order_info_id);
        $creditMemo = new OrderCreditMemo();
        $this->render('creditMemo',[
            'order' => $model,
            'orderItem' => $orderItem,
            'creditMemo' => $creditMemo,
        ]);
    }


	/**
	 * Credit Memo Create using post
	 */
	    public function actionCreditMemoCreate(){
        $creditMemo = new OrderCreditMemo();

        if(isset($_POST)){
            try {
                //get Product Name
                $productPrice = ProductInfo::model()->findByAttributes(['product_id' => $_POST['productId']]);
                $refundAmount = $productPrice->price * $_POST['qty'];

                // Add details in credit memo
                $creditMemo->product_id = $_POST['productId'];
                $creditMemo->order_info_id = $_POST['orderId'];
                $creditMemo->qty_refunded = $_POST['qty'];
                $creditMemo->invoice_number = $_POST['invoiceNo'];
                $creditMemo->memo_status = $_POST['status'];
                $creditMemo->amount_to_refund = $refundAmount;
                $creditMemo->created_at = date('Y-m-d H:i:s');
                $creditMemo->modified_at = date('Y-m-d H:i:s');
                // validate credit memo
                if ($creditMemo->validate()) {
                    $creditMemo->save();
                }

                $orderItem = OrderLineItem::model()->findByAttributes(['order_info_id' => $_POST['orderId'],'product_id' => $_POST['productId']]);
                $oldQty = $orderItem->item_qty;
                // set new quantity
                $newQty = $oldQty - $_POST['qty'];

                $memoPrice = $_POST['qty'] * $productPrice->price;
                $newPrice = $orderItem->item_price - $memoPrice;
                $orderItem->item_price = $newPrice;

                $orderItem->item_qty = $newQty;
                $orderItem->modified_at = date('Y-m-d H:i:s');
                $orderItem->save();
                $orderItem = OrderLineItem::model()->findByAttributes(['order_info_id' => $_POST['orderId'],'product_id' => $_POST['productId']]);
                $orderQty = $orderItem->item_qty;
                if($orderQty <= 0 ){
                    OrderLineItem::model()->deleteAllByAttributes(['order_info_id' => $_POST['orderId'],'product_id' => $_POST['productId']]);
                }

                $orderInfo = OrderInfo::model()->findByAttributes(['order_info_id' => $_POST['orderId']]);
                // set order total
                $oldTotal = $orderInfo->orderTotal;
                $newTotal = $oldTotal - $memoPrice;
                $orderInfo->orderTotal = $newTotal;

                // set order NetTotal
                $oldNetTotal = $orderInfo->netTotal;
                $newNetTotal = $oldNetTotal - $memoPrice;
                $orderInfo->netTotal = $newNetTotal;

                $orderInfo->modified_date = date('Y-m-d H:i:s');
                $orderInfo->save();
                $result = [
                    'result' => true,
                ];

            }catch (Exception $e){
                $result = [
                    'result' => false,
                    'error' => $newNetTotal,
                ];
            }
        }
        echo json_encode($result);
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return OrderInfo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
{
	$model=OrderInfo::model()->findByPk($id);
	if($model===null)
		throw new CHttpException(404,'The requested page does not exist.');
	return $model;
}

	/**
	 * Performs the AJAX validation.
	 * @param OrderInfo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
{
	if(isset($_POST['ajax']) && $_POST['ajax']==='order-info-form')
	{
		echo CActiveForm::validate($model);
		Yii::app()->end();
	}
}

	/*
    * Load address using user_id of user
    */
	public function actionLoadAddresses()
{
	$data=AddressMapping::model()->findAll('user_id=:user_id',
		array(':user_id'=>(int) $_POST['user_id']));


	$data=CHtml::listData($data,'address_mapping_id','address_id');
	echo "<option value=''>Select Address</option>";
	foreach($data as $value=>$addressMappId) {
		$addressMappData = Addresses::model()->find('address_id=:address_id',
			array(':address_id' => (int)$addressMappId));
		echo CHtml::tag('option', array('value' => $value), CHtml::encode($addressMappData->building_no . ', ' . $addressMappData->street . ', ' . $addressMappData->city . ', ' . $addressMappData->region), true);
	}
}

	/*
    *
    */
	public function actionLoadPrice(){

	if($_POST['qty'] > 0) {
		$user = UserInfo::model()->findByAttributes(['user_id' =>$_POST['User_id']]);
		$productDetail = ProductInfo::model()->findByAttributes(['name' => $_POST['productname']]);
		$productSubscription = $productDetail->is_subscription_enabled;
		$totalItemPrice = $productDetail->price * $_POST['qty'];
		$vatAmount = '';
		$vatpercentage = '';
		if (isset($_POST['Address'])) {
			if ($_POST['Address'] == 0) {
				$country = Countries::model()->findByAttributes(['id' => $user->country]);
                $vatpercentage = $country->personal_vat;
				$vatAmount = $totalItemPrice * $country->personal_vat / 100;
			} else {
				$country = Countries::model()->findByAttributes(['id' => $user->busAddress_country]);
                $vatpercentage = $country->buainess_vat;
				$vatAmount = $totalItemPrice * $country->buainess_vat / 100;
			}
		}
		$vatincluded_amount = $totalItemPrice + $vatAmount;
		$result = [
			'result' => true,
			'productPrice' => $totalItemPrice,
			'vatAmount' => $vatAmount,
            'vatpercentage' => $vatpercentage,
			'netTotal' => $vatincluded_amount,
			'productSubscription' => $productSubscription
		];
	}else{
		$result = [
			'result' => false,
		];
	}
	echo json_encode($result);
}

	/*
    * get Address and business address
    */
	public function actionGetAddress(){
	if(isset($_POST['user_id'])){
		$users = UserInfo::model()->findByPk(['user_id' => $_POST['user_id']]);
		$country = Countries::model()->findByAttributes(['id' => $users->country]);
        $users->country = $country->country_name;
        $country2 = Countries::model()->findByAttributes(['id' => $users->busAddress_country]);
        $users->busAddress_country = $country2->country_name;
		$result = [
			'result' => true,
			'userInfo' => $users->attributes,
		];
	}else{
		$result = [
			'result' => false,
		];
	}
	echo json_encode($result);
}

	/**
	 * save and update order item
	 * @param $orderItemArray
	 * @param $orderInfoId
	 */
	protected function saveOrderItem($orderItemArray,$orderInfoId){
	/*echo "<pre>";
    print_r($orderItemArray);die;*/
	foreach ($orderItemArray['product_name'] as $key => $item){
		$orderItem = OrderLineItem::model()->findByAttributes(['order_info_id' => $orderInfoId, 'product_name' => $item]);



		$productidsql = "SELECT product_id,sku from product_info WHERE name = "."'$item'";
		$productinfo = Yii::app()->db->createCommand($productidsql)->queryAll();

		if(!empty($orderItem)){
			$orderItem->item_qty = $orderItemArray['item_qty'][$key];
			$orderItem->item_disc = $orderItemArray['item_disc'][$key];
			$orderItem->item_price = $orderItemArray['item_price'][$key];
			$orderItem->modified_at = date('Y-m-d H:i:s');
			$orderItem->save();
		}else{
			// Item not exist then enter new data
			$orderItem =new OrderLineItem();
			$orderItem->order_info_id = $orderInfoId;
			$orderItem->product_name = $orderItemArray['product_name'][$key];
			$orderItem->product_id = $productinfo[0]['product_id'];
			$orderItem->product_sku = $productinfo[0]['sku'];
			$orderItem->item_qty = $orderItemArray['item_qty'][$key];
			$orderItem->item_disc = $orderItemArray['item_disc'][$key];
			$orderItem->item_price = $orderItemArray['item_price'][$key];
			$orderItem->created_at = date('Y-m-d H:i:s');
			$orderItem->modified_at = date('Y-m-d H:i:s');
			$orderItem->save();
		}
	}
}

	/**
	 * make total,net total and discount
	 * @param $orderItemArray
	 * @return array
	 */
	protected function getOrderAllTotal($orderItemArray){

	$itemPriceTotal = 0;
	$itemDiscTotal = 0;
	foreach ($orderItemArray['item_price'] as $key => $item){
		$itemPriceTotal += $orderItemArray['item_price'][$key];
		$itemDiscTotal += $orderItemArray['item_disc'][$key];
	}
	$result = [
		'orderTotal' => $itemPriceTotal,
		'orderDiscount' => $itemDiscTotal,
		'netTotal' => $itemPriceTotal - $itemDiscTotal
	];
	return $result;
}

	/**
	 * @param $userLicenseData
	 * @param $purchaseProductId
	 */
	protected function saveUserLicenseCount($userLicenseData,$purchaseProductId){
	foreach ($userLicenseData['product_id'] as $key => $productId){
		$product_ids = ProductLicenses::model()->findAllByAttributes(['purchase_product_id' => $productId]);
		foreach ($product_ids as $key2 => $licenseProductId ){
			$userLicenses = UserLicenses::model()->findByAttributes(['product_id' => $licenseProductId->product_id,'user_id' => $purchaseProductId]);

			if(!empty($userLicenses)){
				$userLicenses->product_id = $licenseProductId->product_id;
				$userLicenses->license_no = $licenseProductId->license_no;
				$userLicenses->user_id = $purchaseProductId;
				$userLicenses->is_used = 1;
				$userLicenses->funded_on = date('Y-m-d H:i:s');
				$userLicenses->created_at = date('Y-m-d H:i:s');
				if($userLicenses->validate()){
					$userLicenses->save();
				}
			}else{
				$userLicenses = new UserLicenses();
				$userLicenses->product_id = $licenseProductId->product_id;
				$userLicenses->license_no = $licenseProductId->license_no;
				$userLicenses->user_id = $purchaseProductId;
				$userLicenses->is_used = 1;
				$userLicenses->funded_on = date('Y-m-d H:i:s');
				$userLicenses->created_at = date('Y-m-d H:i:s');
				if($userLicenses->validate()){
					$userLicenses->save();
				}
			}
		}
	}
}


	/**
	 * Manages data for server side datatables.
	 */
	public function actionServerdata(){

	$requestData = $_REQUEST;

	$model= new OrderInfo();
	$array_cols = Yii::app()->db->schema->getTable('order_info')->columns;
	$array = array();
	$i = 0;
	foreach($array_cols as  $key=>$col){
		$array[$i] = $col->name;
		$i++;
	}
	/*$columns = array(
        0 => 'user_id',
        1 => 'full_name'
    );*/
	$columns = $array;

	$sql = "SELECT  * from order_info where 1=1";
//		$data = Yii::app()->db->createCommand($sql)->queryAll();
//		$totalFiltered = count($data);

	if (!empty($requestData['search']['value']))
	{
		$sql.=" AND ( order_info_id LIKE '%" . $requestData['search']['value'] . "%' ";
		foreach($array_cols as  $key=>$col){
			if($col->name != 'order_info_id')
			{
				$sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
			}
		}
		$sql.=")";
//			$sql.=" OR employee_age LIKE '" . $requestData['search']['value'] . "%')";

	}

	$j = 0;
	// getting records as per search parameters
	foreach($columns as $key=>$column){
		if($requestData['columns'][$key]['search']['value'] != ''){   //name
			if($column == 'user_id'){
				$sql.=" AND  user_id = " . $requestData['columns'][$key]['search']['value'] . " ";
			}
			else{
				$sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
			}
//                echo $column;die;
			$sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
		}
		$j++;
	}

//		echo $sql;die;

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
		$nestedData[] = $row['order_info_id'];
		if(ctype_alpha($row['country'])){
			$countrycode = $row['country'];
			$country_sql = "select country_name from countries where country_code = "."'$countrycode'";
			$country_name = Yii::app()->db->createCommand($country_sql)->queryAll();
			if(!empty($country_name)){
				$row['country'] = $country_name[0]['country_name'];
			}
		}
		else if(is_numeric($row['country'])){
			$countryid = $row['country'];
			$country_sql = "select country_name from countries where id = "."'$countryid'";
			$country_name = Yii::app()->db->createCommand($country_sql)->queryAll();
			if(!empty($country_name)){
				$row['country'] = $country_name[0]['country_name'];
			}
		}
		$row['is_subscription_enabled'] = $row['is_subscription_enabled'] == 0? ('No') : ('Yes');

		$row['user_id'] = $row['user_name'];

		switch($row['order_status']){
            case 0 :
                $row['order_status'] = "<span class='m-badge  m-badge--danger m-badge--wide'>Cancelled</span>";
                break;
            case 1:
                $row['order_status'] = "<span class='m-badge  m-badge--primary m-badge--wide'>Success</span>";
                break;
            case 2:
                $row['order_status'] = "<span class='m-badge  m-badge--info m-badge--wide'>Pending</span>";
                break;
            default:
                break;
        }

		foreach($array_cols as  $key=>$col){
            $nestedData[] = $row["$col->name"];
		}

		$data[] = $nestedData;
		$i++;
	}
	/*echo "<pre>";
    print_r($data);die;*/

	$json_data = array(
		"draw" => intval($requestData['draw']),
		"recordsTotal" => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data" => $data   // total data array
	);

	echo json_encode($json_data);
}

	/**
	 * Manages data for server side datatables.
	 */
	public function actionUserwallet($id){
//        echo $id;die;
	/*$alldata = Yii::app()->db->createCommand("SELECT * FROM user_info")->queryAll();
    echo json_encode($alldata);*/

	$requestData = $_REQUEST;

//		$model= new wallet();
	$array_cols = Yii::app()->db->schema->getTable('wallet')->columns;
	$array = array();
	$i = 0;
	foreach($array_cols as  $key=>$col){
		$array[$i] = $col->name;
		$i++;
	}
	/*$columns = array(
        0 => 'user_id',
        1 => 'full_name'
    );*/
	$columns = $array;

	$sql = "SELECT  * from wallet where user_id=".$id;
//		$data = Yii::app()->db->createCommand($sql)->queryAll();
//		$totalFiltered = count($data);

	if (!empty($requestData['search']['value']))
	{
		$sql.=" AND ( wallet_id LIKE '%" . $requestData['search']['value'] . "%' ";
		foreach($array_cols as  $key=>$col){
			if($col->name != 'id')
			{
				$sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
			}
		}
		$sql.=")";
//			$sql.=" OR employee_age LIKE '" . $requestData['search']['value'] . "%')";

	}

	$j = 0;
	// getting records as per search parameters
	foreach($columns as $key=>$column){
		if($requestData['columns'][$key]['search']['value'] != '' ){   //name
			if($column == 'user_id'){
				$sql.=" AND  user_id = " . $requestData['columns'][$key]['search']['value'] . " ";
			}
			else {
				$sql .= " AND $column LIKE '%" . $requestData['columns'][$key]['search']['value'] . "%' ";
			}
		}
		$j++;
	}

//		echo $sql;die;

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
		$nestedData[] = $row['wallet_id'];

		$wallettypesql = "select wallet_type from wallet_type_entity where wallet_type_id ="."'$row[wallet_type_id]'";
		$wallettypenames = Yii::app()->db->createCommand($wallettypesql)->queryAll();

		$denominationsql = "select denomination_type from denomination where denomination_id="."'$row[denomination_id]'";
		$denominations = Yii::app()->db->createCommand($denominationsql)->queryAll();

		$row['wallet_type_id'] = $wallettypenames[0]['wallet_type'];
		$row['denomination_id'] = $denominations[0]['denomination_type'];

		switch($row['transaction_status']){
			case 0 :
				$row['transaction_status'] = "Pending";
				break;
			case 1:
				$row['transaction_status'] = "On Hold";
				break;
			case 2:
				$row['transaction_status'] = "Approved";
				break;
			case 3:
				$row['transaction_status'] = "Rejected";
				break;
			default:
				break;
		}

		switch($row['transaction_type']){
			case 0:
				$row['transaction_type'] = 'Credit';
				break;
			case 1:
				$row['transaction_type'] = 'Debit';
				break;
			default:break;
		}

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
     * Opens settings page for order module.
     */
    public function actionSettings(){
        if(isset($_POST['hidden_email'])){
                $permission = $_POST['hidden_email'];
                $user_id = Yii::app()->user->id;
                $firstsql = "SELECT value from settings where module_name = 'order_email' and settings_key = 'email_permission'";
                $result = Yii::app()->db->createCommand($firstsql)->queryAll();
                if(!empty($result)){
                    $modified_date = date('Y-m-d H:i:s');
                    $sql = "UPDATE settings set value = "."'$permission'".", modified_date = "."'$modified_date'"." where module_name = 'order_email' and settings_key = 'email_permission'";
                }
                else{
                $sql = "INSERT INTO settings (`user_id`,`module_name`,`settings_key`,`value`) values ('$user_id','order_email','email_permission','$permission')";
                }
                Yii::app()->db->createCommand($sql)->execute();
                $_SESSION['delete'] = "Order settings saved successfully...";
        }
        $this->render('settings');
    }

    /**
     * Gets vat Percentage value for the User.
     */
    public function actionGetvatpercentage(){
        if(isset($_POST['adtype']) && isset($_POST['userid'])){
            $users = UserInfo::model()->findByPk(['user_id' => $_POST['userid']]);
            if($_POST['adtype'] == 0){
                $country = Countries::model()->findByAttributes(['id' => $users->country]);
                $result = [
                    'result' => true,
                    'vat' => $country->personal_vat
                ];
            }
            else{
                $country = Countries::model()->findByAttributes(['id' => $users->busAddress_country]);
                $result = [
                    'result' => true,
                    'vat' => $country->buainess_vat
                ];
            }
            echo json_encode($result);
        }
    }
}