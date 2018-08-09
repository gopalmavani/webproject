<?php
/* @var $this RolesController */
/* @var $model Roles */
/* @var $form CActiveForm */
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Role
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('Roles/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
        </div>
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'roles-form',
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            'enableAjaxValidation'=>false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'name' => 'pool-plan-form',
            )
        )); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="row">
                    <div class="controls">
                        <div class="col-md-6 <?php echo $model->hasErrors('role_title') ? 'has-error' : ''; ?>">
                            <?php echo $form->labelEx($model,'role_title'); ?>
                            <?php echo $form->textField($model,'role_title',array('size'=>50,'maxlength'=>50)); ?>
                            <span class="help-block"><?php echo $form->error($model,'role_title'); ?></span>
                        </div>
                    </div>
                </div>

                <!--<div class="row">
                <?php /*echo $form->labelEx($model,'created_at'); */?>
                <?php /*echo $form->textField($model,'created_at'); */?>
                <?php /*echo $form->error($model,'created_at'); */?>
            </div>-->

                <!--<div class="row">
                <?php /*echo $form->labelEx($model,'modified_at'); */?>
                <?php /*echo $form->textField($model,'modified_at'); */?>
                <?php /*echo $form->error($model,'modified_at'); */?>
            </div>-->
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                        'class' => 'btn btn-primary col-md-offset-2',
                        'id' => 'create_roles'
                    )); ?>
                    <?php echo CHtml::link('Cancel', array('Roles/admin'),
                        array(
                            'class' => 'btn btn-default'
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>