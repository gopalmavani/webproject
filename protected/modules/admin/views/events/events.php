<?php
/* @var $this BookingController */
/* @var $model Booking */
$primary_key = Events::model()->tableSchema->primaryKey;
$this->pageTitle = "<span lang='en'>Events</span>";
$tableName = Events::model()->tableSchema->name;
$sql = "SELECT * FROM $tableName";
$result = Yii::app()->db->createCommand($sql)->queryAll();
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css');
?>
    <link href="<?php echo Yii::app()->createUrl('/'); ?>/plugins/css/select2.min.css" rel="stylesheet" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"/>
    <style>
        .modal-content{
            top:270px;
        }
        .modal-footer{
            height: 58px;
        }
        .modal-body{
            height: 75px;
        }
    </style>
<?php if(!empty($result)){?>
    <!--Begin loader-->
    <div class="overlay" style="opacity:0.1 !important;position:unset !important;">
        <div class="loader">
            <div class="m-loader m-loader--lg m-loader--success" style="width: 30px; display: inline-block;"></div>
        </div>
    </div>
    <!--End loader-->
<?php } ?>

    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    Events
                </h3>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="row">
                <div class="col-md-12">
                    <?php Yii::app()->session['controllerName'] = Yii::app()->controller->id; ?>
                    <?php
                    if(!empty($result)){ ?>
                        <div class="pull-right m-b-10" style="margin-right:2%;margin-top:1%;">
                            <?php echo CHtml::link('Create', array($tableName.'/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                        </div>
                        <div style="margin-right:10px;margin-top:1%" class="pull-right m-b-10">
                            <a lang="en" class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                        </div>

                        <?php  if(!empty($hosts) && Yii::app()->user->role == "admin") { ?>
                            <div class="pull-right" style="margin-top:16px;margin-right:1%!important;">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label style="margin-left: -81%;margin-top: 4px;">
                                            Event Host
                                        </label>
                                    </div>
                                    <div class="m-form__control" style="margin-top: -38%;">
                                        <select class="dropbox form-control" id="host" data-column="6">
                                            <option value="">All</option>
                                            <?php foreach ($hosts as $key=>$value) {
                                                if($value['first_name'] != ""){ ?>
                                                    <option value="<?php echo $value['event_host']; ?>"><?php echo $value['first_name']; ?></option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!--Begin data table-->
                        <div id="user-info-grid">
                            <table id="<?php echo $tableName; ?>-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                                <thead class="custom-table-head">
                                <tr>
                                    <th class="custom-table-head" lang="en">Action</th>
                                    <?php
                                    $array_cols = Yii::app()->db->schema->getTable($tableName)->columns;
                                    foreach($array_cols as $key=>$col){
                                        if($col->name == "event_start"){ ?>
                                            <th class="custom-table-head">Event Start Time</th>
                                        <?php } elseif($col->name == "event_end"){ ?>
                                            <th class="custom-table-head">Event End Time</th>
                                        <?php } else{?>
                                            <th class="custom-table-head"><?php echo ucfirst(str_replace('_',' ',$col->name)); ?></th>
                                        <?php }
                                    }
                                    ?>
                                </tr>
                                </thead>
                                <thead>
                                <tr>
                                    <?php
                                    $arr = array_values($array_cols);
                                    echo "<td></td>";
                                    foreach($arr as $key=>$col) {
                                        switch($col->name){
                                            case "event_title":
                                                echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Title'></td>";
                                                break;

                                            case "event_location":
                                                echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Location'></td>";
                                                break;

                                            case "price":
                                                /*case "credits":*/
                                                echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Price'></td>";
                                                break;

                                            case "event_start":
                                                ?>
                                                <td>
                                                    <p class="date_filter">
                                                        <span class="date-range-span"><input class="date_range_filter form-control" type="text" id="event_start_min" data-column="<?= $key ?>" placeholder="From" style="width:100%" /></span>
                                                        <br/><span class="date-range-span"><input class="date_range_filter form-control" type="text" id="event_start_max" data-column="<?= $key ?>" placeholder="To" style="width:100%" /></span>
                                                    </p>
                                                </td>
                                                <?php
                                                break;

                                            case "event_end":
                                                ?>
                                                <td>
                                                    <p class="date_filter">
                                                        <span class="date-range-span"><input  class="date_range_filter form-control" type="text" id="event_end_min" data-column="<?= $key ?>" placeholder="From" style="width:100%" /></span>
                                                        <br/><span class="date-range-span"><input class="date_range_filter form-control" type="text" id="event_end_max" data-column="<?= $key ?>" placeholder="To" style="width:100%" /></span>
                                                    </p>
                                                </td>
                                                <?php
                                                break;
                                        }
                                    }
                                    ?>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!--End data table-->
                    <?php } else { ?>
                        <div align="center" style="margin-bottom: 20px;margin-top:  20px;">
                            <img src="<?php echo Yii::app()->baseUrl."/plugins/img/calendar.png"; ?>" height="20%" width="10%"><br /><br />
                            <h2 lang="en">No events</h2>
                            <p></p>
                            <?php
                            if(ServiceProvider::model()->findByAttributes(array()) == null){ ?>
                                <span lang="en">You can only create event after creating service provider to create service provider <a href="<?php echo Yii::app()->createUrl("/admin/events/serviceProvider"); ?>" lang="en">Click here.</a></span>
                            <?php }else{
                                echo CHtml::link('Create', array($tableName.'/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px'));
                            } ?>
                            <br />
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/core/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/plugins/bootstrap-datetimepicker/moment.min.js"></script>
    <script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
    <script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/select2.min.js"></script>
    <script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/core/jquery.slimscroll.min.js"></script>
    <script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/core/bootbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            //$('.overlay').removeClass("overlayhide");
            var d = new Date();

            var month = d.getMonth()+1;
            var day = d.getDate();

            var currentDate = d.getFullYear() + '/' +
                ((''+month).length<2 ? '0' : '') + month + '/' +
                ((''+day).length<2 ? '0' : '') + day;

            var datatable = $('#<?php echo $tableName; ?>-table').DataTable({
                "fnDrawCallback":function(){
                    if($('#<?php echo $tableName; ?>-table td').hasClass('dataTables_empty')){
                        $('div.dataTables_paginate').hide();
                        $('div#<?php echo $tableName; ?>-table_info').hide();
                    } else {
                        $('div#<?php echo $tableName; ?>-table_info').show();
                        $('div.dataTables_paginate').show();
                    }
                },
                "order": [[ 28, "desc" ]],
                "pageLength":20,
                "lengthMenu": [[20,50,100,200], [20,50,100,200]],
                "scrollX" : true,
                "sScrollX": "100%",
                "bFilter": false,
                "searching": true,
                "dom":'l,B,t,p',
                "processing": true,
                "buttons": [
                    {
                        extend: 'copyHtml5',
                        title: 'Booking Data export '+currentDate
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Booking Data export '+currentDate
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Booking Data export '+currentDate
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Booking Data export '+currentDate
                    },
                    {
                        extend: 'print',
                        title: 'Booking Data export '+currentDate
                    }
                ],
                "serverSide": true,
                "ajax": {
                    "type" : "GET",
                    "url" : "serverdata",
                    "data": function ( d ) {
                        d.host = $('#host').val();
                        d.event_start_min = $('#event_start_min').val();
                        d.event_start_max = $('#event_start_max').val();
                        d.event_end_min = $('#event_end_min').val();
                        d.event_end_max = $('#event_end_max').val();
                        // etc
                    },
                    "dataSrc": function ( json ) {
                        $('.overlay').addClass("overlayhide");
                        return json.data;
                    }
                },
                "columnDefs": [ {
                    "targets": 0,
                    "data": null,
                    "render" : function(data, type, row) {
                        return '<a href="<?php echo Yii::app()->createUrl("admin/".$tableName."/eventview/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/".$tableName."/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="delete-FbFeed" id="'+data[1]+'"><i class="fa fa-times"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl('/admin/Events/booking')."/"?>'+data[1]+'"><i class="fa fa-book"></i></a>';
                    },
                },{
                    "visible":false,
                    "targets":[1,3,4,5,6,7,8,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]
                } ]
            });

            $('.text-box').on( 'keyup', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
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

            $('#clearfilters').on('click',function() {
                datatable.columns().search('').draw();
                datatable.search('').draw();
                $('input[type=text]').val('');
                $('.drop-box').val('');
                $('.date-field').val('');
            });

            $(".date_range_filter").datepicker({
                format : "yyyy-mm-dd",
                autoclose : true
            });

            $('#host').change(function () {
                var i =$(this).attr('data-column');
                var v =$(this).val();
                datatable.columns(i).search(v).draw();
            });

            $('#event_start_min').change(function(){
                var i =$(this).attr('data-column');
                var v =$(this).val();
                datatable.columns(i).search(v).draw();
            });

            $('#event_start_max').change(function(){
                var i =$(this).attr('data-column');
                var v =$(this).val();
                datatable.columns(i).search(v).draw();
            });

            $('#event_end_min').change(function(){
                var i =$(this).attr('data-column');
                var v =$(this).val();
                datatable.columns(i).search(v).draw();
            });

            $('#event_end_max').change(function(){
                var i =$(this).attr('data-column');
                var v =$(this).val();
                datatable.columns(i).search(v).draw();
            });

        });
        $(document).ready(function(){
            if (localStorage.getItem('crudCreateMsg') == 1){
                $("#crudCreateId").removeClass('hide');
                setTimeout(function(){
                    $("#crudCreateId").fadeOut(4000);
                }, 3000);
                localStorage.crudCreateMsg = 0;
            }

            $(' body ').on('click','.delete-FbFeed',function() {
                console.info('hiii');
                var id = $(this).attr('id');
                bootbox.confirm("Are you sure you want to delete this event?", function(result){
                    if (result === true){
                        $.ajax({
                            url: "Delete",
                            type: "POST",
                            data: {'id': id},
                            beforeSend: function () {
                                $(".overlay").removeClass("hide");
                            },
                            success: function (response) {
                                var Result = JSON.parse(response);
                                if (Result.token == 1){
                                    window.location.reload();
                                }
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                            }
                        });
                    }
                });
            });

        });
    </script>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/moment.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/fullcalendar.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/gcal.min.js', CClientScript::POS_END);
?>