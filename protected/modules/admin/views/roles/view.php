<?php
/* @var $this RolesController */
/* @var $model Roles */
$this->pageTitle = "Role : ".$model->role_title;
$id = $model->role_id;
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
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('Roles/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php echo CHtml::link('Create', array('Roles/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php echo CHtml::link('Update', array('Roles/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary'));?>
                <p></p>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            'role_id',
                            'role_title',
                            'created_at',
                            'modified_at',
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>

