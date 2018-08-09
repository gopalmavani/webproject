<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 27/2/18
 * Time: 4:11 PM
 */
?>
<style>
    .form-control{
        font-size:12px;
    }
</style>
<div class="col-md-12">
    <img style="width:20% !important;margin-left:5%;margin-top:2%" src="<?php echo Yii::app()->createUrl('/../plugins/demo/images/logo2.png'); ?>" class="logo img-responsive"/>
    <div class="col-md-6" style="margin-top:2%">
        <p></p>
        <table class="table" border="1px solid #ccc">
            <?php
            if($from == "generatescript"){ ?>
                <th class="text-center" style="background-color:#5c90d2;color:#fff;">Events</th>
                <?php if(empty($events)){
                    echo "<tr><td><b>No events yet</b></td></tr>";
                }
                else{
                    $i = 0;
                    $len = count($events);
                    foreach ($events as $item) { ?>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <b><?php echo $item->event_title; ?></b>
                                    <a href="<?php echo Yii::app()->createUrl('event/booking/')."/".$item->event_id; ?>" class="btn btn-primary pull-right">Book event</a>
                                </div>
                            </td>
                        </tr>
                    <?php   }
                }
                ?>
                <th class="text-center" style="background-color:#5c90d2;color:#fff;">Services</th>
                <?php
                if(empty($services)){
                    echo "<tr><td><b>No services yet</b></td></tr>";
                }
                else{
                    foreach ($services as $service){ ?>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <b><?php echo $service->service_name; ?></b>
                                    <a href="<?php echo Yii::app()->createUrl('event/bookingservice/')."/".$service->service_id; ?>" class="btn btn-primary pull-right">Book service</a>
                                </div>
                            </td>
                        </tr>
                    <?php }
                }
            } else if($from == "booking" || $from == "bookingparticular" || $from == "bookingservices") {
            if($from == "booking"){?>
                <a href="<?php echo Yii::app()->createUrl('/event/generatescript'); ?>" class="btn btn-default" style="margin-left:1%;margin-bottom:1%"><i class="fa fa-angle-left"></i> Back</a>
            <?php } ?>
            <?php
            if(empty($events)){
            }
            else{
                foreach ($events as $item) { ?>
                    <tr>
                        <td>
                            <div class="form-group">
                                <?php if($stop == "disabled"){ ?>
                                    <a href="javascript:void(0);" disabled class="btn btn-primary pull-right">Event Booked</a>
                                <?php }else{ ?>
                                    <a href="<?php echo Yii::app()->createUrl('event/bookingconfirm')."/".$item->event_id; ?>" class="btn btn-primary pull-right">Book</a>
                                <?php } ?>
                                <h5><?php echo $item->event_title; ?></h5>
                                <div><span>Event start &nbsp;&nbsp;</span><?php echo date('m/d/Y h:i A',strtotime(str_replace('/','-',$item->event_start))) ?></div>
                                <div><?php echo $item->event_description; ?></div>
                            </div>
                        </td>
                    </tr>
                <?php  }
            }
            if(empty($services)){}
            else {
                foreach($services as $service){?>
                    <tr>
                        <td>
                            <div class="form-group">
                                <?php if($stop == "disabled"){ ?>
                                    <a href="javascript:void(0);" disabled class="btn btn-primary pull-right">Service Booked</a>
                                <?php }else{ ?>
                                    <a href="<?php echo Yii::app()->createUrl('event/bookingserviceconfirm')."/".$service->service_id; ?>" class="btn btn-primary pull-right">Book</a>
                                <?php } ?>
                                <h5><?php echo $service->service_name; ?></h5>
                                <div><?php echo $service->service_description; ?></div>
                            </div>
                        </td>
                    </tr>
                <?php   }
            }?>

        </table>
        <?php } else if($from == "bookingconfirm"){ ?>
            <input type="hidden" name="open_modal" id="openmodal" value="<?php echo $from; ?>">
            <!--modal-->
            <div class="modal fade" data-keyboard="false" data-backdrop="static" id="bookingconfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onClick="history.go(-1);">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="push" id="exampleModalLongTitle"><?php echo $event->event_title."- &euro;".$event->price; ?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-lg-15">
                                <div class="js-wizard-simple block">
                                    <!-- Step Tabs -->
                                    <ul class="nav nav-tabs nav-tabs-alt nav-justified">
                                        <li class="active">
                                            <a href="#simple-step1" data-toggle="tab">1. New user</a>
                                        </li>
                                        <li>
                                            <a href="#simple-step2" data-toggle="tab">2. Existing User</a>
                                        </li>
                                        <li>
                                            <a href="#simple-step3" data-toggle="tab">3. Add a note</a>
                                        </li>
                                    </ul>
                                    <!-- END Step Tabs -->

                                    <!-- Form -->
                                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('/Event/bookingconfirm')."/".$event->event_id; ?>" method="POST" style="width:100%;margin:-20px;">
                                        <input type="hidden" name="booking[price]" value="<?php echo $event->price; ?>">
                                        <!-- Steps Content -->
                                        <div class="block-content tab-content">
                                            <!-- Step 1 -->
                                            <div class="tab-pane fade in push-30-t push-50 active" id="simple-step1" style="margin-left:-132px;width:140%;">
                                                <div class="form-group">
                                                    <div class="col-sm-8 col-sm-offset-2">
                                                        <div class="form-material">
                                                            <input class="form-control" type="text" id="booking_username" name="booking[username]" placeholder="Please enter your fullname">
                                                            <label for="simple-firstname">Full Name</label>
                                                            <span class="help-block hide" id="username_error">Please enter fullname</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-8 col-sm-offset-2">
                                                        <div class="form-material">
                                                            <input class="form-control" type="text" id="booking_email" name="booking[email]" placeholder="Please enter your email">
                                                            <label for="simple-firstname">Email</label>
                                                            <span class="help-block hide" id="email_error">Please enter email</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
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
                                                            <textarea class="form-control" id="booking_address" name="booking[address]" rows="3" placeholder="Enter your address here"></textarea>
                                                            <label for="simple-details">Address</label>
                                                            <span class="help-block hide" id="address_error">Please enter address</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-8 col-sm-offset-2">
                                                        <div class="form-material">
                                                            <input class="form-control" type="text" id="booking_coupon_code" name="booking[coupon_code]" placeholder="If you have coupon code enter it here">
                                                            <a class="btn btn-warning pull-right" href="javascript:void(0);" id="applycoupon" style="margin-right:-59px;margin-top:-6%;font-size:10px">Apply</a>
                                                            <label for="simple-details">Coupon Code</label>
                                                        </div>
                                                        <span id="wrong_code" class="help-block hide">Wrong coupon code applied</span>
                                                        <span id="right_code" class="hide" style="color:#46c37b;">Coupon code applied</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END Step 1 -->

                                            <!-- Step 2 -->
                                            <div class="tab-pane fade push-30-t push-50" id="simple-step2">
                                                <div class="form-group" style="margin-top:-6%">
                                                    <div class="col-sm-8 col-sm-offset-2">
                                                        <div class="form-material">
                                                            <select class="form-control" name="booking[user_id]" id="booking_user_id">
                                                                <option value="">Select user</option>
                                                                <?php
                                                                $i = 0;
                                                                foreach ($users as $key=>$user){
                                                                    if($i=0){ ?>
                                                                        <option value="<?php echo $user['user_id']; ?>"><?php echo $user['full_name']; ?></option>
                                                                    <?php } else{ ?>
                                                                        <option value="<?php echo $user['user_id']; ?>"><?php echo $user['full_name']; ?></option>
                                                                    <?php  } ?>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="help-block hide" id="user_id_error">Please select user</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-8 col-sm-offset-2">
                                                        <p></p>
                                                        <div class="form-material">
                                                            <input class="form-control" type="text" id="booking_coupon_code_user" name="booking[coupon_code]" placeholder="If you have coupon code enter it here" >
                                                            <a class="btn btn-warning pull-right" id="couponcheck" href="javascript:void(0);" style="margin-right:-59px;margin-top:-11%;font-size:10px">Apply</a>
                                                            <label for="simple-details">Coupon Code</label>
                                                        </div>
                                                        <span id="user_wrong_code" class="help-block hide">Wrong coupon code applied</span>
                                                        <span id="user_right_code" class="hide" style="color:#46c37b;">Coupon code applied</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- END Step 2 -->

                                            <!-- Step 3 -->
                                            <div class="tab-pane fade push-30-t push-50" id="simple-step3">
                                                <div class="form-group">
                                                    <div class="col-sm-8 col-sm-offset-2">
                                                        <div class="form-material">
                                                            <textarea class="form-control" id="simple-progress-details" name="simple-progress-details" rows="9" placeholder="Share something about yourself"></textarea>
                                                            <label for="simple-progress-details">Details</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END Step 3 -->
                                        </div>
                                        <!-- END Steps Content -->

                                        <!-- Steps Navigation -->
                                        <div class="block-content block-content-mini block-content-full border-t">
                                            <!--<div class="col-xs-6">
                                                <button class="wizard-prev btn btn-warning" type="button"><i class="fa fa-arrow-circle-o-left"></i> Previous</button>
                                            </div>-->
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-default" class="close" data-dismiss="modal" aria-label="Close" onClick="history.go(-1);">Close</button>
                                                <button id="submitbutton" class="wizard-finish btn btn-primary" type="submit"><i class="fa fa-check-circle-o"></i> Submit</button>
                                            </div>
                                        </div>
                                        <!-- END Steps Navigation -->
                                    </form>
                                    <!-- END Form -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else if($from == "bookingserviceconfirm"){ ?>
            <input type="hidden" name="open_modal" id="servicemodal" value="<?php echo $from; ?>">

            <!--modal-->
            <div class="modal fade" data-keyboard="false" data-backdrop="static" id="bookingserviceconfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onClick="history.go(-1);">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="push" id="exampleModalLongTitle">
                                <?php echo $service->service_name;if($service->service_price != ""){ echo "- &euro;".$service->service_price; } ?>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-lg-15">
                                <form class="form-horizontal" action="<?= Yii::app()->createUrl('/event/bookingserviceconfirm/')."/".$service->service_id; ?>" method="POST" style="width:100%">

                                    <!--Begin Existing users-->
                                    <div class="form-group">
                                        <div class="form-material">
                                            <label>Existing user</label>
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
                                        <label class="<?= $class; ?>" id="newuser"><span style="font-size:15px">New user</span>&nbsp;&nbsp;<input type="checkbox" id="newusercheck"></label>
                                    </div>
                                    <!--End Existing users-->

                                    <!--Begin Full name and email-->
                                    <div class="newmemberform <?= $myclass; ?>">
                                        <div class="form-group">
                                            <div class="form-material">
                                                <input class="form-control" type="text" id="booking_username" name="booking[username]" placeholder="Please enter your fullname" value="<?= $newmodel->username; ?>">
                                                <label for="simple-firstname">Full Name</label>
                                                <span class="help-block hide" id="username_error">Please enter username</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-material">
                                                <input class="form-control" type="text" id="booking_email" name="booking[email]" placeholder="Please enter your email" value="<?= $newmodel->email; ?>">
                                                <label for="simple-firstname">Email</label>
                                                <span class="help-block hide" id="email_error">Please enter email</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-material">
                                                <input class="form-control" type="text" id="booking_mobile_number" name="booking[mobile_number]" placeholder="Please enter your mobile number" value="<?= $newmodel->mobile_number; ?>">
                                                <label for="simple-firstname">Mobile Number</label>
                                                <span class="help-block hide" id="mobile_error">Please enter mobile</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-material">
                                                <textarea class="form-control" id="booking_address" name="booking[address]" rows="4" placeholder="Enter your address here"><?= $newmodel->address; ?></textarea>
                                                <label for="simple-details">Address</label>
                                                <span class="help-block hide" id="address_error">Please enter address</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End  Full name and email-->

                                    <!--Begin Coupon code-->
                                    <div class="form-group" style="margin-top:6%">
                                        <div class="form-material" style="width:80%;">
                                            <input class="form-control" type="text" id="booking_coupon_code" name="booking[coupon_code]" placeholder="If you have coupon code enter it here" value="<?= $newmodel->coupon_code; ?>">
                                            <a class="btn btn-warning pull-right" href="javascript:void(0);" id="applycoupon" style="margin-right:-59px;margin-top:-6%;font-size:10px">Apply</a>
                                            <label for="simple-details">Coupon Code</label>
                                        </div>
                                        <span id="wrong_code" class="help-block hide">Wrong coupon code applied</span>
                                        <span id="right_code" class="hide" style="color:#46c37b;">Coupon code applied</span>
                                    </div>
                                    <!--End Coupon code-->


                                    <!--Begin start time-->
                                    <div class="form-group <?= $error; ?>" id="starttime" style="margin-left:-22%">
                                        <div class="form-material">
                                            <div>
                                                <label for="event_start" id="startdate" style="margin-left:18%">Choose your slot here</label>
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
                                    </div>
                                    <!--end start time-->

                                    <!--Begin display for timings-->
                                    <?php
                                    $sql = "SELECT * FROM business_openings_hours";
                                    $timings = Yii::app()->db->createCommand($sql)->queryAll();
                                    $serviceprovider = "Company";
                                    $sql = "select * from service_provider";
                                    $result = Yii::app()->db->createCommand($sql)->queryAll();
                                    if(!empty($result)){
                                        $serviceprovider = $result[0]['name'];
                                    }
                                    ?>
                                    <p></p>
                                    <ul style="background-color:#ccc;color:#111;padding:20px;">
                                        <span style="margin-left:-15px;"><?= $serviceprovider  ?>'s Timings please choose your slot accordingly.</span>
                                        <?php foreach ($timings as $key=>$value){
                                            $mytimings = explode(";",$value['timing']);
                                            $min = $mytimings[0];
                                            $max = $mytimings[1];?>
                                            <li>
                                                <?= ucfirst($value['day']); ?> &nbsp;&nbsp;&nbsp;<?= $min.":00"." to ".$max.":00" ?>
                                            </li>

                                        <?php } ?>
                                    </ul>
                                    <!--End display for timings-->

                                    <div align="right">
                                        <button type="button" class="btn btn-secondary" onClick="history.go(-1);">Cancel</button>
                                        <button id="submitservice" class="wizard-finish btn btn-success" type="submit"><i class="fa fa-check-circle-o"></i> Confirm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>


<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.scrollLock.min.js', CClientScript::POS_END);
?>
<script src="<?php echo Yii::app()->createUrl('../'); ?>/plugins/js/plugins/bootstrap-datetimepicker/moment.min.js"></script>
<script src="<?php echo Yii::app()->createUrl('../'); ?>/plugins/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script>
    $(document).ready(function () {
        var from = $('#openmodal').val();
        var newfrom = $('#servicemodal').val();
        console.info(from);
        if(from == "bookingconfirm"){
            $('#bookingconfirm').modal('show');
        }
        if(newfrom == "bookingserviceconfirm"){
            $('#bookingserviceconfirm').modal('show');
        }
    });

    $('#submitbutton').on("click",function(){
        var existing_user = $('#booking_user_id').val();
        var email = $('#booking_email').val();
        var username = $('#booking_username').val();
        var check = 0;
        var mobno = $('#booking_mobile_number').val();
        var address = $('#booking_address').val();

        if(existing_user == ""){
            if(username == ""){
                $('#booking_username').parent().parent().parent().addClass('has-error');
                $('#username_error').removeClass('hide');
                check = 1;
            }
            else{
                $('#booking_username').parent().parent().parent().removeClass('has-error');
                $('#username_error').addClass('hide');
            }

            if(email == ""){
                $('#booking_email').parent().parent().parent().addClass('has-error');
                $('#email_error').removeClass('hide');
                check = 1;
            }
            else{
                if(emailcheck(email) == false){
                    check = 1;
                    $('#booking_email').parent().parent().parent().addClass('has-error');
                    $('#email_error').removeClass('hide');
                    $('#email_error').html('Please enter correct email');
                }
                else{
                    $('#booking_email').parent().parent().parent().removeClass('has-error');
                    $('#email_error').addClass('hide');
                }
            }

            if(mobno == ""){
                $('#booking_mobile_number').parent().parent().parent().addClass('has-error');
                $('#mobile_error').removeClass('hide');
                check = 1;
            }
            else{
                $('#booking_mobile_number').parent().parent().parent().removeClass('has-error');
                $('#mobile_error').addClass('hide');
            }
            if(address == ""){
                $('#booking_address').parent().parent().parent().addClass('has-error');
                $('#address_error').removeClass('hide');
                check = 1;
            }
            else{
                $('#booking_address').parent().parent().parent().removeClass('has-error');
                $('#address_error').addClass('hide');
            }

            if(check == 1){
                return false;
            }
        }

    });


    $('#submitservice').on("click",function(){
        var userid = $('#booking_user_id').val();

        var email = $('#booking_email').val();
        var username = $('#booking_username').val();
        var check = 0;

        if($('#newusercheck').is(':checked')){
            if (username == "") {
                $('#booking_username').parent().parent().addClass('has-error');
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
                $('#booking_user_id').parent().addClass("has-error");
                $("#user_id_error").removeClass('hide');
                /*$('#newuser').css('margin-left',"-15%");
                $('#newuser').css('margin-top',"2%");*/
            }
            else{
                check = 0;
                $('#booking_user_id').parent().removeClass("has-error");
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


    $('#applycoupon').on("click",function(){
        var coupon = $('#booking_coupon_code').val();
        var couponurl = "<?php if(isset($event)){echo Yii::app()->createUrl("/event/checkcoupon/")."/".$event->event_id;} ?>";
        $.ajax({
            url: couponurl,
            type: 'POST',
            data: {coupon: coupon},
            success:function(response){
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

    function emailcheck(value) {
        return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
    }
    $(function () {
        $('#mydate').datetimepicker({
            defaultDate: new Date(),
            minDate: new Date()
        });
    });
</script>
