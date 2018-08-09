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
                View Notifications
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
                                        <a class="nav-link active" href="#btabs-animated-slideleft-home">Notifications</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#btabs-animated-slideleft-profile">Settings</a>
                                    </li>
                                </ul>
                                <div class="block-content tab-content">
                                    <div class="tab-pane fade fade-left in active" id="btabs-animated-slideleft-home">
                                        <div class="block">
                                            <div class="block-content">
                                                <div class="row">
                                                    <?php
                                                    if($notifications) {
                                                        foreach ($notifications as $notification) {
                                                            ?>
                                                            <div class="notice col-md-11">
                                                                <div class="col-md-11">
                                                                    <a href="<?= $notification->url; ?>">
                                                                        <div class="font-w600"><?= $notification->title_html; ?></div>
                                                                    </a><!--</div>-->
                                                                    <div><?= $notification->body_html; ?></div>
                                                                    <div>
                                                                        <small
                                                                                class="text-muted"><?php echo NotificationHelper::time_elapsed_string($notification->created_at); ?></small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1" style="margin-top: 20px;">
                                                                    <a href="javascript:void(0);"><i class="fa fa-times delete"
                                                                                                     id="notify_<?= $notification->id; ?>"></i></a>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                    }else{?>
                                                        <div class="notice col-md-12" style=" width:97%;">
                                                            <div class="col-md-7 pull-right">
                                                                <b lang="en">No notification</b>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade fade-left" id="btabs-animated-slideleft-profile">
                                        <div class="block">
                                            <label class="push" style="font-size:18px;font-weight: 500;">Check necessary boxes to receive notifications</label>
                                            <form method="POST" action="<?= Yii::app()->createUrl('/admin/notificationManager/savesettings'); ?>">
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
                                                        case "eventchecked":
                                                            $eventchecked = 'checked';
                                                            break;
                                                        case "servicechecked":
                                                            $servicechecked = 'checked';
                                                            break;
                                                        case "usersignupchecked":
                                                            $usersignupchecked = 'checked';
                                                            break;
                                                        case "eventcancelchecked":
                                                            $eventcancelchecked = 'checked';
                                                            break;
                                                        case "productorderchecked":
                                                            $productorderchecked = 'checked';
                                                            break;
                                                        case "ordercancelchecked":
                                                            $ordercancelchecked = 'checked';
                                                            break;
                                                        case "wallettransactionchecked":
                                                            $wallettransactionchecked = 'checked';
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;font-weight: 500;">On Event Booking </label>
                                                    <input style="margin-left:46px" type="checkbox" name="Settings[event]" value="eventchecked" <?= $eventchecked; ?>>
                                                </div>
                                                <div class="col-md-12" style="display:none">
                                                    <label style="font-size:15px;font-weight: 500;">On Service Booking </label>
                                                    <input style="margin-left:37px" type="checkbox" name="Settings[service]" value="servicechecked" checked>
                                                </div>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;font-weight: 500;">On User Signup </label>
                                                    <input style="margin-left:65px" type="checkbox" name="Settings[signup]" value="usersignupchecked" <?= $usersignupchecked; ?>>
                                                </div>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;font-weight: 500;">On Event cancellation</label>
                                                    <input style="margin-left:15px" type="checkbox" name="Settings[eventcancel]" value="eventcancelchecked" <?= $eventcancelchecked; ?>>
                                                </div>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;font-weight: 500;">On Product Order </label>
                                                    <input style="margin-left:50px" type="checkbox" name="Settings[productorder]" value="productorderchecked" <?= $productorderchecked; ?>>
                                                </div>
                                                <!--<div class="col-md-12">
                                                    <label style="font-size:15px;">On Order Cancellation </label>
                                                    <input style="margin-left:16px" type="checkbox" name="Settings[ordercancel]" value="ordercancelchecked" <?/*= $ordercancelchecked; */?>>
                                                </div>
                                                <div class="col-md-12">
                                                    <label style="font-size:15px;">On Wallet Transaction </label>
                                                    <input style="margin-left:18px" type="checkbox" name="Settings[wallettransaction]" value="wallettransactionchecked" <?/*= $wallettransactionchecked; */?>>
                                                </div>-->
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
</script>