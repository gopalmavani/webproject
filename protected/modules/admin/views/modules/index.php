<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Modules
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--<div class="form-group m-form__group row">
        <div class="col-md-12">
            <a class="btn btn-primary" style="float: right; margin-bottom: 10px;margin-top: 10px;margin-right: 10px"  href="<?php /*echo Yii::app()->createUrl('/admin/Builder/create'); */?>">Create Module</a>
        </div>
    </div>-->
    <!-- Page Content -->
    <div class="form-group m-form__group row">
        <div class="col-md-12">
            <?php
            for($i=0;$i<$cou;$i++){
                $product_id = $model[$i]['product_id'];
                $sql1 = "SELECT `app_id` , `product_id` FROM `order_mapping` WHERE `app_id` = '$appid' AND `product_id` = '$product_id' AND `order_status` = '1'";
                $buycheck = Yii::app()->sitedb->createCommand($sql1)->queryAll();
                // print_r($buycheck[0]);exit;
                if($i%2 == 0){?>
                    <div class="form-group m-form__group row"></div>
                <?php }?>
                <div class="col-md-6">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">
                                        <?php echo $model[$i]['name'];?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <!--begin::Section-->
                            <div style="float: right;margin-left: 40px;margin-top: -18px">
                                <?php echo $model[$i]['image'];?>
                            </div>
                            <div class="m-section">
                     <span class="m-section__sub" style="margin-top: -10px;">
                     <?php echo $model[$i]['short_description'];?>.
                     </span>
                                <div class="form-group m-form__group row">
                                    <div class="col-xs-12 col-md-6" style="font-size: 12px;">
                                        <b>Installs</b>: <?php echo $model[$i]['description'];?>
                                    </div>
                                    <div class="col-xs-12 col-md-6 text-right" style="font-size: 12px;">
                                        <b>Created</b>:<?php echo gmdate('M-Y', strtotime($model[$i]['created_at']));?>
                                    </div>
                                </div>
                                <p></p>
                                <?php if($model[$i]['sku'] == 'htmlbuilder'){
                                    $htmlbuilder = is_file(Yii::getPathOfAlias('application.modules.admin.views.layouts.builder').'.php')?'yes':'no';
                                    if($htmlbuilder == 'yes'){
                                        $ecominstalled = "true";
                                        ?>
                                    <?php }else{$ecominstalled = "false";}
                                }else{
                                    $ecominstalled = "false";
                                    $table = Yii::app()->db->schema->getTable($model[$i]['sku']);
                                    //$ordertable = Yii::app()->db->schema->getTable('order_info');
                                    if(!empty($table)){
                                        $ecominstalled = "true";
                                        ?>
                                    <?php } }?>
                                <div class="form-group m-form__group row" style="margin-bottom: -45px;">
                                    <div class="col-xs-7" align="left">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#module-6-description" class="btn btn-info center-block" style="margin-left: 10px;">How it works</a>
                                    </div>
                                    <?php if($buycheck == null){ ?>
                                        <div class="col-xs-5" align="right">
                                            <a style="margin-right: 10px;" href="<?php echo Yii::app()->createUrl('/admin/Modules/pay') .'/'.$model[$i]['product_id'] ;?>" class="btn btn-warning center-block pricingCall">Buy Now</a>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="col-xs-5" align="right">
                                            <?php if($ecominstalled == "false"){ ?>
                                                <a style="margin-right: 10px;" href="javascript:void(0)" data-toggle="modal" data-target="#installmodule" class="btn btn-success center-block installer" id="<?php echo "in" . $model[$i]['product_id'];?>">
                                                    Install
                                                </a>
                                            <?php }
                                            else if($ecominstalled == "true" ){ ?>
                                                <a style="margin-right: 10px;" href="javascript:void(0)" class="btn btn-danger center-block installer installed" data-toggle="modal" data-target="#<?php echo "confirm" . $model[$i]['product_id'];?>">
                                                    Uninstall
                                                </a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--uninstall confirmation-->
                <div class="modal fade" id="<?php echo "confirm" . $model[$i]['product_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-center" id="exampleModalLabel"><b>Confirmation</b></h5>
                                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                   </button>-->
                            </div>
                            <div class="modal-body">
                                <span>All data of <?php echo $model[$i]['name'];?> will be removed..Are you sure you want to uninstall this module?</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="<?php echo "un" . $model[$i]['product_id'];?>" class="btn btn-danger">Uninstall</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="installmodule" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel"><b>Please Wait</b></h5>
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>-->
            </div>
            <div class="modal-body text-center">
                <span>Our system is working very hard in installing/removing your module...</span>
            </div>
            <!--<div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary">Save changes</button>
               </div>-->
        </div>
    </div>
</div>
<script>
    var producturl = "<?php echo Yii::app()->createUrl('../admin/product/GenerateTables/'); ?>";
    var orderurl = "<?php echo Yii::app()->createUrl('../admin/order/GenerateTables/'); ?>";
    var is_checked = $('#select-user-table').is(':checked');

    $('#in1').on("click",function(){
        $.ajax({
            url: producturl,
            type: 'POST',
            data: {data: is_checked},
            success: function (response) {
                $.ajax({
                    url: orderurl,
                    type: 'POST',
                    data: {data: is_checked},
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var walleturl = "<?php echo Yii::app()->createUrl('../admin/wallet/GenerateTables/'); ?>";
    var paymenturl = "<?php echo Yii::app()->createUrl('../admin/payment/GenerateTables/'); ?>";
    $('#in2').on("click",function () {
        $.ajax({
            url: walleturl,
            type: 'POST',
            data: {data: is_checked},
            success: function (response) {
                $.ajax({
                    url: paymenturl,
                    type: 'POST',
                    data: {data: is_checked},
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    /*$('#paymentmodule').on("click",function () {
        $.ajax({
            url: paymenturl,
            type: 'POST',
            data: {data: is_checked},
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });*/

    var eventurl = "<?php echo Yii::app()->createUrl('/admin/modules/events'); ?>";
    $('#in3').on("click",function () {
        $.ajax({
            url: eventurl,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var builderurl = "<?php echo Yii::app()->createUrl('/admin/modules/builder') ?>";
    $('#in4').on("click",function () {
        var $this = $(this);
        $this.button('loading');
        setTimeout(function() {
            $this.button('reset');
        }, 8000);

        $.ajax({
            url: builderurl,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var fb_feedurl = "<?php echo Yii::app()->createUrl('/admin/modules/fb_feed'); ?>";
    $('#in5').on("click",function () {
        $.ajax({
            url: fb_feedurl,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var ticketinstall = "<?php echo Yii::app()->createUrl('/admin/modules/ticket') ?>";
    $('#in6').on("click",function () {
        $.ajax({
            url: ticketinstall,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var mt4install = "<?php echo Yii::app()->createUrl('/admin/modules/mt4') ?>";
    $('#in7').on("click",function () {
        $.ajax({
            url: mt4install,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var sliderinstall = "<?php echo Yii::app()->createUrl('/admin/modules/slider') ?>";
    $('#in8').on("click",function () {
        $.ajax({
            url: sliderinstall,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallecom = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallecom'); ?>";
    $('#un1').on("click",function(){
        $.ajax({
            url: uninstallecom,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallwallet = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallwallet'); ?>";
    $('#un2').on("click",function(){
        $.ajax({
            url:uninstallwallet,
            type: 'POST',
            success:function(response){
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallevent = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallevents'); ?>";
    $('#un3').on("click",function () {
        $.ajax({
            url: uninstallevent,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallbuilder = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallbuilder') ?>";
    $('#un4').on("click",function () {
        var $this = $(this);
        $this.button('loading');
        setTimeout(function() {
            $this.button('reset');
        }, 8000);

        $.ajax({
            url: uninstallbuilder,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallfb = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallfb'); ?>";
    $('#un5').on("click",function () {
        $.ajax({
            url: uninstallfb,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallticket = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallticket') ?>";
    $('#un6').on("click",function () {
        $.ajax({
            url: uninstallticket,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallmt4 = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallmt4') ?>";
    $('#un7').on("click",function () {
        $.ajax({
            url: uninstallmt4,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallslider = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallslider') ?>";
    $('#un8').on("click",function () {
        $.ajax({
            url: uninstallslider,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });

    var uninstallpayment = "<?php echo Yii::app()->createUrl('/admin/modules/uninstallpayment'); ?>";
    $('#uninstallpayment').on("click",function(){
        $.ajax({
            url:uninstallpayment,
            type: 'POST',
            success:function(response){
                window.location.reload();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
</script>