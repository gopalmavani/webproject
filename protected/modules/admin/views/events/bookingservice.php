<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 22/3/18
 * Time: 3:38 PM
 */
/*echo "<pre>";
print_r($service);die;*/
$this->pageTitle = "Booking of ".$model->event_title;
$selecteddays = array();
$timingcolumn = array();
foreach($timings as $key=>$value){
    array_push($selecteddays,$value['day']);
    $mytimings = explode(";",$value['timing']);
    $min = $mytimings[0];
    $max = $mytimings[1];
    array_push($timingcolumn,$min);
    array_push($timingcolumn,$max);
}

$theminimum = min($timingcolumn);
$themaximum = max($timingcolumn);
?>


<style>
    .fc-icon-left-single-arrow:after {
        content: "\02039";
        font-weight: 700;
        font-size: 150%;
        top: -7%;
    }
    .fc-icon-right-single-arrow:after {
        content: "\0203A";
        font-weight: 700;
        font-size: 150%;
        top: -7%;
    }
    .fc-list-item-title a {
        text-decoration: underline;
        color: #5c90d2;
        outline: 0;
        cursor: pointer;
    }
</style>

<input type="hidden" id="stop" value="<?php echo $stop; ?>">
<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" action="<?= Yii::app()->createUrl('/admin/events/booking/')."/".$model->event_id; ?>" method="POST" style="width:100%">

            <!--Begin Existing users-->
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-material">
                        <select class="form-control" name="booking[user_id]" id="booking_user_id" style="font-size:18px;">
                            <option value="">Select user</option>
                            <?php
                            foreach ($users as $key=>$user){
                                if(!empty($newmodel->user_id)){
                                    if($newmodel->user_id == $user['user_id']){?>
                                        <option value="<?php echo $user['user_id']; ?>" selected><?php echo $user['full_name']; ?></option>
                                    <?php   } else{ ?>
                                        <option value="<?php echo $user['user_id']; ?>" ><?php echo $user['full_name']; ?></option>
                                    <?php }
                                } else { ?>
                                    <option value="<?php echo $user['user_id']; ?>" ><?php echo $user['full_name']; ?></option>
                                <?php }
                            } ?>
                        </select>
                        <span class="help-block hide" id="user_id_error">Please select user</span>
                    </div>
                    <p></p>
                    <?php
                    if($newmodel->user_id == "" && $newmodel->username != ""){
                        $class = "hide";
                        $myclass= "";
                    }
                    else{
                        $class= "";
                        $myclass= "hide";
                    }
                    ?>
                    <label class="<?= $class; ?>"  id="newuser"><span style="font-size:15px">New user</span>&nbsp;&nbsp;<input type="checkbox" id="newusercheck"></label>
                </div>
            </div>
            <!--End Existing users-->

            <!--Begin Full name and email-->
            <div class="newmemberform <?= $myclass; ?>">
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-material">
                            <input class="form-control" type="text" id="booking_username" name="booking[username]" placeholder="Please enter your fullname" value="<?= $newmodel->username; ?>">
                            <label for="simple-firstname">Full Name</label>
                            <span class="help-block hide" id="username_error">Please enter username</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-material">
                            <input class="form-control" type="text" id="booking_email" name="booking[email]" placeholder="Please enter your email"  value="<?= $newmodel->email; ?>">
                            <label for="simple-firstname">Email</label>
                            <span class="help-block hide" id="email_error">Please enter email</span>
                        </div>
                    </div>
                </div>
                <!--<div class="form-group">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-material">
                            <input class="form-control" type="text" id="booking_mobile_number" name="booking[mobile_number]" placeholder="Please enter your mobile number">
                            <label for="simple-firstname">Mobile Number</label>
                            <span class="help-block hide" id="mobile_error">Please enter mobile</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-material">
                            <textarea class="form-control" id="booking_address" name="booking[address]" rows="4" placeholder="Enter your address here"></textarea>
                            <label for="simple-details">Address</label>
                            <span class="help-block hide" id="address_error">Please enter address</span>
                        </div>
                    </div>
                </div>-->
            </div>
            <!--End  Full name and email-->

            <!--Begin Coupon code-->
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-material">
                        <input class="form-control" type="text" id="booking_coupon_code" name="booking[coupon_code]" placeholder="If you have coupon code enter it here" value="<?= $newmodel->coupon_code; ?>">
                        <a class="btn btn-warning pull-right" href="javascript:void(0);" id="applycoupon" style="margin-right:-59px;margin-top:-6%;font-size:10px">Apply</a>
                        <label for="simple-details">Coupon Code</label>
                    </div>
                    <span id="wrong_code" class="help-block hide">Wrong coupon code applied</span>
                    <span id="right_code" class="hide" style="color:#46c37b;">Coupon code applied</span>
                </div>
            </div>
            <!--End Coupon code-->

            <!--Begin status-->
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-material">
                        <select name="booking[status]" id="booking_status" class="form-control">
                            <?php
                            $status = ['approved','pending','rejected','waitlist'];
                            foreach($status as $key=>$value){
                                if($newmodel->status != ""){
                                    if($newmodel->status == $value){ ?>
                                        <option selected><?= $value; ?></option>
                                    <?php }
                                    else{ ?>
                                        <option><?= $value; ?></option>
                                    <?php }
                                } else {?>
                                    <option><?= $value; ?></option>
                                <?php }
                            }
                            ?>

                        </select>
                        <label for="simple-firstname">Status</label>
                        <span class="help-block hide" id="email_error">Please enter email</span>
                    </div>
                </div>
            </div>
            <!--end status-->


            <!--Begin start time-->
            <div class="form-group <?= $error; ?>" id="starttime">
                <div>
                    <label for="event_start" id="startdate" style="margin-left:18%">Choose slot here</label>
                </div>
                <div>
                    <div class='input-group date' id="mydate" style="width:65%;margin-left:18%;">
                        <input type="text"  name="timeslot" class="form-control">
                        <span class="input-group-addon" style="margin-left:0%">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <?php
                $class="hide";
                if($error == "has-error"){
                    $class="";
                }
                ?>
                <span class="help-block <?= $class; ?>" style="margin-left:18%">Slot timing is wrong please choose your slot according to company's timings.</span>
            </div>
            <!--end start time-->


            <!--Begin display for timings-->
            <label style="background-color: #ccc;color:#111;padding:15px;margin-left:28%;">
                <?php
                $serviceprovider = "Company";
                $sql = "select * from service_provider";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($result)){
                    $serviceprovider = $result[0]['name'];
                }
                ?>
                <?= $serviceprovider  ?>'s Timings please choose your slot accordingly.
                <p></p>
                <table class="table">
                    <?php foreach ($timings as $key=>$value){
                        $mytimings = explode(";",$value['timing']);
                        $min = $mytimings[0];
                        $max = $mytimings[1];?>
                        <tr>
                            <td>
                                <?= ucfirst($value['day']); ?>
                            </td>
                            <td>
                                <?= $min.":00"." to ".$max.":00" ?>
                            </td>
                        </tr>

                    <?php } ?>
                </table>
            </label>
            <!--End display for timings-->

            <div align="right" style="margin-right:2%;margin-bottom:2%">
                <button type="button" class="btn btn-secondary" onClick="history.go(-1);">Cancel</button>
                <button id="submitbutton" class="wizard-finish btn btn-success" type="submit"><i class="fa fa-check-circle-o"></i> Confirm</button>
            </div>
        </form>
        <!-- END Form -->
    </div>
