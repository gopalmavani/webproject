<?php //echo "<pre>";print_r($orders);die;?>
<!--page title start-->
<section class="ImageBackground ImageBackground--overlay v-align-parent u-height350 u-BoxShadow40" data-overlay="5">
    <div class="ImageBackground__holder"> <img src="<?php echo Yii::app()->baseUrl."/plugins/imgs/banner/ban-home.jpg"; ?>" alt=""/> </div>
    <div class="v-align-child">
        <div class="container ">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <h1 class="text-uppercase u-Margin0 u-Weight700 text-white">Mijn bestellingen</h1>
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
                    <li class="active"><span>Mijn bestellingen</span></li>
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
                <h1 class="u-MarginBottom30 u-Weight700 text-uppercase">Mijn bestellingen</h1>
                <div class="Split Split--height2 u-MarginBottom30"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!--grid start-->
                <div class="table-responsive wow fadeInUp" data-wow-delay="200ms">
                    <?php if(!empty($orders)){ ?>
                    <table class="table table-striped table-bordered u-Margin0 text-gray">
                        <thead>
                        <tr>
                            <th>Factuur</th>
                            <th>Bedrag</th>
                            <th>Vervaldatum</th>
                            <th>Openstaand</th>
                            <!--<th></th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $order){?>
                                <tr>
                                    <td><a href="#" class="text-primary"><i class="fa fa-file-pdf-o u-MarginRight5"></i><?php echo $order->invoice_number;?></a></td>
                                    <td>€ <?php echo $order->netTotal;?></td>
                                    <td><?php echo date("d-m-Y", strtotime($order->invoice_date));?></td>
                                <?php
                                if ($order['order_status'] == 1) { ?>
                                    <td class="text-success"><i class="fa fa-check u-MarginRight5"></i>Betaald</td>
<!--                                    <td class="text-center"><a href="#" class="btn btn-primary btn-sm">Download</a></td>
-->                                    <?php }else { ?>
                                    <td class="text-danger"><i class="fa fa-close u-MarginRight5"></i>Onbetaald</td>
<!--                                    <td class="text-center"><a href="#" class="btn btn-primary btn-sm open-popup-link">Betalen</a></td>
-->                                    <?php }?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <?php }else{ ?>
                        U heeft nog geen bestellingen geplaatst.
                    <?php }?>

                </div>
                <!--grid ends-->
            </div>
        </div>
        <!--<div class="row">
            <div class="col-md-12 wow fadeInUp" data-wow-delay="200ms">
                <div class="u-MarginTop20 button-bar text-gray">
                    <div class="pull-left u-xs-MarginBottom10">Vragen over een factuur? Stel ze <a href="mailto:info@cryptotrain.eu">hier</a></div>
                    <a href="reserveer-stap1.php" class="btn btn-primary pull-right">Opnieuw bestellen</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>-->
    </div>
</section>
<!--intro end-->