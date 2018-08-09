<?php
/* @var $this OrderCreditMemoController */
/* @var $data OrderCreditMemo */
?>

<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('credit_memo_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->credit_memo_id), array('view', 'id'=>$data->credit_memo_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order_info_id')); ?>:</b>
	<?php echo CHtml::encode($data->order_info_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('invoice_number')); ?>:</b>
	<?php echo CHtml::encode($data->invoice_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('refund_amount')); ?>:</b>
	<?php echo CHtml::encode($data->refund_amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vat')); ?>:</b>
	<?php echo CHtml::encode($data->vat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order_total')); ?>:</b>
	<?php echo CHtml::encode($data->order_total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('memo_status')); ?>:</b>
	<?php echo CHtml::encode($data->memo_status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_at')); ?>:</b>
	<?php echo CHtml::encode($data->modified_at); ?>
	<br />

	*/ ?>

</div>