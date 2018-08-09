<?php
/* @var $this OrderInfoController */
/* @var $model OrderInfo */

$userName = UserInfo::model()->findByAttributes(['user_id' => $model->user_id]);

if(!empty($userName)){
$this->pageTitle = "<i class='fa fa-cog' aria-hidden='true'></i>" . "ORDER#". $model->order_id . " | " . Yii::app()->dateFormatter->format("MMM dd, yyyy h:m:s", $model->created_date);
}
else{
$this->pageTitle = 'View Order';
}
$id = $model->order_info_id;

?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Order
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right"  style="margin-bottom:2%">
                <?php echo CHtml::link('Go to list', array('orderInfo/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php echo CHtml::link('Create', array('orderInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <?php if(!empty($model->invoice_number)){ ?>
                <a href="<?php echo Yii::app()->createUrl('invoice/Generateinvoice/' . $model->order_info_id); ?> "
                   data-toggle="tooltip" title="Download Invoice" class="btn btn-minw btn-square btn-success">Generate Invoice</a>
                <?php }?>
                <?php if($model->order_status != 1){ ?>
                <?php echo CHtml::link('Update', array('orderInfo/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
                <?php } ?>
            </div>
        </div>
        <!--begin::Section-->
        <div class="m-section">
            <div class="m-section__content">
                <!--Order details and payment details-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="flaticon-statistics"></i>
                                                    </span>
                                            <h2 class="m-portlet__head-label m-portlet__head-label--danger">
                                                    <span lang="en">
                                                        Order Details
                                                    </span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <?php $userName = UserInfo::model()->findByAttributes(['user_id' => $model->user_id]); ?>
                                    <div class="row">
                                        <div class="col-md-5"> <b>Customer Name:</b> </div>
                                        <div class="col-md-7"><?php if(!empty($userName)){ ?>
                                            <a target="_blank" href="<?php echo Yii::app()->CreateUrl('admin/userInfo/view').'/'.$model->user_id; ?>"><?php echo $model->user_name; ?></a>

                                            <?php } else{
                                    echo $model->user_name;
                                }
                                ?>
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-5"><b>Customer Email:</b></div>
                                        <div class="col-md-7"><?php echo $model->email; ?></div>
                                    </div>
                                    <p></p>
                                    <?php if($model->company != ''){ ?>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span><b>Company :</b></span>
                                        </div>
                                        <div class="col-md-7">
                                            <span><?php echo $model->company; ?></span>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span><b>Order Status: </b></span>
                                        </div>
                                        <div class="col-md-7">
                                        <span>
                                            <?php if($model->order_status == 0){
                                                echo "<span class='label label-danger'>Canceled</span>";
                                            }elseif($model->order_status == 2){
                                                echo "<span class='label label-info'>Pending</span>";
                                            }
                                            else{
                                                echo "<span class='label label-success'>Success</span>";
                                            }?>
                                        </span>
                                        </div>
                                    </div><p></p>
                                    <?php if($model->vat_number != ''){ ?>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span><b>Vat Number :</b></span>
                                        </div>
                                        <div class="col-md-7">
                                            <span><?php echo $model->vat_number; ?></span>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span><b>Invoice Number: </b></span>
                                        </div>
                                        <div class="col-md-7">
                                            <span><?php echo $model->invoice_number; ?>
</span>
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span><b>Grand Total: </b></span>
                                        </div>
                                        <div class="col-md-7">
                                            <span><?php echo $model->orderTotal; ?> &euro;</span>
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span><b>Order Comment: </b></span>
                                        </div>
                                        <div class="col-md-7">
                                            <span><?php echo $model->order_comment; ?></span>
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span><b>Subscription:</b></span>
                                        </div>
                                        <div class="col-md-7">
                                <span>
                                    <?php if($model->is_subscription_enabled == 0){
                                        echo "No";
                                    }
                                    else{
                                        echo "Yes";
                                    }?>
                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon m--hide">
                                                    <i class="flaticon-statistics"></i>
                                                </span>
                                            <h2 class="m-portlet__head-label m-portlet__head-label--info">
                                                    <span lang="en">
                                                        Payment Details
                                                    </span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <?php $paymentMode = OrderPayment::model()->findByAttributes(['order_info_id' => $model->order_info_id]); ?>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <span><b>Payment Mode: </b></span>
                                        </div>
                                        <div class="col-md-5">
                                        <span>
                                            <?php
                                            if($paymentMode->payment_mode == 0){
                                                echo "Cash";
                                            }
                                            else{
                                                echo "Check";
                                            }
                                            ?>
                                        </span>
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <span><b>Payment Reference Id: </b></span>
                                        </div>
                                        <div class="col-md-5">
                                            <span><?php echo $paymentMode->payment_ref_id; ?></span>
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <span><b>Payment Method: </b></span>
                                        </div>
                                        <div class="col-md-5">
                                            <span><?php $method = PaymentMethods::model()->findByAttributes(['payment_method_id' =>$paymentMode->payment_method_ref_id]);
                                            echo $method->gateway; ?></span>
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <span><b>Payment Status : </b></span>
                                        </div>
                                        <div class="col-md-5">
                                        <span>
                                            <?php if($paymentMode->payment_status == 0){
                                                echo "<span class='label label-danger'>Failure</span>";
                                            }
                                            elseif($paymentMode->payment_status == 2){
                                                echo "<span class='label label-info'>Pending</span>";
                                            }
                                            else{
                                                echo "<span class='label label-success'>Success</span>";
                                            } ?>
                                        </span>
                                        </div>
                                    </div>
                                    <p></p>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <span><b>Payment Date : </b></span>
                                        </div>
                                        <div class="col-md-5">
                                            <span><?php echo Yii::app()->dateFormatter->format("MMM dd, yyyy h:m:s", $paymentMode->payment_date); ?></span>
                                        </div>
                                    </div>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Order details and payment details-->

                <!--Shooping cart and order total-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-7">
                            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="flaticon-statistics"></i>
                                                    </span>
                                            <h2 class="m-portlet__head-label m-portlet__head-label--warning">
                                                        <span lang="en">
                                                            Shopping Cart
                                                        </span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th  style="text-transform: capitalize;">ProductName</th>
                                                <th  style="text-transform: capitalize;">SKU</th>
                                                <th  style="text-transform: capitalize;">Quantity</th>
                                                <th  style="text-transform: capitalize;">Discount</th>
                                                <th  style="text-transform: capitalize;">Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($itemModel as $key => $item){ ?>
                                            <tr>
                                                <td><a href="<?php echo Yii::app()->createUrl('admin/productInfo/view').'/'.$item['product_id']; ?>" target="_blank"><?php echo $item['product_name'];?></a></td>
                                                <td><?php echo $item['product_sku']; ?></td>
                                                <td><?php echo $item['item_qty']; ?></td>
                                                <td><?php echo $item['item_disc']; ?></td>
                                                <td><?php echo $item['item_price']; ?></td>
                                            </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="flaticon-statistics"></i>
                                                    </span>
                                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                                        <span lang="en">
                                                            Order Total
                                                        </span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="row static-info align-reverse">
                                        <div class="col-md-8">
                                            <span><b>Order Total: </b></span>
                                        </div>
                                        <div class="col-md-3">
                                            <span><?php echo $model->orderTotal; ?> &euro;</span>
                                        </div>
                                    </div>
                                    <div class="row static-info align-reverse">
                                        <div class="col-md-8">
                                            <span><b>Vat: </b></span>
                                        </div>
                                        <div class="col-md-3">
                                            <span><?php echo $model->vat; ?> &euro;</span>
                                        </div>
                                    </div>
                                    <div class="row static-info align-reverse">
                                        <div class="col-md-8">
                                            <span><b>Net Total: </b></span>
                                        </div>
                                        <div class="col-md-3">
                                            <span><?php echo $model->netTotal; ?> &euro;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Shooping cart and order total-->
            </div>
        </div>
        <!--end::Section-->
    </div>
</div>
<!--<div class="row">
    <div class="block block-bordered" style="border-color: #E26A6A;">
        <div class="block-header" style="background-color: #E26A6A">
            <h3 class="block-title"><font color="white"><i class="fa fa-cogs"></i> Billing Address</font> </h3><br/>
        </div>
        <div class="block-content block-content-full">
            <div class="h4 push-5"><b></b></div>
            <address>
                                                                                <br><br>
                                <i class="fa fa-phone"></i><br>
                <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)"></a>
                            </address>
        </div>
    </div>
</div>-->

