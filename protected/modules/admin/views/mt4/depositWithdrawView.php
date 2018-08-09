<?php
/* @var $this Mt4Controller */
/* @var $model ApiDepositWithdraw */

$this->pageTitle = 'View Deposit Withdraw';
$id = $model->id;
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Deposit Withdraw
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right" style="margin-bottom: 1%;">
                <?php echo CHtml::link('Go to list', array('mt4/depositWithdraw'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            'id',
                            'login',
                            'ticket',
                            'symbol',
                            'email',
                            'api_type',
                            'lots',
                            'type',
                            'open_price',
                            'open_time',
                            'close_price',
                            'close_time',
                            'profit',
                            'commission',
                            'agent_commission',
                            'comment',
                            'magic_number',
                            'stop_loss',
                            'take_profit',
                            'swap',
                            'reason',
                            'created_at',
                            'modified_at'
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>

