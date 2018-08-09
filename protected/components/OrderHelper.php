<?php

/**
 * Created by PhpStorm.
 * User: imyuvii
 * Date: 27/10/16
 * Time: 5:06 PM
 */
class OrderHelper extends CApplicationComponent
{
    public static function CreateOrderInfo($data = array())
    {

        $order = OrderInfo::model()->findByAttributes(['order_id' => $data['order_id']]);
        if ($order){
            $order->attributes = $data;
            $order->created_date = date('Y-m-d H:i:s');
            $order->modified_date = date('Y-m-d H:i:s');
            $order->invoice_date = date('Y-m-d H:i:s');
        }else{
            $order = new OrderInfo();
            $order->attributes = $data;
            $order->created_date = date('Y-m-d H:i:s');
            $order->modified_date = date('Y-m-d H:i:s');
            $order->invoice_date = date('Y-m-d H:i:s');
        }
        /*echo "<pre>";
        print_r($order->attributes); die;*/

        if ($order->validate()) {
            if ($order->save()) {
                return $order->order_info_id;
            }
        }else{
            echo "<Pre>";
            print_r($order->getErrors());die;
            return false;
        }

    }

    public static function CreateOrderPayment($data = array())
    {
        $model = OrderPayment::model()->findByAttributes(['order_info_id' => $data['order_info_id']]);
        if ($model) {
            $model->attributes = $data;
            $model->modified_at = date('Y-m-d H:i:s');
        }else{
            $model = new OrderPayment();
            $model->attributes = $data;
            $model->created_at = date('Y-m-d H:i:s');
            $model->modified_at = date('Y-m-d H:i:s');
        }


        if ($model->validate()) {
            if ($model->save()) {
                return $model->payment_id;
            }
        }else{
            return false;
        }

    }

    public static function CreateOrderItem($data = array())
    {
        $model = new OrderLineItem();

        $model->attributes = $data;
        $model->created_at = date('Y-m-d H:i:s');
        $model->modified_at = date('Y-m-d H:i:s');
        /*echo "<pre>";
        print_r($model->attributes); die;*/
        if ($model->validate()) {
            if ($model->save()) {
                return $model->order_line_item_id;
            }
        }else{
            print_r($model->getErrors()); die;
            return false;
        }

    }

    public static function AddAffiliateData($productId, $productQty, $userId, $Mode)
    {
        $affiliateData = ProductAffiliate::model()->findAllByAttributes(['product_id' => $productId]);
        $product_qty = $productQty;
        if(!empty($affiliateData)) {
            $affLevel = [];
            foreach ($affiliateData as $affKey => $affiliate){
                $affLevel[$affKey] = $affiliate->aff_level;
            }
            $maxValue = max($affLevel);
            $userParents = CJSON::decode(BinaryTreeHelper::GetParentTrace($userId,$maxValue));
            foreach ($userParents as $parent){
                if(in_array($parent['level'],$affLevel)){

                    // get transaction type
                    $field = 'Credit';
                    $fieldId = CylFields::model()->findByAttributes(['field_name' => 'transaction_type']);
                    $fieldLabel = CylFieldValues::model()->findByAttributes(['field_id' => $fieldId->field_id,'field_label' => $field]);

                    //get Wallet type
                    $walletTypeId = WalletTypeEntity::model()->findByAttributes(['wallet_type' => 'User']);

                    // get reference id
                    $reference = WalletMetaEntity::model()->findByAttributes(['reference_key' => 'Affiliate Bonus']);

                    // wallet transaction comment
                    $comment = "From UserId-".$userId.", Level-".$parent['level'];


                    // get denomination id
                    $tableId = CylTables::model()->findByAttributes(['table_name' => 'denomination']);
                    $denomFieldId = CylFields::model()->findByAttributes(['field_name' => 'denomination_type', 'table_id' => $tableId->table_id]);
                    $paymentTableId = CylTables::model()->findByAttributes(['table_name' => 'order_payment']);//get Payment Mode
                    $paymentFieldId = CylFields::model()->findByAttributes(['field_name' => 'payment_mode', 'table_id' => $paymentTableId->table_id]);
                    $paymentMode = CylFieldValues::model()->findByAttributes(['field_id' => $paymentFieldId->field_id , 'predefined_value' => $Mode]);

                    $denominationData = CylFieldValues::model()->findByAttributes(['field_id' => $denomFieldId->field_id, 'field_label' => $paymentMode->field_label]);
                    $denominationId = Denomination::model()->findByAttributes(['denomination_id' => $denominationData->predefined_value]);

                    // get transaction status
                    $walletTable = CylTables::model()->findByAttributes(['table_name' => 'wallet']);
                    $transactionField = CylFields::model()->findByAttributes(['field_name' => 'transaction_status', 'table_id' => $walletTable->table_id]);
                    $transactionValue = CylFieldValues::model()->findByAttributes(['field_id' => $transactionField->field_id, 'field_label' => 'Approved']);

                    //get Portal id
                    $portal = Portals::model()->findByAttributes(['portal_name' => 'IrisCall']);

                    // get affiliate
                    $affiliateDetails = ProductAffiliate::model()->findByAttributes(['product_id' => $productId, 'aff_level' => $parent['level']]);
                    $affAmount = $affiliateDetails->amount * $product_qty;

                    $wallet = new Wallet();
                    $wallet->user_id = $parent['userId'];
                    $wallet->wallet_type_id = $walletTypeId->wallet_type_id;
                    $wallet->transaction_type = $fieldLabel->predefined_value;
                    $wallet->reference_id = $reference->reference_id;
                    $wallet->reference_num = $userId;
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

    public static function AddDirectSalesBonus($user_id,$amount)
    {

        /*Direct sales bonus*/
        $userInfo = UserInfo::model()->findByAttributes(['user_id' => $user_id]);
        $sponsor = UserInfo::model()->findByAttributes(['user_id' => $userInfo->sponsor_id]);
        $walletds = new Wallet();
        $walletds->user_id = $sponsor->user_id;
        $walletds->wallet_type_id = 1;
        $walletds->transaction_type = 0;
        $walletds->reference_id = 1;
        $walletds->reference_num = $userInfo->user_id;
        $walletds->transaction_comment = "Direct sales bonus from " . $userInfo->full_name . '(' . $userInfo->sponsor_id . ')';
        $walletds->denomination_id = 1;
        $walletds->transaction_status = 2;
        $walletds->portal_id = 1;
        $walletds->amount = $amount;
        $walletds->created_at = date('Y-m-d H:i:s');

        if($walletds->validate()){
            $walletds->save();
        }else{
            print_r($walletds->getErrors()); die;
        }


        /*Direct sales bonus end*/
    }

    public static function getSubscriptionDurationDenomination($number){
        $denomination = '';
        if($number == 1)
            $denomination = 'Days';
        elseif($number == 2)
            $denomination = 'Weeks';
        elseif($number == 3)
            $denomination = 'Months';
        elseif($number == 4)
            $denomination = 'Years';
        return $denomination;
    }

}