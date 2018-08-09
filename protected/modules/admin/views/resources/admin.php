<?php
/* @var $this ResourcesController */
/* @var $model Resources */

$primary_key = Resources::model()->tableSchema->primaryKey;
$this->pageTitle = "<span lang='en'>Resources</span>";
$tableName = Resources::model()->tableSchema->name;
$sql = "SELECT * from resources";
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

<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">
                Resources Detail
            </h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="col-md-12">
                <?php if(!empty($result)){?>
                    <?php Yii::app()->session['controllerName'] = Yii::app()->controller->id; ?>
                    <div class="pull-right m-b-10" style="margin-right:1%">
                        <?php echo CHtml::link('Create', array($tableName.'/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>"margin-right:12%;margin-top:17%")); ?>
                    </div>
                    <div style="margin-right:10px;" class="pull-right m-b-10">
                        <a style="margin-top:10%" lang="en" class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                    </div>
                    <div id="<?php echo $tableName; ?>-grid">
                        <table id="<?php echo $tableName; ?>-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                            <thead class="custom-table-head">
                            <tr>
                                <th lang="en" class="custom-table-head">Action</th>
                                <?php
                                $array_cols = Yii::app()->db->schema->getTable($tableName)->columns;
                                foreach($array_cols as $key=>$col){
                                    if($col->name == 'is_available'){?>
                                        <th class="custom-table-head">Availability</th>
                                    <?php }else{?>
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
                                    switch ($col->name) {
                                        case "resource_name":
                                            echo "<td><input type='text' data-column='1' class='text-box form-control' style='width:100%' placeholder='Resource Name'></td>";
                                            break;
                                        case "resource_description":
                                            echo "<td><input type='text' data-column='2' class='text-box form-control' style='width:100%' placeholder='Description'></td>";
                                            break;
                                        case "resource_address":
                                            echo "<td><input type='text' data-column='3' class='text-box form-control' style='width:100%' placeholder='Address'></td>";
                                            break;
                                        case "is_available":
                                            echo "<td><select class='drop-box form-control' data-column='4' style='width:100%'>
                                                <option value=''>select</option>
                                                <option value='1'>Yes</option>
                                                <option value='0'>No</option>
                                            </select></td>";
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
                        <h2 lang="en">No Resources</h2>
                        <p></p>
                        <?php
                        if(ServiceProvider::model()->findAll() == null){ ?>
                            <span lang="en">You can only create resource after creating service provider to create service provider <a href="<?php echo Yii::app()->createUrl("/admin/events/serviceProvider"); ?>" lang="en">Click here.</a></span>
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
<!--<script src="<?php /*echo Yii::app()->createUrl('/'); */?>/plugins/js/core/bootbox.min.js"></script>
--><script>
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
            "order": [[ 5, "desc" ]],
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
                    title: 'Resources Data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Resources Data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Resources Data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Resources Data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Resources Data export '+currentDate
                }
            ],
            "serverSide": true,
            "ajax": {
                "type" : "GET",
                "url" : "serverdata",
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
                "targets":[1,6,7]
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
            var v =$(this).val();// getting search input value
            console.info(v);
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

        $(' body ').on('click','.delete-FbFeed',function() {
            var id = $(this).attr('id');
            bootbox.confirm("Are you sure you want to delete this resource?", function(result){
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