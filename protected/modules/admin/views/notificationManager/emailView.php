<?php
/* @var $this NotificationManagerController */
/* @var $model NotificationManager */

$this->pageTitle = "<span lang='en'>Notifications</span>";
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.css');
?>
<style>

    .note-editor note-frame panel{
        height:20% !important;
    }

    .notice {
        position: relative;
        margin: 1em;
        background: #F9F9F9;
        padding: 1em 1em 1em 2em;
        border-left: 4px solid #DDD;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.125);
    }

    .notice:before {
        position: absolute;
        top: 50%;
        margin-top: -17px;
        left: -17px;
        background-color: #DDD;
        color: #FFF;
        width: 30px;
        height: 30px;
        border-radius: 100%;
        text-align: center;
        line-height: 30px;
        font-weight: bold;
        font-family: Georgia;
        text-shadow: 1px 1px rgba(0, 0, 0, 0.5);
    }
    .title{
        display: inline-flex;
    }
</style>


<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Emails
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="block">
                                <ul class="nav nav-tabs" data-toggle="tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#btabs-animated-slideleft-email">Email</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#btabs-animated-slideleft-emailcontent">Email Content</a>
                                    </li>
                                </ul>
                                <div class="block-content tab-content">
                                    <div class="tab-pane fade fade-left in active" id="btabs-animated-slideleft-email">
                                        <div class="block">
                                            <label class="push" style="font-size:18px;font-weight: 500;">Check necessary boxes to Send email</label>
                                            <form method="POST" action="<?= Yii::app()->createUrl('/admin/notificationManager/saveemailsettings'); ?>">
                                                <?php
                                                $eventchecked = "";
                                                $servicechecked = "";
                                                $usersignupchecked = "";
                                                $eventcancelchecked = "";
                                                $productorderchecked = "";
                                                $ordercancelchecked = "";
                                                $wallettransactionchecked = "";
                                                foreach ($settings as $key=>$value){
                                                    switch ($value['value']){
                                                        case "eventmailchecked":
                                                            $eventchecked = 'checked';
                                                            break;
                                                        case "servicemailchecked":
                                                            $servicechecked = 'checked';
                                                            break;
                                                        case "usersignupmailchecked":
                                                            $usersignupchecked = 'checked';
                                                            break;
                                                        case "eventcancelmailchecked":
                                                            $eventcancelchecked = 'checked';
                                                            break;
                                                        case "productordermailchecked":
                                                            $productorderchecked = 'checked';
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;font-weight: 500;">On Event Booking </label>
                                                    <input style="margin-left:46px" type="checkbox" name="Settings[event]" value="eventmailchecked" <?= $eventchecked; ?>>
                                                </div>
                                                <div class="col-md-12" style="display:none">
                                                    <label style="font-size:15px;font-weight: 500;">On Service Booking </label>
                                                    <input style="margin-left:37px" type="checkbox" name="Settings[service]" value="servicemailchecked" checked>
                                                </div>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;font-weight: 500;">On User Signup </label>
                                                    <input style="margin-left:65px" type="checkbox" name="Settings[signup]" value="usersignupmailchecked" <?= $usersignupchecked; ?>>
                                                </div>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;font-weight: 500;">On Event cancellation</label>
                                                    <input style="margin-left:15px" type="checkbox" name="Settings[eventcancel]" value="eventcancelmailchecked" <?= $eventcancelchecked; ?>>
                                                </div>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;font-weight: 500;">On Product Order </label>
                                                    <input style="margin-left:50px" type="checkbox" name="Settings[productorder]" value="productordermailchecked" <?= $productorderchecked; ?>>
                                                </div>
                                                <div class="col-md-12" align="right">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="reset" onclick="history.go(-1);" class="btn btn-default">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade fade-left" id="btabs-animated-slideleft-emailcontent">
                                        <div class="block">
                                            <label class="push" style="font-size:18px;font-weight: 500;">Fill all the content for various type of email</label>
                                            <form method="post" action="<?= Yii::app()->createUrl("/admin/notificationManager/saveemailcontent"); ?>" enctype="multipart/form-data">
                                                <?php
                                                $oneventbooking = "";
                                                $onusersignup = "";
                                                $oneventcancellation= "";
                                                $onproductorder = "";
                                                $foreventbooking = "";
                                                $forusersignup = "";
                                                $foreventcancellation = "";
                                                $forproductorder = "";
                                                if(!empty($emailcontent)){
                                                    foreach($emailcontent as $key=>$value){
                                                        switch($value['key']){
                                                            case "oneventbooking":
                                                                $oneventbooking = $value['description'];
                                                                break;
                                                            case "onusersignup":
                                                                $onusersignup = $value['description'];
                                                                break;
                                                            case "oneventcancellation":
                                                                $oneventcancellation = $value['description'];
                                                                break;
                                                            case "onproductorder":
                                                                $onproductorder = $value['description'];
                                                                break;
                                                            case "foreventbooking":
                                                                $foreventbooking = $value['description'];
                                                                break;
                                                            case "forusersignup":
                                                                $forusersignup = $value['description'];
                                                                break;
                                                            case "foreventcancellation":
                                                                $foreventcancellation = $value['description'];
                                                                break;
                                                            case "forproductorder":
                                                                $forproductorder = $value['description'];
                                                                break;
                                                            default:
                                                                break;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12" style="margin-top:2%">
                                                    <div class="form-group">
                                                        <label class="control-label" style="font-weight: 500;">Choose event here</label>
                                                        <select id="selectevent" class="form-control">
                                                            <option value="eventbookingdiv">On event booking</option>
                                                            <option value="usersignupdiv">On user signup</option>
                                                            <option value="eventcanceldiv">On event cancellation</option>
                                                            <option value="productorderdiv">On product order</option>
                                                        </select>
                                                    </div>


                                                        <div class="form-group myevents" id="eventbookingdiv">
                                                            <label class="control-label" style="font-weight: 500;">On event booking</label>
                                                            <p></p>
                                                            <label class="control-label" style="font-weight: 500;">From email</label>
                                                            <input type="email" name="fromemail[foreventbooking]" value="<?= $foreventbooking; ?>">
                                                            <textarea class="mysummernote form-control" name="emailcontent[oneventbooking]"><?= $oneventbooking; ?></textarea>
                                                            <span class="help-block"></span>
                                                        </div>


                                                        <div class="form-group hide myevents" id="usersignupdiv">
                                                            <label class="control-label" style="font-weight: 500;">On User signup</label>
                                                            <p></p>
                                                            <label class="control-label" style="font-weight: 500;">From email</label>
                                                            <input type="email" name="fromemail[forusersignup]" value="<?= $forusersignup; ?>">
                                                            <textarea class="mysummernote form-control" name="emailcontent[onusersignup]"><?= $onusersignup; ?></textarea>
                                                            <span class="help-block"></span>
                                                        </div>


                                                        <div class="form-group hide myevents" id="eventcanceldiv">
                                                            <label class="control-label" style="font-weight: 500;">On Event cancellation</label>
                                                            <p></p>
                                                            <label class="control-label" style="font-weight: 500;">From email</label>
                                                            <input type="email" name="fromemail[foreventcancellation]" value="<?= $foreventcancellation; ?>">
                                                            <textarea class="mysummernote form-control" name="emailcontent[oneventcancellation]"><?= $oneventcancellation; ?></textarea>
                                                            <span class="help-block"></span>
                                                        </div>


                                                        <div class="form-group hide myevents" id="productorderdiv">
                                                            <label class="control-label" style="font-weight: 500;">On Product order</label>
                                                            <p></p>
                                                            <label class="control-label" style="font-weight: 500;">From email</label>
                                                            <input type="email" name="fromemail[forproductorder]" value="<?= $forproductorder; ?>">
                                                            <textarea class="mysummernote form-control" name="emailcontent[onproductorder]"><?= $onproductorder; ?></textarea>
                                                            <span class="help-block"></span>
                                                        </div>
                                                </div>
                                                <div class="col-md-12" align="right">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="reset" onclick="history.go(-1);" class="btn btn-default">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Activity Timeline -->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Section-->
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/summernote/summernote.min.js';?>"></script>

<script>
    jQuery(function () {
        App.initHelpers('summernote');
    });
    $('.mysummernote').summernote({
        height:'300px'
    });
    $(".delete").on('click',function () {
        var attrId = $(this).attr('id');
        var id = attrId.split('_');
        $.ajax({
            url: "<?= Yii::app()->createUrl('admin/notificationManager/Delete')?>",
            type: "POST",
            data:{'id' : id},
            success: function (response) {
                var Result = JSON.parse(response);
                if (Result.token == 1){
                    window.location.reload();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {

            }
        });
    });

    $("#selectevent").on("click",function(){
        var value = $(this).val();
        $('.myevents').addClass("hide");
        $('#'+value).removeClass('hide');
    });
</script>