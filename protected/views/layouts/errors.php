<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="<?php echo Yii::app()->baseUrl."/plugins//imgs/favicon.png"; ?>" />
    <title>Cryptotrain</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

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
</head>

<body>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<section id="wrapper" class="u-PaddingTop100 u-PaddingBottom0 u-xs-PaddingBottom0">
    <div class="container">
        <?php echo $content; ?>
    </div>
</section>
<!--footer start-->
<footer class="u-PaddingTop60 u-xs-PaddingTop30 u-PaddingBottom60 u-xs-PaddingBottom30 hidden-print">
    <div class="container text-center text-sm">
        <p class="u-MarginBottom5 u-MarginTop20">Copyright 2018 &copy; Cryptotrain.</p>
        <p class="u-MarginBottom0 u-PaddingBottom0"><a href="javascript:void(0);">Cookie beleid</a> | <a href="javascript:void(0);">Disclaimer/Legale informatie</a> | <a href="javascript:void(0);">Privacybeleid</a></p>
    </div>
</footer>
<!--footer end-->

<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/bootstrap.min.js');?>


</body>

</html>