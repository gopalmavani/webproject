<main id="main-container">
    <div class="bg-image" style="background-image: url('<?php echo Yii::app()->baseUrl; ?>/plugins/img/photos/photo3@2x.jpg.jpg');">
        <div class="bg-primary-dark-op">
            <section class="content content-full content-boxed overflow-hidden">
                <!-- Section Content -->
                <div class="push-30-t push-30 text-center">
                    <h1 class="h2 text-white push-10 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">My Accounts</h1>
                </div>
                <!-- END Section Content -->
            </section>
        </div>
    </div>

    <div class="block">
        <div class="block-header bg-gray-lighter">
            <h1 class="block-title" style="font-size: 20px; margin-bottom : 10px">My Accounts</h1>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="block">
                <?php if($userAccounts == null){?>
                    <div class="row" style="margin-bottom: 20%">
                        <h2 align="center">You have no any trading account</h2>
                    </div><p><br/></p><?php }else{ ?>
                <div class="block-content">
                    <?php foreach ($userAccounts as $model) {?>
                        <div onclick="growthGraph(<?php echo $model->Login; ?>)">
                            <table class="js-table-sections table table-hover" style="border: 1px #ccc solid;">
                                <tbody class="js-table-sections-header">
                                <tr style="background-color:  transparent;">
                                    <td><strong>Name:</strong> <?php echo $model->Name; ?></td>
                                    <td><strong>Broker:</strong> Infinox UK</td>
                                    <td><strong>Status:</strong> Trading</td>
                                    <td><strong>Account:</strong> <?php echo $model->Login; ?></td>
                                    <td><strong>Starting Date:</strong> Jul 30, 2015</td>
                                    <td><i class="fa fa-angle-right"></i></td>
                                </tr>
                                </tbody>
                                <tbody>
                                <tr style="background-color: #f9f9f9;">
                                    <td colspan="2"><strong><span class="dp">Deposit</span></strong> &euro; <?php $dep = ApiDepositWithdraw::model()->findAllByAttributes(['login'=> $model->Login , 'type'=>'Deposit']);$profit=0; foreach ($dep as $deposit){$profit = $profit+$deposit->profit;} echo $profit; ?></td>
                                    <td colspan="2"><strong>Withdrawals</strong> &euro; <?php $dep = ApiDepositWithdraw::model()->findAllByAttributes(['login'=> $model->Login , 'type'=>'Withdraw']);$profit=0; foreach ($dep as $withdraw){$profit = $profit+$withdraw->profit;} echo (-1) * $profit; ?></td>
                                    <td colspan="2"><strong>Balance</strong> &euro; <?php echo $model->Balance; ?></td>
                                </tr>
                                <tr style="background-color: #f9f9f9;">
                                    <td style="width: 100%" colspan="6">
                                        <div class="block">
                                            <ul class="nav nav-tabs" data-toggle="tabs">
                                                <li id="growth<?= $model->Login; ?>" class="active" style="cursor: pointer"><a onclick="growthGraph(<?= $model->Login; ?>)">Growth</a></li>
                                                <li id="overallgrowth<?= $model->Login; ?>" style="cursor: pointer"><a onclick="overallGrowth(<?= $model->Login; ?>)" >Balance</a></li>
                                            </ul>
                                            <div id="container#<?php echo $model->Login; ?>"
                                                 style="border: 1px #ccc solid;min-width: 310px; margin: 0 auto">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php //if (count($balance) > 0) { ?>

                        <?php //} ?>
                        <?php //if (count($balance) > 0): ?>
                        <?php //endif; ?>
                    <?php }} ?>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>

</main>
<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/highstock.js';?>"></script>
<script src="<?php echo Yii::app()->baseUrl . '/plugins/js/exporting.js';?>"></script>
<script>
    var seriesOptions = [], seriesCounter = 0, names = ['Balance', 'Equity'];
    /**
     * Create the chart when all data is loaded
     * @returns {undefined}
     */
    function createChart(baldata, equdata, startdata, baseData, type, login) {
        var base = baseData;
        Highcharts.stockChart('container#'+login, {

            rangeSelector: {
                selected: 5
            },
            chart: {
                height: 350,
                type: 'line'
            },
            yAxis: {
                labels: {
                    formatter: function () {
                        if(type == 'growth'){
                            return (this.value > 0 ? ' + ' : '') + this.value + '%';
                        } else {
                            return (this.value > 0 ? ' + ' : '') + this.value;
                        }
                    }
                },
                plotLines: [{
                    value: 0,
                    width: 2,
                    color: 'silver'
                }],
                opposite: false
            },
            tooltip: {
                formatter: function(args){
                    var s = "";
                    $.each(this.points, function(i, point){
                        if(i < 2 ){
                            if(type == 'growth'){
                                var perc = (this.y + 100)*base/100;
                                s += '<span style="color:' + this.series.color +'">' + this.series.name + ': <b>' + Highcharts.numberFormat(perc,2) + '</b><b> (' + Highcharts.numberFormat(this.y,2) + '%) </b><br></span>'
                            } else {
                                var perc = (this.y - base)*100/base;
                                s += '<span style="color:' + this.series.color +'">' + this.series.name + ': <b>' + Highcharts.numberFormat(this.y,2) + '</b><b> (' + Highcharts.numberFormat(perc,2) + '%) </b><br>'
                            }

                        }
                    });
                    return s;
                },
                shared: true
            },

            series: [{
                name: 'Balance',
                data: baldata
            },{
                name: 'Equity',
                data: equdata,
                color: '#90ED7D'
            }, {
                name: 'Default',
                data: startdata,
                tooltip: {
                    enabled: false
                },
                color: '#686161'
            }],
            legend: {
                enabled: true
            },
            navigation: {
                menuItemStyle: {
                    fontSize: '10px'
                }
            }
        });
    }

    jQuery(document).ready(function ($) {

    });
    function growthGraph(login){
        var baldata, equdata, startdata, baseData;
        var type = 'growth';
        <?php if(isset($model)) { ?>
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('mt4/growthgraph'); ?>',
            type: "POST",
            data: {
                login: login
            },
            beforeSend: function(){
                $('.loader').css('display','block');
            },
            success: function (data) {
                var arr = JSON.parse(data);
                baldata = arr['balance'];
                equdata = arr['equity'];
                startdata = arr['start'];
                baseData = arr['baseData'];
                createChart(baldata,equdata,startdata,baseData,type,login);
                $('.loader').css('display','none');
            },
            error: function(data) {
                console.log("Error");
            }
        });
        <?php } ?>
    }
    function overallGrowth(login){
        var baldata, equdata, startdata, baseData;
        var type = 'balance';
        <?php if(isset($model)) {  ?>
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('mt4/overallgrowth'); ?>',
            type: "POST",
            data: {
                login: login
            },
            beforeSend: function(){
                $('.loader').css('display','block');
            },
            success: function (data) {
                var arr = JSON.parse(data);
                baldata = arr['balance'];
                equdata = arr['equity'];
                startdata = arr['start'];
                baseData = arr['baseData'];
                createChart(baldata,equdata,startdata,baseData,type,login);
                $('.loader').css('display','none');
            },
            error: function(data) {
                console.log("Error");
            }
        });
        <?php } ?>
    }
    jQuery(function () {
        // Init page helpers (Table Tools helper)
        App.initHelpers('table-tools');
    });
</script>
