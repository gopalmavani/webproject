<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Settings
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet">
        <form method="post" action="<?php echo Yii::app()->createUrl('/admin/mt4/updatemt4'); ?>" id="mt4account-form" >
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <div class="col-md-6">
                        <div class="col-md-3">
                            <label>Start Date : </label>
                        </div>
                        <div class="col-md-9" style="margin-left: -8%">
                            <?php
                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'name' => 'start_date',
                                'options' => array(
                                    'showAnim' => '',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                                    'dateFormat' => 'yy-mm-dd',
                                    'maxDate' => date('Y-m-d'),
                                    'changeYear' => true,           // can change year
                                    'changeMonth' => true,
                                    'yearRange' => '1900:2100',
                                ),
                                'htmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-3">
                            <label>End Date : </label>
                        </div>
                        <div class="col-md-9" style="margin-left: -10%">
                            <?php
                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'name' => 'end_date',
                                'id' => 'end_date',
                                'options' => array(
                                    'showAnim' => '',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                                    'dateFormat' => 'yy-mm-dd',
                                    'maxDate' => date('Y-m-d'),
                                    'changeYear' => true,           // can change year
                                    'changeMonth' => true,
                                    'yearRange' => '1900:2100',
                                ),
                                'htmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <div class="col-md-6">
                        <div class="col-md-3">
                            <label>Agent Id : </label>
                        </div>
                        <div class="col-md-9" style="margin-left: -8%">
                            <input class="form-control" id="agent" name="agent"/>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row" align="center">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Update Mt4</button>
                    </div><br/>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js', CClientScript::POS_HEAD);
?>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.validate.min.js'; ?>" ></script>
<script>
    $("#start_date").on("change", function()
    {
        $('#end_date').datepicker({
            dateFormat: 'yy-mm-dd',
            //showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '1999:2030',
            //showOn: "button",
            //buttonImage: "images/calendar.gif",
            //buttonImageOnly: true,
            minDate: /*new Date(1999, 10 - 1, 25)*/ $("#start_date").val(),
            maxDate: new Date(),
            inline: true
        });
    });

    $("#mt4account-form").validate({
        debug: true,
        errorClass: "help-block",
        errorElement: "div",
        onfocusout: false,
        onkeyup: false,
        rules:{
            'start_date' : {
                required: true,
            },
            'end_date': {
                required: true,
            },
            'agent': {
                required: true,
                number: true
            }
        },
        messages:{
            'start_date' : {
                required: "Please select start date"
            },
            'end_date': {
                required: "Please select end date"
            },
            'agent': {
                required: "Please enter agent id",
                number: "Please enter only numeric value"
            }
        },
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).parent().parent().addClass('has-error');
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