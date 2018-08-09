<?php
/* @var $this TestimonialController */
/* @var $model Testimonial */

?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Testimonial
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('testimonial/admin'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>"margin-top:-3%")); ?>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            /*'id',*/
                            'Title',
                            [
                                'name' => 'description',
                                'value' => (($model->description == null) ? "No Description": $model->description),
                                'type' => 'raw'
                            ],
                            [
                                'name' => 'media',
                                'value' => (($model->media == null) ? "No Image": '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">'.
                                    CHtml::image(Yii::app()->baseUrl.$model->media,'No Image',
                                        ['height' => 100,'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                                    ).'</div>'),
                                'type' => 'raw'

                            ],
                            'category',
                            'type',
                            'created_at',
                            /*'modified_at',*/
                        ),
                    )); ?>
                </div>
            </div>
            <!--end::Section-->
        </div>
    </div>
</div>
