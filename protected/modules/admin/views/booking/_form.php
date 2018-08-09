<?php
/* @var $this BookingController */
/* @var $model Booking */
/* @var $form CActiveForm */
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Booking
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
                        Booking information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('booking/index'), array('class' => 'btn btn-minw btn-square btn btn-primary')); ?>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'booking-form',
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'name' => 'UserCreate'
            )
        ));
        ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('username') ? 'has-error' : ''; ?> ">
                        <?php echo $form->textFieldControlGroup($model, 'username', array('size' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Full name')); ?>
                        <div id="Booking_username_error" class="custom-error help-block text-right"></div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('email') ? 'has-error' : ''; ?> ">
                        <?php echo $form->textFieldControlGroup($model, 'email', array('size' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'email')); ?>
                        <div id="Booking_email_error" class="custom-error help-block text-right"></div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('mobile_number') ? 'has-error' : ''; ?> ">
                        <?php echo $form->textFieldControlGroup($model, 'mobile_number', array('size' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Mobile number')); ?>
                        <div id="Booking_mobile_number_error" class="custom-error help-block text-right"></div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('address') ? 'has-error' : ''; ?> ">
                        <label class="">
                            <?php echo $form->labelEx($model, 'address', array('class' => 'control-label')); ?>
                        </label>
                        <?php echo $form->textArea($model, 'address', array('size'=>60, 'class' => 'form-control', 'placeholder' => 'Address')); ?>
                        <span class="help-block"><?php echo $form->error($model, 'address'); ?> </span>
                    </div>
                </div>
            </div>


            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('status') ? 'has-error' : ''; ?>">
                        <?php echo $form->dropDownListControlGroup($model, "status", ["pending"=>"Pending","success"=>"success",'cancel'=>'Cancel'], [
                            "class" => "form-control",
                        ]);
                        ?>
                        <span class="help-block"><?php echo $form->error($model, 'status'); ?></span>
                    </div>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                        'class' => 'btn btn-primary',
                    )); ?>
                    <?php echo CHtml::link('Cancel', array('booking/admin'),
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
