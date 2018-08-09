<?php
/* @var $this ProductInfoController */
/* @var $model ProductInfo */

$this->pageTitle = 'View Product'; 
$id = $model->product_id; 
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View <?php echo $model->name; ?>
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('productInfo/admin'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'margin-top:-3%')); ?> 
                <?php echo CHtml::link('Create', array('productInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'margin-top:-3%')); ?> 
                <?php echo CHtml::link('Update', array('productInfo/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'margin-top:-3%')); ?>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'htmlOptions' => array('class' => 'table table-striped m-table'),
                    'attributes'=>array(
                    'product_id',
                    'sku',
                    'name',
                    'price',
                    [
                    'name' => 'description',
                    'value' => ($model->description),
                    'type' => 'raw'
                    ],
                    'short_description',
                    'is_active',
                    [
                    'name' => 'category',
                    'value' => function($model){
                    $categoryId = ProductCategory::model()->findByAttributes(['product_id' => $model->product_id]);
                    $category = Categories::model()->findByPk(['category_id' => $categoryId->category_id]);
                    return $category->category_name;
                    }
                    ],
                    [
                    'name' => 'image',
                    'value' => function($model) {
                    if ($model->image == "") {
                    return '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">' .
                        CHtml::image(Yii::app()->params["siteUrl"] . '/plugins/demo/images/logo.png', 'No Image',
                        ['height' => 100, 'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                        ) . '</div>';
                    } else {
                    return '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">' .
                        CHtml::image(Yii::app()->baseUrl . $model->image, 'No Image',
                        ['height' => 100, 'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                        ) . '</div>';
                    }
                    },
                    'type' => 'raw'

                    ],
                    'created_at',
                    'modified_at',
                    'is_delete',
                                        ),
                    )); ?>

                    <table class="table">
                        <thead>
                        <tr class="odd">
                            <th style="width: 422px;">Product Name</th>
                            <th>Number of License</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pro_license as $key => $license) { ?> 
                        <tr>
                            <td>
                                <?php
			$LicenseName = ProductInfo::model()->findByAttributes(['product_id' => $license['product_id']]);
			echo $LicenseName->name;
			?> 
                            </td>
                            <td><?php echo $license['license_no']; ?></td>
                            <?php } ?> 
                        </tbody>
                    </table>

                    <table class="table">
                        <thead>
                        <tr class="odd">
                            <th style="width: 422px;">Affiliate Level</th>
                            <th>Affiliate Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pro_affiliate as $key => $item) { ?>
                        <tr class="even">
                            <td><?php echo $item['aff_level']; ?></td>
                            <td><?php echo $item['amount']; ?></td>
                        </tr>
                        <?php } ?> 
                        </tbody>
                    </table>

                </div>
            </div>
            <!--end::Section-->
        </div>
    </div>
</div>