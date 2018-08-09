<?php
/* @var $this EventsController */
/* @var $model ServiceProvider */
/* @var $form CActiveForm */

$this->pageTitle = '<span lang="en">Service Provider</span>';
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/ion-rangeslider/css/ion.rangeSlider.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/ion-rangeslider/css/ion.rangeSlider.skinHTML5.css');
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Edit Service Provider
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
                <span class="m-portlet__head-icon m--hide">
                    <i class="la la-gear"></i>
                </span>
                    <h3 class="m-portlet__head-text">
                        Service Provider information
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'ServiceProvider-form',
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'name' => 'UserCreate',
                'class' => "m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed",
            )
        ));
        ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-lg-5">
                    <div class="<?php echo $model->hasErrors('name') ? 'has-error' : ''; ?>">
                        <?php echo $form->textFieldControlGroup($model, 'name', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="col-lg-5" style="margin-left:15%">
                    <div class=" <?php echo $model->hasErrors('email') ? 'has-error' : ''; ?>">
                        <?php echo $form->textFieldControlGroup($model, 'email', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-5">
                    <div class="<?php echo $model->hasErrors('image') ? 'has-error' : ''; ?>">
                        <label class="">
                            <?php echo $form->labelEx($model, 'image', array('class' => 'control-label')); ?>
                        </label><br/>
                        <?php if(!empty($model->image)) {
                            $mysql = "select image from service_provider";
                            $result = Yii::app()->db->createCommand($mysql)->queryAll();
                            $model->image = $result[0]['image'];?>
                            <div class="col-xs-4 m-b-10 image-preview-box-update"  id="imgPreviewBox" >
                                <img src="<?php echo Yii::app()->baseUrl . $model->image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width: 250px;">
                            </div>
                            <?php echo $form->fileField($model, 'image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Service Provider Image')); ?>
                        <?php }else{ ?>
                            <div class="col-xs-4 m-b-10 image-preview-box" id="imgPreviewBox">
                                <img src="<?php echo Yii::app()->baseUrl . $model->image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width: 250px;">
                            </div><br/>
                            <?php echo $form->fileField($model, 'image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Service Provider Image')); ?>
                            <div class="help-block" id="imageTypeError"></div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-lg-5" style="margin-left:15%">
                    <div class="<?php echo $model->hasErrors('description') ? 'has-error' : ''; ?> ">
                        <label><?php echo $form->labelEx($model, 'description', array('class' => 'control-label')); ?></label>
                        <?php echo $form->textArea($model, 'description', array('autofocus' => 'on', 'class' => 'js-summernote form-control', 'placeholder' => 'Event Description')); ?>
                        <span class="help-block"></span>
                    </div>
                </div>

                <div class="col-lg-5" style="margin-top:-6%">
                    <div class=" <?php echo $model->hasErrors('phone_no') ? 'has-error' : ''; ?>">
                        <?php echo $form->textFieldControlGroup($model, 'phone_no', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-12">
                    <h3 lang="en" class="push">Select opening hours</h3>
                    <span class="push" lang="en">Please check the day off days.</span>
                    <ul class="list-group">
                        <?php
                        $selecteddays = array();
                        $sql = "SELECT day FROM business_openings_hours";
                        $result = Yii::app()->db->createCommand($sql)->queryAll();
                        if(!empty($result)){
                            foreach ($result as $key=>$myday){
                                array_push($selecteddays,$myday['day']);
                            }
                        }
                        $days = ["monday","tuesday","wednesday","thursday","friday","saturday","sunday"];

                        if(!empty($selecteddays)) {
                            foreach ($days as $key => $day) {
                                if (in_array($day, $selecteddays)) {
                                    ?>
                                    <li class="list-group-item">
                                        <div style="width:50%">
                                            <span><input type="checkbox" name="<?= $day; ?>" id="<?= $day; ?>" checked><?= ucfirst($day); ?></span>
                                            <p></p>
                                            <input type="text" class="myslider" name="<?= $day; ?>_slider" id="<?= $day ?>_slider" value=""/>
                                            <span id="<?= $day; ?>" class="push hide" align="center">Day Off</span>
                                        </div>
                                    </li>
                                <?php } else { ?>
                                    <li class="list-group-item">
                                        <div style="width:50%">
                                            <span><input type="checkbox" name="<?= $day; ?>" id="<?= $day; ?>"><?= ucfirst($day); ?></span>
                                            <p></p>
                                            <input type="text" class="myslider" name="<?= $day; ?>_slider" id="<?= $day ?>_slider" value=""/>
                                            <span id="<?= $day; ?>" class="push hide" align="center">Day Off</span>
                                        </div>
                                    </li>
                                <?php }
                            }
                        } else {
                            foreach ($days as $key => $day) {
                                if($day != "saturday" && $day != "sunday"){?>
                                    <li class="list-group-item">
                                        <div style="width:50%">
                                            <span><input type="checkbox" name="<?= $day; ?>" id="<?= $day; ?>" checked><?= ucfirst($day); ?></span>
                                            <p></p>
                                            <input type="text" class="myslider" name="<?= $day; ?>_slider" id="<?= $day ?>_slider" value=""/>
                                            <span id="<?= $day; ?>" class="push hide" align="center">Day Off</span>
                                        </div>
                                    </li>
                                <?php   } else { ?>
                                    <li class="list-group-item">
                                        <div style="width:50%">
                                            <span><input type="checkbox" name="<?= $day; ?>" id="<?= $day; ?>" ><?= ucfirst($day); ?></span>
                                            <p></p>
                                            <input type="text" class="myslider" name="<?= $day; ?>_slider" id="<?= $day ?>_slider" value=""/>
                                            <span id="<?= $day; ?>" class="push hide" align="center">Day Off</span>
                                        </div>
                                    </li>
                                <?php }
                            }
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
            <div class="m-form__actions m-form__actions--solid">
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-10" align="right" lang="en">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                            'class' => 'btn btn-info',
                            'id' => "submit_button",
                        )); ?>
                        <button class="btn btn-default" onclick="history.go(-1);">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
        <!--end::Form-->
    </div>
</div>



<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/ckeditor.js', CClientScript::POS_HEAD);
?>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js'; ?>"></script>
<?php
$sql  = "SELECT * from business_openings_hours";
$result = Yii::app()->db->createCommand($sql)->queryAll();
if(!empty($result)){
    ?>
    <input type="hidden" id="hasvalue" value="yes">
    <?php
    foreach ($result as $key=>$value){
        $timings = explode(";",$value['timing']);
        $from = $timings[0];
        $to = $timings[1];?>
        <input type="hidden" id="from_<?php echo $value['day'] ?>" value="<?php echo $from; ?>">
        <input type="hidden" id="to_<?php echo $value['day'] ?>" value="<?php echo $to; ?>">
        <?php
    }
}
?>

<script>
    CKEDITOR.editorConfig = function (config) {
        config.language = 'es';
        config.uiColor = '#F7B42C';
        config.height = 300;
        config.toolbarCanCollapse = true;

    };
    CKEDITOR.replace('ServiceProvider_description');


    // Upload file preview on Application form
    $("#ServiceProvider_image").on("change", function()
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


    var weekday = ["monday","tuesday","wednesday","thursday","friday","saturday","sunday"];
    var myfroid = "";
    var mytoid = "";
    var from = "";
    var to = "";
    var mysliderid = "";

    var hasvalue = $('#hasvalue').val();

    if(hasvalue == "yes"){
        $.each( weekday, function( key, value ) {
            myfroid = "#from_"+value;
            mytoid = "#to_"+value;
            from = $(myfroid).val();
            to = $(mytoid).val();
            mysliderid = "#"+value+"_slider";
            $(mysliderid).ionRangeSlider({
                type: "double",
                min: 00,
                max: 24,
                from : from,
                to : to,
                step: 01,
                postfix:":00",
            });
        });
    }
    else{
        $(".myslider").ionRangeSlider({
            type: "double",
            min: 00,
            max: 24,
            from : 09,
            to : 18,
            step: 01,
            postfix:":00",
        });
    }

    $(document).ready(function () {
        var weekday = ["monday","tuesday","wednesday","thursday","friday","saturday","sunday"];
        $.each( weekday, function( key, value ) {
            if( $("input[name="+value+"]:checked").length < 1){
                $('.js-irs-'+key).css('display',"none");
                $('span#'+value).removeClass('hide');
            }
        });
        /*var atLeastOneIsChecked1 = $('input[name="saturday"]:checked').length;
        var atLeastOneIsChecked2= $('input[name="sunday"]:checked').length;
        if(atLeastOneIsChecked1 < 1){
            $('.js-irs-5').css('display',"none");
            $('span#saturday').removeClass('hide');
        }
        if(atLeastOneIsChecked2 < 1){
            $('.js-irs-6').css('display',"none");
            $('span#sunday').removeClass('hide');
        }*/
    });

    $("input[type=checkbox]").on("change",function(){
        var checkvalue = $(this).attr('name');
        if(checkvalue == "monday"){
            if ($('#monday').is(":checked")){
                $('.js-irs-0').css('display',"block");
                $('span#monday').addClass('hide');
            }
            else{
                $('.js-irs-0').css('display',"none");
                $('span#monday').removeClass('hide');
            }
        }
        if(checkvalue == "tuesday"){
            if ($('#tuesday').is(":checked")){
                $('.js-irs-1').css('display',"block");
                $('span#tuesday').addClass('hide');
            }
            else{
                $('.js-irs-1').css('display',"none");
                $('span#tuesday').removeClass('hide');
            }
        }
        if(checkvalue == "wednesday"){
            if ($('#wednesday').is(":checked")){
                $('.js-irs-2').css('display',"block");
                $('span#wednesday').addClass('hide');
            }
            else{
                $('.js-irs-2').css('display',"none");
                $('span#wednesday').removeClass('hide');
            }
        }
        if(checkvalue == "thursday"){
            if ($('#thursday').is(":checked")){
                $('.js-irs-3').css('display',"block");
                $('span#thursday').addClass('hide');
            }
            else{
                $('.js-irs-3').css('display',"none");
                $('span#thursday').removeClass('hide');
            }
        }
        if(checkvalue == "friday"){
            if ($('#friday').is(":checked")){
                $('.js-irs-4').css('display',"block");
                $('span#friday').addClass('hide');
            }
            else{
                $('.js-irs-4').css('display',"none");
                $('span#friday').removeClass('hide');
            }
        }
        if(checkvalue == "saturday"){
            if ($('#saturday').is(":checked")){
                $('.js-irs-5').css('display',"block");
                $('span#saturday').addClass('hide');
            }
            else{
                $('.js-irs-5').css('display',"none");
                $('span#saturday').removeClass('hide');
            }
        }
        if(checkvalue == "sunday"){
            if ($('#sunday').is(":checked")){
                $('.js-irs-6').css('display',"block");
                $('span#sunday').addClass('hide');
            }
            else{
                $('.js-irs-6').css('display',"none");
                $('span#sunday').removeClass('hide');
            }
        }

    });

</script>