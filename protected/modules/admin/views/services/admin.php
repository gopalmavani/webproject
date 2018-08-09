<?php
/* @var $this ServicesController */
/* @var $model Services */

$primary_key = Services::model()->tableSchema->primaryKey;

$this->pageTitle = 'Services';

$tableName = Services::model()->tableSchema->name;
$sql = "SELECT * from services";
$result = Yii::app()->db->createCommand($sql)->queryAll();
?>
<?php if(!empty($result)){ ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success hide" role="alert" id="crudCreateId">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="alert-heading text-center">Your crud "Services" is generated successfully.</h4>
            </div>
            <?php Yii::app()->session['controllerName'] = Yii::app()->controller->id; ?>
            <?php /*echo CHtml::link('Edit Crud', array('Builder/editCrud/'.$TableID->table_id), array('class' => 'btn btn-primary')); */?>
            <div class="pull-right m-b-10">
                <?php echo CHtml::link('Create', array($tableName.'/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
            <?php
            $sql = "SELECT * FROM $tableName";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($result)){
                ?>
                <div style="margin-right:10px;" class="pull-right m-b-10">
                    <a class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                </div>
                <?php
            }
            ?>


            <div id="<?php echo $tableName; ?>-grid">
                <table id="<?php echo $tableName; ?>-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
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
                            switch ($col->name){

                                case "service_name":
                                case "category":
                                case "created_at":
                                    echo "<td><input type='text' data-column=" .$key. " class='text-box' style='width:100%'></td>";
                                    break;


                                case "user_id": ?>
                                    <td>
                                        <select class="drop-box" data-column="8" style="width:100%;">
                                            <option value="">all</option>
                                            <?php foreach ($staff as $key=>$value) {
                                                if($value['full_name'] != ""){?>
                                                    <option value="<?php echo $value['user_id']; ?>"><?php echo $value['full_name']; ?></option>
                                                <?php }
                                            } ?>
                                        </select>
                                    </td>
                                    <?php
                                    break;

                                case "resource_id":?>
                                    <td>
                                        <select class="drop-box" data-column="9" style="width:100%;">
                                            <option value="">all</option>
                                            <?php foreach ($resources as $key=>$value) {
                                                if($value['resource_name'] != ""){?>
                                                    <option value="<?php echo $value['resource_id']; ?>"><?php echo $value['resource_name']; ?></option>
                                                <?php }
                                            } ?>
                                        </select>
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
        </div>
    </div>
<?php } else{ ?>
    <div class="row">
        <div class="col-md-12" align="center">
            <img src="<?php echo Yii::app()->baseUrl."/plugins/img/calendar.png"; ?>" height="20%" width="10%"><br /><br />
            <h2>No Services</h2>
            <p></p>
            <div class="row">
                <?php
                if(ServiceProvider::model()->findAll() == null){ ?>
                    <span>You can only create service after creating service provider to create service provider <a href="<?php echo Yii::app()->createUrl("/admin/events/serviceProvider"); ?>">Click here.</a></span>
                <?php }else{
                    echo CHtml::link('Create', array($tableName.'/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px'));
                } ?>
            </div>
            <br />
        </div>
    </div>
<?php } ?>

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
                    title: 'Services Data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Services Data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Services Data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Services Data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Services Data export '+currentDate
                }
            ],
            "serverSide": true,
            "ajax": {
                "type" : "GET",
                "url" : "serverdata",
                "dataSrc": function ( json ) {
                    return json.data;
                }
            },
            "columnDefs": [ {
                "targets": 0,
                "data": null,
                "render" : function(data, type, row) {
                    return '<a href="<?php echo Yii::app()->createUrl("admin/".$tableName."/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/".$tableName."/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="delete-FbFeed" id="'+data[1]+'"><i class="fa fa-times"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("/admin/services/bookingservice/")."/" ?>'+data[1]+'"><i class="fa fa-book"></i></a>';
                }
            },{
                "visible":false,
                "targets":[1,3,4,5,6,7,11,12]
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
            $('#staff').val('');
            $('#resources').val('');
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
            var id = $(this).attr('id');
            bootbox.confirm("Are you sure you want to delete this service?", function(result){
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
                            if(Result.token == 1){
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