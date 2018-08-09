<?php
/* @var $this OrderCreditMemoController */
/* @var $model OrderCreditMemo */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'credit_memo_id'); ?>
		<?php echo $form->textField($model,'credit_memo_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'order_info_id'); ?>
		<?php echo $form->textField($model,'order_info_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'invoice_number'); ?>
		<?php echo $form->textField($model,'invoice_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'refund_amount'); ?>
		<?php echo $form->textField($model,'refund_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vat'); ?>
		<?php echo $form->textField($model,'vat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'order_total'); ?>
		<?php echo $form->textField($model,'order_total'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'memo_status'); ?>
		<?php echo $form->textField($model,'memo_status'); ?>
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