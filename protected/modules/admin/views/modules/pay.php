<?php
$this->pageTitle = "Buy " . $model['name'];
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                <?php echo $this->pageTitle;?>
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet">
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <form action="<?php echo Yii::app()->createUrl('/admin/modules/pay/').'/'.$model['product_id'] ;?>" method="post" id="payment-form" name="order-form">
                        <div class="form-group m-form__group row">
                            <div class="col-md-4">
                                <label for="name" class="control-label">Company name</label>
                                <br>
                                <input id="name" name="company" value="" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="address_line_1" class="control-label">Street</label>
                                <br>
                                <input id="address_line_1" name="street" value="" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="address_line_2" class="control-label">Region</label>
                                <input id="address_line_2" name="region" value="" class="form-control">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-md-4">
                                <label for="city" class="control-label">City</label>
                                <input id="city" name="city" value="" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="country" class="control-label">Country</label>
                                <select class="form-control select2-later" style="padding:line-height:inherit !important;" data-vat="country" name="country">
                                    <option value=""></option>
                                    <?php
                                    $countries = Countries::model()->findAll();
                                    foreach ($countries as $value) {
                                        ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->country_name; ?></option>;
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="postcode" class="control-label">Postcode</label>
                                <input id="postcode" name="postcode" value="" class="form-control" data-vat="postal-code">
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <div class="col-md-12" style="padding: 15px">
                                <input type="checkbox"  id="vat_payer" name="vat_payer">
                                <label>I am VAT payer <em>(if you don't know what it is, leave unchecked)</em></label>
                                <div class="form-group hidden" id="vat_text">
                                    <label for="vat_number" class="col-md-4 control-label">VAT number</label>
                                    <div class="col-md-6">
                                        <input id="vat_number" name="vat_number"  class="form-control" data-vat="vat-number"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-md-12">
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <div class="h4">Price: &euro;<span class="vat-subtotal"><?php echo $model['price'];?></span></div>
                                        <!--<div class="h4 voucher_discount hidden">
                                            Discount: $<span id="voucher-discount"></span>
                                        </div>-->
                                        <?php $vat = ($model['price'])*14/100;
                                        $total = $model['price']+$vat;?>
                                        <div class="h4">VAT (<span class="vat-taxrate">14</span>%): &euro;<span class="vat-taxes"><?php echo $vat;?></span></div>
                                        <div class="h4"><strong>Total Price: &euro;<span class="vat-total"><?php echo $total;?></span></strong></div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-xs-4 form-group">
                                        <button class="pay-button btn btn-success" type="submit" style="font-size: 18px">
                                            <span class="pay-button-text">Pay</span>
                                        </button>
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-md-12">
                                <strong>Notice:</strong> <em>We will charge you <strong>only once</strong>, it's not automatic recurring billing. We'll be in touch about renewal for 2nd year.</em>
                                <br><br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.validate.min.js'; ?>" ></script>
<script>
    $('#vat_payer').on('click', function() {
        if ($('#vat_payer').is(":checked")) {
            $("#vat_text").removeClass("hidden");
        }
        else {
            $("#vat_text").addClass("hidden");
        }
    });


    $("#payment-form").validate({
        debug: true,
        errorClass: "help-block",
        errorElement: "div",
        onfocusout: false,
        onkeyup: false,
        rules:{
            'company' : {
                required: true,
            },
            'street': {
                required: true,
            },
            'city': {
                required: true,
            },
            'country': {
                required: true,
            },
            'postcode': {
                required: true,
                number: true
            }
        },
        messages:{
            'company' : {
                required: "Please enter company name"
            },
            'street': {
                required: "Please enter street name"
            },
            'city': {
                required: "Please enter city name",
            },
            'country': {
                required: "Please select country name",
            },
            'postcode': {
                required: "Please enter postcode number",
                number: "Please enter only numeric value"
            }
        },
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).parent().addClass('has-error');
            //$('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).parent().parent().removeClass('has-error');
        },
        submitHandler:function (form) {
            form.submit();
        }
    });

</script>