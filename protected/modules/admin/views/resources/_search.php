<?php
/* @var $this ResourcesController */
/* @var $model Resources */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'resource_id'); ?>
		<?php echo $form->textField($model,'resource_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'resource_name'); ?>
		<?php echo $form->textField($model,'resource_name',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'resource_description'); ?>
		<?php echo $form->textArea($model,'resource_description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'resource_address'); ?>
		<?php echo $form->textField($model,'resource_address',array('size'=>60,'maxlength'=>400)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_available'); ?>
		<?php echo $form->textField($model,'is_available'); ?>
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