<?php
$this->pageTitle = 'Product Subscription';
$sql = "SELECT * FROM product_subscription";
$result = Yii::app()->db->createCommand($sql)->queryAll();
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
<style>
    .dataTables_filter{
        display: none;
    }
    .form-inline input {
        margin-bottom: 10px!important;
    }
    .date_range_filter{
        margin-bottom: 10px!important;
    }
    .dt-buttons{
        float: left !important;
    }
</style>
<?php if(!empty($result)){?>
    <!--Begin loader-->
    <!--<div class="overlay" style="opacity:0.1 !important;position:unset !important;">
        <div class="loader">
            <div class="m-loader m-loader--lg m-loader--success" style="width: 30px; display: inline-block;"></div>
        </div>
    </div>-->
    <!--End loader-->
<?php } ?>
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">
                Subscription Detail
            </h3>
        </div>
    </div>
</div>
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="col-md-12" style="padding-top: 15px;">
                <div class="col-sm-8 col-lg-4">
                    <div class="m-portlet">
                        <div class="m-portlet__body" style="background-color: #efefef;">
                            <div class="row">
                                <div class="h1 font-w700" style="margin-left: 113px;margin-top: -21px;"> <?= $pending['pending_subs']; ?></div>
                                <div class="h5 text-muted text-uppercase push-5-t" style="margin-left: 29px;">Pending Subscriptions</div>
                            </div>
                        </div>
                        <div class="row" style="background-color: white;margin-top: 15px;">
                            <strong style="margin-left: 110px;margin-bottom: 15px;"> &euro; <?= number_format($pending['total_amount'],2); ?></strong> In cash
                        </div>
                    </div>
                </div>
                <div class="col-sm-8 col-lg-4">
                    <div class="m-portlet">
                        <div class="m-portlet__body" style="background-color: #efefef;">
                            <div class="row">
                                <div class="h1 font-w700" style="margin-left: 113px;margin-top: -21px;"> <?= $upcoming['upcoming_subs']; ?></div>
                                <div class="h5 text-muted text-uppercase push-5-t" style="margin-left: 29px;">Upcoming Subscription</div>
                            </div>
                        </div>
                        <div class="row" style="background-color: white;margin-top: 15px;">
                            <strong style="margin-left: 110px;margin-bottom: 15px;"> &euro; <?= number_format($upcoming['total_amount'],2); ?></strong> In cash
                        </div>
                    </div>
                </div>
                <div class="col-sm-8 col-lg-4">
                    <div class="m-portlet">
                        <div class="m-portlet__body" style="background-color: #efefef;">
                            <div class="row">
                                <div class="h1 font-w700" style="margin-left: 126px;margin-top: -21px;"> <?= $new['new_subs']; ?></div>
                                <div class="h5 text-muted text-uppercase push-5-t" style="margin-left: 56px;">New Subscription</div>
                            </div>
                        </div>
                        <div class="row" style="background-color: white;margin-top: 15px;">
                            <strong style="margin-left: 110px;margin-bottom: 15px;"> &euro; <?= number_format($new['total_amount'],2); ?></strong> In cash
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                if(!empty($result)){
                    ?>
                    <div class="pull-right m-b-10" style="margin-right:1%">
                        <a style="margin-top:10%" lang="en" class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                    </div>
                    <div id="subscription-info-grid">
                        <table id="subscription-info-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                            <thead class="custom-table-head">
                            <tr>
                                <th class="custom-table-head" lang="en">Action</th>
                                <?php
                                $array_cols = Yii::app()->db->schema->getTable('product_subscription')->columns;
                                foreach($array_cols as $key=>$col){ ?>
                                    <th class="custom-table-head" ><?php echo ucfirst(str_replace("_"," ",$col->name)); ?></th>
                                <?php } ?>
                            </tr>
                            </thead>

                            <thead>
                            <tr>
                                <?php
                                $arr = array_values($array_cols);
                                foreach($arr as $key=>$col){
                                    switch($col->name)
                                    {
                                        case 'user_name':
                                            echo "<td></td>";
                                            echo "<td><input type='text' data-column='2' class='text-box form-control' style='width:100%'></td>";
                                            break;

                                        case 'product_name':
                                            echo "<td><input type='text' data-column='4' class='text-box form-control' style='width:100%'></td>";
                                            break;

                                        case 'subscription_price':
                                            echo "<td><input type='text' data-column='6' class='text-box  form-control' style='width:100%'></td>";
                                            break;

                                        case 'starts_at':
                                            ?>
                                            <td>
                                                <p class="date_filter">
                                                    <span class="date-range-span"><input class="date_range_filter form-control" type="text-box" id="starts_at_min" data-column="<?= $key ?>" placeholder="From" style="width:100%" /></span>
                                                    <br/><span class="date-range-span"><input class="date_range_filter form-control" type="text-box" id="starts_at_max" data-column="<?= $key ?>" placeholder="To" style="width:100%" /></span>
                                                </p>
                                            </td>
                                            <?php
                                            break;

                                        case 'next_renewal_date':
                                            ?>
                                            <td>
                                                <p class="date_filter">
                                                    <span class="date-range-span"><input  class="date_range_filter form-control" type="text" id="next_renewal_date_min" data-column="<?= $key ?>" placeholder="From" style="width:100%" /></span>
                                                    <br/><span class="date-range-span"><input class="date_range_filter form-control" type="text" id="next_renewal_date_max" data-column="<?= $key ?>" placeholder="To" style="width:100%" /></span>
                                                </p>
                                            </td>
                                            <?php
                                            break;

                                        case 'subscription_status':
                                            $meta = "select field_label,predefined_value from cyl_field_values WHERE field_id = 245";
                                            $cylFields = Yii::app()->db->createCommand($meta)->queryAll();
                                            ?><td><select class='drop-box form-control' data-column='12' style='width:100%'>
                                                <option value=''>Select</option>
                                                <?php foreach($cylFields as $key=>$value){ ?>
                                                    <option value='<?php echo $value['predefined_value']; ?>'> <?php echo $value['field_label']; ?></option>
                                                <?php } ?>
                                            </select>
                                            </td>
                                            <?php
                                            break;

                                        default :
                                            break;
                                    }
                                }
                                ?>
                            </tr>
                            </thead>
                        </table>
                    </div>
                <?php } else {?>
                    <div align="center" style="margin-bottom: 20px;margin-top:  20px;">
                        <img src="<?php echo Yii::app()->baseUrl."/plugins/img/order.png"; ?>" height="20%" width="10%"><br /><br />
                        <h2 lang="en">No Subscription</h2>
                        <p></p>
                        <?php echo CHtml::link('Create', array('orderInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px')); ?>
                        <br />
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js', CClientScript::POS_END);
?>
<script>
    $(document).ready(function() {
        var d = new Date();

        var month = d.getMonth()+1;
        var day = d.getDate();

        var currentDate = d.getFullYear() + '/' +
            ((''+month).length<2 ? '0' : '') + month + '/' +
            ((''+day).length<2 ? '0' : '') + day;
        var datatable = $('#subscription-info-table').DataTable({
            "pageLength":20,
            "lengthMenu": [[20,50,100,200], [20,50,100,200]],
            /*"scrollX" : true,
            "sScrollX": "100%",*/
            /*"pageLength" : 10,
             responsive: {
             details: {
             renderer: $.fn.dataTable.Responsive.renderer.tableAll({tableClass : 'ui table'})
             }
             },*/
            "processing": true,
            "bFilter": false,
            "searching": true,
            "dom":'l,B,t,p',
            "buttons": [
                {
                    extend: 'copyHtml5',
                    title: 'Subscription data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Subscription data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Subscription data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Subscription data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Subscription data export '+currentDate
                }
            ],
            "serverSide": true,
//			"ajax": "serverdata",
            "ajax": {
                "type" : "POST",
                "url" : "serverdata",
                "dataSrc": function ( json ) {
//					console.info(json.data[0]);
//					console.info(json.data.length);
                    /*var i;
                     for (i = 0; i<json.data.length ; i++) {
                     if(json.data[0][8] == 1)
                     {

                     json.data[0][8] = 'male';

                     }
                     else{
                     json.data[0][8] = 'male';

                     }
                     console.info(i);
                     }*/
                    $('.overlay').addClass("overlayhide");
                    return json.data;
                },
                "data": function ( d ) {
                    d.myKey = "myValue";
                    d.starts_at_min = $('#starts_at_min').val();
                    d.starts_at_max = $('#starts_at_max').val();
                    d.next_renewal_date_min = $('#next_renewal_date_min').val();
                    d.next_renewal_date_max = $('#next_renewal_date_max').val();
                }
            },
            "columnDefs": [ {
                "targets": 0,
                "data": null,
                "render" : function(data, type, row) {
                    return '<a href="<?php echo Yii::app()->createUrl("admin/subscription/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/subscription/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>';
                }
                //"defaultContent": "<a href=''><i class='fa fa-eye'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-times'></i></a>"
            },{
                "visible":false,
                "targets":[1,2,4,6,8,9,12,14,15]
            } ]
        });

        $(".date_range_filter").datepicker({
            format : "yyyy-mm-dd",
            autoclose : true
        });

        $('#starts_at_min, #starts_at_max,#next_renewal_date_min,#next_renewal_date_max').change(function(){
            var i =$(this).attr('data-column');
            var v =$(this).val();
            datatable.columns(i).search(v).draw();
        });

        $('.text-box').on('keyup', function () {   // for text boxes
            var i =$(this).attr('data-column');  // getting column index
            console.info(i);
            var v =$(this).val();  // getting search input value
            datatable.columns(i).search(v).draw();
        } );

        $('.date-field').on('change', function () {   // for text boxes
            var i =$(this).attr('data-column');  // getting column index
            console.info(i);
            var v =$(this).val();  // getting search input value
            datatable.columns(i).search(v).draw();
        } );

        $('.drop-box').on('change', function () {   // for text boxes
            var i =$(this).attr('data-column');  // getting column index
            console.info(i);
            var v =$(this).val();  // getting search input value
            datatable.columns(i).search(v).draw();
        } );

        $(".date_range_filter").datepicker({
            format : "yyyy-mm-dd",
            autoclose : true
        });

        $('#clearfilters').on('click',function() {
            datatable.columns().search('').draw();
            datatable.search('').draw();
            $('input[type=text]').val('');
            $('.drop-box').val('');
            $('.date-field').val('');
        });

    });
</script>
