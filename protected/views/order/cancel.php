<?php
/**
 * Created by PhpStorm.
 * User: imyuvii
 * Date: 01/03/17
 * Time: 6:00 PM
 */
/* @var $this SiteController */

$this->pageTitle = "Payment Success";
$this->breadcrumbs = array(
    'User',
    'Payment Success',
);
$model = OrderInfo::model()->findByAttributes(['order_id' => $_GET['orderID'] ]);
$orderItem = OrderLineItem::model()->findAllByAttributes(['order_info_id' => $model->order_info_id]);


?>
<!-- Hero Content -->
<div class="bg-image">
    <div class="bg-primary-dark-op">
        <section class="content content-full content-boxed overflow-hidden">
            <!-- Section Content -->
            <div class="push-30-t push-30 text-center">
                <h1 class="h2 text-white push-10 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown"><i class="fa fa-close text-danger"></i> Payment Failed!</h1>
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
                        <h3 class="block-title page-header">Transaction Details</h3>

                        <p class="pull-left">
                            Payment of <?php echo $_GET['currency'] . ' ' . $_GET['amount']; ?> is failed!<br>
                        </p>
                        <p class="pull-right">
                            Your Order No is <?php echo $_GET['orderID']; ?><br>
                            <?php echo $model->created_date;?>
                        </p>
                    </div>
                    <div class="block-content">
                        <table class="table table-borderless table-vcenter">
                            <thead>
                            <tr>
                                <td></td>
                                <td>
                                    <b>Product Details</b>
                                </td>
                                <td class="text-right">
                                    <b>Quanity</b>
                                </td>
                                <td class="text-right">
                                    <b>Price</b>
                                </td>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($orderItem as $item){
                                $product = ProductInfo::model()->findByAttributes(['product_id' => $item->product_id]);
                                ?>
                                <tr>

                                    <td style="width: 55px;">
                                        <img src="<?php echo Yii::app()->getBaseUrl(true) . $product->image; ?>" class="img-responsive">
                                    </td>
                                    <td>
                                        <a class="h5" href="frontend_ecom_product.html"><?php echo $product->name; ?></a>
                                        <div class="font-s12 text-muted hidden-xs"><?php echo $product->short_description; ?></div>
                                    </td>
                                    <td class="text-right">
                                        <div class="font-w600 text-success"><?php echo $item->item_qty; ?></div>
                                    </td>
                                    <td class="text-right">
                                        <div class="font-w600 text-success">&euro; <?php echo $product->price; ?></div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr class="success">
                                <td class="text-right" colspan="3">
                                    <span class="h4 font-w600">Total</span>
                                </td>
                                <td class="text-right">
                                    <div class="h4 font-w600 text-success">&euro; <?php echo $_GET['amount']; ?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END Products -->
                </form>
            </div>
            <div class="col-sm-4">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title page-header">Order Summary</h3>
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
                                    <p><?php echo $_GET['PAYID']; ?></p>
                                    <p><?php echo $_GET['amount']; ?></p>
                                    <?php foreach ($orderItem as $item){
                                        $total[] = $item->item_price;
                                        ?>
                                    <?php } ?>
                                    <p><?php print_r(array_sum($total));?></p>
                                    <p><?php echo $_GET['amount']; ?></p>
                                </td>
                            </tr>

                            <tr class="success">
                                <td class="text-right" colspan="1">
                                    <span class="h4 font-w600">Total</span>
                                </td>
                                <td class="text-right">
                                    <div class="h4 font-w600 text-success"><?php echo $_GET['amount']; ?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END Products -->
                </form>
            </div>
        </div>
    </section>
</div>