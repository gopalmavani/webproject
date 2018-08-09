<?php
/* @var $this ResourcesController */
/* @var $model Resources */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.css');
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Resource
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
                        Resource information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('resources/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'resources-form',
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'name' => 'UserCreate'
            )
        ));
        ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <!--begin resource_name-->
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('resource_name') ? 'has-error' : ''; ?>">
                        <?php echo $form->textFieldControlGroup($model, 'resource_name', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Resource name')); ?>
                    </div>
                </div>
                <!--end resource_name-->
                <!--begin resource description-->
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('resource_description') ? 'has-error' : ''; ?> ">
                        <label><?php echo $form->labelEx($model, 'resource_description', array('class' => 'control-label')); ?></label>
                        <?php echo $form->textArea($model, 'resource_description', array('autofocus' => 'on', 'class' => 'mysummernote form-control', 'placeholder' => 'Resource Description')); ?>
                        <span class="help-block"></span>
                    </div>
                </div>
                <!--end resource description-->
                <!--begin resource address-->
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('resource_address') ? 'has-error' : ''; ?> ">
                        <label><?php echo $form->labelEx($model, 'resource_address', array('class' => 'control-label')); ?></label>
                        <?php echo $form->textArea($model, 'resource_address', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Resource address')); ?>
                        <span class="help-block"></span>
                    </div>
                </div>
                <!--end resource address-->
                <?php
                $value = "checked";
                if($model->is_available == 0 && $from == "update"){
                    $value = "";
                }
                ?>
                <div class="col-md-12" style="margin-top:10px;margin-left:-1%;">
                    <p></p>
                    <label class="css-input switch switch-primary">
                        Is resource available? &nbsp;<input <?php echo $value; ?> type="checkbox" name="Resources[is_available]" id="Resources_is_display"><span></span>
                    </label>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                        'class' => 'btn btn-primary',
                    )); ?>
                    <?php echo CHtml::link('Cancel', array('resources/admin'),
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

<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.js';?>"></script>

<script>
    jQuery(function () {
        /*App.initHelpers(['datetimepicker']);
        App.initHelpers('slimscroll');*/
        App.initHelpers('summernote');
    });
    $('.mysummernote').summernote({
        height:'300px'
    });
</script>