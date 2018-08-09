<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
<style>
    * {margin:0; padding:0;}
    h1,  h2, h3, h4, h5 {font-family: "Oswald !important"; font-weight:400;}
    .col-product { text-align: left; padding:20px; background: #fff!important;}
    .invoice-grid td{padding:20px;    border-bottom: 1px solid #b8babe !important;}
    .sub-total td, .total td {border-bottom:none !important;}
    body {font-size:14px;}
</style>
<?php
if ($data == 0) { ?>
    <div class="block">
        <div class="block-content block-content-narrow">
            <div class="h1 text-center push-30-t push-30 hidden-print">NO INVOICE FOUND</div>
            <hr class="hidden-print">

        </div>
    </div>
    <?php
} else {
    $id = $data['orderInfo']->order_info_id;
    ?>
    <div class="">
        <div class="block-header">
            <ul class="block-options">
                <li>
                    <a type="button" href="<?php echo Yii::app()->createUrl('invoice/Generateinvoice/' . $id); ?>"><i
                                class="fa fa-download"></i> Download
                        Invoice
                    </a>
                </li>
                <li>
                    <button type="button" onclick="App.initHelper('print-page');"><i class="si si-printer"></i> Print
                        Invoice
                    </button>
                </li>
                <li>
                    <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                </li>
                <li>
                    <button type="button" data-toggle="block-option" data-action="refresh_toggle"
                            data-action-mode="demo"><i
                                class="si si-refresh"></i></button>
                </li>
            </ul>
        </div>
        <div class="block-content block-content-narrow">
            <table width="800" style="margin:0px" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="50%" align="left" style="position:relative;">&nbsp;<img style="position:absolute;top: -146px;" src="<?php echo Yii::app()->createUrl('/images/logo.png'); ?>"/></td>
                                <td width="50%" align="right">
                                    <h1 style="font-size: 60px; line-height: 80px !important; margin:0; font-weight: 700;    color: #2f61a6;">INVOICE</h1>
                                    <h5 style="margin:0 0 30px; font-size: 20px;">Invoice Number : <?php echo $data['orderInfo']->invoice_number; ?></h5>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 20px 0;">
                        <table  width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="50%" align="left">
                                    <h4 style="margin-bottom: 10px; font-size:24px; text-align: right;">ORDER DETAILS</h4>
                                    <p style="margin-top:100px;font-size: 14px; line-height: 20px;">OrderID : <?php echo $data['orderInfo']->order_id; ?></p>
                                    <p style="font-size: 14px; line-height: 20px;">Order Date : <?php echo $data['orderInfo']->created_date; ?></p>
                                </td>
                                <td width="50%" align="right">
                                    <h4 style="margin-bottom: 10px; font-size:24px;text-align: right;">PAYMENT DETAILS</h4>
                                    <p style="font-size: 14px; line-height: 20px;">Payment mode : <?php print_r($data['orderPayment']->transaction_mode); ?></p>
                                    <p style="font-size: 14px;line-height: 20px;">Payment Reference ID : <?php echo $data['orderPayment']->payment_ref_id; ?></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="padding:40px 0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="50%">
                                    <h2 style="color: #2f61a6;font-size: 36px;margin-bottom: 12px;">Company</h2>
                                    <p style="font-size:14px;line-height: 20px;">Address<br>
                                        Town/City<br>
                                        Region Zip/Postal Code<br>
                                        (000) 000-0000</p>
                                </td>
                                <td width="50%" align="right">
                                    <h2 style="color: #2f61a6;font-size: 36px;margin-bottom: 12px;"><?php echo $data['userInfo']->full_name; ?></h2>
                                    <p style="font-size:14px;line-height: 20px;">Address<br>
                                        <?php echo $data['orderInfo']->city; ?><br>
                                        <?php echo $data['orderInfo']->region; ?>, <?php echo $data['orderInfo']->postcode; ?><br>
                                        <?php echo $data['userInfo']->phone; ?></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="table-responsive invoice-grid">
                            <table class="table" border="0" cellpadding="0" cellspacing="0">
                                <thead>
                                <tr>
                                    <th style="background-color:#f5f5f5; padding:20px; border-bottom:1px solid #b8babe; border-top: 1px solid #b8babe;" width="60%"  align="left"><h2 style="color:#000;">Product</h2></th>
                                    <th style="background-color: #f1f1f1; padding:20px;border-bottom: 1px solid #b8babe; border-top: 1px solid #b8babe;" width="18%" align="center"><h2 style="color:#000;">Unit</h2></th>
                                    <th style="background-color: #eee; padding:20px;border-bottom: 1px solid #b8babe;border-top: 1px solid #b8babe;" width="25%" align="right"><h2 style="color:#000;">Amount</h2></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                foreach ($data['orderLineitem'] as $item) {
                                $i++;
                                ?>
                                <tr>
                                    <td class="col-product"><h4 style="font-size: 24px;color: #2f61a6;margin-bottom:5px; text-transform:uppercase;"><?php echo $item->product_name; ?> </h4>
                                    <td align="center" style="background-color: #f7f7f8;"><?php echo $item->item_qty ?></td>
                                    <?php $total_amount = $item->item_qty * $item->item_price ?>
                                    <td align="right" style="background-color: #ededee;" ><strong style=" font-size: 18px;color: #495b64;">€ <?php echo $total_amount; ?></strong></td>
                                </tr>
                                    <?php
                                    $subtotal[] = $total_amount;
                                }
                                ?>

                                <tr class="sub-total">
                                    <td>&nbsp;</td>
                                    <td class="col-label" style="background-color: #ededee; color:#495b65;">Sub Total</td>
                                    <td class="col-value" align="right" style="background-color: #ededee;"><strong style="font-size: 18px; color:#495b65;">€ <?php print_r(array_sum($subtotal)); ?></strong></td>
                                </tr>
                                <tr class="sub-total">
                                    <td>&nbsp;</td>
                                    <td class="col-label" style="background-color: #ededee; color:#495b65;padding-top:0;font-size:14px;">Vat Rate</td>
                                    <td class="col-value" style="background-color: #ededee; color:#495b65;padding-top:0;" align="right"><strong style="font-size: 18px;">€ <?php echo $data['orderInfo']->vat; ?></strong></td>
                                </tr>
                                <tr class="total">
                                    <td>&nbsp;</td>
                                    <?php $total = array_sum($subtotal) + $data['orderInfo']->vat; ?>
                                    <td class="col-label"><strong style="color:#2c5793; font-size:18px;">Total</strong></td>
                                    <td class="col-value" align="right"><strong style="color:#2c5793;font-size:18px;">€ <?php echo $total; ?></strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding-top:50px;"><h3 style="color: #2f61a6;font-size: 30px;line-height: 36px; margin: 0 0 30px 0;">THANK YOU VERY MUCH FOR DOING BUSINESS WITH US.</h3></td>
                </tr>

                <tr>
                    <td>
                        <table width="100%" border="0" style="padding:20px 0; border-top:1px solid #b8babe;" cellspacing="0" cellpadding="0">
                            <tbody><tr>
                                <td width="35%" align="center"><img src="<?php echo Yii::app()->createUrl('/images/icon-map.jpg'); ?>"/><br/><span style="display:block;">Kleinhoefstraat 5/19 2440 Geel BELGIUM</span></td>
                                <td width="28%" align="center"><img src="<?php echo Yii::app()->createUrl('/images/icon-phone.jpg')?>"/><br/><span style="display:block;">+32 14 24 85 11</span>	</td>
                                <td width="35%" align="center"> <img src="<?php echo Yii::app()->createUrl('images/icon-msg.jpg')?>"/><br/><a  style="display:block; color:#000; text-decoration:none;" href="mailto:accounting@thepeoplesweb.net">accounting@thepeoplesweb.net</a> </td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                <tr><td style="background: #eee;padding: 20px; text-align:center; color:#000;">
                        Cyclone is a brand name of <a href="http://force.international/" style="font-weight:bold; color:#000; text-decoration:none;" target="_blank">Force International CVBA</a>
                    </td></tr>
            </table>
        </div>
    </div>

<?php } ?>
