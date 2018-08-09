<?php /* @var $this Controller */ ?>
<?php $application = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", Yii::app()->params['applicationName']))?>
<?php
$nav = NavCheck::menuCheck();
// print_r($_SERVER['REQUEST_URI']);die;
$curaction = explode("/",$_SERVER['REQUEST_URI']);
// echo $curaction;die;
if(isset(Yii::app()->user->id)){
    $cart= Cart::model()->findAllByAttributes(['user_id' => Yii::app()->user->id]);
    $cartcount = count($cart);
}
else{
    $cartcount = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="<?php echo Yii::app()->baseUrl."/plugins/imgs/favicon.png"; ?>" />
    <title>Web Project</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <!-- inject:css -->
    <?php
    //Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/fullcalendar.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/bootstrap.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/vendor/bootsnav/css/bootsnav.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/vendor/font-awesome/css/font-awesome.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/vendor/custom-icon/css/style.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/vendor/owl.carousel2/owl.carousel.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/vendor/magnific-popup/magnific-popup.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/vendor/animate.css/animate.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/main.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/vendor/testimonial/owl.carousel.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/vendor/testimonial/feedback-slider.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/toastr.min.css');
    //Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css');
    //Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/custom.css');
    ?>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <script>
        $(window).load(function() { // makes sure the whole site is loaded
            $("#status").fadeOut(); // will first fade out the loading animation
            $("#preloader").delay(500).fadeOut("slow"); // will fade out the white DIV that covers the website.
        })
    </script>
    <style>
        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }
        nav.navbar.bootsnav.no-background.white ul.nav > li > a {
            color: #6f6f6f;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.3);
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: #000000;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 1500ms infinite linear;
            -moz-animation: spinner 1500ms infinite linear;
            -ms-animation: spinner 1500ms infinite linear;
            -o-animation: spinner 1500ms infinite linear;
            animation: spinner 1500ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
<div id="preloader">
    <div id="status"> <img src="<?php echo Yii::app()->baseUrl."/plugins/imgs/preloader.gif"; ?>" alt="" class="img-responsive"/> </div>
</div>
<div class="loading hide">Loading&#8230;</div>

<?php
/*$cartItem = $sum =  [];
if ($product_table = Yii::app()->db->schema->getTable('product_info')) {
    if(!empty($product_table)) {
        $product = ProductInfo::model()->findAll();
    }
}
if($order_table = Yii::app()->db->schema->getTable('order_info')){
    if(!empty($order_table))
        $userCheck = OrderInfo::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId()]);
}
if($cart_table = Yii::app()->db->schema->getTable('cart')){
    if(!empty($cart_table))
        $cart = Cart::model()->findAllByAttributes(['user_id' => Yii::app()->user->getId()]);
}

if(!empty($product_table)) {
    foreach ($cart as $carts) {
        $cartProduct = ProductInfo::model()->findByAttributes(['product_id' => $carts->product_id]);
        $sum[] = $carts->qty * $cartProduct->price;
        $cartItem[] = [
            'product_id' => $cartProduct->product_id,
            'product_name' => $cartProduct->name,
            'product_price' => $cartProduct->price,
            'product_image' => $cartProduct->image,
            'product_summary' => $cartProduct->short_description,
            'product_qty' => $carts->qty
        ];
    }
    $count = count($cart);
    $cartTotal = array_sum($sum);
}*/
?>

<?php //if(!isset($_COOKIE['cookiepolicy'])){  ?>
    <!--cookie popup start-->
    <!-- <div  id="agree" class="cookie-popup wow fadeInUp" data-wow-delay="100ms">
        <div class="row">
            <div class="col-sm-9">
                Het surfen op onze website kan de installatie van cookies op uw computer tot gevolg hebben. Ze vereenvoudigen het bezoek alsook verbeteren zij de communicatie met de website. U kan de installatie van deze cookies op uw computer weigeren, maar zulke weigering kan de toegang tot bepaalde diensten van de website in de weg staan.
            </div>
            <div class="col-sm-3">
                <a href="javascript:void();" onclick="hide('agree')" class="btn btn-sm btn-primary text-uppercase u-MarginRight10">AKKOORD</a>
                <a href="<?php //echo Yii::app()->createUrl("home/cookiebeleid"); ?>" class="btn btn-sm btn-primary text-uppercase">MEER INFORMATIE</a>
            </div>
        </div>
    </div> -->
    <!--cookie popup ends-->
<?php //} ?>

<?php
function selected($filename){
    if (stristr($_SERVER["PHP_SELF"] ,$filename)){
        echo 'class="active"';
    }
}
?>
<!--header start-->

<header id="header" class="Sticky hidden-print">
    <!-- Start Navigation -->
    <nav class="navbar navbar-default navbar-fixed white no-background bootsnav">
        <div class="container">
            <!-- Start Atribute Navigation -->
            <div class="attr-nav">
                <ul>
                    <?php if(isset(Yii::app()->user->id)){ ?>
                        <?php if($curaction == "checkout" && Yii::app()->controller->id == "product"){ ?>
                            <li> <a href="<?php echo Yii::app()->createUrl("product/checkout") ?>"> <i class="fa fa-shopping-cart"></i> <span class="badge"><?php echo $cartcount; ?></span> </a></li>
                        <?php }
                    } ?>
                    <li class="buy-btn hidden-xs hidden-sm"><a href="<?php echo Yii::app()->createUrl("event/index"); ?>"><span class="btn btn-sm btn-primary n-MarginTop5 text-uppercase">Book Now</span></a></li>
                </ul>
            </div>
            <!-- End Atribute Navigation -->

            <!-- Start Header Navigation -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu"> <i class="fa fa-bars"></i> </button>
                <a class="navbar-brand" style="margin-top: 10px;" href="<?php echo Yii::app()->createUrl("home/index"); ?>"> <!-- <img src="<?php //echo Yii::app()->baseUrl."/plugins/imgs/logo.png"; ?>" height="60px" class="logo logo-display" alt="Web Project"> -->
                 <!--  for scrolled menu sticky image -->
                    <!-- <img src="<?php //echo Yii::app()->baseUrl."/plugins/imgs/logo.png"; ?>" class="logo logo-scrolled" alt="Web Project">--> Web Project</a>  
            </div> 
                        <!-- End Header Navigation -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav navbar-right" data-in="" data-out="">
                    <li class="<?php  if($curaction == "index" || $curaction == ""){echo "active"; } ?>">
                        <a href="<?php echo Yii::app()->createUrl("home/"); ?>">Home</a>
                    </li>
                    <!-- <li class="<?php if($curaction == "overons"){echo "active"; } ?>">
                        <a href="<?php //echo Yii::app()->createUrl("/home/overons"); ?>">Over ons</a>
                    </li>
                    <li class="<?php if($curaction == "seminaries"){echo "active"; } ?>">
                        <a href="<?php //echo Yii::app()->createUrl("/home/seminaries"); ?>">Seminaries</a>
                    </li>
                    <li class="<?php if($curaction == "sprekers"){echo "active"; } ?>">
                        <a href="<?php //echo Yii::app()->createUrl("/home/sprekers"); ?>">Sprekers</a>
                    </li>
                    <li class="<?php if($curaction == "locatie"){echo "active"; } ?>">
                        <a href="<?php //echo Yii::app()->createUrl("/home/locatie"); ?>">Locatie</a>
                    </li> -->
                    <!--<li>
                        <a href="<?php /*echo Yii::app()->createUrl("/home/index"); */?>">Agenda</a>
                    </li>-->
                    <!-- <li class="<?php if($curaction == "contact"){echo "active";} ?>">
                        <a href="<?php //echo Yii::app()->createUrl("/home/contact"); ?>">Contact</a>
                    </li> -->
                    <?php //if(isset(Yii::app()->user->id)){ ?>
                    <!-- <li class="dropdown "><a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-caret"></i>Mijn account</a>
                        <ul class="dropdown-menu">
                            <li class="<?php //if($curaction == "invoice"){echo " active";} ?>"><a href="<?php //echo Yii::app()->createUrl("/order/invoice"); ?>">Mijn bestellingen</a></li>
                            <li class="<?php //if($curaction == "mijngegevens"){echo " active";} ?>"><a href="<?php //echo Yii::app()->createUrl("/user/mijngegevens"); ?>">Mijn gegevens</a></li>
                            <li class="<?php //if($curaction == "paswoordveranderen"){echo " active";} ?>"><a href="<?php //echo Yii::app()->createUrl("/user/paswoordveranderen"); ?>">Paswoord veranderen</a></li>
                            <li><a href="<?php //echo Yii::app()->createUrl('home/logout'); ?>">Uitloggen</a></li>
                            <?php //if(isset(Yii::app()->user->role)){
                                //if(Yii::app()->user->role != 'user'){ ?>
                                    <li><a href="<?php //echo Yii::app()->getBaseUrl().'/admin/home/index'; ?>">Go to Admin</a>
                                    </li>
                                <?php //}
                            //} ?>
                        </ul>
                    </li> -->
                    <?php// } else { ?>
                    <!-- <li>
                        <a href="#login-popup" class="open-popup-link">Inloggen</a>
                    </li> -->
                    <?php //} ?>
                </ul>
            </div>

            <!--wachtwoord vergeten popup start-->
            <div id="wachtwoordvergeten-popup" class="white-popup mfp-hide u-Padding50">
                <h2 class="u-MarginTop0 u-MarginBottom30 text-uppercase">Wachtwoord vergeten</h2>
                <p>Weet je het wachtwoord niet meer? Vul hieronder je e-mailadres in.<br />
                    We sturen dan binnen enkele minuten een e-mail waarmee een nieuw wachtwoord kan worden aangemaakt.</p>
                <form class="row js-ContactForm" method="post" action="<?php echo Yii::app()->createUrl('user/forgot'); ?>">
                    <div class="col-sm-6 u-MarginTop20">
                        <div class="form-group u-MarginBottom20">
                            <label>Je e-mailadres</label>
                            <input type="text" class="form-control" placeholder="Geef hier je e-mailadres in" name="email" autofocus>
                            <span class="help-block" id="forgotpassword"><?php if(isset($_SESSION['forgotpasswordmsg'])){ echo $_SESSION['forgotpasswordmsg']; unset($_SESSION['forgotpasswordmsg']);} ?></span>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Verzenden</button>
                        </div>
                    </div>
                </form>
            </div>
            <!--wachtwoord vergeten popup ends-->

            <!-- /.navbar-collapse -->
        </div>

        <!-- <div class="side-menu"><a href="#"><span><i class="fa fa-calendar"></i></span></a></div> -->
        <!-- Start Side Menu -->
        <div class="side" id="scrollbar"> <a href="#" class="close-side"><i class="fa fa-times"></i></a>
            <div class="u-PaddingTop30 u-PaddingBottom30">
                <h2 class="u-MarginTop0 u-MarginBottom10">Volgende seminaries</h2>
                <p class="text-gray  u-MarginBottom20">Iedere <strong class="text-primary">maandag</strong> en <strong class="text-primary">donderdag</strong> start een nieuw seminarie in Spanje. Daarna 3 maanden begeleiding in België.</p>
                <p><a href="<?php echo Yii::app()->createUrl("event/index"); ?>" class="text-primary">Klik om te reserveren</a></p>
            </div>
            <!--Timeline start-->
            <section class="u-PaddingTop0 u-PaddingBottom0 u-xs-PaddingBottom0">
                <div class="Timeline u-MarginBottom50">
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">06/08/2018 - 09/08/2018</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Alicante, Spanje</strong></h4>
                        </a> </div>
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">09/08/2018 - 12/08/2018</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Alicante, Spanje</strong></h4>
                        </a> </div>
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">13/08/2018 - 16/08/2018</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Alicante, Spanje</strong></h4>
                        </a> </div>
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">16/08/2018 - 19:00 tot 21:00</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Gent, België</strong></h4>
                            <h5 class="u-MarginTop0 u-MarginBottom5 text-primary">Mining</h5>
                        </a> </div>
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">16/08/2018 - 19/08/2018</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Alicante, Spanje</strong></h4>
                        </a> </div>
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">20/08/2018 - 23/08/2018</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Alicante, Spanje</strong></h4>
                        </a> </div>
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">23/08/2018 - 26/08/2018</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Alicante, Spanje</strong></h4>
                        </a> </div>
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">27/08/2018 - 30/08/2018</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Alicante, Spanje</strong></h4>
                        </a> </div>
                    <div class="Timeline__item"> <a href="<?php echo Yii::app()->createUrl("event/index"); ?>">
                            <h5 class="u-MarginTop0 u-MarginBottom5 speaker-name text-primary">30/08/2018 - 19:00 tot 21:00</h5>
                            <h4 class="u-MarginTop0 u-MarginBottom5"><strong>Gent, België</strong></h4>
                            <h5 class="u-MarginTop0 u-MarginBottom5 text-primary">Trading</h5>
                        </a> </div>
                </div>
            </section>
            <!--Timeline end-->
    </nav>
    <!-- End Navigation -->
    <div class="clearfix"></div>
</header>
    <?php echo $content; ?>

<!--schdule info-->
<?php //if(Yii::app()->controller->id != "home" || //Yii::app()->controller->action->id != "index" && Yii::app()->controller->action->id != "cookiebeleid" && Yii::app()->controller->action->id != "disclaimer" && Yii::app()->controller->action->id != "privacybeleid"){ ?>
    <!-- <section class="bg-primary bg-primary--gradient u-PaddingTop50 u-PaddingBottom50 schedule-info">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 u-sm-MarginBottom30">
                    <div class="media u-OverflowVisible wow fadeInUp" data-wow-delay="200ms">
                        <div class="media-left">
                            <div class="Thumb"> <i class="Icon Icon-map Icon--32px" aria-hidden="true"></i> </div>
                        </div>
                        <div class="media-body">
                            <h4 class="u-MarginTop0 u-MarginBottom5 u-Weight700">CryptoTrain Seminarie</h4>
                            <small>Alicante, Spanje</small> </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 u-sm-MarginBottom30">
                    <div class="media u-OverflowVisible wow fadeInUp" data-wow-delay="300ms">
                        <div class="media-left">
                            <div class="Thumb"> <i class="Icon Icon-calendar Icon--32px" aria-hidden="true"></i> </div>
                        </div>
                        <div class="media-body">
                            <h4 class="u-MarginTop0 u-MarginBottom5 u-Weight700">
                                <?php
                                // $today = date("Y-m-d");
                                // //$today = date("2018-08-07");
                                // $fixdate = date("2018-08-06");
                                // $count = "";
                                // if(strtotime($today) < strtotime($fixdate)){
                                //     $endtimestamp = date(strtotime($fixdate.'+3 days'));
                                //     $enddate = date("Y-m-d H:i:s",$endtimestamp);
                                //     echo date("d F",strtotime($fixdate))."-".date("d F",strtotime($enddate));
                                //     $checkindate = date('Y-m-d H:i:s',strtotime($fixdate));
                                //     $sql = "select count(*) as bookingcount from booking where checkindate = '$checkindate'";
                                //     $result = Yii::app()->db->createCommand($sql)->queryAll();
                                //     $count = $result[0]['bookingcount'];
                                // }
                                // else{
                                //     $thisday = date('N',strtotime($today));
                                //     if($thisday == 1 || $thisday == 4) {
                                //         $endtimestamp = date(strtotime($today.'+3 days'));
                                //         $enddate = date("Y-m-d H:i:s",$endtimestamp);
                                //         echo date("d F",strtotime($today))."-".date("d F",strtotime($enddate));
                                //         $checkindate = date('Y-m-d H:i:s',strtotime($today));
                                //         $sql = "select count(*) as bookingcount from booking where checkindate = '$checkindate'";
                                //         $result = Yii::app()->db->createCommand($sql)->queryAll();
                                //         $count = $result[0]['bookingcount'];
                                //     }
                                //     else{
                                //         if($thisday > 1 && $thisday < 4){
                                //             $upcomingdate = date("Y-m-d",strtotime('next thursday',strtotime($today)));
                                //             $upcomingendtimestamp = date(strtotime($upcomingdate.'+3 days'));
                                //             $upcomingenddate = date("Y-m-d H:i:s",$upcomingendtimestamp);
                                //             echo date("d F",strtotime($upcomingdate))."-".date("d F",strtotime($upcomingenddate));
                                //             $checkindate = date('Y-m-d H:i:s',strtotime($upcomingdate));
                                //             $sql = "select count(*) as bookingcount from booking where checkindate = '$checkindate'";
                                //             $result = Yii::app()->db->createCommand($sql)->queryAll();
                                //             $count = $result[0]['bookingcount'];
                                //         }
                                //         elseif($thisday > 4){
                                //             $upcomingdate = date('d F',strtotime('next monday',strtotime($today)));
                                //             $upcomingendtimestamp = date(strtotime($upcomingdate.'+3 days'));
                                //             $upcomingenddate = date("Y-m-d H:i:s",$upcomingendtimestamp);
                                //             echo date("d F",strtotime($upcomingdate))."-".date("d F",strtotime($upcomingenddate));
                                //             $checkindate = date('Y-m-d H:i:s',strtotime($upcomingdate));
                                //             $sql = "select count(*) as bookingcount from booking where checkindate = '$checkindate'";
                                //             $result = Yii::app()->db->createCommand($sql)->queryAll();
                                //             $count = $result[0]['bookingcount'];
                                //         }
                                //     }
                                // }
                                ?>
                            </h4>
                            <small>Persoonlijke coaching</small> </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 u-sm-MarginBottom30">
                    <div class="media u-OverflowVisible wow fadeInUp" data-wow-delay="400ms">
                        <div class="media-left">
                            <div class="Thumb"> <i aria-hidden="true"><img src="<?php //echo Yii::app()->baseUrl."/plugins/imgs/icons/icon-group.svg"; ?>" alt="" width="36px"></i> </div>
                        </div>
                        <div class="media-body">
                            <h4 class="u-MarginTop0 u-MarginBottom5 u-Weight700">Nog <?php //echo 7 - $count; ?> plaatsen</h4>
                            <small>Reserveer snel!</small> </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="media u-OverflowVisible wow fadeInUp" data-wow-delay="500ms">
                        <div class="media-left">
                            <div class="Thumb"> <i aria-hidden="true"><img src="<?php //echo Yii::app()->baseUrl."/plugins/imgs/icons/icon-all.svg" ?>" alt="" width="36px"></i> </div>
                        </div>
                        <div class="media-body">
                            <h4 class="u-MarginTop0 u-MarginBottom5 u-Weight700">All inclusive</h4>
                            <small>Zorg dat je erbij bent!</small> </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
<?php //} ?>
<!--schdule info-->


<!--footer start-->
<footer class="bg-darker u-PaddingTop60 u-xs-PaddingTop30 u-PaddingBottom60 u-xs-PaddingBottom30 hidden-print">
    <div class="container text-center text-sm">
        <img class="u-MarginBottom30" src="<?php //echo Yii::app()->baseUrl."/plugins/imgs/logo-white.png"; ?>" alt="">
        <div class="u-MarginBottom20"><a href="<?php echo Yii::app()->createUrl("event/index"); ?>" class="btn btn-primary n-MarginTop5 text-uppercase">Book Now</a></div>
        <div class="social-links sl-default light-link solid-link circle-link colored-hover"> <a href="#" class="facebook" target="new"> <i class="fa fa-facebook"></i> </a> <a href="#" class="youtube" target="new"> <i class="fa fa-youtube"></i></a></div>
        <p class="u-MarginBottom5 u-MarginTop20">Copyright 2018 &copy; .</p>
        <!-- <p class="u-MarginBottom0 u-PaddingBottom0"><a href="<?php //echo Yii::app()->createUrl("home/cookiebeleid"); ?>">Cookie beleid</a> | <a href="<?php //echo Yii::app()->createUrl("home/disclaimer"); ?>">Disclaimer/Legale informatie</a> | <a href="<?php //echo Yii::app()->createUrl("home/privacybeleid"); ?>">Privacybeleid</a></p> -->
    </div>
</footer>
<!--footer end-->
<a href="#" class="BackToTop hidden-print" title="Back to top"><i class='fa fa-angle-up'></i></a>


<!--login popup start-->
<!-- <input type="hidden" id="loginerror" value="<?php //if(isset($_SESSION['loginerror'])){ echo $_SESSION['loginerror']; unset($_SESSION['loginerror']); } ?>" /> -->
<!-- <div id="login-popup" class="white-popup mfp-hide u-Padding50" style="max-width:400px">
    <h1 class="u-MarginTop0 u-MarginBottom30 text-uppercase">Inloggen</h1>
    <div class="row">
        <div class="col-sm-12 u-xs-MarginBottom60">
            <h4 class="u-MarginTop0 u-MarginBottom10 text-primary">Bestaande klanten</h4>
            <form action="<?php //echo Yii::app()->createUrl("/home/login"); ?>" method="POST" id="loginform">
                <div class="form-group u-MarginBottom20">
                    <input placeholder="Geef hier je e-mailadres in" class="form-control" autofocus="autofocus" name="LoginForm[username]" id="LoginForm_username" type="email">
                </div>
                <div class="form-group u-MarginBottom20">
                    <input class="form-control" placeholder="Geef hier je wachtwoord in" name="LoginForm[password]" id="LoginForm_password"  type="password">
                    <span class="help-block" id="incorrectpassword"><?php //if(isset($_SESSION['incorrectusernameerror'])){ //echo $_SESSION['incorrectusernameerror']; unset($_SESSION['incorrectusernameerror']); }?></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Inloggen</button>
                </div>
                <div><a href="#wachtwoordvergeten-popup" class="open-popup-link">Wachtwoord vergeten?</a></div>
            </form>
            <a href="<?php //echo Yii::app()->createUrl("home/register"); ?>">Registreer hier</a>
        </div>
    </div>
</div> -->
<!--login popup ends-->

<!-- <input type="hidden" id="successtoast" value="<?php //if(isset($_SESSION['successtoast'])){echo $_SESSION['successtoast']; unset($_SESSION['successtoast']); } ?>" > -->
</body>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/bootstrap.min.js');
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.slimscroll.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.scrollLock.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.appear.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.countTo.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.placeholder.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/js.cookie.min.js', CClientScript::POS_END);*/
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datetimepicker/moment.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/magnific-popup/magnific-popup.min.js', CClientScript::POS_END);*/

/*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/moment.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/fullcalendar.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/gcal.min.js');*/

/*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.validate.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/additional-methods.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.md5.js');*/

//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/app.js', CClientScript::POS_END);
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/custom.js', CClientScript::POS_END);
?>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/bootsnav/js/bootsnav.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/waypoints/jquery.waypoints.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/waypoints/sticky.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/headroom/headroom.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/owl.carousel2/owl.carousel.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/jquery.appear/jquery.appear.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/isotope/isotope.pkgd.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/imagesloaded/imagesloaded.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/magnific-popup/jquery.magnific-popup.min.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/main.js"; ?>"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/toastr.min.js"; ?>"></script>

<!--google map-->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCVkU7YnGfown4_i_sm6X36HP2jWTv54&callback=initMap"> </script>
<script>
    function initMap() {
        var location, map, marker;
        location = {lat: 38.30562, lng: -0.93605};
        map = new google.maps.Map(document.getElementById('map'), {
            center: location,
            zoom: 15,
            scrollwheel:false
        });
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }
</script>


<!--banner text animation-->
<!--<script src="<?php /*echo Yii::app()->baseUrl."/plugins/js/vendor/3deffect/jquery.hover3d.js"; */?>" ></script>
<script>
    $(document).ready(function(){
        $(".banner-text").hover3d({
            selector: ".banner-text-inner",
            sensitivity: 100,
        });
    });
</script>-->

<!--testimonial slider-->
<script src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/testimonial/owl.carousel.min.js"; ?>" ></script>
<script src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/testimonial/feedback-slider.js"; ?>" ></script>

<!--back to top-->
<script>
    if ($('.BackToTop').length) {
        var scrollTrigger = 100, // px
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('.BackToTop').addClass('show');
                } else {
                    $('.BackToTop').removeClass('show');
                }
            };
        backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });
        $('.BackToTop').on('click', function (e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }
</script>

<!--animate-->
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl."/plugins/js/vendor/animate.css/animate.min.js"; ?>"></script>
<script type="text/javascript">
    new WOW().init();
    //smoothScroll.init();
</script>

<script>
    // function show(target) {
    //     document.getElementById(target).style.display = 'block';
    // }
    // function hide(target) {
    //     document.getElementById(target).style.display = 'none';
    //     setCookie('cookiepolicy','accepted',8);

    // }


    // function setCookie(cname, cvalue, exdays) {
    //     var d = new Date();
    //     d.setTime(d.getTime() + (exdays*24*60*60*1000));
    //     var expires = "expires="+ d.toUTCString();
    //     document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    // }
</script>


<script type="text/javascript">
    $(document).ready(function () {
        var loginerror = $("#loginerror").val();
        if(loginerror != ""){
            // toastr.error(loginerror);
        }

        var successmessage = $("#successtoast").val();
        if(successmessage != ""){
            // toastr.success(successmessage);
        }

        var incorrectpassword = $("#incorrectpassword").text();
        if(incorrectpassword != ""){
            $("#incorrectpassword").parent().addClass('has-error');
            (function($) {
                $(window).load(function () {
                    $.magnificPopup.open({
                        items: {
                            src: '#login-popup'
                        },
                        type: 'inline'
                    }, 0);
                });
            })(jQuery);
        }

        var forgotpassword = $("#forgotpassword").text();
        if(forgotpassword != ""){
            $("#forgotpassword").parent().addClass('has-error');
            (function($) {
                $(window).load(function () {
                    $.magnificPopup.open({
                        items: {
                            src: '#wachtwoordvergeten-popup'
                        },
                        type: 'inline'
                    }, 0);
                });
            })(jQuery);
        }
    });
</script>
</html>



<?php
$order = RAND(1, 999);
$ogonepspid = 'OGONETEST';
$amount = 100;
$passphrasein = 'TESTPHASEIN';
$PSPID = $ogonepspid; //'OGONETEST';
$Price = $amount * 100;
$passphrase = $passphrasein;//'TESTPHASEIN';
$unique_id = RAND(890000, 895689596);
$baseUrl = Yii::app()->getBaseUrl(true);
$acceprtUrl = $baseUrl . "/order/paypalintegration";
$cancelUrl = $baseUrl . "/order/cancel";
$declineUrl = $baseUrl . "/order/decline";
$exceptionUrl = $baseUrl . "/order/exception";

$Ogone_sha1 = "ACCEPTURL = " . $acceprtUrl . $passphrase .
    "AMOUNT=" . $Price . $passphrase .
    "CANCELURL= " . $cancelUrl . $passphrase .
    "CN=\"deepeak" . $passphrase .
    "CURRENCY=EUR" . $passphrase .
    "DECLINEURL= " . $acceprtUrl . $passphrase .
    "EMAIL=\"deepakvisani@gmail.com" . $passphrase .
    "EXCEPTIONURL= " . $exceptionUrl . $passphrase .
    "LANGUAGE=en_us" . $passphrase .
    "LOGO= \"http://localhost/cyclone/common/app2/Logo.png" . $passphrase .
    "OPERATION=SAL" . $passphrase .
    "ORDERID=" . $unique_id . $passphrase .
    "PSPID=" . $PSPID . $passphrase;

$Ogone_shaOut = "ACCEPTURL = " . $acceprtUrl . $passphrase .
    "CANCELURL= " . $cancelUrl . $passphrase .
    "CN=\"deepak" . $passphrase .
    "CURRENCY=EUR" . $passphrase .
    "DECLINEURL= " . $acceprtUrl . $passphrase .
    "EMAIL=\"deepakvisani@gmail.com" . $passphrase .
    "EXCEPTIONURL= " . $exceptionUrl . $passphrase .
    "LANGUAGE=en_us" . $passphrase .
    "LOGO= \"http://localhost/cyclone/common/app2/Logo.png" . $passphrase .
    "OPERATION=SAL" . $passphrase .
    "PSPID=" . $PSPID . $passphrase;

$Ogone_sha1 = sha1($Ogone_sha1);
$Ogone_sha_out = sha1($Ogone_shaOut);
?>

<?php if(isset(Yii::app()->user->id)){ ?>
    <script>
        var addOrder = '<?php echo Yii::app()->createUrl('order/AddOrder')?>';
        $('.buyProduct').click(function () {
            var id = $(this).attr('id');
            var splitid = id.split("_");
            var id_no = splitid[1];

            $('#orderAmount').val($('#price_' + id_no).val() * 100);
            $('#orderId').val('<?php
                while( in_array( ($n = rand(1,5000000)), array(0, 4784,3071) ) );
                $invoiceNo = $n;
                $userDetail = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
                if(!empty($order_table))
                {
                    $OrderId = OrderInfo::model()->find(array('order' => 'order_info_id DESC'));
                    //$model->attributes = $_POST['OrderInfo'];
                    if ($OrderId == '') {
                        echo $invoiceNo;
                    } else {
                        echo $OrderId['order_id'] + 1;
                    }
                }
                ?>');

            $('#title').val($('#name_' + id_no).val());
            $('#title').val($('#name_' + id_no).val());
            $('#name-info').html($('#name_' + id_no).val());
            $('#price-info').html('&euro; '+$('#price_' + id_no).val());
            $('#pro-img').attr('src', $('#product-img_' + id_no).val());

            $('#pro-price').html('€ '+$('#price_' + id_no).val());



            var personalVat = <?php
                $vatAmountPersonal = Countries::model()->findByAttributes(['country_code' => $userDetail->country]);
                echo ($vatAmountPersonal) ? $vatAmountPersonal->personal_vat : 0 ;
                ?>;
            $('#vat-per').html(personalVat+'%');
            var BusinessVat = <?php
                $vatAmountBusiness = Countries::model()->findByAttributes(['country_code' => $userDetail->busAddress_country]);
                echo ($vatAmountBusiness) ? $vatAmountBusiness->buainess_vat : 0;
                ?>;
            var TotalValuePersonal = ($('#price_' + id_no).val() * personalVat / 100 );
            var TotalValueBusiness = ($('#price_' + id_no).val() * BusinessVat / 100 );

            $('#vatAmount').html('&euro; ' +TotalValuePersonal.toFixed(2));
            $('#vat-Amount').val(TotalValuePersonal.toFixed(2));
            $('#Total-price').html('&euro; ' + (parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)).toFixed(2));
            $('#netTotal').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)).toFixed(2));
            $('#orderAmount').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)) * 100);
            $("#add_box").val(1);
            var VatAmount = TotalValuePersonal;

            $(".billing-add").click(function () {
                if($(this).attr('value') == 1){
                    $('#vat-per').html(personalVat + '%');
                    $('#vatAmount').html('&euro; ' +TotalValuePersonal.toFixed(2));
                    $('#vat-Amount').val(TotalValuePersonal.toFixed(2));
                    VatAmount = TotalValuePersonal;
                    $('#Total-price').html('&euro; ' + (parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)).toFixed(2));
                    $('#netTotal').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)).toFixed(2));
                    $('#orderAmount').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)) * 100);
                    $("#personal_address").show();
                    $("#business_address").hide();
                    $("#add_box").val(1);
                    $("#proceed").removeAttr("disabled");
                }else{
                    if ($(this).attr('data-action') == 'true'){
                        VatAmount = TotalValueBusiness;
                        $('#vat-per').html(BusinessVat + '%');
                        $('#vatAmount').html('&euro; ' +TotalValueBusiness.toFixed(2));
                        $('#vat-Amount').val(TotalValueBusiness.toFixed(2));
                        $('#Total-price').html('&euro; ' + (parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValueBusiness)).toFixed(2));
                        $('#netTotal').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValueBusiness)).toFixed(2));
                        $('#orderAmount').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValueBusiness)) * 100);
                        $("#personal_address").hide();
                        $("#business_address").show();
                        $("#add_box").val(2);
                    }else{
                        $("#proceed").attr("disabled", true);
                        /*$('#proceed').prop('disabled', false);*/
//                    var VatAmount = TotalValueBusiness;
                        $("#personal_address").hide();
                        $("#business_address").show();
//                    console.info('hiii');
                    }
                }
            });

            $("#proceed").click(function () {
                if (!$("input[name=paymentOptions]").is(':checked')) {
                    $("#payment_error").show();
                    $(".payment-error").find("label").css('color', 'darkred');
                } else {
                    $("#payment_error").hide();
                    $(".payment-error").find("label").css('color', 'black');
                }
            });





            $('#proceed').on("click",function(){

                var formdata = {
                    'netTotal': $('#netTotal').val(),
                    'vat-Amount': $('#vat-Amount').val(),
                    'vat': VatAmount,
                    'amount': $('#price_' + id_no).val(),
                    'sku': $('#sku_' + id_no).val(),
                    'order_id': $('#orderId').val(),
                    'address_id': ($('#add_box').val()) ? $('#add_box').val() : 1
                };
                //          console.info(formdata); return false;
                $.ajax({
                    type: "POST",
                    url: addOrder,
                    data: formdata,
                    beforeSend: function () {
                        $(".overlay").removeClass("hide");
                    },
                    success: function (data) {
                        var res = jQuery.parseJSON(data);
                        if (res.token == 1) {
                            $('#submitOgone').click();
                        }
                    }
                });
            });
        });


        var addToCart = '<?php echo Yii::app()->createUrl('product/AddToCart')?>';
        $('.addCart').click(function () {

            var id = $(this).attr('id');
            var splitid = id.split("_");
            var id_no = splitid[1];

            $('#orderId').val('<?php
                while( in_array( ($n = rand(1,5000000)), array(0, 4784,3071) ) );
                $invoiceNo = $n;
                $userDetail = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
                if(!empty($order_table)){
                    $OrderId = OrderInfo::model()->find(array('order' => 'order_info_id DESC'));
                    //$model->attributes = $_POST['OrderInfo'];
                    if ($OrderId == '') {
                        echo $invoiceNo;
                    } else {
                        echo $OrderId['order_id'] + 1;
                    }
                }
                ?>');

            var personalVat = <?php
                $vatAmountPersonal = Countries::model()->findByAttributes(['country_code' => $userDetail->country]);
                echo ($vatAmountPersonal) ? $vatAmountPersonal->personal_vat : 0 ;
                ?>;
            $('#vat-per').html(personalVat+'%');
            var BusinessVat = <?php
                $vatAmountBusiness = Countries::model()->findByAttributes(['country_code' => $userDetail->busAddress_country]);
                echo ($vatAmountBusiness) ? $vatAmountBusiness->buainess_vat : 0;
                ?>;
            var TotalValuePersonal = ($('#price_' + id_no).val() * personalVat / 100 );
            var TotalValueBusiness = ($('#price_' + id_no).val() * BusinessVat / 100 );

            $('#vatAmount').html('&euro; ' +TotalValuePersonal.toFixed(2));
            $('#vat-Amount').val(TotalValuePersonal.toFixed(2));
            $('#Total-price').html('&euro; ' + (parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)).toFixed(2));
            $('#netTotal').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)).toFixed(2));
            $('#orderAmount').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)) * 100);
            $("#add_box").val(1);
            var VatAmount = TotalValuePersonal;


            var formdata = {
                'id': id_no,
                'order_id': $('#orderId').val(),
                'vat-Amount': $('#vat-Amount').val(),
                'vat': VatAmount
            };
            $.ajax({
                type: "POST",
                url: addToCart,
                data: formdata,
                beforeSend: function () {
                    $(".overlay").removeClass("hide");
                },
                success: function (data) {
                    var res = jQuery.parseJSON(data);

                    if (res.token == 1) {
                        $('#cart-count').html('<i class="fa fa-shopping-cart push-5-r"></i> <span class="hidden-xs">Cart</span>('+ res.cartCount + ')');
                        window.location.reload();
                    }
                }
            });
        });

        var addToWishlist = '<?php echo Yii::app()->createUrl('product/AddToWishlist')?>';
        $('.addWishlist').click(function () {

            var id = $(this).attr('id');
            var splitid = id.split("_");
            var id_no = splitid[1];

            var formdata = {
                'id': id_no
            };
            $.ajax({
                type: "POST",
                url: addToWishlist,
                data: formdata,
                beforeSend: function () {
                    $(".overlay").removeClass("hide");
                },
                success: function (data) {
                    var res = jQuery.parseJSON(data);
                    if (res.token == 1) {
                        window.location.reload();
                    }else{
                        window.location.reload();
                    }
                    $(".overlay").addClass("hide");
                }
            });
        });

        var RemoveFromCart = '<?php echo Yii::app()->createUrl('product/RemoveFromCart')?>';
        $('.removeCart').click(function () {
            //console.info('hii');
            var id = $(this).attr('id');
            var splitid = id.split("_");
            var id_no = splitid[1];

            var personalVat = <?php
                $vatAmountPersonal = Countries::model()->findByAttributes(['country_code' => $userDetail->country]);
                echo ($vatAmountPersonal) ? $vatAmountPersonal->personal_vat : 0 ;
                ?>;
            var TotalValuePersonal = ($('#price_' + id_no).val() * personalVat / 100 );
            var qty = $('#qty_'+id_no).html();
            var removablevat = (TotalValuePersonal * qty);
            var currentvat = $('#vat-amount-cart').html();
            var finalvat = (currentvat - removablevat);
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
                        if (res.cartCount == 0 ){
                            $('#emptyCart').show();
                            $('#teBtn').hide();
                            $('#trTotal').hide();
                            $('#vat').hide();
                            $('#vat-amount-cart').hide();
                        }
                        $('#vat-amount-cart').html(finalvat);
                        $('#product_'+res.id).hide();
                        $('#cart-count').html('<i class="fa fa-shopping-cart push-5-r"></i> <span class="hidden-xs">Cart</span>('+ res.cartCount + ')');
                        $('#cartTotalField').val(res.cartTotal);
                        $('#newTotal').html('€ '+(res.cartTotal + finalvat));
                        $('#shoppingCart').html('Shopping Cart ('+res.cartCount+')');
                        window.location.reload();
                    }
                }
            });
        });

        var PlaceOrder = '<?php echo Yii::app()->createUrl('Order/PlaceOrder')?>';
        $('#cart-count').click(function () {
            var id = $(this).attr('id');
            var splitid = id.split("_");
            var id_no = splitid[1];

            <?php if(!empty($product_table)){ ?>
            var cartValue = <?php echo $cartTotal; ?>;
            <?php } ?>

            $('#orderAmount').val($('#cartTotalField').val() * 100);
            $('#title').val('Product Buy');
            $('#orderId').val('<?php
                while( in_array( ($n = rand(1,5000000)), array(0, 4784,3071) ) );
                $invoiceNo = $n;
                $userDetail = UserInfo::model()->findByAttributes(['user_id' => Yii::app()->user->getId()]);
                if(!empty($order_table)){
                    $OrderId = OrderInfo::model()->find(array('order' => 'order_info_id DESC'));
                    //$model->attributes = $_POST['OrderInfo'];
                    if ($OrderId == '') {
                        echo $invoiceNo;
                    } else {
                        echo $OrderId['order_id'] + 1;
                    }
                }
                ?>');

            var personalVat = <?php
                $vatAmountPersonal = Countries::model()->findByAttributes(['country_code' => $userDetail->country]);
                echo ($vatAmountPersonal) ? $vatAmountPersonal->personal_vat : 0 ;
                ?>;
            $('#vat-per').html(personalVat + '%');
            $('#vat-per1').html(personalVat + '%');
            var BusinessVat = <?php
                $vatAmountBusiness = Countries::model()->findByAttributes(['country_code' => $userDetail->busAddress_country]);
                echo ($vatAmountBusiness) ? $vatAmountBusiness->buainess_vat : 0;
                ?>;
            var TotalValuePersonal = ($('#cartTotalField').val() * personalVat / 100 );
            var TotalValueBusiness = ($('#cartTotalField').val() * BusinessVat / 100 );

            $('#vatAmount').html('&euro; ' + TotalValuePersonal.toFixed(2));
            $('#vat-Amount').val(TotalValuePersonal.toFixed(2));
            $('#Total-price').html('&euro; ' + (parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)).toFixed(2));
            $('#netTotal').val((parseFloat($('#price_' + id_no).val()) + parseFloat(TotalValuePersonal)).toFixed(2));
            $('#orderAmount').val((parseFloat($('#cartTotalField').val()) + parseFloat(TotalValuePersonal)) * 100);
            $("#add_box").val(1);

            var VatAmount = TotalValuePersonal;
            var total_cart_amount = $('#cartTotalField').val();
            $('#vat-amount-cart').html(VatAmount);
            var cart_total_payable = parseInt(total_cart_amount) + parseInt(VatAmount);
            $('#newTotal').html('€ '+cart_total_payable);

//        $('#orderAmount').val($('#cartTotalField').val() * 100);
//        console.info($('#vat-Amount').val());return false;
            $('.placeOrder').click(function () {
                var formdata = {
                    'amount': $('#cartTotalField').val(),
                    'order_id': $('#orderId').val(),
                    'vat-Amount': $('#vat-Amount').val(),
                    'vat': VatAmount
                };

                $.ajax({
                    type: "POST",
                    url: PlaceOrder,
                    data: formdata,
                    success: function (data) {
                        var res = jQuery.parseJSON(data);
                        if (res.token == 1) {
                            $('#submitOgone').click();
                        }
                    }
                });
            });
        });


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

        var ResetCart = '<?php echo Yii::app()->createUrl('Product/ResetCart')?>';
        $('.resetCart').click(function () {
            var formdata = 0;
            $.ajax({
                type: "POST",
                url: ResetCart,
                data: formdata,
                beforeSend: function () {
                    $("#resetCartRefresh").addClass("block-opt-refresh");
                },
                success: function (data) {
                    var res = jQuery.parseJSON(data);
                    if (res.token == 1) {
                        $('#shoppingCart').html('Shopping Cart (0)');
                        $('#Table2').show();
                        $('#Table1').hide();
                        $("#resetCartRefresh").removeClass("block-opt-refresh");
                    }
                }
            });
        });
    </script>
