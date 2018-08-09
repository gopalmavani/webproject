<?php $this->pageTitle = ''; ?>
<style>
    .block-header{
        padding:0px !important;
    }
    .m--bg-warning {
        background-color: #ef963f!important;
    }
</style>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Dashboard
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<!-- Page Content -->
<div class="m-content">
    <!-- Draggable Items with jQueryUI (.js-draggable-items class is initialized in App() -> uiHelperDraggableItems()) -->
    <!--First row for draggable small widgets-->
    <div class="row">
        <div class="col-md-4">
            <div class="m-portlet m--bg-warning m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text m--font-light">
                                Basic Details
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin::Widget 29-->
                    <div class="m-widget29">
                        <div class="m-widget_content">
                            <div class="m-widget_content-items">
                                <div class="m-widget_content-item" style="float: left;">
                                    <h3 class="m-widget_content-title">
                                        Users
                                    </h3>
                                    <span class="m--font-accent">
                                        <?php
                                        if (Yii::app()->db->schema->getTable('user_info')) {
                                            $usersql = "SELECT COUNT(*) as usercount FROM user_info";
                                            $user = Yii::app()->db->createCommand($usersql)->queryAll();?>
                                            <a class="h2 font-w300 text-primary" href="<?php echo Yii::app()->createUrl('/admin/userInfo/admin') ?>"
                                               data-toggle="countTo" style="margin-left: 15px;" data-to="<?php echo $user[0]['usercount']; ?>"><?php echo $user[0]['usercount']; ?></a>
                                        <?php }  ?>
                                    </span>
                                </div>
                                <div class="m-widget_content-item" style="float: right;">
                                    <a href="<?php echo Yii::app()->createUrl('/admin/userInfo/admin') ?>">
                                        <div class="m-demo-icon" style="">
                                            <div class="m-demo-icon__preview">
                                                <i class="fa fa-user-o" style="font-size:5rem;margin-top: 23px"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                        $sql = "SELECT * from widget_mapping where widget_name = 'Total Products'";
                        $result = Yii::app()->db->createCommand($sql)->queryAll();
                        if(!empty($result)){
                            $active = $result[0]['is_active'];
                            if($active == 1){ ?>
                                <div class="m-widget_content">
                                    <div class="m-widget_content-items">
                                        <div class="m-widget_content-item" style="float: left;">
                                            <h3 class="m-widget_content-title">
                                                Products
                                            </h3>
                                            <span class="m--font-accent">
                                        <?php
                                        if (Yii::app()->db->schema->getTable('product_info')) {
                                            $productsql = "SELECT COUNT(*) as productcount FROM product_info";
                                            $product = Yii::app()->db->createCommand($productsql)->queryAll(); ?>
                                            <a class="h2 font-w300 text-primary" href="<?php echo Yii::app()->createUrl('/admin/ProductInfo/admin') ?>"
                                               data-toggle="countTo" style="margin-left: 15px;" data-to="<?php echo $product[0]['productcount']; ?>"><?php echo $product[0]['productcount']; ?></a>
                                        <?php } ?>
                                    </span>
                                        </div>
                                        <div class="m-widget_content-item" style="float: right;">
                                            <a href="<?php echo Yii::app()->createUrl('/admin/productInfo/admin') ?>">
                                                <div class="m-demo-icon" style="">
                                                    <div class="m-demo-icon__preview">
                                                        <i class="fa fa-product-hunt" style="font-size:5rem;margin-top: 23px"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } }?>
                        <?php
                        $sql = "SELECT * from widget_mapping where widget_name = 'Total Orders'";
                        $result = Yii::app()->db->createCommand($sql)->queryAll();
                        if(!empty($result)){
                            $active = $result[0]['is_active'];
                            if($active == 1){ ?>
                                <div class="m-widget_content">
                                    <div class="m-widget_content-items">
                                        <div class="m-widget_content-item" style="float: left;">
                                            <h3 class="m-widget_content-title">
                                                Order
                                            </h3>
                                            <span class="m--font-accent">
                                        <?php
                                        if (Yii::app()->db->schema->getTable('order_info')) {
                                            $ordersql = "SELECT COUNT(*) as ordercount FROM order_info";
                                            $order = Yii::app()->db->createCommand($ordersql)->queryAll(); ?>
                                            <a class="h2 font-w300 text-primary" href="<?php echo Yii::app()->createUrl('/admin/OrderInfo/admin') ?>"
                                               data-toggle="countTo" style="margin-left: 15px" data-to="<?php echo $order[0]['ordercount']; ?>"><?php echo $order[0]['ordercount']; ?></a>
                                            <?php
                                        }
                                        ?>
                                    </span>
                                        </div>
                                        <div class="m-widget_content-item" style="float: right;">
                                            <a href="<?php echo Yii::app()->createUrl('/admin/OrderInfo/admin') ?>">
                                                <div class="m-demo-icon" style="">
                                                    <div class="m-demo-icon__preview">
                                                        <i class="fa fa-shopping-basket" style="font-size:5rem;margin-top: 23px"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } }?>
                    </div>
                    <!--end::Widget 29-->
                </div>
            </div>
        </div>
        <div class="col-md-8 m-portlet">
            <div class="m-grid__item m-grid__item--fluid m-wrapper" id="orderDetails">
                <div class="m-subheader">
                    <div class="d-flex align-items-center">
                        <div class="mr-auto">
                            <h3 class="m-subheader__title">Order Details</h3>
                        </div>
                        <div>
                            <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                                <span class="m-subheader__daterange-label">
                                    <span class="m-subheader__daterange-title"></span>
                                    <span class="m-subheader__daterange-date m--font-brand"></span>
                                </span>
                                <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                                    <i class="la la-angle-down"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body clearfix" style="height: 400px;">
                    <div class="m-section__content clearfix" style="height: 550px;">
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class='alert alert-success'>
                                    <h5 class='m-subheader__title' style=' color: #fff;'>Total Orders</h5>
                                    <span id="TotalorderCount" class='m-subheader__title' style=' color: #fff;'></span>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class='alert alert-warning'>
                                    <h5 class='m-subheader__title' style=' color: #fff;'>Pending Orders</h5>
                                    <span class='m-subheader__title' id="TotalPendingOrder" style=' color: #fff;'></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div  class="m-portlet">
                            <div class="m-portlet__body">
                                <div class="m-section">
                                    <div class="m-section__content">
                                        <div id="order" class="m-datatable m-datatable--default m-datatable--brand m-datatable--lock m-datatable--scroll m-datatable--loaded" style="position: static;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End First row for draggable small widgets-->

