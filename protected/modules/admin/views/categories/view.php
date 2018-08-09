<?php
/* @var $this CategoriesController */
/* @var $model Categories */

$id = $model->category_id; 
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View <?php echo $model->category_name; ?> 
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('categories/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
                <?php echo CHtml::link('Create', array('categories/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
                <?php echo CHtml::link('Update', array('categories/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary')); ?>                <p></p>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'htmlOptions' => array('class' => 'table table-striped m-table'),
                    'attributes'=>array(
                    'category_id',
                    'category_name',
                    'description',
                    [
                    'name' => 'image',
                    'value' => (($model->image == null) ? "No Image": '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">'.
                        CHtml::image(Yii::app()->baseUrl.$model->image,'No Image',
                        ['height' => 100,'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                        ).'</div>'),
                    'type' => 'raw'

                    ],
                    'is_active',
                    'created_at',
                    'modified_at',
                    'is_delete',
                    'is_parent',
                    'parent_id',
                    ),
                    )); ?>
                </div>
            </div>
            <!--end::Section-->
        </div>
    </div>
</div>
