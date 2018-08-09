<?php
/* @var $this SlidersController */
/* @var $model Sliders */
/* @var $form CActiveForm */
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Slider
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
                        Slider information
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'sliders-form',
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'name' => 'UserCreate'
            )
        ));
        ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="form-group <?php echo $model->hasErrors('image') ? 'has-error' : ''; ?>">
                        <?php echo $form->labelEx($model, 'image', array('class' => 'control-label')); ?><br/>
                        <?php if(!empty($model->image)) { ?>
                            <div class="col-xs-4 m-b-10 image-preview-box-update"  id="imgPreviewBox" >
                                <img src="<?php echo Yii::app()->baseUrl . $model->image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width:250px">
                            </div>
                            <?php echo $form->fileField($model, 'image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Slider Image', 'style' => 'padding-bottom: 32px;')); ?>
                            <div class="help-block">
                                <?php echo $form->error($model,'image'); ?>
                            </div>
                        <?php }else{ ?>
                            <div class="col-xs-4 m-b-10 image-preview-box" id="imgPreviewBox">
                                <img src="<?php echo Yii::app()->baseUrl . $model->image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width:250px">
                            </div><br/>
                            <?php echo $form->fileField($model, 'image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Slider Image', 'style' => 'padding-bottom: 32px;')); ?>
                            <div class="help-block">
                                <?php echo $form->error($model,'image'); ?>
                            </div>
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
                    <?php echo CHtml::link('Cancel', array('sliders/admin'),
                        array(
                            'class' => 'btn btn-default'
                        )
                    ); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Upload file preview on Application form
    $("#Sliders_image").on("change", function()
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