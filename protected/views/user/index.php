<!--page title start-->
<section class="ImageBackground ImageBackground--overlay v-align-parent u-height350 u-BoxShadow40" data-overlay="5">
    <div class="ImageBackground__holder"> <img src="<?php echo Yii::app()->baseUrl."/plugins/imgs/banner/ban-home.jpg"; ?>" alt=""/> </div>
    <div class="v-align-child">
        <div class="container ">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <h1 class="text-uppercase u-Margin0 u-Weight700 text-white">Mijn gegevens</h1>
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
                    <li class="active"><span>Mijn gegevens</span></li>
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
                <h1 class="u-MarginBottom10 u-Weight700 text-uppercase">Mijn gegevens</h1>
                <div class="Split Split--height2"></div>
            </div>
        </div>
        <form class="js-ContactForm"  name="user-update" id="user_update">
            <div class="row text-gray wow fadeInUp" data-wow-delay="200ms">
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php
                                $male = $female = '';
                                if ($user->gender == 1){
                                    $male = 'checked';
                                }else{
                                    $female = 'checked';
                                } ?>
                                <label>Aanhef *</label>
                                <div>
                                    <div class="radio">
                                        <input type="radio" value="1" name="profile[gender]" <?php echo $male?>>
                                        <label for="radio1">Mannelijk</label>
                                    </div>
                                    <div class="radio">
                                        <input type="radio" value="2" name="profile[gender]" <?php echo $female; ?> >
                                        <label for="radio2">Vrouwelijk</label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Voornaam *</label>
                                <input class="form-control " type="text" id="profile-firstname" name="profile[first_name]" placeholder="uw voornaam in te geven.." value="<?php echo $user->first_name;?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Familienaam *</label>
                                <input class="form-control " type="text" id="profile-lastname" name="profile[last_name]" placeholder="uw familienaam in te geven.." value="<?php echo $user->last_name;?>">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Bedrijfsnaam</label>
                                <input class="form-control " type="text" id="profile-businessname" name="profile[business_name]" placeholder="Geef uw bedrijfsnaam ine.." value="<?php echo $user->business_name;?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Land *</label>
                                <select class="form-control" id="profile-country" name="profile[country]">
                                <?php
                                 $countries = Yii::app()->ServiceHelper->getCountry();
                                 foreach($countries as $key=>$country){
                                     if($user->country == $key){ ?>
                                        <option value="<?php echo $key; ?>" selected><?php echo $country; ?></option>
                                     <?php } else { ?>
                                        <option value="<?php echo $key; ?>"><?php echo $country; ?></option>
                                     <?php }
                                 }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Postcode en gemeente *</label>
                                <input class="form-control " type="text" id="profile-postcode" name="profile[postcode]" placeholder="uw postcode in te geven.." value="<?php echo $user->postcode?>">
                                <div class="text-sm text-gray u-MarginTop5">Type je postcode en gemeente (Bijv. 2000 Antwerpen)</div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Straatnaam *</label>
                                <input class="form-control " type="text" id="profile-street" name="profile[street]" placeholder="Geef uw straatnaam in.." value="<?php echo $user->street;?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>regio *</label>
                                <input class="form-control " type="text" id="profile-region" name="profile[region]" placeholder="Geef uw regio in.." value="<?php echo $user->region;?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Huisnummer en bus *</label>
                                <input class="form-control " type="text" id="profile-building_num" name="profile[building_num]" placeholder="Geef uw huisnummer in.." value="<?php echo $user->building_num;?>">
                                <div class="text-sm text-gray u-MarginTop5">Bijv. "10-C"</div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Stad</label>
                                <input class="form-control " type="text" id="profile-city" name="profile[city]" placeholder="Geef uw stad in.." value="<?php echo $user->city;?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Telefoonnummer</label>
                                <input class="form-control " type="text" id="profile-phone" name="profile[phone]" placeholder="uw telefoonnumer in te geven.." value="<?php echo $user->phone;?>">
                                <div class="text-sm text-gray u-MarginTop5">(vul hier het telefoonnummer in waarop je overdag bereikbaar bent)</div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary u-MarginRight10">Opslaan</button>
                            <a href="javascript:void(0);" onclick="history.go(-1);" class="btn btn-primary">Annuleren</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
</section>
<!--intro end-->

<script>

    $("form[id='user_update']").validate({
        debug: true,
        errorClass: "help-block",
        errorElement: "div",
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            'profile[first_name]': {
                required: true,
                lettersonly: true
            },
            'profile[last_name]': {
                required: true,
                lettersonly: true
            },
            'profile[email]': {
                required: true,
                email: true
            },
            'profile[date_of_birth]': {
                required: true
            },
            /*'profile[passport_no]': {
                required: true
            }*/
        },
        messages: {
            'profile[first_name]': {
                required: "Gelieve uw voornaam in te geven",
                //lettersonly: "Gelieve enkel letters in te geven"
            },
            'profile[last_name]': {
                required: "Gelieve uw familienaam in te geven",
                //lettersonly: "Gelieve enkel letters in te geven"
            },
            'profile[email]': {
                required: "Gelieve uw e-mailadres in te geven",
                email: "Gelieve het correct e-mailadres in te geven"
            },
            'profile[date_of_birth]': {
                required: "Gelieve uw geboortedatum in te geven"
            },
            /*'profile[passport_no]': {
                required: "Please enter passport number"
            }*/
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
            var UpdateUser = '<?php echo Yii::app()->createUrl('User/Update')?>';
            $.ajax({
                type: "POST",
                url: UpdateUser,
                data: $('form').serialize(),
                beforeSend: function () {
                    $(".overlay").removeClass("hide");
                },
                success: function (data) {
                    var res = jQuery.parseJSON(data);
                    if (res.token == 1) {
                        $(".overlay").addClass("hide");
                        toastr.success("Profile updated successfully");
                        window.scrollTo(0,0);
//                        $("#update-msg").focus();
//                        window.location.reload();
                        //$("#myElem").show().delay(5000).fadeOut();
                    }
                }
            });
        }
    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "Only alphabetical characters");

    /*jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z]+$/i.test(value);
    }, "Letters only please");*/

    $("#UserInfo_image").change(function () {
        $( "#upload-form-1" ).submit();
    });
    $("#imgArea").hover(function () {
        $( "#imgChange" ).toggle();
    });

</script>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.validate.min.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/additional-methods.min.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.md5.js', CClientScript::POS_BEGIN);
?>