</div>
<!-- END Page Content -->
<?php
$current_year = date("Y");
if (Yii::app()->db->schema->getTable('user_info')) {
    $Jancriteria = new CDbCriteria;
    $Jancriteria->condition = "created_at >= '$current_year-01-01 00:00:00' AND created_at <= '$current_year-01-31 23:59:59'";
    $janUser = UserInfo::model()->find($Jancriteria);

    $Febcriteria = new CDbCriteria;
    $Febcriteria->condition = "created_at >= '$current_year-02-01 00:00:00' AND created_at <= '$current_year-02-28 23:59:59'";
    $febUser = UserInfo::model()->findAll($Febcriteria);

    $Marcriteria = new CDbCriteria;
    $Marcriteria->condition = "created_at >= '$current_year-03-01 00:00:00' AND created_at <= '$current_year-03-31 23:59:59'";
    $marUser = UserInfo::model()->findAll($Marcriteria);

    $Aprcriteria = new CDbCriteria;
    $Aprcriteria->condition = "created_at >= '$current_year-04-01 00:00:00' AND created_at <= '$current_year-04-30 23:59:59'";
    $aprUser = UserInfo::model()->findAll($Aprcriteria);

    $Maycriteria = new CDbCriteria;
    $Maycriteria->condition = "created_at >= '$current_year-05-01 00:00:00' AND created_at <= '$current_year-05-31 23:59:59'";
    $mayUser = UserInfo::model()->findAll($Maycriteria);

    $Juncriteria = new CDbCriteria;
    $Juncriteria->condition = "created_at >= '$current_year-06-01 00:00:00' AND created_at <= '$current_year-06-30 23:59:59'";
    $junUser = UserInfo::model()->findAll($Juncriteria);

    $Julcriteria = new CDbCriteria;
    $Julcriteria->condition = "created_at >= '$current_year-07-01 00:00:00' AND created_at <= '$current_year-07-31 23:59:59'";
    $julUser = UserInfo::model()->findAll($Julcriteria);

    $Augcriteria = new CDbCriteria;
    $Augcriteria->condition = "created_at >= '$current_year-08-01 00:00:00' AND created_at <= '$current_year-08-31 23:59:59'";
    $augUser = UserInfo::model()->findAll($Augcriteria);

    $Sepcriteria = new CDbCriteria;
    $Sepcriteria->condition = "created_at >= '$current_year-09-01 00:00:00' AND created_at <= '$current_year-09-30 23:59:59'";
    $sepUser = UserInfo::model()->findAll($Sepcriteria);

    $Octcriteria = new CDbCriteria;
    $Octcriteria->condition = "created_at >= '$current_year-10-01 00:00:00' AND created_at <= '$current_year-10-31 23:59:59'";
    $octUser = UserInfo::model()->findAll($Octcriteria);

    $Novcriteria = new CDbCriteria;
    $Novcriteria->condition = "created_at >= '$current_year-11-01 00:00:00' AND created_at <= '$current_year-11-30 23:59:59'";
    $novUser = UserInfo::model()->findAll($Novcriteria);

    $Deccriteria = new CDbCriteria;
    $Deccriteria->condition = "created_at >= '$current_year-12-01 00:00:00' AND created_at <= '$current_year-12-31 23:59:59'";
    $decUser = UserInfo::model()->findAll($Deccriteria);
}

