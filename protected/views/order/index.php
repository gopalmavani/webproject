<?php
/**
 * Created by PhpStorm.
 * User: imyuvii
 * Date: 01/03/17
 * Time: 6:00 PM
 */
/* @var $this SiteController */

$this->pageTitle = "View Orders";
$this->breadcrumbs = array(
    'Orders',
    'View Orders',
);
?>

<div class="bg-image" style="background-image: url('<?php echo Yii::app()->baseUrl; ?>/plugins/img/photos/photo3@2x.jpg.jpg');">
    <div class="bg-primary-dark-op">
        <section class="content content-full content-boxed overflow-hidden">
            <!-- Section Content -->
            <div class="push-30-t push-30 text-center">
                <h1 class="h2 text-white push-10 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">All Orders</h1>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
</div>



<div class="block" style="margin-bottom:0px;">
    <div class="block-header bg-gray-lighter">
        <h3 class="block-title">All Orders</h3>
    </div>
    <section class="content content-boxed">
        <div class="block-content">
            <?php
            if ($orders) {
                ?>
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>

                    <tr>
                        <th class="text-center" style="width: 100px;">ID</th>
                        <th class="hidden-xs text-center">Date</th>
                        <th>Status</th>
                        <th class="hidden-xs text-right">Value</th>
                        <th class="text-center">Action</th>
                    </tr>

                    </thead>
                    <tbody>
                    <?php

                    foreach ($orders as $order) {
                        ?>
                        <tr>
                            <td class="text-center">
                                <a class="font-"
                                   href="<?php echo Yii::app()->createUrl('order/detail/' . $order['order_id']); ?>">
                                    <strong><?php echo $order['order_id']; ?></strong>
                                </a>
                            </td>
                            <td class="hidden-xs text-center"><?php echo $order['created_date']; ?></td>
                            <td>
                                <?php
                                if ($order['order_status'] == 1) { ?>

                                    <span class="label label-success">Success</span>

                                <?php } else { ?>

                                    <span class="label label-danger">Pending</span>

                                <?php } ?>

                            </td>
                            <td class="text-right hidden-xs">
                                <strong><?php echo $order['orderTotal']; ?></strong>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-xs">
                                    <a href="<?php echo Yii::app()->createUrl('order/detail/' . $order['order_id']); ?>"
                                       data-toggle="tooltip" title="View" class="btn btn-default"><i class="fa fa-eye"></i></a>
                                    <?php if ($order['order_status'] == 1) { ?>
                                        <a href="<?php echo Yii::app()->createUrl('invoice/Generateinvoice/' . $order['order_info_id']); ?>"
                                           data-toggle="tooltip" title="Download Invoice" class="btn btn-default"><i
                                                class="fa fa-download"></i></a>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php  }
            else{ ?>
                <table class="table table-borderless table-striped table-vcenter">
                    <tr>
                        <td class="text-center">

                        </td>
                        <td class="hidden-xs text-center"></td>
                        <td width="15%" class="text-right">No records found!</td>
                        <td class="text-right hidden-xs">
                            <strong></strong>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">

                            </div>
                        </td>
                    </tr>
                </table>
            <?php } ?>
        </div>
</div>
</section>
<!-- END All Orders -->
