<?php
/* @var $this ServicesController */
/* @var $model Services */

$id = $model->service_id;
$primary_key = Booking::model()->tableSchema->primaryKey;
$tableName = Booking::model()->tableSchema->name;
?>


<div class="row">
    <div class="pull-right m-b-10">
        <?php echo CHtml::link('Go to list', array('services/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
        <?php echo CHtml::link('update', array('services/update/'.$model->service_id), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
    </div>
</div>
<div class="row">
    <div class="block">
        <ul class="nav nav-tabs" data-toggle="tabs">
            <li class="active">
                <a href="#btabs-animated-slideleft-eventview">Home</a>
            </li>
            <li>
                <a href="#btabs-animated-slideleft-attendees">Attendees</a>
            </li>
            <li>
                <a href="#btabs-animated-slideleft-publish">Publish</a>
            </li>

        </ul>
        <div class="block-content tab-content">
            <div class="tab-pane fade fade-left in active" id="btabs-animated-slideleft-eventview">
                <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'htmlOptions' => array('class' => 'table'),
                    'attributes'=>array(
                        /*'service_id',*/
                        'service_name',
                        [
                            'name' => 'service_description',
                            'value' => (($model->service_description == null) ? "No Description": $model->service_description),
                            'type' => 'raw'
                        ],
                        [
                            'name' => 'user_id',
                            'value' => (($model->user_id == null) ? "No staff member assigned": $model->user_id),
                            'type' => 'raw'
                        ],
                        [
                            'name' => 'resource_id',
                            'value' => (($model->resource_id == null) ? "No resource assigned": $model->resource_id),
                            'type' => 'raw'
                        ],
                        [
                            'name' => 'service_image',
                            'value' => (($model->service_image == null) ? "No Image": '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">'.
                                CHtml::image(Yii::app()->baseUrl.$model->service_image,'No Image',
                                    ['height' => 100,'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                                ).'</div>'),
                            'type' => 'raw'

                        ],
                        [
                            'name' => 'is_display',
                            'value' =>(($model->is_display == 0) ? "<span class='label label-danger'>No</span>":"<span class='label label-success'>Yes</span>"),
                            'type' => 'raw',
                        ],

                        'service_price',
                        'service_duration',
                        'category',
                        'total_booking',
                        'created_at',
                        'modified_at',
                    ),
                )); ?>
            </div>
            <div class="tab-pane fade fade-left" id="btabs-animated-slideleft-attendees">
                <?php
                $sql = "SELECT * FROM $tableName where service_id = ".$id;
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($result)){?>
                    <div class="pull-right m-b-10">
                        <?php echo CHtml::link('Book', array('services/bookingservice/'.$id), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                    </div>
                    &nbsp;&nbsp;
                    <div style="margin-right:10px;" class="pull-right m-b-10">
                        <a class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                    </div>

                    <div id="user-info-grid">
                        <table id="<?php echo $tableName; ?>-table" class="table table-striped table-bordered" style="font-size:13px;width:100% !important;" cellspacing="0" cellpadding="0">
                            <thead class="custom-table-head">
                            <tr>
                                <th class="custom-table-head">Action</th>
                                <?php
                                $array_cols = Yii::app()->db->schema->getTable($tableName)->columns;
                                foreach($array_cols as $key=>$col){
                                    ?>
                                    <th class="custom-table-head"><?php echo ucfirst(str_replace('_',' ',$col->name)); ?></th>
                                    <?php
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
                                    if($col->name != "booking_id" && $col->name != "event_id" && $col->name != "service_id" && $col->name != "user_id" && $col->name != "created_at" && $col->name != "modified_at")
                                        echo "<td><input type='text' data-column=" .$key. " class='text-box' style='width:100% !important;'></td>";
                                }
                                ?>
                            </tr>
                            </thead>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <img src="<?php echo Yii::app()->baseUrl."/plugins/img/calendar.png"; ?>" height="20%" width="10%"><br /><br />
                            <h2>No bookings yet</h2>
                            <p></p>
                            <div class="row">
                                <?php echo CHtml::link('Book', array('services/bookingservice/'.$id), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px')); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="tab-pane fade fade-left" id="btabs-animated-slideleft-publish">
                <?php if($model->is_display == 1){ ?>
                    <div class="row" style="background-color:#eee">
                        <div class="col-md-10" style="margin-left:3%;margin-top:2%">
                            <h4 class="push">Widget</h4>
                            <span class="text">Take bookings directly from your site by embedding the BookingBug widget. To embed the widget, you need to copy the text below and add it to the HTML code of your website.</span>
                            <p></p>
                            <textarea rows="1" style="width:800px;" readonly><script type="text/javascript" src="<?php echo Yii::app()->params['siteUrl']."/".Yii::app()->params['applicationName']."/event/serviceparticular"."/".$model->service_id; ?>"></script></textarea>
                            <p></p>
                        </div>
                    </div>
                    <p></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>



<script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/core/bootbox.min.js"></script>
<script>
    $(document).ready(function() {
        $('#<?php echo $tableName; ?>-table').css('width',"100%");
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
            "pageLength":20,
            "lengthMenu": [[20,50,100,200], [20,50,100,200]],
            /*"scrollX" : true,
            "sScrollX": "100%",*/
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
                "url" : "<?php echo Yii::app()->createUrl('/admin/services/attendiestable/')."/".$id; ?>",
                "dataSrc": function ( json ) {
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
                "targets":[1,2,3,7,10,11]
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