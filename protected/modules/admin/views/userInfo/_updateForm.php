<?php
/* @var $this UserInfoController */
/* @var $model UserInfo */
/* @var $form CActiveForm */
?>
<style>
    #RadioCompany{
        display: inline;
    }
</style>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Update Customer
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
                        Customer information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('userInfo/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
        </div>
        <!--begin::Form-->
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id' => 'users-info-form',
                    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'name' => 'UserCreate'
                    )
                ));
                ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-4 <?php echo $model->hasErrors('first_name') ? 'has-error' : ''; ?>">
                    <?php echo $form->textFieldControlGroup($model, 'first_name', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'First Name')); ?>
                </div>
                <div class="col-md-4  <?php echo $model->hasErrors('last_name') ? 'has-error' : ''; ?>">
                    <?php echo $form->textFieldControlGroup($model, 'last_name', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Last Name')); ?>
                </div>
                <div class="col-md-4 <?php echo $model->hasErrors('full_name') ? 'has-error' : ''; ?>">
                    <?php echo $form->textFieldControlGroup($model, 'full_name', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control', 'placeholder' => 'Full Name', 'readOnly'=>true)); ?>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-4 email-validate <?php echo $model->hasErrors('email') ? 'has-error' : ''; ?>">
                    <?php echo $form->textFieldControlGroup($model, 'email', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                    <p id='email-valid' class='animated fadeInDown  help-block'>Please enter valid Email address.</p>                    </div>

                <?php if($model->user_id != 1){ ?>
                <div class="col-md-4 <?php echo $model->hasErrors('role') ? 'has-error' : ''; ?>">
                    <div class="controls">
                        <?php echo $form->label($model, 'role', array('class' => 'control-label')); ?>
                        <span class="required">*</span>
                        <?php $list = CHtml::listData(Roles::model()->findAll(),'role_title','role_title');
                            echo $form->dropDownList($model, 'role', $list, array('class' => 'form-control',
                                'empty' => 'Select Role'));
                            ?>
                        <span class="help-block"><?php echo $form->error($model, 'role'); ?></span>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <hr>
                    <h4>Personal Information</h4>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <?php echo $form->labelEx($model, 'gender', array('class' => 'control-label')); ?>
                    <div class="col-md-12 <?php echo $model->hasErrors('gender') ? 'has-error' : ''; ?>" >
                        <div class="radio">
                            <?php echo $form->radioButtonList($model, 'gender', array(
                                    '1' => 'Male', '2' => 'Female',
                                ),array(
                                    'labelOptions'=>array('style'=>'display:inline'), // add this code
                                    'separator'=>'  ')); ?>

                        </div>
                        <span class="help-block"><?php $form->error($model, 'gender'); ?></span>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('sponsor_id') ? 'has-error' : ''; ?>">
                            <div class="controls">
                                <?php echo $form->label($model, 'sponsor_id', array('class' => 'control-label')); ?>
                                <span class="required">*</span>
                                <?php $list = CHtml::listData(UserInfo::model()->findAll(), 'user_id', 'full_name');
                                    echo $form->dropDownList($model, 'sponsor_id', $list, array('class' => 'form-control',
                                        'empty' => 'Select Sponsor'));
                                    ?>
                                <span
                                        class="help-block"><?php $form->error($model, 'sponsor_id'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('is_enabled') ? 'has-error' : ''; ?>">
                            <?php echo $form->dropDownListControlGroup($model, 'is_enabled', array('1' => 'Yes', '0' => 'No'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'is_enabled'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('date_of_birth') ? 'has-error' : ''; ?>">
                            <?php echo $form->labelEx($model, 'dateOfBirth', array('class' => 'control-label')); ?>
                            <span class="required" aria-required="true">*</span>
                            <?php
                                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                    'model' => $model,
                                    'attribute' => 'date_of_birth',
                                    //'value'=>$model->dateOfBirth,
                                    // additional javascript options for the date picker plugin
                                    'options' => array(
                                        'showAnim' => '',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                                        'dateFormat' => 'yy-mm-dd',
                                        'maxDate' => date('Y-m-d'),
                                        'changeYear' => true,           // can change year
                                        'changeMonth' => true,
                                        'yearRange' => '1900:' . date('Y'),
                                    ),
                                    'htmlOptions' => array(
                                        'class' => 'form-control',
                                        'readOnly' => true
                                        //'style'=>'height:20px;background-color:green;color:white;',
                                    ),
                                ));
                                ?>
                            <span class="help-block"><?php echo $form->error($model, 'date_of_birth'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('is_active') ? 'has-error' : ''; ?>">
                            <?php echo $form->dropDownListControlGroup($model, 'is_active', array('1' => 'Yes', '0' => 'No'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'is_active'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 custom_fields">
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <h4>Address</h4>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('building_num') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'building_num', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('street') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'street', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('region') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'region', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('city') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'city', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('postcode') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'postcode', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('country') ? 'has-error' : ''; ?>">
                            <?php echo $form->dropDownListControlGroup($model, 'country', Yii::app()->ServiceHelper->getCountry(), array('prompt' => 'Select Country', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group <?php echo $model->hasErrors('phone') ? 'has-error' : ''; ?>">
                            <?php echo $form->textFieldControlGroup($model, 'phone', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            <p id='phone-valid' class='help-block'>Please enter valid Phone number.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-12">
                    <h4>Billing Info</h4>
                </div>
                <div class="col-md-12" id="RadioCompany">
                    <div class="col-md-6">
                        <input type="radio" value="1" name="Business" />
                        <label> Company</label>
                        <input type="radio" value="2" name="Business"/>
                        <label> Personal Affiliate</label>
                    </div>
                </div>
            </div>
            <div class="hiddendefault">
                <div class="form-group m-form__group row">
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('business_name') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'business_name', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('vat_number') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'vat_number', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <input type="checkbox" id="diff_address" name="Diffrent Address">
                    <label id="check_box">Use Different Address</label>
                </div>
            </div>

            <div id="companyAddress">
                <div class="form-group m-form__group row" >
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('busAddress_building_num') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'busAddress_building_num', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('busAddress_street') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'busAddress_street', array('class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('busAddress_region') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'busAddress_region', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('busAddress_city') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'busAddress_city', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('busAddress_postcode') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'busAddress_postcode', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('busAddress_country') ? 'has-error' : ''; ?>">
                                <?php echo $form->dropDownListControlGroup($model, 'busAddress_country', Yii::app()->ServiceHelper->getCountry(), array('prompt' => 'Select Country', 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group <?php echo $model->hasErrors('business_phone') ? 'has-error' : ''; ?>">
                                <?php echo $form->textFieldControlGroup($model, 'business_phone', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
                                <p id='business-phone-valid' class='help-block'>Please enter valid Phone number.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                            'class' => 'btn btn-primary'
                        )); ?>
                    <?php echo CHtml::link('Cancel', array('userInfo/admin'),
                            array(
                                'class' => 'btn btn-default'
                            )
                        );
                        ?>
                </div>
            </div>
        </div>
        <!-- form -->
        <?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        $('#UserInfo_business_name').hide();
        $("#UserInfo_vat_number").hide();
        var label = $("label[for='"+$('#UserInfo_vat_number').attr('id')+"']");
        label.hide();
        var label1 = $("label[for='"+$('#UserInfo_business_name').attr('id')+"']");
        label1.hide();
        $("#diff_address").hide();
        $("#check_box").hide();
        $("#companyAddress").hide();
    });

    $('#users-info-form input').on('change', function() {
        var value = ($('input[name=Business]:checked', '#users-info-form').val());
        if(value == 1){
            $('#UserInfo_business_name').show();
            $("#UserInfo_vat_number").show();
            var label = $("label[for='"+$('#UserInfo_vat_number').attr('id')+"']");
            label.show();
            var label1 = $("label[for='"+$('#UserInfo_business_name').attr('id')+"']");
            label1.show();
            $("#diff_address").show();
            $("#check_box").show();
            if ($('#diff_address').is(":checked")){
                $("#companyAddress").show();
            }
            else {
                $("#companyAddress").hide();
            }

        }
        else{
            //console.info('individual');
            $('#UserInfo_business_name').hide();
            $("#UserInfo_vat_number").hide();
            var label = $("label[for='"+$('#UserInfo_vat_number').attr('id')+"']");
            label.hide();
            var label1 = $("label[for='"+$('#UserInfo_business_name').attr('id')+"']");
            label1.hide();
            $("#diff_address").hide();
            $("#check_box").hide();
            $("#companyAddress").hide();
        }

    });
</script>