?>
<script src="<?php echo Yii::app()->createUrl('/plugins/js/plugins/slick/slick.min.js'); ?>"></script>
<!--<script type="text/javascript" src="<?php /*echo Yii::app()->baseUrl. '/js/daterangepicker.min.js'; */?>"></script>
--><!--<script src="<?php /*echo Yii::app()->createUrl('/plugins/js/plugins/chartjs/Chart.min.js'); */?>"></script>
--><script src="<?php echo Yii::app()->createUrl('/plugins/js/plugins/chartjs/Charts/flotcharts.js'); ?>"></script>
<!--<script src="<?php /*echo Yii::app()->createUrl('/plugins/js/core/jquery.countTo.min.js'); */?>"></script>-->
<script src="<?php echo Yii::app()->createUrl('/plugins/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>

<script>
    jQuery(function () {
        // Init page helpers (Slick Slider plugin)
        App.initHelpers('slick');
    });

    jQuery(function () {
        // Init page helpers (Appear + CountTo plugins)
        App.initHelpers(['appear', 'appear-countTo']);
    });

    /*
     *  Document   : base_pages_dashboard.js
     *  Author     : pixelcave
     *  Description: Custom JS code used in Dashboard Page
     */

    var BasePagesDashboard = function () {
        // Chart.js Chart, for more examples you can check out http://www.chartjs.org/docs
        var initDashChartJS = function () {
            // Get Chart Container
            var $dashChartLinesCon = jQuery('.js-dash-chartjs-lines')[0].getContext('2d');

            // Set Chart and Chart Data variables
            var $dashChartLines, $dashChartLinesData;

            // Lines Chart Data
            var $dashChartLinesData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Last Week',
                        fillColor: 'rgba(44, 52, 63, .1)',
                        strokeColor: 'rgba(44, 52, 63, .55)',
                        pointColor: 'rgba(44, 52, 63, .55)',
                        pointStrokeColor: '#fff',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(44, 52, 63, 1)',
                        data: [
                            <?php
                            if (Yii::app()->db->schema->getTable('user_info')) {
                            echo count($janUser); ?>,
                            <?php echo count($febUser); ?>,
                            <?php echo count($marUser); ?>,
                            <?php echo count($aprUser); ?>,
                            <?php echo count($mayUser); ?>,
                            <?php echo count($junUser); ?>,
                            <?php echo count($julUser); ?>,
                            <?php echo count($augUser); ?>,
                            <?php echo count($sepUser); ?>,
                            <?php echo count($octUser); ?>,
                            <?php echo count($novUser); ?>,
                            <?php echo count($decUser);
                            }
                            ?>

                        ]
                    }
                ]
            };

            // Init Lines Chart
            $dashChartLines = new Chart($dashChartLinesCon).Line($dashChartLinesData, {
                scaleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
                scaleFontColor: '#999',
                scaleFontStyle: '600',
                tooltipTitleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
                tooltipCornerRadius: 3,
                maintainAspectRatio: false,
                responsive: true
            });
        };

        return {
            init: function () {
                // Init ChartJS chart
                initDashChartJS();
            }
        };
    }();

    // Initialize when page loads
    jQuery(function () {
        BasePagesDashboard.init();
    });
