<?php
/* @var $this FbFeedController */
/* @var $model FbFeed */

$this->pageTitle = 'View Facebook Feed';
$id = $model->id;
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Facebook Feed
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('fbFeed/index'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php echo CHtml::link('Create', array('fbFeed/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php echo CHtml::link('Update', array('fbFeed/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary'));?>
                <p></p>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            'title',
                            'description',
                            [
                                'label' => 'is_enabled',
                                'value' => function($model){
                                    if ($model->is_enabled == 0){
                                        return 'No';
                                    }else{
                                        return 'Yes';
                                    }
                                }
                            ],
                            'created_at',
                            'source'
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
