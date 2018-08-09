<?php
/* @var $this TicketingController */
/* @var $model Ticketing */
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Ticket
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right" style="margin-bottom: 1%">
                <?php echo CHtml::link('Go to list', array('ticketing/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php echo CHtml::link('Create', array('ticketing/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php echo CHtml::link('Update', array('ticketing/update/'.$model->ticket_id), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            'ticket_id',
                            'title',
                            'ticket_detail',
                            'description',
                            [
                                'name' => 'status',
                                'value' => function($model){
                                    if($model->status == 0){
                                        return 'In Progress';
                                    }
                                    else{
                                        return 'Done';
                                    }
                                },
                            ],
                            [
                                'name' => 'attachment',
                                'value' => function($model) {
                                    $images = json_decode($model->attachment);
                                    $displayimages = "";
                                    if(!empty($images)){
                                        foreach ($images as $image){
                                            $displayimages .= '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">' .
                                                CHtml::image(Yii::app()->baseUrl . $image, 'No Image',
                                                    ['height' => 100, 'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                                                ) . '</div>';
                                        }
                                    }
                                    return $displayimages;
                                },
                                'type' => 'raw'

                            ],
                            'created_at',
                            'modified_at',
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>