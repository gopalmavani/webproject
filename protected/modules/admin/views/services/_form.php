<?php
/* @var $this ServicesController */
/* @var $model Services */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.css');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="block">
            <div class="block-content block-content-narrow">
                <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'services-form',
                    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'enctype' => 'multipart/form-data',
                        'name' => 'UserCreate'
                    )
                ));
                ?>
                <?php echo $form->errorSummary($model); ?>
                <div class="row">
                    <!--begin service_name-->
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('service_name') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'service_name', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Service')); ?>
                        </div>
                    </div>
                    <!--end service_name-->

                    <!--begin service description-->
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('service_description') ? 'has-error' : ''; ?> ">
                            <label><?php echo $form->labelEx($model, 'service_description', array('class' => 'control-label')); ?></label>
                            <?php echo $form->textArea($model, 'service_description', array('autofocus' => 'on', 'class' => 'js-summernote form-control', 'placeholder' => 'Service Description')); ?>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <!--end service description-->

                    <!--begin service image-->
                    <div class="col-md-12" style="margin-top:5%">
                        <div class="form-group <?php echo $model->hasErrors('service_image') ? 'has-error' : ''; ?>">
                            <label class="">
                                <?php echo $form->labelEx($model, 'service_image', array('class' => 'control-label')); ?>
                            </label><br/>
                            <?php if(!empty($model->service_image)) { ?>
                                <div class="col-xs-4 m-b-10 image-preview-box-update"  id="imgPreviewBox" >
                                    <img src="<?php echo Yii::app()->baseUrl . $model->service_image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" >
                                </div>
                                <?php echo $form->fileField($model, 'service_image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Service Image')); ?>
                            <?php }else{ ?>
                                <div class="col-xs-4 m-b-10 image-preview-box" id="imgPreviewBox">
                                    <img src="<?php echo Yii::app()->baseUrl . $model->service_image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" >
                                </div><br/>
                                <?php echo $form->fileField($model, 'service_image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Service Image')); ?>
                                <div class="help-block" id="imageTypeError"></div>
                            <?php } ?>
                        </div>
                    </div>
                    <!--end service image-->

                    <!--begin user-->
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('user_id') ? 'has-error' : ''; ?> ">
                            <div class="controls">
                                <?php echo $form->label($model, 'user_id', array('class' => 'control-label')); ?>
                                <?php $list = CHtml::listData(UserInfo::model()->findAll(array("condition"=>"role = 'Employee'")), 'user_id', 'full_name');
                                echo $form->dropDownList($model, 'user_id', $list, array('class' => 'form-control',
                                    'empty' => 'Select Staff member')); ?>
                                <span class="help-block"><?php echo $form->error($model, 'user_id'); ?></span>
                            </div>
                        </div>
                    </div>
                    <!--End user-->

                    <!--Begin Resource -->
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('resource_id') ? 'has-error' : ''; ?> ">
                            <div class="controls">
                                <?php echo $form->label($model, 'resource_id', array('class' => 'control-label')); ?>
                                <?php $list = CHtml::listData(Resources::model()->findAll(array("condition"=>"is_available = '1'")), 'resource_id', 'resource_name');
                                echo $form->dropDownList($model, 'resource_id', $list, array('class' => 'form-control',
                                    'empty' => 'Select Resource'));
                                ?>
                                <span class="help-block"><?php echo $form->error($model, 'resource_id'); ?></span>
                            </div>
                        </div>
                    </div>
                    <!--End resources-->

                    <?php
                    if($model->total_booking == ""){
                        $value = 1;
                    }
                    else{
                        $value = $model->total_booking;
                    }
                    ?>
                    <!--Begin total booking -->
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('total_booking') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'total_booking', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Total no. of bookings','value'=>$value)); ?>
                        </div>
                    </div>
                    <!--End total booking-->


                    <!--begin service_price-->
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('service_price') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'service_price', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Service Price')); ?>
                        </div>
                    </div>
                    <!--end service_price-->

                    <!--begin Category-->
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('category') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'category', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Service Category')); ?>
                        </div>
                    </div>
                    <!--end Category-->

                    <?php
                    $hours = "";
                    $minutes = "";
                    if($model->service_duration != ""){
                        $hours = floor($model->service_duration/ 60);
                        $minutes = ($model->service_duration % 60);
                    } ?>
                    <!--Begin service duration-->
                    <div class="col-md-12" style="margin-left:-1%" id="create_duration">
                        <label class="control-label">Service Duration</label>
                        <div style="margin-top:5px;">
                            <label class="control-label">Hours &nbsp;</label>
                            <select name="Services[hours]" id="Services_hours"  class="form-control"  style="width: 10%;display:-moz-box!important;">
                                <?php for($i=0 ; $i<=23 ; $i++){
                                    if($i == $hours){  ?>
                                        <option selected><?php echo $i; ?></option>
                                    <?php } else { ?>
                                        <option><?php echo $i; ?></option>
                                    <?php   }
                                } ?>
                            </select>
                            &nbsp;&nbsp;&nbsp;
                            <label class="control-label">Minutes &nbsp;</label>
                            <select name="Services[minutes]" id="Services_minutes" class="form-control"  style="width: 10%;display:-moz-box!important;">
                                <?php for($i=00 ; $i<=60 ; $i = $i+5){
                                    if($i == $minutes){ ?>
                                        <option><?php echo $i; ?></option>
                                    <?php } else { ?>
                                        <option><?php echo $i; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <span class="help-block hide" id="duration_error">Invalid Duration</span>
                    </div>
                    <!--End service duration-->


                    <?php
                    $value="checked";
                    if($model->is_display == 0 && $from == "update"){
                        $value = "";
                    } ?>
                    <div class="col-md-12" style="margin-top:10px;margin-left:-1%;">
                        <p></p>
                        <label class="css-input switch switch-primary">
                            Display service on the booking page &nbsp;<input <?php echo $value; ?> type="checkbox" name="Services[is_display]" id="Services_is_display"><span></span>
                        </label>
                    </div>


                </div>
                <div class="row" align="right">
                    <div class="col-md-12">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                            'class' => 'btn btn-primary',
                            'id'=>'submit_button',
                        )); ?>
                        <?php echo CHtml::link('Cancel', array('services/admin'),
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
</div>


<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.js';?>"></script>
<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/dropzonejs/dropzone.js';?>"></script>
<script src="<?php echo Yii::app()->createUrl('../'); ?>/plugins/js/plugins/bootstrap-datetimepicker/moment.min.js"></script>
<script src="<?php echo Yii::app()->createUrl('../'); ?>/plugins/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?php echo Yii::app()->createUrl('../'); ?>/plugins/js/select2.min.js"></script>
<script src="<?php echo Yii::app()->createUrl('../'); ?>/plugins/js/core/jquery.slimscroll.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?&libraries=places&key=AIzaSyBU1sstLYM9RgAjxmJE7bLNZxEiisiDnJI"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/jquery.geocomplete.js"></script>
<script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/logger.js"></script>


<script>
    $("div#myId").dropzone({ url: "/file/post" });

    jQuery(function () {
        App.initHelpers(['datetimepicker']);
        App.initHelpers('slimscroll');
        App.initHelpers('summernote');
    });

    // Upload file preview on Application form
    $("#Services_service_image").on("change", function()
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

    /*$('#submit_button').on("click",function(){
        var hours = $('#Services_hours').val();
        var minutes = $('#Services_minutes').val();
        if(hours == 0 && minutes == 0){
            $('#duration_error').removeClass('hide');
            $('#duration_error').parent().addClass('has-error');
            return false;
        }
    });*/

    /*$('#change_duration').on("click",function () {
       $('#create_duration').removeClass('hide');
       $('#update_duration').addClass('hide');
    });*/
</script>