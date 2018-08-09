<?php
/* @var $this OrderCreditMemoController */
/* @var $model OrderCreditMemo */
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View CreditMemo
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet">
        <div class="m-portlet__body">
            <div class="pull-right">
                <?php echo CHtml::link('Go to list', array('orderCreditMemo/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <p></p>
            </div>

            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php 
                    $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'htmlOptions' => array('class' => 'table'),
                    'attributes'=>array(
                    'credit_memo_id',
                    'order_info_id',
                    'invoice_number',
                    'refund_amount',
                    'vat',
                    'order_total'
                    ),
                    )); ?>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon m--hide">
                                            <i class="flaticon-statistics"></i>
                                        </span>
                                        <h2 class="m-portlet__head-label m-portlet__head-label--warning">
                                            <span lang="en">
                                                Credit Items
                                            </span>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Product Name</th>
                                        <th>Order Quantity</th>
                                        <th>Refunded Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($creditItems as $k=>$item){ ?>
                                        <tr>
                                            <td class="text-center"><?= $k+1; ?></td>
                                            <td><?= $item->product_name; ?></td>
                                            <td><?= $item->order_item_qty; ?></td>
                                            <td><?= $item->refund_item_qty; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
