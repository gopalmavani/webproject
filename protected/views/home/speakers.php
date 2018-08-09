<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 23/7/18
 * Time: 4:58 PM
 */
$this->pageTitle = "Speakers";
?>


<!--page title start-->
<section class="ImageBackground ImageBackground--overlay v-align-parent u-height350 u-BoxShadow40 js-Parallax banner" data-overlay="4">
    <div class="ImageBackground__holder"> <img src="<?php echo Yii::app()->baseUrl."/plugins/imgs/banner/ban-sprekers.jpg"; ?>" alt=""/> </div>
    <div class="v-align-child">
        <div class="container ">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <h1 class="text-uppercase u-Margin0 u-Weight700 text-white">Sprekers</h1>
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
                    <li class="active"><span>Sprekers</span></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!--speakers start-->
<section class="u-MarginTop100 u-xs-MarginTop30 u-MarginBottom100 u-xs-MarginBottom30 position-relative">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-10 col-md-offset-1 wow fadeInUp" data-wow-delay="200ms">
                <h1 class=" u-MarginBottom10 u-Weight700 text-uppercase">Maak kennis met onze professionals</h1>
                <div class="Split Split--height2"></div>
            </div>
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 u-MarginBottom100 u-xs-MarginBottom30 u-sm-MarginBottom30 wow fadeInUp" data-wow-delay="200ms">
                <p class="u-PaddingTop30">Cryptotrain heeft zich omringd met vakmensen om 'U' tegen te zeggen. Volgende inspirerende sprekers delen graag hun kennis met jou.</p>
            </div>
        </div>
        <div class="row text-center u-MarginBottom50">
            <div class="col-md-3 col-sm-6 u-MarginBottom35 wow fadeInUp" data-wow-delay="200ms">
                <div class="u-BoxShadow100">
                    <div class="Blurb u-InlineBlock u-sm-PaddingTop20 u-xs-PaddingTop50"> <a href="#s1-popup" class="open-popup-link hover-img">
                            <figure> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/tomdeblock-cryptotrain.jpg"; ?>" alt=""> <span class="Icon Icon-plus2"></span> </figure>
                        </a>
                        <h4 class="u-MarginTop25 u-MarginBottom0">Tom de Block</h4>
                        <p class="text-gray">Solution Architect Settlemint</p>
                        <p class="text-primary">Blockchain</p>
                        <p class="u-MarginTop20 u-MarginBottom20 Anchors"> <a class="text-muted" href="https://www.linkedin.com/in/navigio/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></p>
                        <div id="s1-popup" class="white-popup mfp-hide">
                            <div class="row">
                                <div class="col-md-5 u-sm-MarginBottom30"> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/tomdeblock-cryptotrain.jpg"; ?>" alt=""> </div>
                                <div class="col-md-7">
                                    <h3 class="u-MarginTop0 u-MarginBottom0">Tom de Block</h3>
                                    <p class="text-gray">Solution Architect Settlemint</p>
                                    <p class="text-primary">Blockchain</p>
                                    <p>Als Ex Swift en Ex Schengen medewerker staat Tom nog steeds met één been in de Oude Wereld bij ING en met een sterke voet in de Nieuwe Wereld bij Settlemint.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 u-MarginBottom35 wow fadeInUp" data-wow-delay="300ms">
                <div class="u-BoxShadow100">
                    <div class="Blurb u-InlineBlock u-sm-PaddingTop20 u-xs-PaddingTop50"> <a href="#s2-popup" class="open-popup-link hover-img">
                            <figure> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/cryptotrain-sprekers-brecht-van-craen.jpg"; ?>" alt=""> <span class="Icon Icon-plus2"></span> </figure>
                        </a>
                        <h4 class="u-MarginTop25 u-MarginBottom0">Brecht van Craen</h4>
                        <p class="text-gray">CFO bij BitSignals</p>
                        <p class="text-primary">Trading</p>
                        <p class="u-MarginTop20 u-MarginBottom20 Anchors"> <a class="text-muted" href="#" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></p>
                        <div id="s2-popup" class="white-popup mfp-hide">
                            <div class="row">
                                <div class="col-md-5 u-sm-MarginBottom30"> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/cryptotrain-sprekers-brecht-van-craen.jpg"; ?>" alt=""> </div>
                                <div class="col-md-7">
                                    <h3 class="u-MarginTop0 u-MarginBottom0">Brecht Van Craen</h3>
                                    <p class="text-gray">CFO bij BitSignals</p>
                                    <p class="text-primary">Trading</p>
                                    <p>Voor Brecht is de economische revolutie die cryptocurrencies zullen teweegbrengen de belangrijkste reden waarom hij 2 jaar geleden heeft besloten zich volledig toe te wijden aan de blockchain technologie.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 u-MarginBottom35 wow fadeInUp" data-wow-delay="400ms">
                <div class="u-BoxShadow100">
                    <div class="Blurb u-InlineBlock u-sm-PaddingTop20 u-xs-PaddingTop50"> <a href="#s3-popup" class="open-popup-link hover-img">
                            <figure> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/cryptotrain-sprekers-Kevin-robbens.jpg"; ?>" alt=""> <span class="Icon Icon-plus2"></span> </figure>
                        </a>
                        <h4 class="u-MarginTop25 u-MarginBottom0">Kevin Robbens</h4>
                        <p class="text-gray">CTO bij BitSignals</p>
                        <p class="text-primary">Trading</p>
                        <p class="u-MarginTop20 u-MarginBottom20 Anchors"> <a class="text-muted" href="#" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></p>
                        <div id="s3-popup" class="white-popup mfp-hide">
                            <div class="row">
                                <div class="col-md-5 u-sm-MarginBottom30"> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/cryptotrain-sprekers-Kevin-robbens.jpg"; ?>" alt=""> </div>
                                <div class="col-md-7">
                                    <h3 class="u-MarginTop0 u-MarginBottom0">Kevin Robbens</h3>
                                    <p class="text-gray">CTO bij BitSignals</p>
                                    <p class="text-primary">Trading</p>
                                    <p>Kevin is al langer dan 5 jaar betrokken in de Crypto Space en heeft een specialisatie in de toepassing en ontwikkeling van Distributed Ledger Technologies.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 u-MarginBottom35 wow fadeInUp" data-wow-delay="500ms">
                <div class="u-BoxShadow100">
                    <div class="Blurb u-InlineBlock u-sm-PaddingTop20 u-xs-PaddingTop50"> <a href="#s4-popup" class="open-popup-link hover-img">
                            <figure> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/stijn-rasschaert-cryptotrain.jpg"; ?>" alt=""> <span class="Icon Icon-plus2"></span> </figure>
                        </a>
                        <h4 class="u-MarginTop25 u-MarginBottom0">Stijn Rasschaert</h4>
                        <p class="text-gray">Tax Partner bij BDO</p>
                        <p class="text-primary">Tax &amp; Legal</p>
                        <p class="u-MarginTop20 u-MarginBottom20 Anchors"> <a class="text-muted" href="https://www.linkedin.com/in/stijnrasschaert/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></p>
                        <div id="s4-popup" class="white-popup mfp-hide">
                            <div class="row">
                                <div class="col-md-5 u-sm-MarginBottom30"> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/stijn-rasschaert-cryptotrain.jpg"; ?>" alt=""> </div>
                                <div class="col-md-7">
                                    <h3 class="u-MarginTop0 u-MarginBottom0">Stijn Rasschaert</h3>
                                    <p class="text-gray">Tax Partner bij BDO</p>
                                    <p class="text-primary">Tax &amp; Legal</p>
                                    <p>Stijn Rasschaert behaalde diverse diploma’s in accountancy en tax. In 2005 behaalde Stijn de titel van Accountant en Belastingconsulent bij het beroepsinstituut voor Accountants en Belastingconsulenten (IAB). Binnen BDO is Stijn verantwoordelijk voor een team van generalistische fiscalisten.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 u-MarginBottom35 wow fadeInUp" data-wow-delay="200ms">
                <div class="u-BoxShadow100">
                    <div class="Blurb u-InlineBlock u-sm-PaddingTop20 u-xs-PaddingTop50"> <a href="#s5-popup" class="open-popup-link hover-img">
                            <figure> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/alexandra-martin-cryptotrain.jpg"; ?>" alt=""> <span class="Icon Icon-plus2"></span> </figure>
                        </a>
                        <h4 class="u-MarginTop25 u-MarginBottom0">Alexandra Martin</h4>
                        <p class="text-gray">Tax Manager bij BDO</p>
                        <p class="text-primary">Tax &amp; Legal</p>
                        <p class="u-MarginTop20 u-MarginBottom20 Anchors"> <a class="text-muted" href="https://www.linkedin.com/in/alexandra-martin-b3ab894/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></p>
                        <div id="s5-popup" class="white-popup mfp-hide">
                            <div class="row">
                                <div class="col-md-5 u-sm-MarginBottom30"> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/alexandra-martin-cryptotrain.jpg"; ?>" alt=""> </div>
                                <div class="col-md-7">
                                    <h3 class="u-MarginTop0 u-MarginBottom0">Alexandra Martin</h3>
                                    <p class="text-gray">Tax Manager bij BDO</p>
                                    <p class="text-primary">Tax &amp; Legal</p>
                                    <p>Alexandra Martin studeerde aan de Universiteit Gent en behaalde daar een Masterdiploma Rechten en een Master na Master in Fiscaal Recht. In 2015 behaalde Alexandra haar titel van Belastingconsulent bij het beroepsinstituut voor Accountants en Belastingconsulenten (IAB). Binnen BDO maakt Alexandra deel uit van het Global Expatriate Services Center of Competence.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 u-MarginBottom35 wow fadeInUp" data-wow-delay="300ms">
                <div class="u-BoxShadow100">
                    <div class="Blurb u-InlineBlock u-sm-PaddingTop20 u-xs-PaddingTop50"> <a href="#s6-popup" class="open-popup-link hover-img">
                            <figure> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/jimmy-butzen-cryptotrain.jpg"; ?>" alt=""> <span class="Icon Icon-plus2"></span> </figure>
                        </a>
                        <h4 class="u-MarginTop25 u-MarginBottom0">Jimmy Butzen</h4>
                        <p class="text-gray">CTO bij Mining Kingz</p>
                        <p class="text-primary">Mining</p>
                        <p class="u-MarginTop20 u-MarginBottom20 Anchors"> <a class="text-muted" href="https://www.linkedin.com/in/jimmy-butzen-b7743950/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></p>
                        <div id="s6-popup" class="white-popup mfp-hide">
                            <div class="row">
                                <div class="col-md-5 u-sm-MarginBottom30"> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/jimmy-butzen-cryptotrain.jpg"; ?>" alt=""> </div>
                                <div class="col-md-7">
                                    <h3 class="u-MarginTop0 u-MarginBottom0">Jimmy Butzen</h3>
                                    <p class="text-gray">CTO bij Mining Kingz</p>
                                    <p class="text-primary">Mining</p>
                                    <p>Jimmy Butzen is een zelfstandig ondernemer en Software Developer bij Miningkingz. Hij is al 6 jaar betrokken bij in de "Crypto space". Hij kijkt voortdurend naar de toekomst en gelooft sterk in de Blockchain technologieën. Jimmy ziet het als een wereldwijd ontwaken en een enorme kans op transparantie en eerlijkheid voor ieder individu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 u-MarginBottom35 wow fadeInUp" data-wow-delay="400ms">
                <div class="u-BoxShadow100">
                    <div class="Blurb u-InlineBlock u-sm-PaddingTop20 u-xs-PaddingTop50"> <a href="#s7-popup" class="open-popup-link hover-img">
                            <figure> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/rob-van-kranenburg-cryptotrain.jpg"; ?>" alt=""> <span class="Icon Icon-plus2"></span> </figure>
                        </a>
                        <h4 class="u-MarginTop25 u-MarginBottom0">Rob Van Kranenburg</h4>
                        <p class="text-gray">Founder bij Council</p>
                        <p class="text-primary">IoT (Internet of Things)</p>
                        <p class="u-MarginTop20 u-MarginBottom20 Anchors"> <a class="text-muted" href="https://www.linkedin.com/in/robvankranenburg/" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></p>
                        <div id="s7-popup" class="white-popup mfp-hide">
                            <div class="row">
                                <div class="col-md-5"> <img class="img-responsive" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/sprekers/rob-van-kranenburg-cryptotrain.jpg"; ?>" alt=""> </div>
                                <div class="col-md-7">
                                    <h3 class="u-MarginTop0 u-MarginBottom0">Rob Van Kranenburg</h3>
                                    <p class="text-gray">Founder bij Council</p>
                                    <p class="text-primary">IoT (Internet of Things)</p>
                                    <p>Rob bouwt mee aan het ecosysteem voor het internet van de volgende generatie. Hij werkt als Ecosystem Manager voor EU-projecten als Tagitsmart en Next Generation Internet en is mede-oprichter van Bricolabs. Daarnaast zetelt hij in de adviesraad van SmartCitiesWorld, is hij voorzitter van het IERC én is hij lid van het International Advisory Panel (IAP) van IoT Asia 2017.</p>
                                    <p>Hij schreef het boek "The Internet of Things". Samen met Christian Nold publiceerde hij " The Internet of People for a Post-Oil World" en is hij co-editor van "Enabling Things to Talk".</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--speakers end-->