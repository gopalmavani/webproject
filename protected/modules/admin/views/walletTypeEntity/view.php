<?php
/* @var $this WalletTypeEntityController */
/* @var $model WalletTypeEntity */

$this->pageTitle = 'View WalletType';
$id = $model->wallet_type_id; 
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Wallet Type
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('walletTypeEntity/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
                <?php echo CHtml::link('Create', array('walletTypeEntity/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
                <?php echo CHtml::link('Update', array('walletTypeEntity/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary')); ?>            </div>
            <p></p>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'htmlOptions' => array('class' => 'table'),
                    'attributes'=>array(
                    		'wallet_type_id',
		'wallet_type',
		'created_at',
		'modified_at',
                    ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
