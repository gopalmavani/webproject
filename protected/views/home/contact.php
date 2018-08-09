<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 23/7/18
 * Time: 4:58 PM
 */
$this->pageTitle = "Contact us";
?>

<!--page title start-->
<section class="ImageBackground ImageBackground--overlay v-align-parent u-height350 u-BoxShadow40 js-Parallax banner" data-overlay="4">
    <div class="ImageBackground__holder"> <img src="<?php echo Yii::app()->baseUrl."/plugins/"; ?>imgs/banner/ban-contact.jpg" alt=""/> </div>
    <div class="v-align-child">
        <div class="container ">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <h1 class="text-uppercase u-Margin0 u-Weight700 text-white">Contact</h1>
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
                    <li class="active"><span>Contact</span></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- contact start-->
<div class="container u-MarginTop100 u-xs-MarginTop30 u-MarginBottom100 u-xs-MarginBottom30">
    <div class="row text-center">
        <div class="col-md-10 col-md-offset-1 wow fadeInUp" data-wow-delay="200ms">
            <h1 class=" u-MarginBottom10 u-Weight700 text-uppercase">Niet gevonden wat je zocht?</h1>
            <div class="Split Split--height2"></div>
        </div>
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 u-MarginBottom100 u-xs-MarginBottom30 u-sm-MarginBottom30 wow fadeInUp" data-wow-delay="200ms">
            <p class="u-PaddingTop30">Vul het contactformulier hieronder in en wij contacteren jou binnen 24u.</p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 u-sm-MarginBottom60 wow fadeInUp" data-wow-delay="200ms">
            <form class="row js-ContactForm" method="post">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Voornaam*" name="fname" data-error="Voornaam" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Achternaam*" name="lname" data-error="Achternaam" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email*" name="email" data-error="Ongeldig email adres" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <textarea class="form-control" rows="4" placeholder="Bericht*" name="bericht" data-error="U moet een bericht invoeren" required></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <img src="<?php echo Yii::app()->baseUrl."/plugins/"; ?>imgs/captcha.png" alt="" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="#thanks-popup" class="btn btn-primary open-popup-link">VERZENDEN</a>
                </div>

            </form>
            <!--thanks popup start-->
            <div id="thanks-popup" class="white-popup mfp-hide u-Padding50 text-center">
                <h1 class="u-MarginTop0 u-MarginBottom10 text-uppercase"><strong>Bedankt voor je bericht.</strong></h1>
                <h4 class="u-MarginTop0 u-MarginBottom0 text-gray">We nemen binnenkort contact met u op.</h4>
            </div>
            <!--thanks popup ends-->
        </div>
        <div class="col-md-6 wow fadeInUp" data-wow-delay="200ms">
            <div class="map-area">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1565.436332924165!2d-0.9362538502339063!3d38.30561787490515!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd63952e4559d843%3A0x3b2f49b512447ed5!2sPartida+Algezar%2C+4%2C+03688+La+Canalosa%2C+Alicante%2C+Spain!5e0!3m2!1sen!2sin!4v1532599637099" width="100%" height="350" frameborder="0" allowfullscreen></iframe>
            </div>
            <h3 class="u-Weight700 u-MarginTop0"> CRYPTOTRAIN </h3>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="text-gray">
                <tr>
                    <td valign="top" width="30px"><i class="fa fa-map-marker"></i></td>
                    <td valign="top">Partida Algezar 4<br>
                        03688 La Canalosa<br>
                        Alicante - Spanje</td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top"><i class="fa fa-phone"></i></td>
                    <td valign="top">+32 (0) 487 73 00 72</td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top"><i class="fa fa-envelope"></i></td>
                    <td valign="top"><a href="mailto:info@cryptotrain.eu">info@cryptotrain.eu</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<!-- contact end-->


<script src="<?php echo Yii::app()->baseUrl."/plugins/"; ?>vendor/bootstrap-validator/validator.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl."/plugins/"; ?>build/js/mailer.js"></script>