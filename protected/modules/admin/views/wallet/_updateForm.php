<?php
/* @var $this WalletController */
/* @var $model Wallet */
/* @var $form CActiveForm */
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Update Walllet
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
                        Wallet information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('wallet/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'wallet-form',
                    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                    'enableAjaxValidation'=>false,
                    'htmlOptions' => array(
                        'enctype' => 'multipart/form-data',
                        'name' => 'product-info-form',
                    )
                )); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('transaction_comment') ? 'has-error' : ''; ?>">
                            <?php echo $form->textAreaControlGroup($model, 'transaction_comment', array('size'=>60,'maxlength'=>80, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                                    </div>

                <div class="col-md-6">

                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('transaction_status') ? 'has-error' : ''; ?>">
                            <?php
                                $fieldId = CylFields::model()->findByAttributes(['field_name' => 'transaction_status']);
                                $list = CHtml::listData(CylFieldValues::model()->findAllByAttributes(['field_id' => $fieldId->field_id ]),'predefined_value','field_label');
                                echo $form->dropDownListControlGroup($model, 'transaction_status', $list, [
                                    'prompt' => 'Select Transaction Status',
                                    'class' => 'js-select2 form-control',
                                ]);
                                ?>
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
                    <?php echo CHtml::link('Cancel', array('wallet/admin'),
								array(
									'class' => 'btn btn-default'
								)
							);
							?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>