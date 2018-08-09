<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 25/7/18
 * Time: 4:40 PM
 */
$this->pageTitle = "Register";
?>

<section class="ImageBackground ImageBackground--overlay " data-overlay="5">
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
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-md-12 event-info">
                <div class="banner-text u-MarginBottom100">
                    <div class="banner-text-inner">
                        <h1 class="u-MarginTop150 u-xs-MarginTop100 u-MarginBottom30 u-Weight700 text-uppercase wow fadeInUp" data-wow-delay="200ms">REGISTREER</h1>
                        <div class="Split Split--height2 u-MarginBottom60 wow fadeInUp" data-wow-delay="200ms"></div>
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                                <div class="reserveer-form u-Padding30 wow fadeInUp" data-wow-delay="200ms">
                                    <div class="row">
                                        <h1 class="text-center" style="color:#666;">Informatie</h1>
                                        <form name="reserveer" action="<?php echo Yii::app()->createUrl("/home/register"); ?>" class="text-gray text-left" method="post" id="registerform">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Voornaam</label>
                                                        <input type="text" class="form-control" placeholder="" name="register[first_name]">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Familienaam</label>
                                                        <input type="text" class="form-control" placeholder="" name="register[last_name]">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" placeholder="" name="register[email]" id="register_email">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Wachtwoord</label>
                                                        <input type="password" class="form-control" placeholder="" name="register[password]">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Telefoonnummer</label>
                                                        <input type="text" class="form-control" placeholder="" name="register[phone]">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 text-center">
                                                    <button type="submit" class="btn btn-primary">Verder</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script type="text/javascript">
    $.validator.addMethod("customemail",
        function(value, element) {
            return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
        },
        "Sorry, I've enabled very strict email validation"
    );

    $("form[id='registerform']").validate({
        debug: true,
        errorClass: "help-block",
        errorElement: "div",
        onfocusout: false,
        onkeyup: false,
        onclick:false,
        rules:{
            'register[first_name]' : {
                required: true,
            },
            'register[last_name]' : {
                required: true,
            },
            'register[email]' : {
                required: true,
                customemail:true,
            },
            'register[password]': {
                required: true,
                minlength : 8,
            },
            'register[phone]': {
                required: true,
                number : true,
                minlength : 10,
                maxlength : 10,
            },
        },
        messages:{
            'register[first_name]' : {
                required: "Gelieve uw Voornaam in te geven",
            },
            'register[last_name]' : {
                required: "Gelieve uw Familienaam in te geven",
            },
            'register[email]' : {
                required: "Gelieve uw e-mailadres in te geven",
                customemail: "Gelieve het correct e-mailadres in te geven",
            },
            'register[password]': {
                required: "Gelieve uw wachtwoord in te geven",
                minlength: "Gelieve minimum 8 karakters in te geven",
            },
            'register[phone]': {
                required: "Gelieve uw telefoonnummer in te geven",
                number : "Gelieve enkel cijfers in te geven",
                minlength : "Gelieve exact 10 cijfers in te geven",
                maxlength : "Gelieve exact 10 cijfers in te geven",
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
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('home/register'); ?>",
                data: $("#registerform").serialize(),
                beforeSend: function () {
                    $(".loading ").removeClass('hide');
                },
                success: function (data) {
                    $(".loading ").addClass('hide');
                    var res = JSON.parse(data);
                    if (res.token == 1) {
                        form.submit();
                        window.location.href="<?php echo Yii::app()->createUrl('home/'); ?>";
                    }
                    else{
                        $("#register_email").parent().addClass('has-error');
                        $("#register_email").parent().append("<span id='register_email-error' style='margin-left:0px;' class='help-block'>Dit e-mailadres is reeds geregistreerd.</span>");
                        $("#register_email").parent().removeClass("form-group");
                        return false;
                    }
                }
            });

        }
    });
</script>