<?php } ?>
<script type="text/javascript">
    $('.paypalmethod').click(function () {

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
                    var OrderId = $('#orderId').val();
                    //$('#paypalInvoice').val(OrderId);
                    var url = '<?= $acceprtUrl; ?>'+'/'+OrderId;
                    window.location.href = url;
                }
            }
        });
    });
</script>


<form action="https://ogone.test.v-psp.com/ncol/test//orderstandard_utf8.asp" method="post" name="ogone"
      id="paymentSubmit">
    <input name="PSPID" type="hidden" value="deepak2499"/>
    <input name="ORDERID" id="orderId" type="hidden" value=""/>
    <input name="TITLE" id="title" type="hidden" value=""/>
    <input name="AMOUNT" id="orderAmount" type="hidden" value=""/>
    <input name="CURRENCY" type="hidden" value="EUR"/>
    <input name="LANGUAGE" type="hidden" value="en_us"/>
    <input name="USERID" type="hidden" value="<?php echo Yii::app()->user->getId(); ?>"/>
    <input name="CN" type="hidden" value="Deepak Visani1"/>
    <input name="EMAIL" type="hidden" value="deepakvisani1@gmail.com"/>
    <input name="OPERATION" type="hidden" value="SAL"/>
    <input name="SHASIGN" type="hidden" value="<?php echo $Ogone_sha1; ?>;"/>
    <input name="LOGO" type="hidden" value="Logo to be shown on page"/>


    <input name="acceptUrl" type="hidden" value="<?php echo $acceprtUrl; ?>"/>
    <input name="declineUrl" type="hidden" value="<?php echo $declineUrl; ?>"/>
    <input name="exceptionUrl" type="hidden" value="<?php echo $exceptionUrl; ?>"/>
    <input name="cancelurl" type="hidden" value="<?php echo $cancelUrl; ?>"/>
    <input name="submit" id="submitOgone" class="hide" type="submit" value="submit"/>
</form>


<!--<script type="text/javascript" src="<?php /*echo Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.validate.min.js';  */?>"></script>
<script type="text/javascript" src="<?php /*echo Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/additional-methods.min.js';  */?>"></script>-->
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.validate.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/additional-methods.min.js');
?>

<script type="text/javascript">
    $.validator.addMethod("customemail",
        function(value, element) {
            return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
        },
        "Sorry, I've enabled very strict email validation"
    );

    $("form[id='loginform']").validate({
        debug: true,
        errorClass: "help-block",
        errorElement: "div",
        onfocusout: false,
        onkeyup: false,
        onclick:false,
        rules:{
            'LoginForm[username]' : {
                required: true,
                customemail:true,
            },
            'LoginForm[password]': {
                required: true
            },
        },
        messages:{
            'LoginForm[username]' : {
                required: "Gelieve Geef je e-mailadres in",
                customemail: "Gelieve het correct e-mailadres in te geven",
            },
            'LoginForm[password]': {
                required: "Gelieve wachtwoord ingeven",
            },
        },
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
            $(element).parent().addClass('has-error');
            //$('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).parent().removeClass('has-error');
        },
        submitHandler:function (form) {
            form.submit();
        }
    });
</script>