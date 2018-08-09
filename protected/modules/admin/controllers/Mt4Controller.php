<?php

class Mt4Controller extends CController
{
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['updatemt4'],
                'users' => ['*'],
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionAccounts()
    {
        $TableID = CylTables::model()->findByAttributes(['table_name' => ApiAccounts::model()->tableSchema->name]);
        $model = new ApiAccounts('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ApiAccounts']))
            $model->attributes = $_GET['ApiAccounts'];
        $alldata = ApiAccounts::model()->findAll();

        $this->render('accounts', array(
            'TableID' => $TableID,
            'model' => $model,
            'alldata' => $alldata,
        ));
    }

    public function actionSettings()
    {
        $this->render('settings');
    }

    /**
     * Manages data for server side datatables.
     */
    public function actionServerdata()
    {
        $requestData = $_REQUEST;
        $model = new ApiAccounts();
        $primary_key = $model->tableSchema->primaryKey;
        $array_cols = Yii::app()->db->schema->getTable(ApiAccounts::model()->tableSchema->name)->columns;
        $array = array();
        $i = 0;
        foreach ($array_cols as $key => $col) {
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;

        $sql = "SELECT  * from " . ApiAccounts::model()->tableSchema->name . " where 1=1";
        if (!empty($requestData['search']['value'])) {
            $sql .= " AND ( $primary_key LIKE '%" . $requestData['search']['value'] . "%' ";
            foreach ($array_cols as $key => $col) {
                if ($col->name != $primary_key) {
                    $sql .= " OR " . $col->name . " LIKE '%" . $requestData['search']['value'] . "%'";
                }
            }
            $sql .= ")";
        }

        $j = 0;

        // getting records as per search parameters
        foreach ($columns as $key => $column) {

            if (!empty($requestData['columns'][$key]['search']['value'])) {   //name
                $sql .= " AND `$column` LIKE '%" . $requestData['columns'][$key]['search']['value'] . "%' ";
            }
            $j++;
        }

        $count_sql = str_replace("*", "count(*) as columncount", $sql);
        $data = Yii::app()->db->createCommand($count_sql)->queryAll();
        $totalData = $data[0]['columncount'];
        $totalFiltered = $totalData;

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
            $requestData['length'] . "   ";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $data = array();
        $i = 1;


        foreach ($result as $key => $row) {
            $nestedData = array();
            $nestedData[] = $row[$primary_key];
            foreach ($array_cols as $key => $col) {
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

    public function actionUpdatemt4()
    {
        //echo "<pre>";print_r($_POST);die;
        if (isset($_POST['start_date'])) {
            $start = $_POST['start_date'];
            $end = $_POST['end_date'];
            $agent = $_POST['agent'];
        } else {
            $sql = "SELECT date FROM `api_logs` ORDER BY `id` DESC LIMIT 1";
            $startdate = Yii::app()->db->createCommand($sql)->queryAll();
            if ($startdate == null) {
                $start = date("Y-m-d");
            } else {
                $start = $startdate[0]['date'];
            }
            $end = date("Y-m-d");
            $agent = 8780;
        }
        $curl = curl_init();
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.4xsolutions.com/Mt4manager/Accounts/GetAccountsWithHistory/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => CJSON::encode([
                "ServerLogin" => "431",
                "ServerPassword" => "TL@123",
                "ServerAddress" => "MAM2.infinox.com",
                "ServerPort" => 443,
                "StartDate" => $start . " 00:00:00",
                "EndDate" => $end . " 23:59:59",
                "TradeTypes" => ["BALANCE"],
                "Agent" => $agent,
            ]),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $response;die;
            $decode = json_decode($response, true);
            /*echo "<pre>";
            print_r($decode);die;*/
            if ($decode == true) {
                $new_accounts = 0; // initializing trade counts
                $updated_accounts = 0;
                $count = 0;

                foreach ($decode as $k => $res) {
                    /*echo "<pre>";
                    print_r($res);*/
                    $accountTrades = $res['Trades'];
                    /*echo "<pre>";
                    print_r($accountTrades);*/
                    foreach ($accountTrades as $i => $trade) {
                        /*echo "<pre>";
                        print_r($trade['Comment']);*/
                        if(isset($trade['Comment'])){
                            $comment = $trade['Comment'];
                            if(stripos($comment, 'Deposit') !== false || stripos($comment, 'Transfer In') !== false ){
                                //echo "hii";die;
                                $apiDW = new ApiDepositWithdraw();
                                $apiDW->login = $trade['Login'];
                                $apiDW->ticket = $trade['Ticket'];
                                $apiDW->api_type = $trade['Type'];
                                $apiDW->symbol = $trade['Symbol'];
                                $apiDW->lots = $trade['Lots'];
                                $apiDW->open_price = $trade['OpenPrice'];
                                $apiDW->open_time = $trade['OpenTime'];
                                $apiDW->close_price = $trade['ClosePrice'];
                                $apiDW->close_time = $trade['CloseTime'];
                                $apiDW->profit = $trade['Profit'];
                                $apiDW->commission = $trade['Commission'];
                                $apiDW->agent_commission = $trade['AgentCommission'];
                                $apiDW->comment = $trade['Comment'];
                                $apiDW->magic_number = $trade['MagicNumber'];
                                $apiDW->stop_loss = $trade['StopLoss'];
                                $apiDW->take_profit = $trade['TakeProfit'];
                                $apiDW->swap = $trade['Swap'];
                                $apiDW->created_at = date('Y-m-d');
                                $apiDW->type = "Deposit";
                                $apiDW->email = $res['EmailAddress'];
                                $apiDW->save();
                            }
                            elseif(stripos($comment, 'Withdraw') !== false  || stripos($comment, 'Transfer Out') !== false ){
                                $apiDW = new ApiDepositWithdraw();
                                $apiDW->login = $trade['Login'];
                                $apiDW->ticket = $trade['Ticket'];
                                $apiDW->api_type = $trade['Type'];
                                $apiDW->symbol = $trade['Symbol'];
                                $apiDW->lots = $trade['Lots'];
                                $apiDW->open_price = $trade['OpenPrice'];
                                $apiDW->open_time = $trade['OpenTime'];
                                $apiDW->close_price = $trade['ClosePrice'];
                                $apiDW->close_time = $trade['CloseTime'];
                                $apiDW->profit = $trade['Profit'];
                                $apiDW->commission = $trade['Commission'];
                                $apiDW->agent_commission = $trade['AgentCommission'];
                                $apiDW->comment = $trade['Comment'];
                                $apiDW->magic_number = $trade['MagicNumber'];
                                $apiDW->stop_loss = $trade['StopLoss'];
                                $apiDW->take_profit = $trade['TakeProfit'];
                                $apiDW->swap = $trade['Swap'];
                                $apiDW->created_at = date('Y-m-d');
                                $apiDW->type = "Withdraw";
                                $apiDW->email = $res['EmailAddress'];
                                $apiDW->save();
                            }

                        }
                    }

                    $accountLogin = $res['Login'];
                    $accountCount = ApiAccounts::model()->find('Login=:login', [':login' => $accountLogin]);

                    if (!isset($accountCount->Login)) { // checking if account exists? if not than it will insert records
                        $accountModel = new ApiAccounts();
                        $accountModel->created_date = date('Y-m-d h:i:s');
                        $new_accounts++;
                    } else {
                        $accountModel = ApiAccounts::model()->find('Login=:login', [':login' => $accountLogin]);
                        $accountModel->modified_at = date('Y-m-d h:i:s');
                        $updated_accounts++;
                    }
                    $accountModel->Login = $res['Login'];
                    $accountModel->Name = $res['Name'];
                    $accountModel->Currency = $res['Currency'];
                    $accountModel->Balance = $res['Balance'];
                    $accountModel->Equity = $res['Equity'];
                    $accountModel->EmailAddress = $res['EmailAddress'];
                    $accountModel->Group = $res['Group'];
                    $accountModel->Agent = $res['Agent'];
                    $accountModel->RegistrationDate = $res['RegistrationDate'];
                    $accountModel->Leverage = $res['Leverage'];
                    $accountModel->Address = $res['Address'];
                    $accountModel->City = $res['City'];
                    $accountModel->State = $res['State'];
                    $accountModel->PostCode = $res['PostCode'];
                    $accountModel->Country = $res['Country'];
                    $accountModel->PhoneNumber = $res['PhoneNumber'];
                    $accountModel->created_date = date('Y-m-d h:i:s');
                    $accountModel->save();

                    $model = new UserDailyBalance();
                    $model->email_address = $res['EmailAddress'];
                    $model->login = $res['Login'];
                    $model->balance = $res['Balance'];
                    $model->agent = $res['Agent'];
                    $model->equity = $res['Equity'];
                    $model->created_at = date('Y-m-d h:i:s');
                    $model->modified_at = date('Y-m-d h:i:s');
                    if ($model->save()) {
                        $count++;
                    }
                }
                //echo '<div class="success"> No of new accounts: ' . $new_accounts . ', No of updated accounts: ' . $updated_accounts . ' </div>';

                $logs = new ApiLogs();
                $logs->status = 1;
                $logs->created_date = date('Y-m-d H:i:s');
                $logs->date = $end;
                $logs->total_accounts = $new_accounts + $updated_accounts;
                $logs->log = 'No of new accounts: ' . $new_accounts . ', No of updated accounts: ' . $updated_accounts . ', No of inserted row in user_daily_balance: ' . $count;
                $logs->save(false); // saving logs
                $this->redirect(Yii::app()->createUrl('/admin/mt4/accounts'));
            }
            else {
                echo '<div class="error"> Could not fetch accounts from API , <i>' . $decode['error']['error'] . '</i></div>';
                $logs = new ApiLogs();
                $logs->status = 0;
                $logs->created_date = date('Y-m-d H:i:s');
                $logs->date = $end;
                $logs->log = 'API Error #: Could not fetch accounts from API , ' . $decode['error']['error'];
                $logs->save(false); // saving logs
            }
        }
        if (isset($_POST['start_date'])) {
            $this->redirect(Yii::app()->createUrl('/admin/mt4/accounts'));
        }
    }
    public function actionDepositWithdraw()
    {
        $TableID = CylTables::model()->findByAttributes(['table_name' => ApiDepositWithdraw::model()->tableSchema->name]);
        $model = new ApiDepositWithdraw('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ApiDepositWithdraw']))
            $model->attributes = $_GET['ApiDepositWithdraw'];
        $alldata = ApiDepositWithdraw::model()->findAll();

        $this->render('depositWithdraw', array(
            'TableID' => $TableID,
            'model' => $model,
            'alldata' => $alldata,
        ));
    }

    /**
     * Manages data for server side datatables.
     */
    public function actiondatatable(){
        $requestData = $_REQUEST;
        $model = new ApiDepositWithdraw;
        $primary_key = $model->tableSchema->primaryKey;
        $array_cols = Yii::app()->db->schema->getTable(ApiDepositWithdraw::model()->tableSchema->name)->columns;
        $array = array();
        $i = 0;
        foreach($array_cols as  $key=>$col){
            $array[$i] = $col->name;
            $i++;
        }

        $columns = $array;

        $sql = "SELECT  * from ".ApiDepositWithdraw::model()->tableSchema->name." where 1=1";
        if (!empty($requestData['search']['value']))
        {
            $sql.=" AND ( $primary_key LIKE '%" . $requestData['search']['value'] . "%' ";
            foreach($array_cols as  $key=>$col){
                if($col->name != $primary_key)
                {
                    $sql.=" OR ".$col->name." LIKE '%" . $requestData['search']['value'] . "%'";
                }
            }
            $sql.=")";
        }

        $j = 0;

        // getting records as per search parameters
        foreach($columns as $key=>$column){

            if( !empty($requestData['columns'][$key]['search']['value']) ){   //name
                $sql.=" AND $column LIKE '%".$requestData['columns'][$key]['search']['value']."%' ";
            }
            $j++;
        }

        $count_sql = str_replace("*","count(*) as columncount",$sql);
        $data = Yii::app()->db->createCommand($count_sql)->queryAll();
        $totalData = $data[0]['columncount'];
        $totalFiltered = $totalData;

        $sql.=" ORDER BY `close_time` DESC , " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," .
            $requestData['length'] . "   ";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $data = array();
        $i=1;


        foreach ($result as $key => $row)
        {
            $nestedData = array();
            $nestedData[] = $row[$primary_key];
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

    public function actionDepositWithdrawView($id)
    {
        $model=ApiDepositWithdraw::model()->findByPk($id);
        $this->render('depositWithdrawView',array(
            'model'=>$model,
        ));
    }

    public function actionView($id)
    {
        $model=ApiAccounts::model()->findByPk($id);
        $this->render('view',array(
            'model'=>$model,
        ));
    }
}
