<?php

class Mt4Controller extends Controller
{
    public $layout = 'main';

    public function actionAccount(){
        // echo strtotime('2018-03-03 10:20:30'); exit;
        /*$email = Yii::app()->db->createCommand()
            ->select('DISTINCT(email_address)')
            ->from('user_daily_balance')
            ->queryAll();
        $agent = Yii::app()->db->createCommand()
            ->select('DISTINCT(agent)')
            ->from('user_daily_balance')
            ->queryAll();*/
        $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->id]);
        $emailName = $user->email;
        $userAccounts = ApiAccounts::model()->findAllByAttributes([
            'EmailAddress' => $user->email
        ]);
        $agentNumber = 8034;
        $dailyBalance = UserDailyBalance::model()->findAllByAttributes(['email_address'=>$emailName,'agent'=>$agentNumber]);
        $balanceArray = array();
        $equityArray = array();
        foreach ($dailyBalance as $record){
            $datetime = strtotime($record->created_at);
            $datetime *= 1000; // convert from Unix timestamp to JavaScript time
            $balArray = [(int)$datetime, $record->balance];
            array_push($balanceArray, $balArray);
            $eqArray = [(int)$datetime, $record->equity];
            array_push($equityArray, $eqArray);
        }
//        print(CJSON::encode($balanceArray).'<br>');
//        print(CJSON::encode($equityArray));
//        exit;
        $this->render('account' , [
            'balance'=>$balanceArray,
            'equity'=>$equityArray,
            'userAccounts' => $userAccounts
        ]);
    }
    /*
     * @param login
     * @returns balance and equity
     * growth of one day
     */
    public function actionGrowthgraph(){
        $login = $_POST['login'];
        //$login = 104293;
        /*$today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day',strtotime($today)));
        $today = '%' . $today . '%';
        $yesterday = '%' . $yesterday . '%';
        $balance = Yii::app()->db->createCommand()
        ->select('balance,equity,created_at')
        ->from('user_daily_balance')
        ->Where(['or like','created_at',[$today,$yesterday]])
        ->andWhere('login=:id',[':id'=>$login])
        ->order('created_at asc')
        ->queryAll();*/
        $balance = Yii::app()->db->createCommand()
            ->select('balance,equity,created_at')
            ->from('user_daily_balance')
            ->where('login=:id',[':id'=>$login])
            ->order('created_at asc')
            ->queryAll();
        $baldata = array();
        $equdata = array();
        $startdata = array();
        $result = array();
        if (count($balance) > 0) {
            unset($baldata);
            unset($equdata);
            unset($startdata);
            $startValue = $balance[0]['balance'];
            foreach ($balance as $bal) {
                $datetime = strtotime($bal['created_at']);
                $datetime *= 1000; // convert from Unix timestamp to JavaScript time
                $value = (floatval($bal['balance']) - $startValue)*100/$startValue;
                $baldata[] = [$datetime,$value];
                $startdata[] = [$datetime,floatval(0)];
                $equity = (floatval($bal['equity']) - $startValue)*100/$startValue;
                $equdata[] = [$datetime,$equity];
            }
        }
        $result['balance'] = $baldata;
        $result['equity'] = $equdata;
        $result['start'] = $startdata;
        $result['baseData'] = $startValue;
        echo json_encode($result);
    }

    /*
    * @param login
    * @returns balance and equity
    * growth from starting
    */
    public function actionOverallgrowth(){
        $login = $_POST['login'];
        //$login = 104293;
        //$balance = Yii::app()->db->createCommand("SELECT balance,equity,created_at FROM `user_daily_balance` where login=$login ORDER by created_at asc")->queryAll();
        $balance = Yii::app()->db->createCommand()
            ->select('balance,equity,created_at')
            ->from('user_daily_balance')
            ->where('login=:id',[':id'=>$login])
            ->order('created_at asc')
            ->queryAll();
        $baldata = array();
        $equdata = array();
        $startdata = array();
        $result = array();
        if (count($balance) > 0) {
            unset($baldata);
            unset($equdata);
            unset($startdata);
            $startValue = $balance[0]['balance'];
            foreach ($balance as $bal) {
                $datetime = strtotime($bal['created_at']);
                $datetime *= 1000; // convert from Unix timestamp to JavaScript time
                $value = floatval($bal['balance']);
                $baldata[] = [$datetime,$value];
                $startdata[] = [$datetime,floatval($balance[0]['balance'])];
                $equity = floatval($bal['equity']);
                $equdata[] = [$datetime,$equity];
            }
        }
        $result['balance'] = $baldata;
        $result['equity'] = $equdata;
        $result['start'] = $startdata;
        $result['baseData'] = $startValue;
        echo json_encode($result);
    }

}