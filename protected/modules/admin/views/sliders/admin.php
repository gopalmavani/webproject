<?php
/* @var $this SlidersController */
/* @var $model Sliders */

$primary_key = Sliders::model()->tableSchema->primaryKey;

$this->pageTitle = 'Sliders';
?>
<!-- Web fonts -->
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
<!-- Page JS Plugins CSS -->
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/slick.min.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/themes/slick-theme.min.css');
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Sliders
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <div align="right">
                        <?php echo CHtml::link('Add Another image', array('sliders/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                    </div><br/>
                    <div align="right">
                        <?php echo CHtml::link('Delete Any image', array('sliders/deleteImage'), array('class' => 'btn btn-minw btn-square btn-danger')); ?>
                    </div>

                    <h4 class="content-heading">No. of Image Added in Slider: <?php echo count($model);?></h4>


                    <!--Bootstrap slider-->
                    <?php $count = count($model);
                    $i =0;
                    if($count == 0){?>
                        <h3>Please add image for slider</h3>
                        <?php echo CHtml::link('Add image', array('sliders/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                    <?php }else{ ?>
                        <h5 class="block-title">Autoplay</h5>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="width:70%">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <?php foreach ($model as $image) {
                                    if($i == 0){
                                        $class = "carousel-item active";
                                    } else {
                                        $class = "carousel-item";
                                    } ?>
                                    <div class="<?= $class; ?>">
                                        <img class="d-block w-100" src="<?php echo Yii::app()->baseUrl;?><?php  echo $image->image;?>" alt="">
                                    </div>
                                    <?php $i++;
                                }?>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    <?php }?>
                    <!--Bootstrap slider-->
                </div>
            </div>
            <!--end::Section-->
        </div>
    </div>
</div>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/slick.min.js', CClientScript::POS_END); ?>
<!-- Page JS Code -->
<script>
    jQuery(function () {
        // Init page helpers (Slick Slider plugin)
        App.initHelpers('slick');
    });
</script>
