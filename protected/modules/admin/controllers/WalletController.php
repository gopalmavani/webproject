<?php

class WalletController extends CController
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
	));
}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
public function actionCreate()
{
    $model=new Wallet;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Wallet']['user_id']))
    {
        $user = UserInfo::model()->findByAttributes(['user_id' => $_POST['Wallet']['user_id']]);
        if(!empty($user)){
            $model->attributes=$_POST['Wallet'];
            $model->created_at = date('Y-m-d H:i:s');
            $model->modified_at = date('Y-m-d H:i:s');
            $users = ($user->user_id) ? ' of user <a href="'.Yii::app()->createUrl('admin/userInfo/view').'/'.$user->user_id.'"> '.$user->full_name.'</a>' : '';
            $amount = ($_POST['Wallet']['amount']) ? ' for &euro;'.$_POST['Wallet']['amount'] : '';
            $body = 'Wallet request '.$amount.$users.' received.';
        }

        if($model->validate()) {
            if ($model->save()){
            $url = Yii::app()->createUrl('admin/wallet/view/').'/'.$model->wallet_id;
        }
        if($model->transaction_type == 1){
            NotificationHelper::AddNotitication('Withdrawal Request',$body,'info',$user->user_id,1,$url);
        }
        $this->redirect(array('view', 'id' => $model->wallet_id));
        }
        /*else{
            echo "<pre>";
            print_r($model->getErrors());die;
        }*/
    }

    $this->render('create',array(
    'model'=>$model,
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

	// Uncomment the following line if AJAX validation is needed
	// $this->performAjaxValidation($model);

	if(isset($_POST['Wallet']))
	{
		$model->attributes=$_POST['Wallet'];
		$model->modified_at = date('Y-m-d H:i:s');
		if($model->validate()) {
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->wallet_id));
		}
	}

	$this->render('update',array(
		'model'=>$model,
	));
}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
public function actionDelete()
{
    /*$this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));*/

    $model = Wallet::model()->findByAttributes(['wallet_id' => $_POST['id']]);
    if(!empty($model)){
        if (Wallet::model()->deleteAll("wallet_id ='" .$model->wallet_id. "'")){
            echo json_encode([
                'token' => 1,
            ]);
        }else{
            echo json_encode([
                'token' => 0,
            ]);
        }
    }
}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
{
	$this->redirect(array('admin'));
	$dataProvider=new CActiveDataProvider('Wallet');
	$this->render('index',array(
		'dataProvider'=>$dataProvider,
	));
}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
{
	$model=new Wallet('search');
	$model->unsetAttributes();  // clear any default values
	if(isset($_GET['Wallet']))
		$model->attributes=$_GET['Wallet'];

	$this->render('admin',array(
		'model'=>$model,
	));
}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Wallet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
{
	$model=Wallet::model()->findByPk($id);
	if($model===null)
		throw new CHttpException(404,'The requested page does not exist.');
	return $model;
}

	/**
	 * Performs the AJAX validation.
	 * @param Wallet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
{
	if(isset($_POST['ajax']) && $_POST['ajax']==='wallet-form')
	{
		echo CActiveForm::validate($model);
		Yii::app()->end();
	}
}

	/**
	 * created crud for pending record of wallet.
	 */

	public function actionPending(){

	$model=new WalletSearch('search');

	$model->unsetAttributes();
	if(isset($_GET['Wallet']))
		$model->attributes=$_GET['Wallet'];

	$this->render('pending',array(
		'model'=>$model,
	));
}


	/**
	 * Manages data for server side datatables.
	 */
	public function actionServerdata(){
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

	$sql = "SELECT  * from wallet where 1=1";
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

	/*echo "<pre>";
    print_r($result);die;*/
	foreach ($result as $key => $row)
	{
		$nestedData = array();
		$nestedData[] = $row['wallet_id'];

		$username_sql = "select full_name from user_info where user_id = "."'$row[user_id]'";
		$user_names = Yii::app()->db->createCommand($username_sql)->queryAll();

		$wallettypesql = "select wallet_type from wallet_type_entity where wallet_type_id ="."'$row[wallet_type_id]'";
		$wallettypenames = Yii::app()->db->createCommand($wallettypesql)->queryAll();

		$denominationsql = "select denomination_type from denomination where denomination_id="."'$row[denomination_id]'";
		$denominations = Yii::app()->db->createCommand($denominationsql)->queryAll();

		$row['user_id'] = $user_names[0]['full_name'];
		$row['wallet_type_id'] = $wallettypenames[0]['wallet_type'];
		$row['denomination_id'] = $denominations[0]['denomination_type'];

		switch($row['transaction_status']){
            case 0 :
                $row['transaction_status'] = "<span class='m-badge  m-badge--brand m-badge--wide'>Pending</span>";
                break;
            case 1:
                $row['transaction_status'] = "<span class='m-badge  m-badge--info m-badge--wide'>On Hold</span>";
                break;
            case 2:
                $row['transaction_status'] = "<span class='m-badge  m-badge--primary m-badge--wide'>Approved</span>";
                break;
            case 3:
                $row['transaction_status'] = "<span class='m-badge  m-badge--danger m-badge--wide'>Rejected</span>";
                break;
            case 4:
                $row['transaction_status'] = "<span class='m-badge  m-badge--metal m-badge--wide'>Expired</span>";
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
//			$nestedData[] = $row["employee_age"];
//			$nestedData[] = '<a href="'.$url.'"><span class="glyphicon glyphicon-pencil"></span></a>';
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
	public function actionPendingdata(){
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

	$sql = "SELECT  * from wallet where transaction_status=0";
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
			else{
				$sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
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

	/*echo "<pre>";
    print_r($result);die;*/
	foreach ($result as $key => $row)
	{
		$nestedData = array();
		$nestedData[] = $row['wallet_id'];

		$username_sql = "select full_name from user_info where user_id = "."'$row[user_id]'";
		$user_names = Yii::app()->db->createCommand($username_sql)->queryAll();

		$wallettypesql = "select wallet_type from wallet_type_entity where wallet_type_id ="."'$row[wallet_type_id]'";
		$wallettypenames = Yii::app()->db->createCommand($wallettypesql)->queryAll();

		$denominationsql = "select denomination_type from denomination where denomination_id="."'$row[denomination_id]'";
		$denominations = Yii::app()->db->createCommand($denominationsql)->queryAll();

		$row['user_id'] = $user_names[0]['full_name'];
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
//			$nestedData[] = $row["employee_age"];
//			$nestedData[] = '<a href="'.$url.'"><span class="glyphicon glyphicon-pencil"></span></a>';
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
     * Opens user balance page
     */
    public function actionUserbalance(){
        $model=new Wallet('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Wallet']))
            $model->attributes=$_GET['Wallet'];

        $this->render('userbalance',array(
            'model'=>$model,
        ));
    }

    /**
     * Shows datatable for userbalance page.
     */
    public function actionUserbalancetable(){
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

        $sql = "SELECT  w.*,u.email,u.first_name from wallet w LEFT JOIN user_info u on w.user_id = u.user_id where 1=1";
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
                    $sql .= " AND u.first_name LIKE '%".$requestData['columns'][$key]['search']['value']."%'";
                }
                if($column == "transaction_comment"){
                    $sql .= " AND u.email LIKE '%" . $requestData['columns'][$key]['search']['value'] . "%' ";
                }
                if($column != "user_id" && $column != "transaction_comment"){
                    $sql .= " AND $column LIKE '%" . $requestData['columns'][$key]['search']['value'] . "%' ";
                }
            }
            $j++;
        }


        $sql .= " GROUP BY w.user_id";
        $count_sql = str_replace("w.*","count(*) as columncount",$sql);
        $data = Yii::app()->db->createCommand($count_sql)->queryAll();
        if(empty($data)){
            $totalData = 0 ;

        }
        else {
        $totalData = $data[0]['columncount'];
        }
        $totalFiltered = $totalData;

        $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
            $requestData['length'] . "   ";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $data = array();
        $i=1;

        /*echo "<pre>";
        print_r($result);die;*/
        foreach ($result as $key => $row)
        {

            $nestedData = array();
            $nestedData[] = $row['wallet_id'];

            $CashBalance = 0;

            $query = Yii::app()->db->createCommand()
                ->select('d.*,SUM(IF(w.transaction_type=0,w.amount,0)) as credit,SUM(IF(w.transaction_type=1,w.amount,0)) as debit')
                ->from('wallet w')
                ->join('denomination d','d.denomination_id = w.denomination_id')
                ->where('w.user_id = '. $row['user_id'].' AND w.transaction_status = "2" group by w.denomination_id')
                ->queryAll();
            /*echo"<pre>";
            print_r($query);die;*/
            if($query)
            {
                $row['amount'] = $query[0]['credit'] - $query[0]['debit'];
            }

            $username_sql = "select first_name from user_info where user_id = "."'$row[user_id]'";
            $user_names = Yii::app()->db->createCommand($username_sql)->queryAll();

            $row['modified_at'] = $row['user_id'];
            $row['user_id'] = $user_names[0]['first_name'];
            $row['transaction_comment'] = $row['email'];



            foreach($array_cols as  $key=>$col){
                $nestedData[] = $row["$col->name"];
            }
//			$nestedData[] = $row["employee_age"];
//			$nestedData[] = '<a href="'.$url.'"><span class="glyphicon glyphicon-pencil"></span></a>';
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

    public function actionSearchUser(){
        //echo "<pre>";print_r($_GET['q']);die;
        if(isset($_GET)){
            $users = Yii::app()->db->createCommand()
                ->select('user_id, first_name')
                ->from('user_info')
                ->where('first_name LIKE "%'.($_GET['q']).'%"')
                ->queryAll();
            //echo "<pre>";print_r($users);die;
            foreach ($users as $user){
                $data[] = ['text' => $user['first_name'], 'id' => $user['user_id']];
            }
            echo json_encode($data);
        }
    }

    /**
     * @param $id
     * opens page for particular user transactions
     */
    public function actionUserbalanceview($id){
        $model=new Wallet('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Wallet']))
            $model->attributes=$_GET['Wallet'];

        $CashBalance = 0;

        $query = Yii::app()->db->createCommand()
            ->select('d.*,SUM(IF(w.transaction_type=0,w.amount,0)) as credit,SUM(IF(w.transaction_type=1,w.amount,0)) as debit')
            ->from('wallet w')
            ->join('denomination d','d.denomination_id = w.denomination_id')
            ->where('w.user_id = '. $id.' AND w.transaction_status = "2" group by w.denomination_id')
            ->queryAll();

        /*echo"<pre>";
        print_r($query);die;*/
        if($query)
        {
            $CashBalance = $query[0]['credit'] - $query[0]['debit'];
        }


        $this->render("userbalanceview",array(
            'model'=>$model,
            "id"=>$id,
            "CashBalance"=>$CashBalance,
        ));
    }

    /**
     * datatable for particular user transactions
     */
    public function actionParticularUser($id){

        $requestData = $_REQUEST;

        $array_cols = Yii::app()->db->schema->getTable('wallet')->columns;
        $array = array();
        $i = 0;
        foreach($array_cols as  $key=>$col){
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;

        $sql = "SELECT  * from wallet where user_id=".$id;

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

            $username_sql = "select first_name from user_info where user_id = "."'$row[user_id]'";
            $user_names = Yii::app()->db->createCommand($username_sql)->queryAll();

            $wallettypesql = "select wallet_type from wallet_type_entity where wallet_type_id ="."'$row[wallet_type_id]'";
            $wallettypenames = Yii::app()->db->createCommand($wallettypesql)->queryAll();

            $denominationsql = "select denomination_type from denomination where denomination_id="."'$row[denomination_id]'";
            $denominations = Yii::app()->db->createCommand($denominationsql)->queryAll();

            $row['user_id'] = $user_names[0]['first_name'];
            $row['wallet_type_id'] = $wallettypenames[0]['wallet_type'];
            $row['denomination_id'] = $denominations[0]['denomination_type'];

            switch($row['transaction_status']){
                case 0 :
                    $row['transaction_status'] = "<span class='m-badge  m-badge--brand m-badge--wide'>Pending</span>";
                    break;
                case 1:
                    $row['transaction_status'] = "<span class='m-badge  m-badge--info m-badge--wide'>On Hold</span>";
                    break;
                case 2:
                    $row['transaction_status'] = "<span class='m-badge  m-badge--primary m-badge--wide'>Approved</span>";
                    break;
                case 3:
                    $row['transaction_status'] = "<span class='m-badge  m-badge--danger m-badge--wide'>Rejected</span>";
                    break;
                case 4:
                    $row['transaction_status'] = "<span class='m-badge  m-badge--metal m-badge--wide'>Expired</span>";
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

}