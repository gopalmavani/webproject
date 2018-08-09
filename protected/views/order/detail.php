<?php
/**
 * Created by PhpStorm.
 * User: imyuvii
 * Date: 01/03/17
 * Time: 6:00 PM
 */
/* @var $this SiteController */

$this->pageTitle = "Order Detail";
$this->breadcrumbs = array(
    'Order',
    'Order Detail',
);

?>
<!-- Hero Content -->
<div class="bg-image"style="background-image: url('<?php echo Yii::app()->baseUrl; ?>/plugins/img/photos/photo3@2x.jpg.jpg');">
    <div class="bg-primary-dark-op">
        <section class="content content-full content-boxed overflow-hidden">
            <!-- Section Content -->
            <div class="push-30-t push-30 text-center">
                <h1 class="h2 text-white push-10 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">Order</h1>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
</div>
<!-- END Hero Content -->
<!-- Products -->
<div class="bg-gray-lighter">
    <section class="content content-boxed">
        <div class="row">
            <div class="col-sm-8">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title page-header">Transaction Details
                            <?php if ($order->order_status == 1){?>
                                <span class="pull-right">
                                <a style="margin-top:-5%" class="btn btn-default" href="<?php echo Yii::app()->createUrl('invoice/Generateinvoice/'.$order->order_info_id); ?>" ><i class="fa fa-arrow-circle-down"></i> Invoice</a></button>

                                    <a style="margin-top:-5%" class="btn btn-default" href="<?php echo Yii::app()->createUrl('invoice/View/'.$order->order_info_id); ?>" ><i class="fa fa-print fa-lg"></i>   Print Invoice</a>
                            </span>
                            <?php } ?>
                        </h3>
                        <p class="pull-left">
                            <?php /*echo "<pre>";
                                    print_r($orderlineitem[0]->item_price);die;*/?>
                            Payment of <?php echo "&euro;". $orderlineitem[0]->item_price?> is received by <?php echo Yii::app()->params['applicationName']?>!<br>
                            Your transaction is <b><?php print_r( $orderStatus->field_label);  ?></b>
                        </p>
                        <p class="pull-right">
                            Your Order No is <?php echo $order->order_id; ?><br>
                            <?php echo date('Y-m-d h:i a', strtotime(str_replace('-','/', $order->created_date)));?>
                        </p>
                    </div>
                    <div class="block-content">
                        <table class="table table-borderless table-vcenter">
                            <thead>
                            <tr>

                                <td>

                                </td>
                                <td>
                                    <b>Product Details</b>
                                </td>
                                <td class="text-right">
                                    <b>Quantity</b>
                                </td>
                                <td class="text-right">
                                    <b>Price</b>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($orderlineitem as $item){
                                $product = ProductInfo::model()->findByAttributes(['product_id' => $item->product_id]);
                                if(isset($product)){
                                    ?>
                                    <tr>

                                        <td style="width: 55px;">
                                            <img src="<?php echo Yii::app()->getBaseUrl(true) . $product->image; ?>" class="img-responsive">
                                        </td>
                                        <td>
                                            <a class="h5" href="<?php echo Yii::app()->createUrl('product/detail/' . $item->product_id); ?>"><?php echo $item->product_name; ?></a>
                                            <div class="font-s12 text-muted hidden-xs"><?php echo $product->short_description; ?></div>
                                        </td>
                                        <td class="text-right">
                                            <div class="font-w600 text-success"><?php echo $item->item_qty; ?></div>
                                        </td>
                                        <td class="text-right">
                                            <div class="font-w600 text-success">&euro; <?php echo $product->price; ?></div>
                                        </td>
                                    </tr>
                                <?php }
                            }
                            ?>
                            <tr class="success">
                                <td class="text-right" colspan="3">
                                    <span class="h4 font-w600">Total</span>
                                </td>
                                <td class="text-right">
                                    <div class="h4 font-w600 text-success">&euro; <?php echo $orderlineitem[0]->item_price; ?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END Products -->
            </div>
            <div class="col-sm-4">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title page-header" style="padding-bottom:0%">Order Summary</h3>
                    </div>
                    <div class="block-content">
                        <table class="table table-borderless table-vcenter">
                            <tbody>
                            <tr>
                                <td>
                                    <p>Payment Reference id</p>
                                    <p>Paid amount</p>
                                    <p>Product Price</p>
                                    <p>Subtotal</p>
                                </td>
                                <td style="width: 55px;" class="text-right">
                                    <p><?php echo $orderpayment->payment_ref_id; ?></p>
                                    <p><?php echo $orderlineitem[0]->item_price; ?></p>
                                    <?php foreach ($orderlineitem as $item){
                                        $total[] = $item->item_price;
                                        ?>
                                    <?php } ?>
                                    <p><?php print_r(array_sum($total));?></p>
                                    <p><?php echo $orderlineitem[0]->item_price; ?></p>
                                </td>
                            </tr>

                            <tr class="success">
                                <td class="text-right" colspan="1">
                                    <span class="h4 font-w600">Total</span>
                                </td>
                                <td class="text-right">
                                    <div class="h4 font-w600 text-success"><?php echo $orderlineitem[0]->item_price; ?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END Products -->
            </div>
        </div>
    </section>
</div>
