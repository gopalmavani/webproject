<?php
/* @var $this ResourcesController */
/* @var $model Resources */

$this->pageTitle = "<span lang='en'>View Resource</span>";
?>


<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View <?= $model->resource_name; ?>
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('resources/admin'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>"margin-top:-3%")); ?>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            /*'resource_id',*/
                            'resource_name',
                            [
                                'name' => 'resource_description',
                                'value' => (($model->resource_description == null) ? "No Description": $model->resource_description),
                                'type' => 'raw'
                            ],
                            'resource_address',
                            [
                                'name' => 'is_available',
                                'value' =>(($model->is_available == 0) ? "<span class='label label-danger'>Not available</span>":"<span class='label label-success'>Available</span>"),
                                'type' => 'raw',
                            ],
                            'created_at',
                            'modified_at',
                        ),
                    )); ?>
                </div>
            </div>
            <!--end::Section-->
        </div>
    </div>
</div>
