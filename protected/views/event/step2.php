<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 24/7/18
 * Time: 12:54 PM
 */
$this->pageTitle = "Book Now";
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
                        <h1 class="u-MarginTop150 u-xs-MarginTop100 u-MarginBottom30 u-Weight700 text-uppercase wow fadeInUp" data-wow-delay="200ms">Enter Address</h1>
                        <div class="Split Split--height2 u-MarginBottom60 wow fadeInUp" data-wow-delay="200ms"></div>
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                                <div class="reserveer-form u-Padding30 wow fadeInUp" data-wow-delay="200ms">
                                    <form name="reserveer" action="<?php echo Yii::app()->createUrl("/event/step2"); ?>" class="text-gray text-left" method="post" id="step2form">
                                        <div class="row">
                                            <div class="col-sm-12" style="margin-bottom:20px">
                                                <label>Name</label>
                                                <span style="color:#666;"><?php if(isset($user)){echo  $user->full_name;} else{ echo $_SESSION['myguestuser']['myfirstname']." ".$_SESSION['myguestuser']['mylastname']; } ?></span>
                                            </div>
                                            <div class="col-sm-6 ">
                                                <div class="form-group">
                                                    <label>Street</label>
                                                    <input type="text" class="form-control" placeholder="" name="step2[street]" value="<?php  if(isset($user)){ echo $user->street;}elseif(isset($_SESSION['myguestuser']['mystreet'])){ echo $_SESSION['myguestuser']['mystreet']; }?>">
                                                </div>
                                            </div>
                                            <!--<div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label>Region</label>
                                                    <input type="text" class="form-control" placeholder="" name="step2[region]" value="<?/*= $user->region;*/?>">
                                                </div>
                                            </div>-->
                                            <div class="col-sm-6 ">
                                                <div class="form-group">
                                                    <label>House Number</label>
                                                    <input type="text" class="form-control" placeholder="" name="step2[building_num]" value="<?php  if(isset($user)){ echo $user->building_num;}elseif(isset($_SESSION['myguestuser']['mybuilding_num'])){ echo $_SESSION['myguestuser']['mybuilding_num']; }?>">
                                                    <div class="text-sm text-gray u-MarginTop5">Bijv. "10-C"</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6" style="max-height: 100px;">
                                                <div class="form-group">
                                                    <label>Postcode</label>
                                                    <input type="text" class="form-control" placeholder="" name="step2[postcode]" value="<?php  if(isset($user)){ echo $user->postcode;}elseif(isset($_SESSION['myguestuser']['mypostcode'])){ echo $_SESSION['myguestuser']['mypostcode']; }?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>Country</label>
                                                    <select class="form-control" name="step2[country]">
                                                        <?php
                                                        $countries = Yii::app()->ServiceHelper->getCountry();
                                                        if(isset($user)){
                                                            foreach($countries as $key=>$country){
                                                                if($user->country == $key){ ?>
                                                                    <option value="<?php echo $key; ?>" selected><?php echo $country; ?></option>
                                                                <?php } else { ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $country; ?></option>
                                                                <?php }
                                                            }
                                                        }
                                                        elseif(isset($_SESSION['myguestuser']['mycountry'])){
                                                            foreach($countries as $key=>$country){
                                                                if($_SESSION['myguestuser']['mycountry'] == $key){ ?>
                                                                    <option value="<?php echo $key; ?>" selected><?php echo $country; ?></option>
                                                                <?php } else { ?>
                                                                    <option value="<?php echo $key; ?>"><?php echo $country; ?></option>
                                                                <?php }
                                                            }
                                                        }
                                                        else{
                                                            foreach($countries as $key=>$country){ ?>
                                                                <option value="<?php echo $key; ?>"><?php echo $country; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>City</label>
                                                    <input type="text" class="form-control" placeholder="" name="step2[city]" value="<?php  if(isset($user)){ echo $user->city;}elseif(isset($_SESSION['myguestuser']['mycity'])){ echo $_SESSION['myguestuser']['mycity']; }?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>Contact Number</label>
                                                    <input type="text" class="form-control" placeholder="" name="step2[phone]" value="<?php  if(isset($user)){ echo $user->phone;}elseif(isset($_SESSION['myguestuser']['myphone'])){ echo $_SESSION['myguestuser']['myphone']; }?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 text-center">
                                                <button type="submit" class="btn btn-primary">Submit</button>
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
</section>



<script type="text/javascript">
    $.validator.addMethod("customemail",
        function(value, element) {
            return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
        },
        "Sorry, I've enabled very strict email validation"
    );

    $("form[id='step2form']").validate({
        debug: true,
        errorClass: "help-block",
        errorElement: "div",
        onfocusout: false,
        onkeyup: false,
        onclick:false,
        rules:{
            'step2[street]' : {
                required: true,
            },
            /*'step2[region]' : {
                required: true,
            },*/
            'step2[building_num]' : {
                required: true,
            },
            'step2[postcode]': {
                required: true,
            },
            'step2[country]' : {
                required: true,
            },
            'step2[city]' : {
                required: true,
            },
            'step2[phone]': {
                required: true,
                number : true,
                minlength : 10,
                maxlength : 10,
            },
        },
        messages:{
            'step2[street]' : {
                required: "Please enter street",
            },
            /*'step2[region]' : {
                required: "Please enter region",
            },*/
            'step2[building_num]' : {
                required: "Please enter building number",
            },
            'step2[postcode]': {
                required: "Gelieve uw postcode in te geven."
            },
            'step2[country]' : {
                required: "Please select country",
            },
            'step2[city]' : {
                required: "Please enter city",
            },
            'step2[phone]': {
                required: "Gelieve uw telefoonnumer in te geven",
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
            form.submit();
        }
    });
</script>