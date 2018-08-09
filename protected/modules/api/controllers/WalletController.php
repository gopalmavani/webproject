<?php

class WalletController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
			'class'=>'path.to.AnotherActionClass',
			'propertyName'=>'propertyValue',
			),
		);
	}*/

	/*
	* Get portals name
	* returns json for portals name
	*/
	public function actionGetPortals()
		{
			$portals = Portals::model()->findAll();

			foreach ($portals as $portal) {
				$data[$portal->portal_id] = $portal->portal_name;
			}

			if ($data) {
				ApiResponse::json(true, 200, $data, "Portal name fetched");
			} else {
				ApiResponse::json(true, 200, [], "No portals found");
		}
	}

	/*
	* Get Wallet Type Name
	* return json for wallet type name
	*/
	public function actionGetWalletType()
	{
		$wallets = WalletTypeEntity::model()->findAll();

		foreach ($wallets as $wallet) {
			$data[$wallet->wallet_type_id] = $wallet->wallet_type;
		}

		if ($data) {
			ApiResponse::json(true, 200, $data, "Wallet type fetched");
		} else {
			ApiResponse::json(true, 200, [], "No wallet type found");
		}
	}

	/*
	* Get Denomination Name
	* return json for Denomination name
	*/
	public function actionGetDenominationType()
	{
		//$denominations = Denomination::model()->findAll(array('group' => 'denomination_type'));
		$denominations = Denomination::model()->findAll();
		foreach ($denominations as $denomination) {
			$data[$denomination->denomination_id] = $denomination->denomination_type;
		}

		if ($data) {
			ApiResponse::json(true, 200, $data, "Denomiantion type fetched");
		} else {
			ApiResponse::json(true, 200, [], "No Denomiantion type found");
		}
	}

	/*
	* Get Currency Name
	* return json for Currency name
	*/
	public function actionGetCurrencyName()
	{
		//$denominations = Denomination::model()->findAll(array('group' => 'denomination_type'));
		$denominations = Denomination::model()->findAll();
		foreach ($denominations as $denomination) {
			if ($denomination->currency) {
				$data[$denomination->denomination_id] = $denomination->currency;
			}
		}

		if ($data) {
			ApiResponse::json(true, 200, $data, "Currency type fetched");
		} else {
			ApiResponse::json(true, 200, [], "No Currency type found");
		}
	}

	/**
	* This method is used to add wallet data
	* @return json array
	*/
	public function actionAddWallet()
	{

		if (isset($_POST)) {
			$request = Yii::app()->request->getRawBody();
			$wallet = new Wallet;


			$result = CJSON::decode($request);
			$wallet->attributes = $result;

			$denomiantion_type = Denomination::model()->findByAttributes(array('denomination_id' => $result['denomination_id']));

			if ($denomiantion_type->denomination_type != "cash") {
			$wallet->updated_balance = 0.0;
		}

		$exist_denomiantion = Wallet::model()->findByAttributes(array('denomination_id' => $result['denomination_id'], 'user_id' => $result['user_id']), array('order' => 'wallet_id DESC', 'limit' => 1));
		if ($denomiantion_type->denomination_type === "cash") {

			if ($exist_denomiantion) {
				if ($result['transaction_type'] === "credit") {
					$wallet->updated_balance = $result['amount'] + $exist_denomiantion->updated_balance;
				}

				if ($result['transaction_type'] === "debit") {
					$wallet->updated_balance = $exist_denomiantion->updated_balance - $result['amount'];
				}
			} else {
				$wallet->updated_balance = $result['amount'];
			}
		}

		$wallet->created_at = date('Y-m-d');
			if ($wallet->validate()) {
				if ($wallet->save()) {
					$data = [
					'wallet_id' => $wallet->wallet_id,
					];
					ApiResponse::json(true, 200, $data, "Wallet model saved successfully");
				} else {
					ApiResponse::json(false, 501, [], "Could not save wallet model");
				}
			}else{
				print_r($wallet->Errors);
			}
		}
	}

	/**
	* This method is used to View wallet data
	* @return json array
	*/
	public function actionViewWallet()
	{
		$request = Yii::app()->request->getRawBody();
		$result = CJSON::decode($request);

		$arr = array();
		foreach ($result as $key => $res) {
			if ($res && $key != "start_date" && $key != "end_date") {
				$arr[$key] = $res;
			}
		}

		$from = $result['start_date']." 00:00:00";
		$to = $result['end_date']." 23:59:59";

		if ($from === '1970-01-01' && $to != '1970-01-01' || $from != '1970-01-01' && $to === '1970-01-01'){
			ApiResponse::json(true, 501, [], "Please Enter Date Range Correctly");
		} else {

			if ($arr && $from === '1970-01-01' && $to === '1970-01-01') {
				$models = Wallet::model()->findAllByAttributes($arr);
			} elseif ($from != '1970-01-01' && $to != '1970-01-01' & $arr) {
				$criteria = new CDbCriteria();
				$criteria->addBetweenCondition('created_at', ($from), ($to), 'AND');
				$models = Wallet::model()->findAllByAttributes($arr, $criteria);
			}elseif (!$arr && $from != '1970-01-01' && $to != '1970-01-01') {
				$criteria = new CDbCriteria();
				$criteria->addBetweenCondition('created_at', ($from), ($to), 'AND');
				$models = Wallet::model()->findAllByAttributes($arr, $criteria);
			} else {
				$models = Wallet::model()->findAll();
			}
			if ($models) {
				foreach ($models as $model) {
					$wallets[] = $model->attributes;
				}
				if ($wallets) {
					ApiResponse::json(true, 200, $wallets, "Total " . count($wallets) . " Transactin fetched");
				} else {
					ApiResponse::json(false, 500, $wallets);
				}
			} else {
				ApiResponse::json(false, 500, [], "No records found for given parameter");
			}
		}
	}
}