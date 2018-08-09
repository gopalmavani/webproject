<style>
    .error{
        color: #B94B48;
    }
</style>
<!--page title start-->
<section class="ImageBackground ImageBackground--overlay v-align-parent u-height350 u-BoxShadow40" data-overlay="5">
    <div class="ImageBackground__holder"> <img src="<?php echo Yii::app()->baseUrl."/plugins/imgs/banner/ban-home.jpg"; ?>" alt=""/> </div>
    <div class="v-align-child">
        <div class="container ">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <h1 class="text-uppercase u-Margin0 u-Weight700 text-white">Paswoord veranderen</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<!--page title end-->


<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ol class="breadcrumb u-MarginTop20 u-MarginBottom20">
                    <li><a href="<?php echo Yii::app()->createUrl("/"); ?>">Home</a></li>
                    <li class="active"><span>Paswoord veranderen</span></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!--intro start-->
<section class="u-MarginTop100 u-xs-MarginTop30 u-MarginBottom100 u-xs-MarginBottom30 position-relative">
    <div class="container">
        <div class="row u-MarginBottom30">
            <div class="col-md-12 wow fadeInUp" data-wow-delay="200ms">
                <h1 class="u-MarginBottom10 u-Weight700 text-uppercase">PASWOORD VERANDEREN</h1>
                <div class="Split Split--height2"></div>
            </div>
        </div>
        <form class="js-ContactForm"  name="password-change" id="password_change1">
            <div class="row text-gray wow fadeInUp" data-wow-delay="200ms">
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><strong>Huidig wachtwoord </strong></label>
                                        <input type="hidden" id="current_password_hidden" name="current-password-hidden" value="<?php echo $user->password?>">
                                        <input class="form-control" type="password" id="current_password" name="current-password" placeholder="Huidig wachtwoord ingeven.." value="">
                                    </div>
                                </div>
                                <div class="col-sm-6"></div>
                                <div class="clearfix"></div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><strong>Nieuw wachtwoord </strong></label>
                                        <input class="form-control" type="password" id="new_password" name="new-password" placeholder="Nieuw wachtwoord ingeven.." value="">
                                    </div>
                                </div>
                                <div class="col-sm-6"></div>
                                <div class="clearfix"></div>
                                <div class="col-sm-6">
                                    <label><strong>Bevestig nieuw wachtwoord </strong></label>
                                    <input class="form-control" type="password" id="confirm-pass" name="confirm-password" placeholder="Enter confirm password.." value="">
                                </div>
                                <div class="col-sm-6"></div>
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <button type="submit" class="btn btn-primary u-MarginRight10">Opslaan</button>
                                    <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-primary">Annuleren</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.validate.min.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/additional-methods.min.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.md5.js', CClientScript::POS_BEGIN);
?>
<script>
    $("#current_password").change(function () {
        var newVal = MD5($("#current_password").val());
        $("#current_password").val(newVal);
    });

    var ChangePassword = '<?php echo Yii::app()->createUrl('user/UpdatePassword')?>';

    $("#password_change1").validate({
        rules: {
            'current-password': {
                required: true,
                equalTo: '#current_password_hidden'
            },
            'new-password': {
                required: true,
                minlength: 8
            },
            'confirm-password': {
                required: true,
                equalTo: '#new_password'
            }
        },
        messages: {
            'current-password': {
                required: 'Gelieve uw huidig wachtwoord in te geven',
                equalTo: 'Het wachtwoord is niet juist'
            },
            'new-password': {
                required: 'Gelieve een nieuw wachtwoord in te geven',
                minlength: 'Uw wachtwoord dient minimum 8 karakters te bevatten'
            },
            'confirm-password': {
                required: 'Gelieve uw wachtwoord opnieuw te bevestigen',
                equalTo: 'Gelieve hetzelfde wachtwoord in te geven als hierboven'
            }
        },
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).parent().parent().addClass('has-error');
            //$('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).parent().parent().removeClass('has-error');
        },
        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                url: ChangePassword,
                data: $('form').serialize(),
                beforeSend: function () {
                    $(".overlay").removeClass("hide");
                },
                success: function (data) {
                    var res = jQuery.parseJSON(data);
                    if (res.token == 1) {
                        $(".overlay").addClass("hide");
                        toastr.success("Password updated successfully");
                        $("#update-msg").show().delay(5000).fadeOut();
                        window.scrollTo(0,0);
                    }
                }
            });
        }
    });
</script>
