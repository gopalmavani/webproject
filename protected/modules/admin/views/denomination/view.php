<?php
/* @var $this CompensationsController */
/* @var $model Compensations */

$this->pageTitle = 'View Pool Plan';
$id = $model->denomination_id;
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Denomination
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
                <?php echo CHtml::link('Create', array('denomination/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php echo CHtml::link('Update', array('denomination/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary'));?>
                <p></p>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            'denomination_id',
                            'denomination_type',
                            'sub_type',
                            'label',
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