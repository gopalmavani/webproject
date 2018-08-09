<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 25/7/18
 * Time: 10:45 AM
 */
?>

<!--page title start-->
<section class="ImageBackground ImageBackground--overlay v-align-parent u-height350 u-BoxShadow40" data-overlay="5">
    <div class="ImageBackground__holder"> <img src="<?php echo Yii::app()->baseUrl."/plugins/imgs/banner/ban-home.jpg"; ?>" alt=""/> </div>
    <div class="v-align-child">
        <div class="container ">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <h1 class="text-uppercase u-Margin0 u-Weight700 text-white">Thank You</h1>
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
                    <li class="active"><span>Thank You</span></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!--intro start-->
<section class="u-MarginTop100 u-xs-MarginTop30 u-MarginBottom100 u-xs-MarginBottom30 position-relative">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInUp text-center" data-wow-delay="200ms">
                <h3 class=" u-xs-FontSize16 u-MarginBottom10 u-Weight700 text-uppercase">YOUR ORDER HAS BEEN RECIEVED.</h3>
                <h1 class="u-FontSize60 u-xs-FontSize40 u-MarginTop0 u-MarginBottom30 u-Weight700 text-uppercase text-primary">THANK YOU FOR YOUR PURCHASE!</h1>
                <h4 class="u-MarginBottom5">Your order no. is:
                    <strong>
                        <?php
                        if(isset(Yii::app()->user->id)){
                            echo $_SESSION["orderid".Yii::app()->user->id];
                        }
                        else{
                            echo $_SESSION["orderid".$_SESSION['mynewid']];
                        } ?>
                    </strong></h4>
                <p class="u-MarginBottom60">You will receive an e-mail confirming the order with details about your order.</p>
            </div>
        </div>
    </div>
</section>
<!--intro end-->
