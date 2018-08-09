<?php
/* @var $this BookingController */
/* @var $model Booking */

$primary_key = Booking::model()->tableSchema->primaryKey;

$this->pageTitle = "<span lang='en'>Booking</span>";
$tableName = Booking::model()->tableSchema->name;
$sql = "SELECT * FROM $tableName";
$result = Yii::app()->db->createCommand($sql)->queryAll();
?>
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
                Bookings
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->


<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="col-md-12">
                <?php
                if(!empty($result)){?>
                    <?php Yii::app()->session['controllerName'] = Yii::app()->controller->id; ?>

                    <div style="margin-right:10px;margin-top:1%;" class="pull-right m-b-10">
                        <a class="btn btn-outline-primary" id="clearfilters" lang="en">Clear Filters <i class="fa fa-times"></i></a>
                    </div>

                    <?php  if(!empty($hosts) && Yii::app()->user->role == "admin") { ?>
                        <div class="pull-right" style="margin-top:16px;margin-right:1%!important;;">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label">
                                    <label style="margin-left: -81%;margin-top: 1px;">
                                        Event Host
                                    </label>
                                </div>
                                <div class="m-form__control" style="margin-top: -33%;">
                                    <select class="dropbox form-control" id="host" data-column="0">
                                        <option value="">All</option>
                                        <?php foreach ($hosts as $key=>$value) {
                                            if($value['full_name'] != ""){ ?>
                                                <option value="<?php echo $value['event_host']; ?>"><?php echo $value['full_name']; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div id="<?php echo $tableName; ?>-grid">
                        <table id="<?php echo $tableName; ?>-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                            <thead class="custom-table-head">
                            <tr>
                                <th class="custom-table-head" lang="en">Action</th>
                                <?php
                                $array_cols = Yii::app()->db->schema->getTable($tableName)->columns;
                                foreach($array_cols as $key=>$col){
                                    if($col->name == "event_id"){ ?>
                                        <th class="custom-table-head">Event title</th>
                                    <?php } else {?>
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
                                        case "event_id":
                                            echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Event title'></td>";
                                            break;
                                        case "username":
                                            echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Username'></td>";
                                            break;
                                        case "number_of_people":
                                            echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Number of people'></td>";
                                            break;
                                        case "email":
                                            echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Email'></td>";
                                            break;
                                        case "status":
                                            echo "<td><select class='drop-box  form-control' data-column=" .$key. " style='width:100%'>
                                        <option>select</option>
                                        <option>pending</option>
                                        <option>approved</option>
                                        <option>rejected</option>
                                        <option>waitlist</option>
                                        <option>success</option>
                                        <option>cancelled</option>
                                        </select></td>";
                                            break;
                                        case "created_at":
                                            ?>
                                            <td>
                                                <p class="date_filter">
                                                    <span class="date-range-span"><input  class="date_range_filter form-control" type="text" id="create_start" data-column="<?= $key ?>" placeholder="From" style="width:100%" /></span>
                                                    <br/><span class="date-range-span"><input class="date_range_filter form-control" type="text" id="create_end" data-column="<?= $key ?>" placeholder="To" style="width:100%" /></span>
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
                    <div class="row"><br/></div>
                <?php } else { ?>
                    <div align="center" style="margin-bottom: 20px;margin-top:  20px;">
                        <img src="<?php echo Yii::app()->baseUrl."/plugins/img/calendar.png"; ?>" height="20%" width="10%"><br /><br />
                        <h2 lang="en">No bookings yet</h2>
                        <p></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/core/bootbox.min.js"></script>
<script>
    $(document).ready(function() {
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
            "order": [[ 12, "desc" ]],
            "pageLength":20,
            "lengthMenu": [[20,50,100,200], [20,50,100,200]],
            "scrollX" : true,
            "sScrollX": "100%",
            "processing": true,
            "bFilter": false,
            "searching": true,
            "dom":'l,B,t,p',
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
                    d.create_start = $('#create_start').val();
                    d.create_end  = $('#create_end').val();
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
                    return '<a href="<?php echo Yii::app()->createUrl("admin/".$tableName."/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/".$tableName."/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="delete-FbFeed" id="'+data[1]+'"><i class="fa fa-times"></i></a>';
                }
            },{
                "visible":false,
                "targets":[1,3,6,7,8,9,10,11,12,13,14,15,18,19,20,21,22,23,24,26]
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

        $('#host').change(function(){
            var i =$(this).attr('data-column');
            var v =$(this).val();
            datatable.columns(i).search(v).draw();
        });

        $(".date_range_filter").datepicker({
            format : "yyyy-mm-dd",
            autoclose : true
        });

        $('#create_start').change(function(){
            var i =$(this).attr('data-column');
            var v =$(this).val();
            datatable.columns(i).search(v).draw();
        });

        $('#create_end').change(function(){
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
    });

    $(' body ').on('click','.delete-FbFeed',function() {
        var id = $(this).attr('id');
        bootbox.confirm("Are you sure you want to delete this booking?", function(result){
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
</script>