<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 22/2/18
 * Time: 4:20 PM
 */
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-treeview/bootstrap-treeview.css');
$this->pageTitle = $model->event_title."- &euro;".$model->price;
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Booking
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
                        Booking information
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('/admin/Events/booking')."/".$model->event_id; ?>" method="POST" style="width:100%">
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <input type="hidden" name="booking[price]" value="<?php echo $model->price; ?>">
                    <div class="form-group">
                        <div class="col-md-12">
                            <select class="form-control" name="booking[user_id]" id="booking_user_id" style="font-size: 14px;width: 398px;">
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

                            <p></p>
                            <label id="newuser"><span style="font-size:15px">New user</span>&nbsp;&nbsp;<input type="checkbox" id="newusercheck"></label>
                        </div>
                    </div>
                </div>
                <div class="newmemberform hide">
                    <div class="form-group m-form__group row">
                        <div class="col-md-5">
                            <div class="form-material">
                                <label for="simple-firstname">Full Name</label>
                                <input class="form-control" type="text" id="booking_username" name="booking[username]" placeholder="Please enter your fullname">
                                <span class="help-block hide" id="username_error">Please enter username</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-sm-5">
                            <div class="form-material">
                                <label for="simple-firstname">Email</label>
                                <input class="form-control" type="text" id="booking_email" name="booking[email]" placeholder="Please enter your email">
                                <span class="help-block hide" id="email_error">Please enter email</span>
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
                </div>
                <div class="form-group m-form__group row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="form-material">
                                <label for="simple-firstname">Status</label>
                                <select name="booking[status]" id="booking_status" class="form-control" style="font-size: 14px;width: 360%;height: 100%;">
                                    <option>pending</option>
                                    <option>approved</option>
                                    <option>rejected</option>
                                    <option>waitlist</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="form-material">
                                <label for="simple-details">Coupon Code</label>
                                <input class="form-control" type="text" id="booking_coupon_code" name="booking[coupon_code]" placeholder="If you have coupon code enter it here" style="font-size: 14px;width: 162%;height: 100%;">
                                <a class="btn btn-success pull-right" href="javascript:void(0);" id="applycoupon" style="margin-right: -196px;margin-top: -18%;font-size: 12px;">Apply</a>
                            </div>
                            <span id="wrong_code" class="help-block hide">Wrong coupon code applied</span>
                            <span id="right_code" class="hide" style="color:#46c37b;">Coupon code applied</span>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3" align="right">
                        <button type="button" class="btn btn-secondary" onClick="history.go(-1);">Cancel</button>
                        <button id="submitbutton" class="wizard-finish btn btn-primary" type="submit"><i class="fa fa-check-circle-o"></i> Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!--Begin stop booking modal-->
<div class="modal fade shop-login-modal" id="bookingdone" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true" role="dialog" aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Event booked up.</h5>
            </div>
            <div class="modal-body">
                This Event is booked up , you can not book this event now.
                <p></p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" onclick="history.go(-1)">Go back</a>
            </div>
        </div>
    </div>
</div>
<!--End stop booking modal-->
<script>
    $(document).ready(function () {
        $value = $('#stop').val();
        if($value == "stop"){
            $('#bookingdone').modal('show');
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

    /*$('#booking_user_id').change(function(){
        $('#newusercheck').parent().addClass('hide');
    });*/
    $('#newusercheck').on("click",function () {
        if($(this).is(':checked')){
            $('.newmemberform').removeClass('hide');
        }
        else{
            $('.newmemberform').addClass('hide');
        }
    });

    $('#submitbutton').on("click",function(){
        var userid = $('#booking_user_id').val();

        var email = $('#booking_email').val();
        var username = $('#booking_username').val();
        var check = 0;
        var mobno = $('#booking_mobile_number').val();
        var address = $('#booking_address').val();

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
                check = 1;
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
            /*$('#newuser').css('margin-left',"-15%");
            $('#newuser').css('margin-top',"2%");*/
        }

        /*if(mobno == ""){
            $('#booking_mobile_number').parent().parent().parent().addClass('has-error');
            $('#mobile_error').removeClass('hide');
            return false;
        }
        if(address == ""){
            $('#booking_address').parent().parent().parent().addClass('has-error');
            $('#address_error').removeClass('hide');
            return false;
        }*/


        if (check == 1) {
            return false;
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
</script>