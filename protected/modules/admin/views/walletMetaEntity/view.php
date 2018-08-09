<?php
/* @var $this WalletMetaEntityController */
/* @var $model WalletMetaEntity */

$this->pageTitle = 'View WalletMeta';
$id = $model->reference_id; 
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View WalletMetaEntity
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('WalletTypeEntity/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
                <?php echo CHtml::link('Create', array('walletMetaEntity/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
                <?php echo CHtml::link('Update', array('walletMetaEntity/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary')); ?>                <p></p>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'htmlOptions' => array('class' => 'table table-striped m-table'),
                    'attributes'=>array(
                    		'reference_id',
		'reference_key',
		'reference_desc',
		'reference_data',
		'created_at',
		'modified_at',
                    ),
                    )); ?>
                </div>
            </div>
            <!--end::Section-->
        </div>
    </div>
</div>

