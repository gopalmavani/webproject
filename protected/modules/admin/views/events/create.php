<?php
/* @var $this EventsController */
/* @var $model Events */
/* @var $form CActiveForm */

$this->pageTitle = "<span lang='en'>Create Event</span>";
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/dropzonejs/dropzone.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/colorpicker/css/colorpicker.css');
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/colorpicker/css/layout.css');

$firstsql1 = "SELECT * from settings where module_name='events' and settings_key = 'event_host_role'";
$result1 = Yii::app()->db->createCommand($firstsql1)->queryAll();
if(!empty($result1)){
    $rolevalue = $result1[0]['value'];
}
else{
    $rolevalue = "Employee";
}
?>
<style>
    .pac-container:after {
        /* Disclaimer: not needed to show 'powered by Google' if also a Google Map is shown */

        background-image: none !important;
        height: 0px;
    }
    /*.image-preview{
        display:none;
    }*/

</style>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Event/Service
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
                        Event/Service information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('Events/view'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'event-form',
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'name' => 'event-form',
            ),
        )); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="form-material has-error">
                    <p id="productAddError" class="help-block " style="display: none;" lang="en">Event already saved</p>
                </div>
                <!--begin event title-->
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('event_title') ? 'has-error' : ''; ?>">
                        <?php echo $form->textFieldControlGroup($model, 'event_title', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Event Title')); ?>
                        <span class="help-block hide" style="color: #a94442" id="titleerror">Please enter event title</span>
                    </div>
                </div>
                <!--end event title-->

                <!--Begin Type-->
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('type') ? 'has-error' : ''; ?>">
                        <?php echo $form->dropDownListControlGroup($model, "type", ["event"=>"Event","service"=>"Service"], [
                            "class" => "form-control",
                        ]);
                        ?>
                        <span class="help-block"><?php echo $form->error($model, 'type'); ?></span>
                    </div>
                </div>
                <!--End Type-->

                <!--begin event description-->
                <div class="col-md-12">
                    <div class="form-group <?php echo $model->hasErrors('event_description') ? 'has-error' : ''; ?> ">
                        <label><?php echo $form->labelEx($model, 'event_description', array('class' => 'control-label')); ?></label>
                        <?php echo $form->textArea($model, 'event_description', array('autofocus' => 'on', 'class' => 'mysummernote form-control', 'placeholder' => 'Event Description')); ?>
                        <span class="help-block"></span>
                    </div>
                </div>
                <!--end event description-->
                <div class="col-md-12">
                    <p></p><p></p>
                    <div class="col-md-6">
                        <div id="eventdates">
                            <?php if($from == "create"){ ?>
                                <!--begin event type-->
                                <div class="form-group <?php echo $model->hasErrors('event_type') ? 'has-error' : ''; ?>">
                                    <?php
                                    echo $form->dropDownListControlGroup($model, "event_type", ["single"=>"Single Event","regular"=>"Regular Event","specific"=>"Specific days"], [
                                        "selected"=>"single",
                                        "class" => "form-control",
                                    ]);
                                    ?>
                                </div>
                                <!--end event type-->
                                <!--Begin radio buttons for singleday/multiday-->
                                <div class="hide" id="singlemultiradio">
                                    <div class="form-group">
                                        <input type="radio" class="singlemulti" id="single" name="day" value="single" checked>Single Day Event
                                        &nbsp;&nbsp;
                                        <input type="radio" name="day" class="singlemulti" id="multi" value="multi">Multi Day Event
                                    </div>
                                </div>
                                <!--End radio buttons for singleday/multiday-->
                                <!--Begin recurring-->
                                <?php $sql = "SELECT * from recurring";
                                $result = Yii::app()->db->createCommand($sql)->queryAll(); ?>
                                <div class="hide" id="recurringsection">
                                    <div class="form-group">
                                        <label for="recurring" class="control-label">Recurring</label>
                                        <select name="Events[recurring_span]" id="recurring" class="form-control">
                                            <?php
                                            foreach ($result as $key=>$value){
                                                switch ($value['recurring_span']){
                                                    case "Every day":
                                                    case "Weekdays only":
                                                    case "Weekends only":
                                                    case "Once a week":
                                                    case "Once a fortnight":
                                                    case "Monthly(repeat by day)":
                                                    case "Every two months(repeat by day)":
                                                    case "Every Quarter (repeat by day)":
                                                    case "Every six weeks":
                                                    case "Twice a Year (repeat by day)":
                                                        ?>
                                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['recurring_span']; ?></option>
                                                    <?php   }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <!--End recurring-->

                                <!--Begin start time-->
                                <div class="form-group" id="starttime">
                                    <div>
                                        <label for="event_start" id="startdate">Start Time</label>
                                    </div>
                                    <div>
                                        <div class='input-group date' id='event_start'>
                                            <input type='text' class="form-control"  name="Events[event_start]" id="input_event_start" />
                                            <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <span class="help-block hide" id="startdateerror">Please enter start date</span>
                                    </div>
                                </div>
                                <!--end start time-->

                                <!--begin end time for single event type-->
                                <?php
                                $hours = "";
                                $minutes = "";
                                if($model->service_duration != ""){
                                    $hours = floor($model->service_duration/ 60);
                                    $minutes = ($model->service_duration % 60);
                                } ?>
                                <div class="form-group" id="singletypeendtime" style="margin-top: 36px">
                                    <label class="control-label">Duration</label>
                                    <div style="margin-top:5px;">
                                        <label>Hours &nbsp;</label>
                                        <select name="Events[hours]" id="Events_hours"  class="form-control"  style="width: 20%;display:-moz-box!important;">
                                            <?php for($i=1 ; $i<=23 ; $i++){
                                                if($i == $hours){  ?>
                                                    <option selected><?php echo $i; ?></option>
                                                <?php } else { ?>
                                                    <option><?php echo $i; ?></option>
                                                <?php   }
                                            } ?>
                                        </select>

                                        <label>Minutes &nbsp;</label>
                                        <select name="Events[minutes]" id="Events_minutes" class="form-control"  style="width: 20%;display:-moz-box!important;">
                                            <?php for($i=00 ; $i<=60 ; $i = $i+5){
                                                if($i == $minutes){ ?>
                                                    <option selected><?php echo $i; ?></option>
                                                <?php } else { ?>
                                                    <option><?php echo $i; ?></option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <span class="help-block hide" id="duration_error">Invalid Duration</span>
                                </div>
                                <!--stop end time for single event type-->

                                <!--begin end time-->
                                <div class="form-group" id="endtime">
                                    <div style="margin-top: 36px">
                                        <label for="event_end" id="enddate">End Time</label>
                                    </div>
                                    <div>
                                        <div class='input-group date' id='event_end'>
                                            <input type='text' class="form-control" name="Events[event_end]" id="input_event_end" />
                                            <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <span class="help-block hide" id="errormsg">Please enter end date</span>
                                    </div>
                                </div>
                                <!--End end time-->

                                <!--Begin Date field for specific days type of event-->
                                <div class="form-group hide" id="specific_field" style="margin-top:5%">
                                    <div>
                                        <label for="event_end">Dates</label>
                                    </div>
                                    <div>
                                        <div class='input-group date' id='specific_start_date'>
                                            <input type='text' class="form-control" name="Events[specific]" />
                                            <span class="input-group-addon"  style="width:37px!important;padding-top:8px!important;">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group hide" id="specific_field_time" style="margin-top: 5%!important;">
                                    <div>
                                        <label for="event_end">Time</label>
                                    </div>
                                    <div>
                                        <div class='input-group date' id='specific_type_time'>
                                            <input type='text' class="form-control" name="Events[specific_time]" />
                                            <span class="input-group-addon"  style="width:37px!important;padding-top:8px!important;">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                                <!--End Date field for specific days type of event-->
                            <?php } else { ?>
                                <!--start time for update-->
                                <div class="form-group">
                                    <div>
                                        <label for="event_start">Start Time</label>
                                    </div>
                                    <div>
                                        <div class='input-group date' id='event_start_update'>
                                            <input type='text' class="form-control"  name="Events[event_start]" value="<?php echo date('m/d/Y h:i A',strtotime(str_replace('/','-',$model->event_start))) ?>" />
                                            <span class="input-group-addon" style="width: 37px !important;padding-top: 8px!important;">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                             </span>
                                        </div>
                                    </div>
                                </div>
                                <!--start time for update-->
                                <!--End time for update-->
                                <div class="form-group">
                                    <div>
                                        <label for="event_end">End Time</label>
                                    </div>
                                    <div>
                                        <div class='input-group date' id='event_end_update'>
                                            <input type='text' class="form-control" name="Events[event_end]" value="<?php echo date('m/d/Y h:i A',strtotime(str_replace('/','-',$model->event_end))) ?>" />
                                            <span class="input-group-addon" style="width: 37px !important;padding-top: 8px!important;">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!--End time for update-->
                            <?php } ?>
                        </div>
                        <!--Begin service duration-->
                        <div id="duration" class="hide">
                            <?php
                            $hours = "";
                            $minutes = "";
                            if($model->service_duration != ""){
                                $hours = floor($model->service_duration/ 60);
                                $minutes = ($model->service_duration % 60);
                            } ?>
                            <div class="col-md-12" style="margin-left:-1%" id="create_duration">
                                <label class="control-label">Service Duration</label>
                                <div style="margin-top:5px;">
                                    <label>Hours &nbsp;</label>
                                    <select name="Services[hours]" id="Services_hours"  class="form-control"  style="width: 20%;display:-moz-box!important;">
                                        <?php for($i=0 ; $i<=23 ; $i++){
                                            if($i == $hours){  ?>
                                                <option selected><?php echo $i; ?></option>
                                            <?php } else { ?>
                                                <option><?php echo $i; ?></option>
                                            <?php   }
                                        } ?>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>Minutes &nbsp;</label>
                                    <select name="Services[minutes]" id="Services_minutes" class="form-control"  style="width: 20%;display:-moz-box!important;">
                                        <?php for($i=00 ; $i<=60 ; $i = $i+5){
                                            if($i == $minutes){
                                                if($i == 0 ){?>
                                                    <option>1</option>
                                                <?php } else{ ?>
                                                    <option selected><?php echo $i; ?></option>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <option><?php echo $i; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                                <span class="help-block hide" id="duration_error">Invalid Duration</span>
                            </div>

                            <div class="form-group mystarttime hide" id="mystarttime">
                                <div>
                                    <label for="event_start" id="mystartdate">Start Time</label>
                                </div>
                                <div>
                                    <div class='input-group date' id='my_event_start'>
                                        <input type='text' class="form-control"  name="Events[duration_event_start]" id="my_input_event_start" value="<?php echo date('m/d/Y h:i A',strtotime(str_replace('/','-',$model->event_start))); ?>"/>
                                        <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group mystarttime hide" id="myendtime">
                                <div>
                                    <label for="event_start" id="myenddate">End Time</label>
                                </div>
                                <div>
                                    <div class='input-group date' id='my_event_end'>
                                        <input type='text' class="form-control"  name="Events[duration_event_end]" id="my_input_event_end" value="<?php echo date('m/d/Y h:i A',strtotime(str_replace('/','-',$model->event_end))); ?>" />
                                        <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!--End service duration-->

                        <?php if(!empty($model->event_title) && $from == "update"){ ?>
                            <div class="col-md-12">
                                <p></p>
                                <label>Apply this chages to all events of this group &nbsp;&nbsp;<input type="checkbox" name="Events[applyall]" id="applyall"></label>
                            </div>
                        <?php } ?>

                        <!--Begin invite users-->
                        <div style="margin-top:16%">
                            <label style="font-size:16px;">Invite users</label>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label id="check_box">Select all users &nbsp<input type="checkbox" name ="user" id="all_user"></label>
                                </div>
                            </div>
                            <div class="form-group"  id="user">
                                <div class="col-md-12">
                                    <?php
                                    $usersList = CHtml::listData(UserInfo::model()->findAll(["order" => "full_name"]), "user_id",
                                        function ($data){
                                            return "{$data->first_name}  {$data->last_name}";
                                        });
                                    echo $form->dropDownListControlGroup($model, "user_id", $usersList, [
                                        "prompt" => "Select User",
                                        "class" => "js-select2-multiple form-control",
                                        "multiple" => "multiple"
                                    ]);
                                    ?>
                                    <div class="help-block" id="user_msg"></div>
                                </div>
                            </div>
                        </div>
                        <!--Begin invite users-->

                    </div>
                    <div class="col-md-6">
                        <!--begin event url-->
                        <div>
                            <div class="form-group <?php echo $model->hasErrors('event_url') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'event_url', array('size' => 255, 'maxlength' => 255, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Event Url')); ?>
                            </div>
                        </div>
                        <!--end event url-->
                        <!--begin event location-->
                        <div>
                            <div class="form-group <?php echo $model->hasErrors('event_location') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'event_location', array('size' => 255, 'maxlength' => 255, 'autofocus' => 'on','id' => 'event_location','class' => 'form-control', 'placeholder' => 'Event Location')); ?>
                            </div>
                        </div>
                        <!--end event location-->
                        <!--Begin event host-->
                        <div>
                            <div class="form-group <?php echo $model->hasErrors('event_host') ? 'has-error' : ''; ?>">
                                <?php
                                $usersList = CHtml::listData(UserInfo::model()->findAll(["condition"=>"role = "."'$rolevalue'","order" => "full_name"]), "user_id",
                                    function ($data){
                                        return "{$data->first_name}  {$data->last_name}";
                                    });
                                echo $form->dropDownListControlGroup($model, "event_host", $usersList, [
                                    "prompt" => "Select Host",
                                    "class" => "js-select2 form-control",
                                ]);
                                ?>
                            </div>
                            <?php if(empty($usersList)){ ?>
                                <span><b>Note: </b>You must a have user of role of <?php echo "'$rolevalue'"; ?> to select event host.</span>
                                <span><br />You can change this role in settings.</span>
                            <?php } ?>
                        </div>
                        <!--end event host-->

                        <!--Begin resource id-->
                        <div style="margin-top:10%">
                            <div class="form-group <?php echo $model->hasErrors('resource_id') ? 'has-error' : ''; ?>">
                                <?php
                                $usersList = CHtml::listData(Resources::model()->findAll(["order" => "resource_name"]), "resource_id",
                                    function ($data){
                                        return "{$data->resource_name}";
                                    });
                                echo $form->dropDownListControlGroup($model, "resource_id", $usersList, [
                                    "prompt" => "Select Resource",
                                    "class" => "form-control",
                                ]);
                                ?>
                            </div>
                        </div>
                        <!--End resource id-->

                        <!--Begin color-->
                        <div style="margin-top:10%">
                            <span><b>Note: </b>Color is using for display event in calendarview & booking display for that event using selected colour</span>
                            <div class="form-group <?php echo $model->hasErrors('color') ? 'has-error' : ''; ?>">
                                <?php if(!empty($model->color)){
                                    echo $form->textFieldControlGroup($model, 'color', array('size' => 255, 'maxlength' => 255, 'autofocus' => 'on','id' => 'color','class' => 'form-control', 'placeholder' => 'Event Color' ,'value' => $model->color));
                                } else {
                                    echo $form->textFieldControlGroup($model, 'color', array('size' => 255, 'maxlength' => 255, 'autofocus' => 'on','id' => 'color','class' => 'form-control', 'placeholder' => 'Event Color' ,'value' => 'ef963f'));
                                } ?>
                                <span class="help-block hide" style="color: #a94442" id="colorerror">Please enter color</span>
                            </div>
                        </div>
                        <!--End color-->
                    </div>
                </div>

                <!--begin event image-->
                <div class="col-md-12" style="margin-top:0%">
                    <div class="form-group <?php echo $model->hasErrors('event_image') ? 'has-error' : ''; ?>">
                        <label class="">
                            <?php echo $form->labelEx($model, 'event_image', array('class' => 'control-label')); ?>
                        </label><br/>
                        <?php if(!empty($model->event_image)) {  ?>
                            <div class="col-xs-4 m-b-10 image-preview-box-update"  id="imgPreviewBox" >
                                <img src="<?php echo Yii::app()->baseUrl . $model->event_image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true"  style="width:250px">
                            </div>
                            <?php echo $form->fileField($model, 'event_image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Event Image')); ?>
                        <?php }else{ ?>
                            <div class="col-xs-4 m-b-10 image-preview-box" id="imgPreviewBox">
                                <img src="<?php echo Yii::app()->baseUrl . $model->event_image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width:250px">
                            </div><br/>
                            <?php echo $form->fileField($model, 'event_image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Event Image')); ?>
                            <div class="help-block" id="imageTypeError"></div>
                        <?php } ?>
                    </div>
                </div>
                <!--end event image-->
                <div class="col-md-12">
                    <hr>
                    <h3>Ticket Details</h3>
                    <div class="col-md-6">
                        <?php if(empty($model->booking_start_date)){?>
                            <!--begin booking start date-->
                            <div class="form-group" id="bookingstartdate" style="margin-top:10%">
                                <div class="col-md-12">
                                    <label for="event_end">Booking start date </label>
                                </div>
                                <div class="col-md-12">
                                    <div class='input-group date' id='booking_start_date'>
                                        <input type='text' class="form-control" name="Events[booking_start_date]" />
                                        <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--End booking start date-->
                        <?php } else { ?>
                            <!--begin booking start date update-->
                            <div class="form-group" id="bookingstartdateupdate" style="margin-top:10%">
                                <div class="col-md-12">
                                    <label for="event_end">Booking start date </label>
                                </div>
                                <div class="col-md-12">
                                    <div class='input-group date' id='booking_start_date_update'>
                                        <input type='text' class="form-control" name="Events[booking_start_date]" value="<?php echo date('m/d/Y h:i A',strtotime(str_replace('/','-',$model->booking_start_date))) ?>" />
                                        <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <!--End booking start date update-->
                        <?php } ?>
                        <!--begin total ticketing-->
                        <div class="col-md-12" style="margin-top:3%">
                            <div class="form-group <?php echo $model->hasErrors('total_tickets') ? 'has-error' : ''; ?>">
                                <?php if(!empty($model->total_tickets)){
                                    echo $form->textFieldControlGroup($model, 'total_tickets', array('label'=>'Maximum number of tickets available for this service','size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Total number of tickets','value'=>$model->total_tickets));
                                } else {
                                    echo $form->textFieldControlGroup($model, 'total_tickets', array('label'=>'Maximum number of tickets available for this service','size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Total number of tickets','value'=>'1'));
                                } ?>
                            </div>
                        </div>
                        <!--end total ticketing-->
                        <!--begin max_num_bookings-->
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('max_num_bookings') ? 'has-error' : ''; ?>">
                                <?php if(!empty($model->max_num_bookings)){
                                    echo $form->textFieldControlGroup($model, 'max_num_bookings', array('label'=>'Maximum number of bookings single user can do','size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Max number of bookings per person','value'=>$model->max_num_bookings));
                                } else {
                                    echo $form->textFieldControlGroup($model, 'max_num_bookings', array('label'=>'Maximum number of bookings single user can do','size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Max number of bookings per person','value'=>'1'));
                                } ?>
                            </div>
                        </div>
                        <!--end max_num_bookings-->
                        <!--begin price-->
                        <div class="col-md-12" style="margin-top:2%;margin-left:-3%;">
                            <div class="col-md-6">
                                <div class="form-group <?php echo $model->hasErrors('price') ? 'has-error' : ''; ?>">
                                    <?php if(!empty($model->price)){
                                        echo $form->textFieldControlGroup($model, 'price', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'price per ticket','value'=>$model->price));
                                    } else {
                                        echo $form->textFieldControlGroup($model, 'price', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'price per ticket','value'=>'0'));
                                    } ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <?php
                                $usersList = CHtml::listData(Denomination::model()->findAll(["order" => "denomination_type"]), "denomination_id","sub_type",
                                    function ($data){
                                        return "{$data->sub_type}";
                                    });
                                echo $form->dropDownListControlGroup($model, "denomination_id", $usersList, [
                                    "class" => "form-control",
                                ]);
                                ?>
                            </div>
                        </div>
                        <!--end price-->

                        <!--begin credits-->
                        <!--<div class="col-md-12" style="margin-top:5%">
                            <div class="form-group <?php /*echo $model->hasErrors('credits') ? 'has-error' : ''; */?>">
                                <?php /*if(!empty($model->credits)){
                                    echo $form->textFieldControlGroup($model, 'credits', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Credits','value'=>$model->credits));
                                } else {
                                    echo $form->textFieldControlGroup($model, 'credits', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Credits','value'=>'0'));
                                } */?>
                            </div>
                        </div>-->
                        <!--end credits-->

                    </div>
                    <div class="col-md-6" style="margin-top:5%">
                        <!--begin coupon code-->
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('coupon_code') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'coupon_code', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Coupon code')); ?>
                            </div>
                        </div>
                        <!--end coupon code-->
                        <?php if(empty($model->coupon_start_date) && empty($model->coupon_end_date)){ ?>
                            <!--begin coupon start date-->
                            <div class="form-group" id="couponstartdate">
                                <div class="col-md-12">
                                    <label for="event_end">Coupon start date </label>
                                </div>
                                <div class="col-md-12">
                                    <div class='input-group date' id='coupon_start_date'>
                                        <input type='text' class="form-control" name="Events[coupon_start_date]" />
                                        <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <!--End coupong start date-->
                            <!--begin booking coupon end date-->
                            <div class="form-group" id="couponenddate" style="margin-top:27%">
                                <div class="col-md-12">
                                    <label for="event_end">Coupon end date </label>
                                </div>
                                <div class="col-md-12">
                                    <div class='input-group date' id='coupon_end_date'>
                                        <input type='text' class="form-control" name="Events[coupon_end_date]" />
                                        <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <!--end  coupon end date-->
                        <?php } else { ?>
                            <!--begin coupon start date update-->
                            <div class="form-group" id="couponstartdateupdate">
                                <div class="col-md-12">
                                    <label for="event_end">Coupon start date </label>
                                </div>
                                <div class="col-md-12">
                                    <div class='input-group date' id='coupon_start_date_update'>
                                        <input type='text' class="form-control" name="Events[coupon_start_date]" value="<?php echo date('m/d/Y h:i A',strtotime(str_replace('/','-',$model->coupon_start_date))) ?>" />
                                        <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <!--End coupong start date update-->
                            <!--begin coupon end date update-->
                            <div class="form-group" id="couponenddateupdate" style="margin-top:34%">
                                <div class="col-md-12">
                                    <label for="event_end">Coupon end date </label>
                                </div>
                                <div class="col-md-12">
                                    <div class='input-group date' id='coupon_end_date_update'>
                                        <input type='text' class="form-control" name="Events[coupon_end_date]" value="<?php echo date('m/d/Y h:i A',strtotime(str_replace('/','-',$model->coupon_end_date))) ?>"/>
                                        <span class="input-group-addon" style="width:37px!important;padding-top:8px!important;">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <!--end  coupon end date update-->
                        <?php } ?>

                        <div class="col-md-12"  style="margin-top:5%;">
                            <div class="form-group <?php echo $model->hasErrors('hours_before_registration') ? 'has-error' : ''; ?>" style="width:80%;">
                                <?php echo $form->textFieldControlGroup($model, 'hours_before_registration', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'No. of hours before which user can registration')); ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <h3>Cancellation details</h3>
                    <div class="col-md-6" style="margin-top:3%;">
                        <div class="col-md-12">
                            <?php
                            if($model->is_cancel == 1){
                                $value = "checked";
                            }
                            else{
                                $value = "";
                            }

                            ?>
                            <label class="m-checkbox m-checkbox--check-bold m-checkbox--state-success">
                                <input type="checkbox" id="Events_is_cancel" name="Events[is_cancel]" <?= $value; ?>>Is cancellation allowed?
                                <span></span>
                            </label>
                        </div>

                        <div class="col-md-12"  style="margin-top:5%;">
                            <div class="form-group <?php echo $model->hasErrors('hours_before_cancel') ? 'has-error' : ''; ?>" style="width:80%;">
                                <?php echo $form->textFieldControlGroup($model, 'hours_before_cancel', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'No. of hours before which user can cancel')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('cancellation_rules') ? 'has-error' : ''; ?> ">
                                <label><?php echo $form->labelEx($model, 'cancellation_rules', array('class' => 'control-label')); ?></label>
                                <?php echo $form->textArea($model, 'cancellation_rules', array('autofocus' => 'on', 'class' => 'mysummernote form-control', 'placeholder' => 'cancellation rules')); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php /*if($model->is_notification == 1) {
                    $value = "checked";
                 }else {
                    $value = "";
                    }
                */?><!--<div class="col-md-12">
                            <label style="font-size:15px;">Receive notifications for this event&nbsp;&nbsp;<input type="checkbox" name="Events[notification]" <?php /*echo $value;*/?>></label>
                     </div>-->
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                        'class' => 'btn btn-primary',
                        'id' => 'submit_button',
                    )); ?>
                    <?php echo CHtml::link('Cancel', array('events/view'),
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

<input type="hidden" id="myfrom" value="<?= $from; ?>" />

<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.js';?>"></script>
<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/dropzonejs/dropzone.js';?>"></script>
<script src="<?php echo Yii::app()->baseUrl ; ?>/plugins/js/plugins/bootstrap-datetimepicker/moment.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/plugins/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/plugins/js/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/plugins/js/core/jquery.slimscroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/jquery.geocomplete.js"></script>
<script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/logger.js"></script>
<script src="<?php echo Yii::app()->baseUrl; ?>/plugins/js/plugins/colorpicker/js/colorpicker.js"></script>

<script>
    $('.mysummernote').summernote({
        height:'200px'
    });
    $("div#myId").dropzone({ url: "/file/post" });
    jQuery(function () {
        App.initHelpers(['datetimepicker']);
        App.initHelpers('slimscroll');
        App.initHelpers('summernote');
    });
    $(".select2Search").select2();
    $('#color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
        }
    });
    $(document).ready(function () {
        //$("#imgPreviewBox").css("display","none");
        $('.js-select2-multiple').select2();
        $('.select2-container').css('width', '100%');
        /* initialize the external events*/
        /* initialize the calendar*/
        $('#external-events .fc-event').each(function () {
            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
        });

        var type = $("#Events_type").val();
        if(type == "service"){
            $("#eventdates").addClass("hide");
            $("#duration").removeClass("hide");
            $(".mystarttime").removeClass("hide");
        }
        else if(type == "event"){
            $("#duration").addClass("hide");
            $("#eventdates").removeClass("hide");
            $(".mystarttime").addClass("hide");
        }
    });

    $('#all_user').change(function(){
        if($("#all_user").is(':checked') ){
            $("#Events_user_id").prop('disabled', true);
            $('#Events_user_id').val('all');
        }else{
            $("#Events_user_id").prop('disabled', false);
        }

    });

    // Upload file preview on Application form
    $("#Events_event_image").on("change", function()
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


    $(function () {
        var myfrom = $("#myfrom").val();
        $('#event_start').datetimepicker({
            defaultDate: new Date(),
            startDate: new Date()
        });

        if(myfrom == 'create'){
            $('#my_event_start').datetimepicker({
                defaultDate: new Date(),
            });
        }
        else{
            $('#my_event_start').datetimepicker();
        }

        if(myfrom == 'create'){
            $('#my_event_end').datetimepicker({
                defaultDate: new Date(),
            });
        }
        else{
            $('#my_event_end').datetimepicker();
        }

        $('#booking_start_date').datetimepicker({
            defaultDate: new Date(),
        });

        $('#event_end').datetimepicker({
            defaultDate: new Date(),
        });

        $("#event_start").on("dp.change", function (e) {
            $('#event_end').data("DateTimePicker").minDate(e.date);
        });

        $("#event_end").on("dp.change", function (e) {
            $('#event_start').data("DateTimePicker").maxDate(e.date);
        });

        $('#specific_start_date').datepicker({
            multidate:true,
        });
        $("#specific_start_date").datepicker().datepicker("setDate", new Date());

        $('#specific_type_time').datetimepicker({
            format:"hh:ii:ss"
        });

        $('#coupon_start_date').datetimepicker({
            defaultDate:new Date(),
            //startDate: new Date()
        });
        $('#coupon_end_date').datetimepicker({
            defaultDate:new Date(),
            //startDate: new Date()
        });

        //jquery.noConflict();

        $('#event_start_update').datetimepicker({
            //useCurrent: false //Important! See issue #1075
        });

        $('#event_end_update').datetimepicker({
            //useCurrent: false //Important! See issue #1075
        });

        $("#event_start_update").on("dp.change", function (e) {
            $('#event_end_update').data("DateTimePicker").minDate(e.date);
        });

        $("#event_end_update").on("dp.change", function (e) {
            $('#event_start_update').data("DateTimePicker").maxDate(e.date);
        });

        $('#booking_start_date_update').datetimepicker();
        $('#coupon_start_date_update').datetimepicker();
        $('#coupon_end_date_update').datetimepicker();

    });


    function formatAMPM(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return strTime;
    }

    $(document).ready(function(){
        var eventtype = $('#Events_event_type').val();
        if(eventtype == "single"){
            var daytype = $('input[name=day]:checked', '#event-form').val();
            if(daytype == 'single'){
                $('#singlemultiradio').removeClass('hide');
                $('#startdate').html('Date ');
                $('#endtime').hide();
                $("#singletypeendtime").show();
            }
        }
        if(eventtype == "regular"){
            $('#recurringsection').removeClass('hide');
            $('#singletypeendtime').hide();
        }
        if(eventtype == "specific"){
            $('#endtime').hide();
            $('#starttime').hide();
            $('#input_event_start').val('');
            $('#specific_field').removeClass('hide');
            $('#specific_field_time').removeClass('hide');
            $('#singletypeendtime').hide();
        }

        if($("#Events_is_cancel").prop('checked')){
            $("#Events_hours_before_cancel").parent().parent().show();
            $("#Events_cancellation_rules").parent().show();
        }else{
            $("#Events_hours_before_cancel").parent().parent().hide();
            $("#Events_cancellation_rules").parent().hide();
        }
    });

    $('#Events_event_type').on("change keyup",function(){
        var eventtype = $('#Events_event_type').val();
        if(eventtype != "single"){
            $('#singlemultiradio').addClass('hide');
            $('#startdate').html('Start time');
            $('#endtime').show();
            $('#singletypeendtime').hide();
        }
        if(eventtype == "single"){
            $('#recurringsection').addClass('hide');
            $('#singlemultiradio').removeClass('hide');
            $('#startdate').html('Date ');
            $('#endtime').hide();
            $('#single').prop('checked',true);
            $('#singletypeendtime').show();
        }
        if(eventtype == "regular"){
            $('#recurringsection').removeClass('hide');
            $("#enddate").html("End Time");
        }
        if(eventtype != "regular"){
            $('#recurringsection').addClass('hide');
        }
        if(eventtype == "specific"){
            $('#endtime').hide();
            $('#starttime').hide();
            $('#input_event_start').val('');
            $('#specific_field').removeClass('hide');
            $('#specific_field_time').removeClass('hide');
        }
        if(eventtype !=  "specific"){
            $('#specific_field_time').addClass('hide');
            $('#specific_field').addClass('hide');
            $('#starttime').show();
        }
    });

    $('#submit_button').on("click",function(){
        var maineventtype = $("#Events_type").val();
        var title = $("#Events_event_title").val();
        var color = $("#color").val();
        if(title == ''){
            $('#Events_event_title').parent().parent().addClass('has-error');
            $('#titleerror').removeClass('hide');
        }
        if(color == ''){
            $('#color').parent().parent().addClass('has-error');
            $('#colorerror').removeClass('hide');
        }
        if(maineventtype == 'event'){
            var eventtype =  $('#Events_event_type').val();
            var enddate = $('#input_event_end').val();
            var startdate = $('#input_event_start').val();
            if(eventtype == "regular" && enddate == ""){
                $('#input_event_end').removeClass("help-block");
                $('#input_event_end').parent().parent().addClass('has-error');
                $('#errormsg').removeClass('hide');
                return false;
            }
            if(eventtype == 'single' && startdate == ""){
                $('#input_event_start').removeClass("help-block");
                $('#input_event_start').parent().parent().addClass('has-error');
                $('#startdateerror').removeClass('hide');
                $('html, body').animate({
                    scrollTop: $("#Events_event_type").offset().top
                });
                return false;
            }

        }

    });


    /*$(function () {
        $("form[id='event-form']").validate({
            debug: true,
            errorClass: "help-block",
            errorElement: "div",
            onfocusout: false,
            onkeyup: false,
            rules: {

            },
            messages: {

            },
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
                $(element).parent().parent().addClass('has-error');
                //$('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).parent().parent().removeClass('has-error');
            },
            submitHandler:function (form) {
                form.submit();
            }
        });
    });*/

    /*$(function(){
        $("#event_location").geocomplete()
            .bind("geocode:result", function(event, result){
                $.log("Result: " + result.formatted_address);
            })
            .bind("geocode:error", function(event, status){
                $.log("ERROR: " + status);
            })
            .bind("geocode:multiple", function(event, results){
                $.log("Multiple: " + results.length + " results found");
            });
    });*/


    $('.singlemulti').on("change",function(){
        var daytype = $('input[name=day]:checked', '#event-form').val();
        if(daytype == 'multi') {
            $('#startdate').html('Start Time');
            $('#endtime').show();
            $('#singletypeendtime').hide();
        }
        if(daytype == 'single'){
            $('#startdate').html('Date ');
            $('#endtime').hide();
            $('#singletypeendtime').show();
        }
    });

    $("#Events_type").on("change",function(){
        var type = $("#Events_type").val();
        if(type == "service"){
            $("#eventdates").addClass("hide");
            $("#duration").removeClass("hide");
            $(".mystarttime").removeClass("hide");
        }
        else if(type == "event"){
            $("#duration").addClass("hide");
            $("#eventdates").removeClass("hide");
            $(".mystarttime").addClass("hide");
        }

    });

    $("#Events_is_cancel").on("change click",function () {
        if($("#Events_is_cancel").prop('checked')){
            $("#Events_hours_before_cancel").parent().parent().show();
            $("#Events_cancellation_rules").parent().show();
        }
        else{
            $("#Events_hours_before_cancel").parent().parent().hide();
            $("#Events_cancellation_rules").parent().hide();
        }
    });

    $('#submit_button').on("click",function(){
        var type = $("#Events_type").val();
        if(type == "service"){
            var hours = $('#Services_hours').val();
            var minutes = $('#Services_minutes').val();
            if(hours == 0 && minutes == 0){
                $('#duration_error').removeClass('hide');
                $('#duration_error').parent().addClass('has-error');
                return false;
            }
        }
    });
</script>