</script>
<script>
    jQuery(function () {
        // Init page helpers (jQueryUI)
        App.initHelpers('draggable-items');
    });
</script>

<script type="text/javascript">
    $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#m_dashboard_daterangepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var startdate = start.format('YYYY-MM-DD');
            var enddate = end.format('YYYY-MM-DD');
            console.info("hi");
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/home/OrderFilter')?>',
                type: "post",
                data:{'startdate':startdate,'enddate':enddate},
                beforeSend: function (response) {
                    $('#orderDetails').addClass('block-opt-refresh');
                },
                success: function (response) {
                    $('#orderDetails').removeClass('block-opt-refresh');
                    var result = jQuery.parseJSON(response);

                    $('#order').html(result.reponse_detail);
                    $('#TotalorderCount').html(result.no_of_orders);
                    $('#TotalLicenesCount').html(result.total_license);
                    $('#TotalPendingOrder').html(result.pending_orders);

                }
            });
        }

        $('#m_dashboard_daterangepicker').daterangepicker(cb);

        cb(start, end);

    });
</script>
<script>
    jQuery(function () {
        // Init page helpers (Appear + CountTo plugins)
        App.initHelpers(['appear', 'appear-countTo']);
    });


    /*
 *  Document   : base_pages_ecom_dashboard.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in eCommerce Dashboard Page
 */

    var BasePagesEcomDashboard = function() {
        // Chart.js Chart, for more examples you can check out http://www.chartjs.org/docs
        var initOverviewChart = function(){
            // Get Chart Container
            var $chartOverviewCon = jQuery('.js-chartjs-overview')[0].getContext('2d');

            // Set Chart Options
            var $chartOverviewOptions = {
                scaleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
                scaleFontColor: '#999',
                scaleFontStyle: '600',
                tooltipTitleFontFamily: "'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif",
                tooltipCornerRadius: 3,
                maintainAspectRatio: false,
                responsive: true
            };

            // Overview Chart Data
            var $chartOverviewData = {
                labels: [<?php echo $days; ?>],
                datasets: [
                    {
                        label: 'Events',
                        fillColor: 'rgba(171, 227, 125, .3)',
                        strokeColor: 'rgba(171, 227, 125, 1)',
                        pointColor: 'rgba(171, 227, 125, 1)',
                        pointStrokeColor: '#fff',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(171, 227, 125, 1)',
                        data: [<?php echo implode(',',$daywiseevents); ?>]
                    }
                ]
            };

            // Init Overview Chart
            var $chartOverview = new Chart($chartOverviewCon).Line($chartOverviewData, $chartOverviewOptions);
        };

        return {
            init: function () {
                // Init Overview Chart
                initOverviewChart();
            }
        };
    }();

</script>