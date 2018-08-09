<?php
/* @var $this DenominationController */
/* @var $model Denomination */
/* @var $form CActiveForm */
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Denomination
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <!--<span class="m-portlet__head-icon m--hide">
                        <i class="la la-gear"></i>
                    </span>-->
                    <h3 class="m-portlet__head-text">
                        Denomination information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('walletTypeEntity/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'pool-plan-form',
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            'enableAjaxValidation'=>false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'name' => 'Denomination-form',
            )
        )); ?>
        <?php //echo $form->errorSummary($model); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <div class="form-group col-md-12">
                        <div class="col-md-6 <?php echo $model->hasErrors('denomination_type') ? 'has-error' : ''; ?>">
                            <label class=control-label"><?php echo $form->labelEx($model,'denomination_type'); ?></label>
                            <?php echo $form->textField($model,'denomination_type',array('size'=>60,'maxlength'=>255, 'class' => 'form-control', 'placeholder' => 'Denomination Type')); ?>
                            <?php echo $form->error($model, 'denomination_type',array('class' => 'help-block')) ?>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6 <?php echo $model->hasErrors('sub_type') ? 'has-error' : ''; ?>">
                            <label class="control-label"><?php echo $form->labelEx($model,'sub_type'); ?></label>
                            <?php echo $form->textField($model,'sub_type',array('size'=>50,'maxlength'=>50, 'class' => 'form-control', 'placeholder' => 'Sub Type')); ?>
                            <?php echo $form->error($model, 'sub_type',array('class' => 'help-block')) ?>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6 <?php echo $model->hasErrors('label') ? 'has-error' : ''; ?>">
                            <label class="control-label"><?php echo $form->labelEx($model,'label'); ?></label>
                            <?php echo $form->textField($model,'label',array('size'=>50,'maxlength'=>50, 'class' => 'form-control', 'placeholder' => 'Label')); ?>
                            <?php echo $form->error($model, 'label',array('class' => 'help-block')) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                        'class' => 'btn btn-primary col-md-offset-2',
                        'id' => 'create_denomination'
                    )); ?>
                    <?php echo CHtml::link('Cancel', array('WalletTypeEntity/admin'),
                        array(
                            'class' => 'btn btn-default'
                        )
                    );
                    ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>