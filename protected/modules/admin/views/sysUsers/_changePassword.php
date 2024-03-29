<div class="row">
    <div class="col-lg-12">
        <div class="block">
            <div class="block-content block-content-narrow">
                <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id' => 'sysUsersChangePassword',
                    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                    'enableAjaxValidation' => false,
                ));
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Change Password</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-material has-error">
                            <p id="passwordError" class="help-block has-error" style="display: none;"></p>
                        </div>
                        <div class="form-material has-success">
                            <p id="passwordMessage" class="help-block " style="display: none;"></p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <div class="control-group">
                                    <label class="control-label required" for="SysUsers_newPassword">New Password <span class="required">*</span></label>
                                    <div class="controls">
                                        <input maxlength="50" class="form-control input-50" name="SysUsers[newPassword]" id="SysUsers_newPassword" type="password">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group ">
                                <div class="control-group">
                                    <label class="control-label required" for="SysUsers_confirmPassword">Confirm Password <span class="required">*</span></label>
                                    <div class="controls">
                                        <input maxlength="50" class="form-control input-50" name="SysUsers[confirmPassword]" id="SysUsers_confirm_password" type="password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row col-md-12">
                    <div class="form-group">
                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Change' : 'Save', array(
                            'class' => 'btn btn-primary',
                        )); ?>
                        <?php echo CHtml::link('Cancel', array('sysUsers/admin'),
                            array(
                                'class' => 'btn btn-default'
                            )
                        );
                        ?>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    var changePassword = '<?php  echo Yii::app()->createUrl('sysUsers/changePassword/'.$model->id);  ?>';
</script>
