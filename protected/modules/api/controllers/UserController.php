<?php

class UserController extends Controller
{
	/**
	* Specifies the access control rules.
	* This method is used by the 'accessControl' filter.
	* @return array access control rules
	*/
	public function accessRules()
    	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('index', 'view', 'create', 'update', 'admin', 'delete', 'add'),
				'users' => array('*'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	* Displays a particular model.
	* retuern json data for given parameter.
	*/
	public function actionView()
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
			$models = UserInfo::model()->findAllByAttributes($arr);
			} elseif ($from != '1970-01-01' && $to != '1970-01-01' & $arr) {
			$criteria = new CDbCriteria();
			$criteria->addBetweenCondition('created_at', ($from), ($to), 'AND');
			$models = UserInfo::model()->findAllByAttributes($arr, $criteria);
			}elseif (!$arr && $from != '1970-01-01' && $to != '1970-01-01') {
			$criteria = new CDbCriteria();
			$criteria->addBetweenCondition('created_at', ($from), ($to), 'AND');
			$models = UserInfo::model()->findAllByAttributes($arr, $criteria);
			} else {
			$models = UserInfo::model()->findAll();
			}
			if ($models) {
			foreach ($models as $model) {
			$users[] = $model->attributes;
			}
			if ($users) {
			ApiResponse::json(true, 200, $users, "Total " . count($users) . " users fetched");
			} else {
			ApiResponse::json(false, 500, $users);
			}
			} else {
			ApiResponse::json(false, 500, [], "No records found for given parameter");
			}
		}
	}

	/**
	* This method is used by create new user,address,business
	* @return json array
	*/
	public function actionAdd()
	{
		$request = Yii::app()->request->getRawBody();
		$model = new UserInfo;
		$result = CJSON::decode($request);
		// call save users method
		$this->saveUsers($model, $result);
	}

	/**
	* Updates a particular model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id the ID of the model to be updated
	*/
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'UserInfo');
		$request = Yii::app()->request->getRawBody();

		// decode of json request data
		$result = CJSON::decode($request);

		// Call SaveUsers data for update
		$this->saveUsers($model, $result);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

	}

	/**
	* Deletes a particular model.
	* If deletion is successful, the browser will be redirected to the 'admin' page.
	* @param integer $id the ID of the model to be deleted
	*/
	public function actionDelete($id)
	{
		if ($this->loadModel($id, 'UserInfo')->delete()) {
		$data = [
		"user_id" => $id
		];
			ApiResponse::json(true, 200, $data, "Successfully deleted");
		} else {
			ApiResponse::json(true, 404, [], "The requested page does not exist.");
		}
	}

	/**
	* Lists all models.
	*/
	public function actionIndex()
	{
		try {
			$users = UserInfo::model()->findAll();
			ApiResponse::json(true, 200, $users, count($users) . " users fetched");
		} catch (Exception $e) {
			ApiResponse::json(false, 500, $users);
		}
	}

	/**
	* Manages all models.
	*/
	public function actionAdmin()
	{
		$model = new UserInfo('search');
		$model->unsetAttributes();  // clear any default values
			if (isset($_GET['UserInfo']))
				$model->attributes = $_GET['UserInfo'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer $id the ID of the model to be loaded
	* @param string $modelName the Name of the model to be loaded
	* @return UserInfo the loaded model
	* @throws CHttpException
	*/
	public function loadModel($id, $modelName)
	{
		$model = $modelName::model()->findByPk($id);
			if ($model === null) {
				ApiResponse::json(false, 404, [], "The requested page does not exist.");
				exit;
			}
		return $model;
	}

	/**
	* This method is using $userId to get all user data with user personal addresses and business addressess
	* @param $userId
	* @return array
	*/
	protected function getUserData($userId)
	{
		$userModel = $this->loadModel($userId, 'UserInfo');
		$usersArray = $userModel->attributes;
		// get all rows match to userId in 'addresses_mapping' table
		return $usersArray;
	}

	/**
	* This function update and create user,
	* json decode data pass in $request
	* @param $model
	* @param $request
	* @return json array
	*/
	protected function saveUsers($model, $request)
	{
		try {
			$model->attributes = $request;
			$model->created_at = date('Y-m-d h:i:s');
			$model->modified_at = date('Y-m-d h:i:s');
			// Save user-data using UserInfo object
			$timestamp = date("d-m-Y", strtotime($request['date_of_birth']));
			$model->date_of_birth = $timestamp;
			$fullName = $request['first_name'] . " " . $request['last_name'];
			$model->full_name = $fullName;
			$errors['email'] = ValidationHelper::ValidateEmail($model->email);
			$errors['dob'] = ValidationHelper::ValidateDOB($model->date_of_birth);
			$errors['phone'] = ValidationHelper::ValidatePhoneNumber($model->phone);
			$errors['business_phone'] = ValidationHelper::ValidatePhoneNumber($model->business_phone);

			$error = array();
			foreach ($errors as $err) {
				if ($err) {
					$error[] = $err;
				}
			}

			if ($error) {
				print_r($error);
			} else {
				$model->date_of_birth = date('Y-m-d',strtotime(str_replace('-','/', $model->date_of_birth)));
				if ($model->validate()) {
					if ($model->save()) {
						$data = ["user_id" => $model['user_id']];
						ApiResponse::json(true, 200, $data, "Saved successfully");
					} else {
						ApiResponse::json(false, 501, [], "Could not save model");
					}
				} else {
					print_r($model->Errors);
				}
			}

		} catch (Exception $e) {
			ApiResponse::json(false, 500, [], $e->getMessage());
		}
	}
}