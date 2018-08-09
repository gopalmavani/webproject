<?php

class WalletController extends Controller
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
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $CashBalance = 0;

        $query = Yii::app()->db->createCommand()
            ->select('d.*,SUM(IF(w.transaction_type=0,w.amount,0)) as credit,SUM(IF(w.transaction_type=1,w.amount,0)) as debit')
            ->from('wallet w')
            ->join('denomination d','d.denomination_id = w.denomination_id')
            ->where('w.user_id = '. Yii::app()->user->getId().' group by w.denomination_id')
            ->queryAll();

        /*echo"<pre>";
        print_r($query);die;*/
        if($query)
        {
            $CashBalance = $query[0]['credit'] - $query[0]['debit'];
        }


        $NewData = Yii::app()->db->createCommand()
            ->select('r.reference_desc,w.wallet_id,w.reference_num,w.created_at,w.transaction_status,w.amount,w.transaction_type,w.transaction_comment')
            ->from('wallet w')
            ->join('wallet_meta_entity r','r.reference_id = w.reference_id')
            ->where('w.user_id = '. Yii::app()->user->getId().' order by created_at desc')
            ->queryAll();

        /*echo "<pre>";
        print_r($NewData);die;*/
        $this->render('index', array('wallets' => $NewData, 'cashbalance' => $CashBalance));
    }

    /**
     * check withdraw amount available in account
     * @return bool
     */
    public function actionCheckWithdrawAmount(){

        //$result = 'true';
        /*
                $walletCredit = Yii::app()->db->createCommand()
                    ->select('SUM(amount) as amount,created_at')
                    ->from('wallet')
                    ->where('user_id = '. Yii::app()->user->getId(). ' and denomination_id = 1 and transaction_type = 0' )
                    ->queryRow();

                $walletDebit = Yii::app()->db->createCommand()
                    ->select('SUM(amount) as amount,created_at')
                    ->from('wallet')
                    ->where('user_id = '. Yii::app()->user->getId(). ' and denomination_id = 1 and transaction_type = 1' )
                    ->queryRow()*/;

        $query = Yii::app()->db->createCommand()
            ->select('d.*,SUM(IF(w.transaction_type=0,w.amount,0)) as credit,SUM(IF(w.transaction_type=1,w.amount,0)) as debit')
            ->from('wallet w')
            ->join('denomination d','d.denomination_id = w.denomination_id')
            ->where('w.user_id = '. Yii::app()->user->getId().' group by w.denomination_id')
            ->queryAll();

        $availableAmount = $query[0]['credit'] - $query[0]['debit'];


        if($_POST['amount'] > $availableAmount){
            echo json_encode([
                'token' => 0,
            ]);
        }else{
            echo json_encode([
                'token' => 1,
            ]);
        }
        //echo $result;
    }

    /**
     * withdraw funds into wallet
     */
    public function actionWithdrawFunds(){
        $result = false;
        /*
                $walletCredit = Yii::app()->db->createCommand()
                    ->select('SUM(amount) as amount,created_at')
                    ->from('wallet')
                    ->where('user_id = '. Yii::app()->user->getId(). ' and denomination_id = 1 and transaction_type = 0' )
                    ->queryRow();

                $walletDebit = Yii::app()->db->createCommand()
                    ->select('SUM(amount) as amount,created_at')
                    ->from('wallet')
                    ->where('user_id = '. Yii::app()->user->getId(). ' and denomination_id = 1 and transaction_type = 1' )
                    ->queryRow();*/

        $query = Yii::app()->db->createCommand()
            ->select('d.*,SUM(IF(w.transaction_type=0,w.amount,0)) as credit,SUM(IF(w.transaction_type=1,w.amount,0)) as debit')
            ->from('wallet w')
            ->join('denomination d','d.denomination_id = w.denomination_id')
            ->where('w.user_id = '. Yii::app()->user->getId().' group by w.denomination_id')
            ->queryAll();

        $CashBalance = $query[0]['credit'] - $query[0]['debit'];

        $wallet = new Wallet();
        $user = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);

        $wallet->user_id = Yii::app()->user->getId();
        $wallet->wallet_type_id = 1;
        $wallet->transaction_type = 1;
        $wallet->reference_id = 3;
        $wallet->reference_num = $user->user_id;
        $wallet->transaction_comment = 'Withdraw by ' .$user->full_name. '('.$user->user_id.') to Netteler account';
        $wallet->denomination_id = 1;
        $wallet->transaction_status = 2;
        $wallet->portal_id = 1;
        $wallet->amount = $_POST['amount'];
        $wallet->created_at = date('Y-m-d H:i:s');
        $wallet->modified_at = date('Y-m-d H:i:s');

        if ($wallet->validate()) {
            if($wallet->save()){
                echo json_encode([
                    'token' => 1,
                    'cbalance' => $CashBalance - $_POST['amount']
                ]);
            }else{
                echo json_encode([
                    'token' => 0,
                ]);
            }
        }
        //return $result;
    }

    public function actionIndex_bkpup()
    {
        $CashBalance = 0;
        $planData = $Totaldata = [];

        $query = Yii::app()->db->createCommand()
            ->select('d.*,SUM(IF(w.transaction_type=0,w.amount,0)) as credit,SUM(IF(w.transaction_type=1,w.amount,0)) as debit')
            ->from('wallet w')
            ->join('denomination d','d.denomination_id = w.denomination_id')
            ->where('w.user_id = '. Yii::app()->user->getId().' group by w.denomination_id')
            ->queryAll();


//        $query -> select(['SUM(amount)'])->from('wallet')->join('full join','user_info','user_info.user_id = wallet.user_id');
//
        /*echo"<pre>";
        print_r($query);die;*/

        if($query)
        {
            $CashBalance = $query[0]['credit'] - $query[0]['debit'];
        }

        $ids = $total = $plan = $amount = array();

        $compensations_plan = CompensationsPlan::model()->findAll();

        foreach ($compensations_plan  as  $plan){
            $ids[]=$plan->ref_id;
            $total[]=Wallet::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId(),'denomination_id' => 1,'reference_id'=> $plan->id, 'transaction_type' => 0]);

            foreach ($total as $amt){
                foreach ($amt as $val){
                    $amount[]=$val->amount;
                }
            }
            $name=strtolower(str_replace(' ','_',$plan->name));
            $Sum[$name] = array_sum($amount);
        }
        $planID = implode(',',$ids);
        $plans = Yii::app()->db->createCommand()
            ->select('reference_id, created_at,transaction_status,transaction_comment,transaction_type, SUM(amount) as amount')
            ->from('wallet')
            ->where('user_id = '. Yii::app()->user->getId(). ' AND denomination_id = 1 AND reference_id IN ('.$planID.') AND transaction_type = 0 GROUP BY reference_id' )
            ->queryAll();

        foreach ($plans as $plan){

            $status=CylFieldValues::model()->findByAttributes(['field_id'=>155, 'predefined_value' => $plan['transaction_status']]);

            $type=CylFieldValues::model()->findByAttributes(['field_id'=>150, 'predefined_value'=>$plan['transaction_type']]);

            $reference_meta = WalletMetaEntity::model()->findByAttributes(['reference_id' => $plan['reference_id']]);
            if ($plan['transaction_status'] == 0) {
                $class = "label label-info";
            }elseif($plan['transaction_status'] == 1) {
                $class="label label-warning";
            } elseif ($plan['transaction_status'] == 2) {
                $class="label label-success";
            } else {
                $class="label label-danger";
            }
            $planData[]= [
                'reference_desc' => '<label style="font-weight: 700; font-size: 14px;">'.$reference_meta->reference_desc.' </label><br />'.date('d/m/Y H:i a',strtotime($plan['created_at'])),
                'date' => date('d/m/Y H:i a',strtotime($plan['created_at'])),
                'status' => $status['field_label'],
                'statusClass' => $class,
                'amount' => $plan['amount'],
                'type' => $type['field_label'],
                'comment' => $plan['transaction_comment'].' for the month '.date('M Y'),
            ];

        }

        $wallets = Wallet::model()->findAllByAttributes(array('user_id' => Yii::app()->user->getId()), array('order'=>'created_at DESC'));;
        foreach ($wallets as $wallet){
            if (!in_array($wallet->reference_id, $ids)){
                if ($wallet->transaction_status == 0) {
                    $class = "label label-info";
                }elseif($wallet->transaction_status == 1) {
                    $class="label label-warning";
                } elseif ($wallet->transaction_status == 2) {
                    $class="label label-success";
                } else {
                    $class="label label-danger";
                }
                $status=CylFieldValues::model()->findByAttributes(['field_id'=>155, 'predefined_value'=>$wallet->transaction_status]);
                $type=CylFieldValues::model()->findByAttributes(['field_id'=>150, 'predefined_value'=>$wallet->transaction_type]);
                $reference_meta = WalletMetaEntity::model()->findByAttributes(['reference_id' => $wallet->reference_id]);
                $Totaldata[]= [
                    'reference_desc' => '<label style="font-weight: 700; font-size: 14px;">'.$reference_meta->reference_desc .'</label><br /> Wallet Transaction  Id : '.$wallet->wallet_id.' <br /> '.$reference_meta->reference_data .' : '.$wallet->reference_num .'<br />'.date('d/m/Y H:i a',strtotime($wallet->created_at)),
                    'date' => date('d/m/Y H:i a',strtotime($wallet->created_at)),
                    'status' => $status->field_label,
                    'statusClass' => $class,
                    'amount' => $wallet->amount,
                    'type' => $type->field_label,
                    'comment' => $wallet->transaction_comment,
                ];

            }

        }
        $NewData = array_merge($planData, $Totaldata);
        /*echo "<pre>";
        print_r($NewData); die;*/
        $this->render('index', array('wallets' => $NewData, 'cashbalance' => $CashBalance));
    }


}