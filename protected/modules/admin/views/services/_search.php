<?php
/* @var $this ServicesController */
/* @var $model Services */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'service_id'); ?>
		<?php echo $form->textField($model,'service_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'service_name'); ?>
		<?php echo $form->textField($model,'service_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'service_description'); ?>
		<?php echo $form->textArea($model,'service_description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'service_image'); ?>
		<?php echo $form->textField($model,'service_image',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_display'); ?>
		<?php echo $form->textField($model,'is_display'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'service_price'); ?>
		<?php echo $form->textField($model,'service_price',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'service_duration'); ?>
		<?php echo $form->textField($model,'service_duration',array('size'=>60,'maxlength'=>300)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'category'); ?>
		<?php echo $form->textField($model,'category',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modified_at'); ?>
		<?php echo $form->textField($model,'modified_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->