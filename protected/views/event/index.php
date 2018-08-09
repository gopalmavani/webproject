<?php $this->pageTitle = "Book Now";
?>
<link href="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/datetimepicker/bootstrap-datetimepicker.min.css"; ?>" rel="stylesheet" media="screen">
<section class="ImageBackground ImageBackground--overlay js-FullHeight js-Parallax" data-overlay="5">
    <div class="ImageBackground__holder">
        <div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active bg bg1"></div>
                <div class="item bg bg2"></div>
                <div class="item bg bg3"></div>
                <div class="item bg bg4"></div>
                <div class="item bg bg5"></div>
                <div class="item bg bg6"></div>
                <div class="item bg bg7"></div>
                <div class="item bg bg8"></div>
            </div>
        </div>
    </div>
    <div class="container u-vCenter">
        <div class="row text-center text-white">
            <div class="col-sm-10 col-sm-offset-1 event-info">
                <div class="banner-text u-MarginBottom100">
                    <div class="banner-text-inner">
                        <h1 class="u-MarginTop150 u-xs-MarginTop100 u-MarginBottom30 u-Weight700 text-uppercase wow fadeInUp" data-wow-delay="200ms">Book your slot</h1>
                        <div class="Split Split--height2 u-MarginBottom60 wow fadeInUp" data-wow-delay="200ms"></div>
                        <div class="reserveer-form step1 u-Padding30 u-PaddingBottom20 u-sm-PaddingBottom30 wow fadeInUp" data-wow-delay="200ms">
                            <form name="reserveer" action="<?php echo Yii::app()->createUrl("event/index"); ?>" method="post" id="bookingform">
                                <div class="row">
                                    <div class="col-sm-6 col-md-2">
                                        <label style="color:#666;margin-left:-20px;">Date</label>
                                        <div class="form-group u-MarginBottom10">
                                            <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input class="form-control" name="booking[checkindate]" type="text" value="" placeholder="Date" id="checkindate">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-2">
                                        <label style="color:#666;margin-left:-50px;">First Name</label>
                                        <div class="form-group u-MarginBottom10"><input type="text" class="form-control" placeholder="Naam*" name="booking[first_name]" value="<?php if(isset($user)){ echo $user->first_name; }elseif(isset($_SESSION['myguestuser'])){ echo $_SESSION['myguestuser']['myfirstname']; } ?>" ></div>
                                    </div>
                                    <div class="col-sm-4 col-md-2">
                                        <label style="color:#666;margin-left:-33px;">Last Name</label>
                                        <div class="form-group u-MarginBottom10"><input type="text" class="form-control" placeholder="Naam*" name="booking[last_name]" value="<?php if(isset($user)){ echo $user->last_name; } elseif(isset($_SESSION['myguestuser'])){ echo $_SESSION['myguestuser']['mylastname']; } ?>" ></div>
                                    </div>
                                    <div class="col-sm-4 col-md-2">
                                        <label style="color:#666;margin-left:-37px;">E-mail</label>
                                        <div class="form-group u-MarginBottom10"><input type="email" id="email" class="form-control" placeholder="Email*" name="booking[email]" value="<?php if(isset($user)){ echo $user->email; }elseif(isset($_SESSION['myguestuser'])){ echo $_SESSION['myguestuser']['myemail']; } ?>" ></div>
                                    </div>
                                    <div class="col-sm-4 col-md-2">
                                        <label style="color:#666;margin-left:-12px;">No. Of People</label>
                                        <div class="form-group u-MarginBottom10">
                                            <!--<input type="number" value="Gasten*" min="1" max="7" class="form-control" name="booking[numberofpeople]" placeholder="Gasten*" id="numberofpeople" required>-->
                                            <select name="booking[numberofpeople]" id="numberofpeople" class="form-control">
                                                <?php
                                                $i = "";
                                                for($i = 1; $i<=7 ; $i++){ ?>
                                                    <option><?php echo $i; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span id="seatsmessage" style="display:none;color:#666;margin:0px;font-size:11px;">Hurry! Only &nbsp;<span style="color:darkred;margin:0px"><span id="noofseats" style="margin:0px"></span>&nbsp; Seats</span> &nbsp;left</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2">
                                        <label></label>
                                        <button type="submit" class="btn btn-primary" style="margin-top:5px;" id="submitbutton">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
<!--                         <div class="hide" id="mycheckoutdisplay">
                            <h2 class="u-Weight600">Uw check out datum : <span id="checkoutdate"></span></h2>
                        </div>
 -->                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/datetimepicker/bootstrap-datetimepicker.js"; ?>" charset="UTF-8"></script>
<!--suppress JSAnnotator -->
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.form_date').datetimepicker({
        // daysOfWeekDisabled: [0,2,3,5,6],
        startDate: new Date(),
        language:  'fr',
        weekStart: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.form_time').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });

    $(document).on("keydown","#numberofpeople",function(){
        return false;
    });

    $(document).on("change",".form_date",function(){
        var date = $("#checkindate").val();
        date = new Date(Date.parse(date));

        var newdate = date;

        newdate.setDate(newdate.getDate() + 3);

        var dd = newdate.getDate();
        var mm = newdate.getMonth() + 1;
        var y = newdate.getFullYear();

        var monthNames = [ "januari", "februari", "maart", "april", "mei", "juni",
            "juli", "augustus", "september", "oktober", "november", "december" ];
        if(mm == 1){
            mm = 'januari';
        }if(mm == 2){
            mm = 'februari';
        }if(mm == 3){
            mm = 'maart';
        }if(mm == 4){
            mm = 'april';
        }if(mm == 5){
            mm = 'mei';
        }if(mm == 6){
            mm = 'juni';
        }if(mm == 7){
            mm = 'juli';
        }if(mm == 8){
            mm = 'augustus';
        }if(mm == 9){
            mm = 'september';
        }if(mm == 10){
            mm = 'oktober';
        }if(mm == 11){
            mm = 'november';
        }if(mm == 12){
            mm = 'december';
        }
        var someFormattedDate = dd + ' ' + mm  + ' ' + y;

        $("#mycheckoutdisplay").removeClass('hide');
        $("#checkoutdate").text(someFormattedDate);
    });

    Date.prototype.getMonthName = function() {
        var monthNames = [ "januari", "februari", "maart", "april", "mei", "juni",
            "juli", "augustus", "september", "oktober", "november", "december" ];
        return monthNames[this.getMonth()];
    }
</script>


<script type="text/javascript">
    $.validator.addMethod("customemail",
        function(value, element) {
            return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
        },
        "Sorry, I've enabled very strict email validation"
    );

    $("form[id='bookingform']").validate({
        debug: true,
        errorClass: "help-block",
        errorElement: "p",
        onfocusout: false,
        onkeyup: false,
        onclick:false,
        rules:{
            'booking[checkindate]' : {
                required: true,
            },
            'booking[first_name]' : {
                required: true,
            },
            'booking[last_name]' : {
                required: true,
            },
            'booking[email]' : {
                required: true,
                customemail:true,
            },
            'booking[numberofpeople]' : {
                required: true,
            },
        },
        messages:{
            'booking[checkindate]' : {
                required: "Please enter date",
            },
            'booking[first_name]' : {
                required: "Enter Name",
            },
            'booking[last_name]' : {
                required: "Enter last name",
            },
            'booking[email]' : {
                required: "Gelieve uw e-mailadres in te geven",
                customemail:"Invalid Email!",
            },
            'booking[numberofpeople]' : {
                required: "Enter num of people",
            },
        },
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).parent().addClass('has-error');
            //$('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).parent().removeClass('has-error');
        },
        submitHandler:function (form) {
            form.submit();
        }
    });

    $("#checkindate").on("change",function(){
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('event/checkseats'); ?>",
            data: $("#bookingform").serialize(),
            success: function (data) {
                console.info(data);

                var res = JSON.parse(data);
                if (res.token == 1) {
                    $('select').children('option').remove();
                    var string  = "";
                    var i = '';
                    for (i = 1 ; i <= res.seats ; i++){
                        string += "<option>"+i+"</option>";
                    }
                    $('select').append(string);
                    $("#submitbutton").removeAttr('disabled');
                    $("#seatsmessage").css("display","block");
                    $("#noofseats").text(res.seats);
                }
                else{
                    toastr.error(res.msg);
                    $("#submitbutton").attr('disabled','disabled');
                }
            }
        });
    });
</script>

