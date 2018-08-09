<?php
/* @var $this WalletController */
/* @var $model Wallet */

$userName = UserInfo::model()->findByAttributes(['user_id' => $model->user_id]);
if(!empty($userName)){
$this->pageTitle = $userName->full_name."'s wallet";
}
else{
$this->pageTitle = 'View Wallet';
}
$id = $model->wallet_id; 
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator" lang="en">
                View Transaction
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <!--<span class="m-portlet__head-icon m--hide">
                        <i class="la la-gear"></i>
                    </span>-->
                    <h3 class="m-portlet__head-text">
                        Transaction Details
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('wallet/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php if($model->transaction_status != 2) { ?>
                    <?php echo CHtml::link('Create', array('wallet/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                    <?php echo CHtml::link('Update', array('wallet/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php } ?>
                <p></p>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-12">
                            <div class="col-lg-6">
                                <!-- Default Pagination -->
                                <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                            <span class="m-portlet__head-icon m--hide">
                                                <i class="flaticon-statistics"></i>
                                            </span>
                                                <h2 class="m-portlet__head-label m-portlet__head-label--info">
                                                    <span>Basic Details </span>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <?php $userName = UserInfo::model()->findByAttributes(['user_id' => $model->user_id]); ?>
                                        <span><b>Username : </b></span>
                                        <span>
                                            <?php if(!empty($userName)){ ?>
                                            <a target="_blank" href="<?php echo Yii::app()->CreateUrl('admin/userInfo/view').'/'.$model->user_id; ?>"><?php echo $userName->full_name; ?>
</a>
                                            <?php } else { ?>
                                            <span><?php echo $model->user_id;?>
</span>
                                            <?php } ?>
                                        </span>
                                        <p></p>
                                        <span><b>Wallet Type : </b></span>
                                        <?php $wallet_type = WalletTypeEntity::model()->findByAttributes(['wallet_type_id' => $model->wallet_type_id]);
                                            if(!empty($wallet_type)) { ?>
                                        <span><?php echo $wallet_type->wallet_type; ?>
</span>
                                        <?php } else{
                                            echo "<span>$model->wallet_type_id;</span>";
                                        }?>
                                        <p></p>
                                        <span><b>Transaction Type : </b></span>
                                        <?php
                                            $fieldId = CylFields::model()->findByAttributes(['field_name' => 'transaction_type']);
                                            $orderStatus = CylFieldValues::model()->findByAttributes(['field_id' => $fieldId->field_id, 'predefined_value' => $model->transaction_type]);
                                            if(!empty($fieldId) && !empty($orderStatus)){
                                                echo "<span>$orderStatus->field_label</span>";
                                            }
                                            else{
                                                echo "<span>$model->transaction_type;</span>";
                                            }?>
                                        <p></p>
                                        <span><b>Transaction Comment : </b></span>
                                        <span><?php echo $model->transaction_comment; ?>
</span>
                                        <p></p>
                                        <span><b>Denomination : </b></span>
                                        <?php
                                            $wallet_type = Denomination::model()->findByAttributes(['denomination_id' => $model->denomination_id]);
                                            $fieldId = CylFields::model()->findByAttributes(['field_name' => 'denomination_id']);
                                            $fieldLabel = CylFieldValues::model()->findByAttributes(['field_id' => $fieldId->field_id,'predefined_value' => $wallet_type->denomination_type]);
                                            echo "<span>$fieldLabel[field_label]. $wallet_type->currency</span>";
                                        ?>
                                        <p></p>
                                        <span><b>Transaction Status : </b></span>
                                        <?php
                                        $fieldId = CylFields::model()->findByAttributes(['field_name' => 'transaction_status']);
                                        $orderStatus = CylFieldValues::model()->findByAttributes(['field_id' => $fieldId->field_id, 'predefined_value' => $model->transaction_status]);
                                        if(!empty($fieldId) && !empty($orderStatus)){
                                            if($orderStatus->field_label == "Pending"){
                                                echo "<span class='m-badge m-badge--brand m-badge--wide'>$orderStatus->field_label</span>";
                                            }
                                            elseif ($orderStatus->field_label == "On Hold"){
                                                echo "<span class='m-badge  m-badge--info m-badge--wide'>$orderStatus->field_label</span>";
                                            }
                                            elseif ($orderStatus->field_label == "Approved"){
                                                echo "<span class='m-badge  m-badge--primary m-badge--wide'>$orderStatus->field_label</span>";
                                            }else{
                                                echo "<span class='m-badge  m-badge--danger m-badge--wide'>$orderStatus->field_label</span>";
                                            }
                                        }
                                        else{
                                            echo "<span>$model->transaction_status</span>";
                                        }
                                        ?>
                                        <p></p>
                                        <span><b>Amount : </b></span>
                                        <span><?php echo $model->amount; ?>
</span>
                                        <!--<p></p>
                                <span><b>Updated Balance : </b></span>
                                <span></span>-->

                                    </div>
                                </div>
                                <!-- END Default Pagination -->
                            </div>
                            <div class="col-lg-6">
                                <!-- Disabled and Active States -->
                                <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                            <span class="m-portlet__head-icon m--hide">
                                                <i class="flaticon-statistics"></i>
                                            </span>
                                                <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                                    <span>Other Details </span>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <span><b>Reference : </b></span>
                                        <?php
                                            $wallet_meta = WalletMetaEntity::model()->findByAttributes(['reference_id' => $model->reference_id]);
                                            if(!empty($wallet_meta)){
                                                echo "<span>$wallet_meta->reference_desc</span>";
                                            }
                                            else{
                                                echo "<span>$model->reference_id</span>";
                                            }?>
                                        <p></p>
                                        <span><b>Reference Number : </b></span>
                                        <span><?php echo $model->reference_num; ?>
</span>
                                        <p></p>
                                        <span><b>Portal : </b></span>
                                        <?php
                                            $wallet_type = Portals::model()->findByAttributes(['portal_id' => $model->portal_id]);
                                            if(!empty($wallet_type)){
                                                echo "<span>$wallet_type->portal_name</span>";
                                            }
                                            else{
                                                echo "<span>$model->portal_id</span>";
                                            }?>
                                        <p></p>
                                        <span><b>Created Date : </b></span>
                                        <span><?php echo $model->created_at; ?>
</span>
                                        <p></p>
                                        <span><b>Modified Date : </b></span>
                                        <span><?php echo $model->modified_at; ?>
</span>
                                    </div>
                                </div>
                                <!-- END Disabled and Active States -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>