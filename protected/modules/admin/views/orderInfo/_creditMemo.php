<?php
/* @var $this OrderInfoController */
/* @var $model OrderInfo */
/* @var $form CActiveForm */
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Create CreditMemo
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet">
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'order-info-credit-form',
                    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                    'enableAjaxValidation'=>false,
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
                )); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-lg-12">
                    <input style="display: none;" name="order_info_id" value="<?= $order->order_info_id; ?>">
                    <input style="display: none;" name="invoice_number" value="<?= $order->invoice_number; ?>">
                    <div class="form-material has-error">
                    <p id="memoError" class="help-block has-error" style="display: none;"></p>
                </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($orderItem != NULL){ ?>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center" width="40%">Product Name</th>
                                    <th class="text-center" width="15%">Qty</th>
                                    <th width="15%">Refund Quantity</th>
                                    <th class="text-center" width="15%">Amount</th>
                                    <th width="15%">Refund Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($orderItem as $key => $item) { ?>
                                <tr>
                                    <td class="col-md-2 text-center" width="40%">
                                        <?php echo $item->product_name; ?>
                                        <input style="display: none" name="OrderCreditMemo[order_line_item_id][]" id="productId<?= '_'.$key; ?>" value="<?php echo $item['order_line_item_id']; ?>">
                                    </td>
                                    <td class="col-md-1 text-center" width="15%" id="itemQty">
                                        <?php echo $item['item_qty']; ?>                                    </td>
                                    <td class="col-md-2" width="15%">
                                        <div class="col-md-12">
                                            <input autofocus="autofocus" class="form-control item_qty" min="1" max="<?php echo $item['item_qty']; ?>" name="OrderCreditMemo[qty_refund][]" id="item_qty<?= '_'.$key; ?>" type="number" >
                                        </div>
                                    </td>
                                    <td class="col-md-1 text-center" width="15%" id="item_price_<?= $key; ?>">
                                        <?php echo $item['item_price']; ?>
                                    </td>
                                    <td class="col-md-1 text-center item_refund" width="15%" id="item_refund_amt_<?= $key; ?>">
                                        0
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="4" class="text-right">
                                        <h3 class="profile-username">Amount to Refund:</h3>
                                    </td>
                                    <td><input name="refund_amount" class="form-control" type="number" value="0" id="final_refund">+ VAT</td>
                                </tr>
                                </tbody>
                            </table>
                            <?php } else { ?>
                            <span>No Product Found</span>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pull-right">
                                <?php echo CHtml::submitButton($creditMemo->isNewRecord ? 'Generate Memo' : 'Save', array(
                            'class' => 'btn btn-primary',
                            'id' => 'submit_button',
                        )); ?>
                        <?php echo CHtml::link('Cancel', array('orderInfo/admin'),
                            array(
                                'class' => 'btn btn-default'
                            )
                        );
                        ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<script>
    //Update refund amount on change in quantity
    $('.item_qty').on('keyup change', function (e) {
        var id_text = $(this).attr('id');
        var id_array = id_text.split('_');
        var id = id_array['2'];
        var item_qty = parseInt($(this).val());
        var item_amt = $('#item_price_'+id).html();
        var item_refund_amt = item_qty * item_amt;
        $('#item_refund_amt_'+id).html(item_refund_amt);

        //Update total refund amount
        var total_refund = 0;
        $('.item_refund').each(function () {
            total_refund += parseFloat($(this).html());
        });
        $('#final_refund').val(total_refund);
    });
</script>
