<?php
/* @var $this WalletTypeEntityController */
/* @var $model WalletTypeEntity */
/* @var $form CActiveForm */
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                WalletTypeEntity
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
                        WalletTypeEntity information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('walletTypeEntity/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
            </div>
        </div>
        <!--begin::Form-->
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
					'id'=>'wallet-type-entity-form',
					'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                    'enableAjaxValidation'=>false,
                    'htmlOptions' => array(
                        'enctype' => 'multipart/form-data',
                    )
				)); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('wallet_type') ? 'has-error' : ''; ?> ">
                            <?php echo $form->textFieldControlGroup($model, 'wallet_type', array('size'=>60,'maxlength'=>80, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
								'class' => 'btn btn-primary',
							)); ?>
                    <?php echo CHtml::link('Cancel', array('walletTypeEntity/admin'),
								array(
									'class' => 'btn btn-default'
								)
							);
							?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
