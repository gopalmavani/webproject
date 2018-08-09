<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 24/7/18
 * Time: 7:49 PM
 */
$this->pageTitle = "Checkout";
$sql = "SELECT SUM(number_of_people) from booking";
$result = Yii::app()->db->createCommand($sql)->queryAll();
?>
<!--page title start-->
<section class="ImageBackground ImageBackground--overlay v-align-parent u-height350 u-BoxShadow40" data-overlay="5">
    <div class="ImageBackground__holder"> <img src="<?php echo Yii::app()->baseUrl."/plugins/imgs/banner/ban-home.jpg"; ?>" alt=""/> </div>
    <div class="v-align-child">
        <div class="container ">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <h1 class="text-uppercase u-Margin0 u-Weight700 text-white">Cart Page</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<!--page title end-->

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ol class="breadcrumb u-MarginTop20 u-MarginBottom20">
                    <li><a href="<?php echo Yii::app()->createUrl("/"); ?>">Home</a></li>
                    <li class="active"><span>Cart Page</span></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!--intro start-->
<section class="u-MarginTop100 u-xs-MarginTop30 u-MarginBottom100 u-xs-MarginBottom30 position-relative">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInUp" data-wow-delay="200ms">
                <h1 class="u-MarginBottom30 u-Weight700 text-uppercase">Cart</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!--grid start-->
                <div class="table-responsive wow fadeInUp" data-wow-delay="200ms">
                    <table class="table u-Margin0 text-gray shopping-cart">
                        <thead>
                        <tr class="cart-header">
                            <th>Product</th>
                            <th width="15%" class="text-center">Price</th>
                            <th width="10%" class="text-center">Quantity</th>
                            <th width="15%" class="text-right">Total</th>
                            <th width="10%" class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $mytotal = 0;
                        foreach ($cartItem as $key=>$product){ ?>
                            <tr class="cart-item">
                                <td><div class="row">
                                        <div class="col-xs-3"><img src="<?php echo Yii::app()->baseUrl.$product['product_image']; ?>" class="cart-thumb img-responsive" alt="" /></div>
                                        <div class="col-xs-9">
                                            <div><strong><?php
                                                    if(isset(Yii::app()->user->id)){
                                                        echo $product['product_name']." ". date("d-M-Y",strtotime($_SESSION["myid".Yii::app()->user->id]['checkindate']))." - ".date("d-M-Y",strtotime($_SESSION["myid".Yii::app()->user->id]['checkoutdate']));
                                                    }
                                                    else{
                                                        echo $product['product_name']." ". date("d-M-Y",strtotime($_SESSION["myguestuser"]['mycheckindate']))." - ".date("d-M-Y",strtotime($_SESSION["myguestuser"]['mycheckoutdate']));
                                                    }
                                                    ?>
                                                </strong></div>
                                        </div>
                                    </div></td>
                                <td class="text-center"><div class="text-danger text-strike">€<?php echo $product['original_price']; ?></div>
                                    <div>€<?php echo $product['product_price']; ?></div></td>
                                <td class="text-center"><strong><?php if(isset(Yii::app()->user->id)){ echo $_SESSION["myid".Yii::app()->user->id]['numberofpeople'];}else{  echo $_SESSION["myguestuser"]['mynumberofpeople'];} ?></strong></td>
                                <?php
                                if(isset(Yii::app()->user->id)){
                                    $total =  $_SESSION["myid".Yii::app()->user->id]['numberofpeople']* $product['product_price'];
                                }
                                else{
                                    $total =  $_SESSION["myguestuser"]['mynumberofpeople']* $product['product_price'];
                                }

                                    $mytotal += $total;
                                ?>
                                <td class="text-right text-primary"><strong>€<?php echo $total; ?></strong></td>
                                <td class="text-center"><a href="javascript:void(0);" class="removeCart" id="remove_<?php echo $product['product_id']; ?>"><i class="fa fa-trash text-gray"></i></a></td>
                            </tr>

                        <?php }

                        if(empty($cartItem)){?>

                        <tr class="cart-footer">
                            <td colspan="5"><a href="#">Winkelmand updaten</a></td>
                        </tr>
                        <?php } ?>
                        <?php
                        if(isset(Yii::app()->user->id)){
                            $vatAmountPersonal = Countries::model()->findByAttributes(['id' => $_SESSION["myid".Yii::app()->user->id]['country']]);
                        }
                        else{
                            $vatAmountPersonal = Countries::model()->findByAttributes(['id' => $_SESSION["myguestuser"]['mycountry']]);
                        }
                        $vatamount = round(($mytotal * 0)/100);
                        $totalpayable = round($mytotal) + $vatamount;
                        ?>

                        <tr class="cart-total">
                            <td colspan="3"></td>
                            <td class="text-left bg-total" style="font-size: 18px;">Total <br/><span style="font-size: 12px;">[VAT inclusive 21%]</span></td>
                            <td class="text-right bg-total">€<span><?php echo $totalpayable; ?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!--grid ends-->
            </div>
        </div>
        <?php if($totalpayable != 0){ ?>
        <div class="row">
            <div class="col-md-12">
                <div class="u-MarginTop20 button-bar text-gray"> <a href="#paymentmethod-popup" class="btn btn-primary pull-right open-popup-link">Proceed to payment</a>
                    <div class="clearfix"></div>
                </div>
                <!--<div class="u-MarginTop20 button-bar text-gray"> <a href="#Banktransferdetails-popup" class="btn btn-primary pull-right open-popup-link">Doorgaan met afrekenen</a>
                    <div class="clearfix"></div>
                </div>-->
            </div>
        </div>
        <?php } ?>
    </div>
    </div>
