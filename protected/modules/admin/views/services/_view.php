<?php
/* @var $this ServicesController */
/* @var $data Services */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->service_id), array('view', 'id'=>$data->service_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_name')); ?>:</b>
	<?php echo CHtml::encode($data->service_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_description')); ?>:</b>
	<?php echo CHtml::encode($data->service_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_image')); ?>:</b>
	<?php echo CHtml::encode($data->service_image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_display')); ?>:</b>
	<?php echo CHtml::encode($data->is_display); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_price')); ?>:</b>
	<?php echo CHtml::encode($data->service_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_duration')); ?>:</b>
	<?php echo CHtml::encode($data->service_duration); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?php echo CHtml::encode($data->category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_at')); ?>:</b>
	<?php echo CHtml::encode($data->modified_at); ?>
	<br />

	*/ ?>

</div>