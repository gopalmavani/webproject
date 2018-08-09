<main id="main-container">

    <div class="bg-image" style="background-image: url('<?php echo Yii::app()->baseUrl; ?>/plugins/img/photos/photo3@2x.jpg.jpg');">
        <div class="bg-primary-dark-op">
            <section class="content content-full content-boxed overflow-hidden">
                <!-- Section Content -->
                <div class="push-30-t push-30 text-center">
                    <h1 class="h2 text-white push-10 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">Products</h1>
                </div>
                <!-- END Section Content -->
            </section>
        </div>
    </div>

    <!-- e-Commerce Header -->
    <!--<div class="bg-white">
        <section class="content content-mini content-boxed">
            <div class="row items-push">
                <div class="col-xs-6 col-sm-3">
                </div>
            </div>
        </section>
    </div>-->
    <!-- END e-Commerce Header -->

    <div class="block" style="margin-bottom:0px;">
        <div class="block-header bg-gray-lighter">
            <h3 class="block-title">All Products</h3>
        </div>
        <div class="block-content">
            <?php if(isset($_SESSION['addWish'])){ ?>
                <div class="alert alert-success" role="alert" id="autoalert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading text-center"><?php if(isset($_SESSION['addWish'])){ echo $_SESSION['addWish']; unset($_SESSION['addWish']); }; ?></h4>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['allWish'])){ ?>
                <div class="alert alert-danger" role="alert" id="autoalalert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading text-center"><?php if(isset($_SESSION['allWish'])){ echo $_SESSION['allWish']; unset($_SESSION['allWish']); }; ?></h4>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['addCart'])){ ?>
                <div class="alert alert-success" role="alert" id="addCart">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading text-center"><?php if(isset($_SESSION['addCart'])){ echo $_SESSION['addCart']; unset($_SESSION['addCart']); }; ?></h4>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['addalCart'])){ ?>
                <div class="alert alert-success" role="alert" id="addalcart">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading text-center"><?php if(isset($_SESSION['addalCart'])){ echo $_SESSION['addalCart']; unset($_SESSION['addalCart']); }; ?></h4>
                </div>
            <?php } ?>
            <section class="content content-boxed">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Products -->
                        <div class="row">
                            <?php
                            $i = 0;
                            if($products) {
                                foreach ($products as $product) {
                                    $i++;
                                    ?>
                                    <div class="col-sm-3 col-lg-3">
                                        <div class="block">
                                            <div class="img-container" style="height: 225px;">
                                                <input type="hidden" id="sku_<?php echo $i; ?>"
                                                       value="<?php echo $product->sku; ?>">
                                                <input type="hidden" id="name_<?php echo $i; ?>"
                                                       value="<?php echo $product->name; ?>">
                                                <input type="hidden" id="price_<?php echo $i; ?>"
                                                       value="<?php echo $product->price; ?>">
                                                <input id="<?php echo "sku_" . $i; ?>" name="pack-name"
                                                       type="hidden" value="<?php $product->sku; ?>"/>
                                                <input type="hidden" id="product-img_<?php echo $i; ?>" value="<?php echo Yii::app()->getBaseUrl(true) . $product->image; ?>">

                                                <img src="<?php echo Yii::app()->getBaseUrl(true) . $product->image; ?>" style="width: 62%;margin-left:60px;" class="img-responsive">
                                                <div class="img-options">
                                                    <div class="img-options-content">
                                                        <div class="push-20">
                                                            <a class="btn btn-sm btn-default"
                                                               href="<?php echo Yii::app()->createUrl('product/detail/' . $product->product_id); ?>">View</a>
                                                            <button type="button" class="btn btn-sm btn-default buyProduct" data-toggle="modal" data-target="#checkout"
                                                                    id="buy-now_<?php echo $i; ?>">Buy now
                                                            </button>
                                                        </div>
                                                        <div class="push-20">
                                                            <a class="btn btn-sm btn-default addCart"
                                                               id="cart_<?php echo $product->product_id; ?>"
                                                               href="javascript:void(0)">
                                                                <i class="fa fa-plus"></i> Add to cart
                                                            </a>

                                                            <a class="btn btn-sm btn-default addWishlist"
                                                               id="wishlist_<?php echo $product->product_id; ?>"
                                                               href="javascript:void(0)">
                                                                <i class="fa fa-plus"></i> Add to Wishlist
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="block-content" style="height: 140px;">
                                                <div class="push-10">
                                                    <div class="h4 font-w600 text-success pull-right push-10-l">&euro; <?php echo $product->price ?></div>
                                                    <a class="h4"
                                                       href="<?php echo Yii::app()->createUrl('product/detail/' . $product->product_id); ?>"><?php echo $product->name; ?></a>
                                                </div>
                                                <p class="text-muted"><?php echo $product->short_description; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }else{
                                ?>
                                <table class="table table-borderless table-striped table-vcenter">
                                    <tr>
                                        <td class="text-center">

                                        </td>
                                        <td class="hidden-xs text-center"></td>
                                        <td width="15%" class="text-right">No product found!</td>
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
                        <!-- END Products -->
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<!-- Pop Out Modal -->
<div class="modal fade" id="checkout" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Terms &amp; Conditions</h3>
                </div>
                <div class="block-content">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <h3 class="bold control-label" id="name-info"></h3><hr>
                            <img id="pro-img" src=" " width="45px" class="img-responsive inline"><br>
                            <h5>Price :  <span id="price-info"></span></h5>

                            <div class="form-group">
                                <label class="bold">VAT (<span id="vat-per"></span>) :</label>
                                <label class="bold" id="vatAmount"></label>
                            </div>
                            <div class="form-group">
                                <h3 class="bold green">Total:</h3>
                                <input type="hidden" value="" id="netTotal"/>
                                <input type="hidden" value="" id="vat-Amount"/>
                                <input type="hidden" value="" id="add_box"/>
                                <label class="bold" id="Total-price"></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h3 class="bold">Address</h3><hr>
                            <?php
                            $userInfo = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
                            $country = Countries::model()->findByAttributes(['id' => $userInfo->busAddress_country]);
                            if ($userInfo->busAddress_building_num && $userInfo->busAddress_street && $userInfo->busAddress_city && $userInfo->busAddress_postcode){ ?>
                            <div class="row">
                                <div style="margin-left:20px">
                                    <input type="radio" class="billing-add" name="billingAdddress" value="1" id="personal_add" checked /> <label>Personal</label>
                                    <div id="personal_address" style="display: block;">
                                        <address>
                                            <p><?php echo $userInfo->building_num?>,
                                                <?php echo $userInfo->street?>,
                                                <?php echo $userInfo->region?>,</p>
                                            <p></p><?php echo $userInfo->city?>,
                                            <?php
                                            $country = Countries::model()->findByAttributes(['id' => $userInfo->country]);
                                            echo $country->country_name; ?>
                                            <?php echo $userInfo->postcode?>.</p>
                                        </address>
                                    </div>
                                </div>


                                <div style="margin-left:20px">
                                    <input type="radio" class="billing-add" name="billingAdddress" data-action="true" value="2" id="business_add"/> <label>Business</label>
                                    <div style="display: none;" id="business_address">
                                        <address>
                                            <p><?php echo $userInfo->busAddress_building_num; ?>,
                                                <?php echo $userInfo->busAddress_street; ?>,
                                                <?php echo $userInfo->busAddress_region; ?>,</p>
                                            <p></p><?php echo $userInfo->busAddress_city; ?>,
                                            <?php
                                            $country = Countries::model()->findByAttributes(['id' => $userInfo->busAddress_country]);
                                            echo ($country) ? $country->country_name : ''; ?>,
                                            <?php echo $userInfo->busAddress_postcode; ?>.</p>
                                        </address>
                                    </div>
                                </div>

                                <?php }else{ ?>
                                    <div class="row">
                                        <div style="margin-left:20px">
                                            <input type="radio" class="billing-add" name="billingAdddress" value="1" id="personal_add" checked /> <label>Personal</label>
                                            <div id="personal_address" style="display: block;">
                                                <address>
                                                    <?php
                                                    if($userInfo->country!='0')
                                                    {
                                                    ?>
                                                    <p><?php echo $userInfo->building_num?>,
                                                        <?php echo $userInfo->street?>,
                                                        <?php echo $userInfo->region?>,</p>
                                                    <p></p><?php echo $userInfo->city?>,
                                                    <?php
                                                    $country = Countries::model()->findByAttributes(['country_code' => $userInfo->country]);
                                                    echo $country->country_name;
                                                    ?>
                                                    <?php echo $userInfo->postcode; }?></p>
                                                </address>
                                            </div>
                                        </div>
                                        <div style="margin-left:20px">
                                            <div style="background-color: #e2e1e1; margin-top: 12px; padding: 5px 15px 7px 15px;" class="green">
                                                <span><b>Info : </b>If you wish to pay through a company, please update company details.</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>


                                <div id="adddress_personal"></div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border:none;">
                    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal" id="proceed">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Pop Out Modal -->
<script>
    $(document).ready(function(){
        $('#autoalert').fadeOut(5500);
        $('#addCart').fadeOut(5500);
        $('#autoalalert').fadeOut(5500);
        $('#addalcart').fadeOut(5500);
    });
</script>