</section>
<!--intro end-->

<input type="hidden" id="mytotalamount" value="<?php echo $totalpayable; ?>">
<input type="hidden" id="myvatamount" value="<?php echo $vatamount; ?>">
<input type="hidden" id="myvat" value="<?php  echo $vatAmountPersonal->personal_vat; ?>">



<!--payment method popup start-->
<div id="paymentmethod-popup" class="white-popup mfp-hide u-Padding50 text-center">
    <h2 class="u-MarginTop0 u-MarginBottom30 text-uppercase">Select Payment Method</h2>
    <a href="" class="btn btn-primary u-MarginRight10 u-xs-MarginBottom10">Other</a> <a href="#Banktransferdetails-popup" class="btn btn-primary open-popup-link">Bank Transfer</a> </div>
<!--payment method popup ends-->

<!--bank transfer details popup start-->
<div data-backdrop="static" data-keyboard="false" id="Banktransferdetails-popup" class="white-popup mfp-hide u-Padding50">
    <h2 class="u-MarginTop0 u-MarginBottom30 text-uppercase">Bank details</h2>
    <p class="text-center">
        <button title="SLUITEN" type="button" class="mfp-close btn-close u-MarginBottom10 u-MarginAuto placeOrder">Place Order</button>
    </p>

</div>
<!--bank transfer details popup ends-->


<?php
$currAddr = $_SERVER['SERVER_ADDR'];

$prodAddr = Yii::app()->params['ProdIPAddr'];

if($prodAddr == $currAddr){

    $payPalUrl = Yii::app()->params['PaypalUrl'];

} else {

    $payPalUrl = Yii::app()->params['PaypalSandboxUrl'];

}

?>

<form action="<?= $payPalUrl; ?>" method="post" name="paypal" id="paypalSubmit">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="invoice" value="" id="paypalInvoice">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="currency_code" value="EUR">
    <input type="hidden" name="business" value="mojoonpcpp-facilitator@hotmail.com">
    <input type="hidden" name="email" value="<?php if(isset(Yii::app()->user->id)){ echo $_SESSION["myid".Yii::app()->user->id]['email'];}else{ echo $_SESSION["myguestuser"]['myemail'];}  ?>">
    <!--<input type="hidden" name="business" value="accounting-facilitator@iriscall.com">
    <input type="hidden" name="email" value="accounting-buyer@iriscall.com">-->
    <?php $counter = 1; ?>
    <?php foreach ($cartItem as $key=>$product){  ?>
        <input type="hidden" name="item_name_<?= $counter; ?>" value="<?= $product['product_name']; ?>">
        <input type="hidden" name="amount_<?= $counter; ?>" value="<?= $product['product_price']; ?>">
        <input type="hidden" name="quantity<?= $counter; ?>" value="<?php if(isset(Yii::app()->user->id)){ echo $_SESSION["myid".Yii::app()->user->id]['numberofpeople']; }else{ echo $_SESSION["myguestuser"]['mynumberofpeople'];}  ?>">
        <?php $counter++; ?>
    <?php } ?>
    <input type="hidden" name="tax_cart" value="<?php echo $vatamount; ?>">
    <input type="hidden" name="return" value="" id="paypalReturn">
    <input type="hidden" name="cancel_return" value="" id="paypalCancel">
    <input type="hidden" name="image_url" value="<?php echo Yii::app()->baseUrl."/plugins/imgs/logo.png"; ?>">
    <input type="submit" value="PayPal" id="submitPaypal" style="display: none;">
</form>


<?php if(!isset(Yii::app()->user->id)){ ?>
<script type="text/javascript">
    var PlaceOrder = '<?php echo Yii::app()->createUrl('Order/PlaceOrder')?>';
    $('.placeOrder').click(function () {
        $('#orderId').val('<?php
            while( in_array( ($n = rand(1,5000000)), array(0, 4784,3071) ) );
            $invoiceNo = $n;
            $OrderId = OrderInfo::model()->find(array('order' => 'order_info_id DESC'));
            if ($OrderId == '') {
                echo $invoiceNo;
            } else {
                echo $OrderId['order_id'] + 1;
            }
            ?>');

        var formdata = {
            'amount': $('#mytotalamount').val(),
            'order_id': $('#orderId').val(),
            'vat-Amount': $('#myvatamount').val(),
            'vat': $("#myvat").val(),
        };
        /*console.info(formdata);
        return false;*/

        $.ajax({
            type: "POST",
            url: PlaceOrder,
            data: formdata,
            beforeSend: function () {
                $(".loading ").removeClass('hide');
            },
            success: function (data) {
                //toastr.success("Order Placed Successfully");
                var res = jQuery.parseJSON(data);
                if (res.token == 1) {
                    window.location.href = "<?php echo Yii::app()->createUrl("order/thankyou"); ?>";
                    //$('#submitOgone').click();
                }
            }
        });
    });


    var RemoveFromCart = '<?php echo Yii::app()->createUrl('product/RemoveFromCart')?>';
    $('.removeCart').click(function () {
        //console.info('hii');
        var id = $(this).attr('id');
        var splitid = id.split("_");
        var id_no = splitid[1];

        var formdata = {
            'id': id_no
        };
        $.ajax({
            type: "POST",
            url: RemoveFromCart,
            data: formdata,
            /*beforeSend: function () {
             $(".overlay").removeClass("hide");
             },*/
            success: function (data) {
                var res = jQuery.parseJSON(data);

                if (res.token == 1) {
                    window.location.reload();
                }
            }
        });
    });
</script>
<?php } ?>
