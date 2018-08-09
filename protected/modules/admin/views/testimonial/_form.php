<?php
/* @var $this TestimonialController */
/* @var $model Testimonial */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/dropzonejs/dropzone.css');
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Testimonial
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
                        Testimonial information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('testimonial/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'testimonial-form',
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
            )
        ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <div class=" <?php echo $model->hasErrors('Title') ? 'has-error' : ''; ?>">
                        <?php echo $form->textFieldControlGroup($model, 'Title', array('class' => 'form-control')); ?>
                        <span class="help-block"><?php echo $form->error($model, 'Title'); ?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class=" <?php echo $model->hasErrors('description') ? 'has-error' : ''; ?>">
                        <!--<label><?php /*echo $form->labelEx($model, 'description', array('class' => 'control-label')); */?></label>
                        --><?php /*echo $form->textArea($model, 'description', array('class' => 'mysummernote form-control', 'placeholder' => 'Description')); */?>
                        <label class="">
                            <?php echo $form->labelEx($model, 'description', array('class' => 'control-label')); ?>
                        </label>
                        <?php echo $form->textArea($model, 'description', array('size'=>60, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Description')); ?>                        <span class="help-block"><?php echo $form->error($model, 'description'); ?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('media') ? 'has-error' : ''; ?>">
                        <label class="">
                            <?php echo $form->labelEx($model, 'media', array('class' => 'control-label')); ?>
                        </label><br/>
                        <?php if(!empty($model->media)) {  ?>
                            <div class="col-xs-4 m-b-10 image-preview-box-update"  id="imgPreviewBox" >
                                <img src="<?php echo Yii::app()->baseUrl . $model->media; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true"  style="width:250px">
                            </div>
                            <?php echo $form->fileField($model, 'media', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Event Image')); ?>
                        <?php }else{ ?>
                            <div class="col-xs-4 m-b-10 image-preview-box" id="imgPreviewBox">
                                <img src="<?php echo Yii::app()->baseUrl . $model->media; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width:250px">
                            </div><br/>
                            <?php echo $form->fileField($model, 'media', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Event Image')); ?>
                            <div class="help-block" id="imageTypeError"></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                        'class' => 'btn btn-primary',
                    )); ?>
                    <a class="btn btn-default" onclick="history.go(-1);">Cancel</a>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.js';?>"></script>
<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/dropzonejs/dropzone.js';?>"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/plugins/js/select2.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/plugins/js/core/jquery.slimscroll.min.js"></script>


<script>
    jQuery(function () {
        App.initHelpers(['datetimepicker']);
        App.initHelpers('slimscroll');
        App.initHelpers('summernote');
    });

    // Upload file preview on Application form
    $("#testimonial_media").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) {
            $("#imgPreviewBox").css("display","none");
            return;
        } // no file selected, or no FileReader support
        $("#imgPreviewBox").css("display","none");
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function(e){ // set image data
                $("#imagePreview").attr('src', e.target.result);
                $("#imgPreviewBox").css("display","block");
            }
        }
    });


</script>