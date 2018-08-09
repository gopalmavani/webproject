<?php
/* @var $this ResourcesController */
/* @var $data Resources */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->resource_id), array('view', 'id'=>$data->resource_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_name')); ?>:</b>
	<?php echo CHtml::encode($data->resource_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_description')); ?>:</b>
	<?php echo CHtml::encode($data->resource_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resource_address')); ?>:</b>
	<?php echo CHtml::encode($data->resource_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_available')); ?>:</b>
	<?php echo CHtml::encode($data->is_available); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_at')); ?>:</b>
	<?php echo CHtml::encode($data->modified_at); ?>
	<br />


</div>