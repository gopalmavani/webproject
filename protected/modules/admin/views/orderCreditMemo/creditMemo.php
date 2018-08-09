<?php if ($data == 0) { ?>    <div class="block">
        <div class="block-content block-content-narrow">
            <div class="h1 text-center push-30-t push-30 hidden-print">NO INVOICE FOUND</div>
            <hr class="hidden-print">
        </div>
    </div>
    <?php } else { $id = $data['orderInfo']->order_info_id; ?>    <table width="800" style="margin:0 auto; font-family:open sans, arial;" border="0" cellspacing="0" cellpadding="0">

        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" style="position:relative;">
                            <img style="top: -146px; width: 400px" src="<?php echo Yii::app()->getBaseUrl(true).'/images/logo.png';?>"/>
                        </td>
                        <td align="right">
                            <h1 style="display: block; font-size: 60px; line-height: 80px !important; margin:0; font-weight: 700;color: #2f61a6;">Credit Memo</h1>
                            <h5 style="margin:0 0 30px; font-size: 20px;">Credit Memo No: <?= $data['memo']->credit_memo_id; ?></h5>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding: 40px 0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="30%">
                            <h4 style="margin-bottom: 10px; font-size:30px; color: #0b0b0b; text-align: right;">ORDER DETAILS</h4>
                            <p style="font-size: 20px; line-height: 20px;">OrderID : <?= $data['orderInfo']->order_id; ?></p>
                            <p style="font-size: 20px; line-height: 20px;">Order Date : <?php echo date('d-M-Y', strtotime($data['orderInfo']->invoice_date)); ?></p>
                        </td>
                        <td>&nbsp;
                        </td>
                        <td align="right">
                            <h4 style="margin-bottom: 10px; font-size:30px; color: #0b0b0b; text-align: right;">PAYMENT DETAILS</h4>
                            <p style="font-size: 20px; line-height: 20px;">Payment mode : <?php print_r($data['orderPayment']->transaction_mode); ?></p>
                            <p style="font-size: 20px;line-height: 20px;">Payment Reference ID : <?php echo $data['orderPayment']->payment_ref_id; ?></p>
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
                            <h3 style="color: #2f61a6;font-size: 36px;margin-bottom: 18px;">Cyclone</h3>
                            <p style="font-size:18px;line-height: 20px;">
                                Force International CVBA<br>
                                Kleinhoefstraat 5/19<br>
                                2440 Geel<br>
                                +32 (0) 14/ 56 35 24<br><br>
                                VATnr = BE0647 631485<br>
                                BankAccountNr = BE63 0689 0467 8308<br>
                                BIC = GKCCBEBB<br>
                        </td>
                        <td width="50%" align="right">
                            <h3 style="color: #2f61a6;font-size: 36px;margin-bottom: 18px;"><?php echo $data['userInfo']->full_name; ?></h3>
                            <p style="font-size:18px;line-height: 20px;">
                                <?php echo $data['userInfo']->email;?><br>
                                <?php if(!is_null($data['orderInfo']->company)) echo $data['orderInfo']->company;?><br>
                                <?php echo $data['orderInfo']->building; ?> <?php echo $data['orderInfo']->street; ?> <br>
                                <?php echo $data['orderInfo']->region; ?><br>
                                <?php echo $data['orderInfo']->postcode; ?> <?php echo $data['orderInfo']->city; ?><br>
                                <?php echo $data['orderInfo']->country; ?><br><br>
                                <?php if(!is_null($data['orderInfo']->company)) echo "VATnr = ".$data['orderInfo']->vat_number;?><br>
                                <?php echo $data['userInfo']->phone; ?>                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <div class="table-responsive invoice-grid">
                    <table class="table" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 50px">
                        <thead>
                        <tr>
                            <th style="padding:20px;border-bottom: 1px solid #b8babe; border-top: 1px solid #b8babe;" bgcolor="#f5f5f5" width="70%" align="left"><h2 style="color:#000;">Product</h2></th>
                            <th style="padding:20px;border-bottom: 1px solid #b8babe; border-top: 1px solid #b8babe;" bgcolor="#f5f5f5" width="15%" align="left"><h2 style="color:#000;">Price</h2></th>
                            <th style="padding:20px;border-bottom: 1px solid #b8babe; border-top: 1px solid #b8babe;" bgcolor="#f1f1f1" width="15%" align="center"><h2 style="color:#000;">Unit</h2></th>
                            <th style="padding:20px;border-bottom: 1px solid #b8babe;border-top: 1px solid #b8babe;" bgcolor="#eee" width="20%" align="right"><h2 style="color:#000;">Amount</h2></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data['orderLineitem'] as $item) { ?>
                            <tr>
                                <td class="col-product" style="padding:20px;">
                                    <h4 style="font-size: 26px;color: #2f61a6;margin-bottom:5px; text-transform:uppercase;">
                                    <?php echo $item->product_name; ?>                                    </h4>
                                    <!--<p style="font-size:14px;"><?/*= $product->description; */?></p>--></td>
                                <td align="center" bgcolor="#f7f7f8" style="font-size: 24px;"><?= $item->item_price; ?></td>
                                <td align="center" bgcolor="#f7f7f8" style="font-size: 24px;"><?= $item->item_qty; ?></td>
                                <?php $total_amount = $item->item_qty * $item->item_price ?>                                <td align="right" style="padding-right: 15px; font-size: 24px;" bgcolor="#ededee" ><strong style="font-size: 18px;color: #495b64;">€ <?php echo $total_amount; ?></strong></td>
                                <?php $subtotal[] = $total_amount; ?>                            </tr>

                        <?php } ?>
                        <tr class="sub-total">
                            <td>&nbsp;</td>
                            <td class="col-label" colspan="2" style="background-color: #ededee; padding:15px; color:#495b65;font-size:26px;">Sub Total</td>
                            <td class="col-value" bgcolor="#ededee" align="right" style="background-color: #ededee;color:#495b65;"><strong style="font-size: 24px;">€ <?= $data['memo']->refund_amount; ?></strong></td>
                        </tr>
                        <tr class="sub-total">
                            <td>&nbsp;</td>
                            <td class="col-label" colspan="2" style="background-color: #ededee; padding:15px; color:#495b65;font-size:26px;">Vat</td>
                            <td class="col-value" style="background-color: #ededee; color:#495b65;" align="right"><strong style="font-size: 24px;">€ <?php echo $data['memo']->vat; ?></strong></td>
                        </tr>
                        <tr class="total">
                            <td>&nbsp;</td>
                            <td class="col-label" colspan="2" style="background-color: #ededee; padding:15px; color:#495b65;font-size:26px;"><strong style="color:#2c5793; font-size:18px;">Total</strong></td>
                            <td class="col-value" align="right"><strong style="color:#2c5793;font-size:30px;">€ <?php echo $data['memo']->order_total; ?></strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>

        <tr>
            <td align="center" style="padding: 30px 0;"><p style="color: #2f61a6;font-size: 30px;line-height: 36px;">THANK YOU VERY MUCH FOR DOING BUSINESS WITH US.</p></td>
        </tr>

        <tr>
            <td>
                <table width="100%" border="0" style="padding:20px 0; border-top:1px solid #b8babe;" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td width="35%" align="center" style="padding:20px;"><img src="<?php echo Yii::app()->createUrl('/images/icon-map.jpg'); ?>" width="80px"/><br/><span style="display:block; font-size: 24px">Kleinhoefstraat 5/19 2440 Geel BELGIUM</span></td>
                        <td width="28%" align="center"><img src="<?php echo Yii::app()->createUrl('/images/icon-phone.jpg')?>"  width="80px" /><br/><span style="display:block; font-size: 24px">+32 14 24 85 11</span>	</td>
                        <td width="35%" align="center"> <img src="<?php echo Yii::app()->createUrl('images/icon-msg.jpg')?>"  width="80px" /><br/><a  style="display:block; font-size: 24px color:#000; text-decoration:none;" href="mailto:accounting@cbm.net">accounting@cbmglobal.io</a> </td>
                    </tr>
                    </tbody></table>
            </td>
        </tr>
        <tr><td style="background: #eee;padding: 20px; text-align:center; color:#000; font-size: 20px">
                Cyclone is a brand name of <a href="http://force.international/" style="font-weight:bold; color:#000; text-decoration:none;" target="_blank">Force International CVBA</a>
            </td></tr>
    </table>
<?php } ?>