</div>

<!--Timeslot validation modal-->
<!--<div class="modal fade shop-login-modal" id="bookingdone" tabindex="-1" aria-hidden="true" role="dialog" aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle">Select Time slot</h5>
            </div>
            <div class="modal-body">
                Please selecte time slot to book the service.
                <p></p>
            </div>
        </div>
    </div>
</div>-->
<!--timeslot validation modal-->



<!--Begin stop booking modal-->
<div class="modal fade shop-login-modal" id="bookingdone" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true" role="dialog" aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Service booked up.</h5>
            </div>
            <div class="modal-body">
                This Service is booked up , you can not book this service now.
                <p></p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" onclick="history.go(-1)">Go back</a>
            </div>
        </div>
    </div>
</div>
<!--End stop booking modal-->

<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/moment.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/fullcalendar.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/gcal.min.js', CClientScript::POS_END);
?>
<script src="<?php echo Yii::app()->createUrl('../'); ?>/plugins/js/plugins/bootstrap-datetimepicker/moment.min.js"></script>
<script src="<?php echo Yii::app()->createUrl('../'); ?>/plugins/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script>
    $(document).ready(function () {
        $value = $('#stop').val();
        if($value == "stop"){
            $('#bookingdone').modal('show');
        }
    });

    var list = [];
    $('.book').on("click",function () {
        $(this).tooltip('disable');
        $('html, body').animate({
            scrollTop: $("#information").offset().top
        }, 1000);
    });

    $('#submitbutton').on("click",function(){
        var userid = $('#booking_user_id').val();

        var email = $('#booking_email').val();
        var username = $('#booking_username').val();
        var check = 0;

        if($('#newusercheck').is(':checked')){
            if (username == "") {
                $('#booking_username').parent().parent().parent().addClass('has-error');
                $('#username_error').removeClass('hide');
                check = 1;
            }

            if (email == "") {
                $('#booking_email').parent().parent().parent().addClass('has-error');
                $('#email_error').removeClass('hide');
                check = 1;
            }
            else {
                if (emailcheck(email) == false) {
                    check = 1;
                    $('#booking_email').parent().parent().parent().addClass('has-error');
                    $('#email_error').removeClass('hide');
                    $('#email_error').html('Please enter correct email');
                }
            }
        }
        else{
            if(userid == "") {
                check =1 ;
                $('#booking_user_id').parent().parent().parent().addClass("has-error");
                $("#user_id_error").removeClass('hide');
                $('#newuser').css('margin-left',"-15%");
                $('#newuser').css('margin-top',"2%");
            }
            else{
                check = 0;
                $('#booking_user_id').parent().parent().parent().removeClass("has-error");
                $("#user_id_error").addClass('hide');
            }
        }

        if (check == 1) {
            return false;
        }
    });

    $('#booking_user_id').on("change",function () {
        var value = $(this).val();
        if(value == ""){
            $('#newusercheck').prop('checked', false);
            $('#newuser').show();
        }
        else{
            $('#newusercheck').prop('checked', false);
            $('.newmemberform').addClass('hide');
            $('#newuser').hide();
        }
    });

    $('#newusercheck').on("click",function () {
        if($(this).is(':checked')){
            $('#booking_user_id').val('');
            $('.newmemberform').removeClass('hide');
        }
        else{
            $('.newmemberform').addClass('hide');
        }
    });

    function emailcheck(value) {
        return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
    }

    $('#applycoupon').on("click",function(){
        var coupon = $('#booking_coupon_code').val();
        var couponurl = "<?php if(isset($model)){echo Yii::app()->createUrl("/admin/events/checkcoupon/")."/".$model->event_id;} ?>";

        $.ajax({
            url: couponurl,
            type: 'POST',
            data: {coupon: coupon},
            success:function(response){
                console.info()
                var res = JSON.parse(response);
                if(res.token == 0){
                    $('#wrong_code').parent().parent().addClass('has-error');
                    $('#wrong_code').removeClass('hide');
                }
                if(res.token == 1){
                    $('#wrong_code').parent().parent().removeClass('has-error');
                    $('#wrong_code').addClass('hide');
                    $('#right_code').removeClass('hide');
                    $("#booking_coupon_code").prop("readonly", true);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });


    $(function () {
        $('#mydate').datetimepicker({
            defaultDate: new Date(),
            minDate: new Date()
        });
    });
</script>
