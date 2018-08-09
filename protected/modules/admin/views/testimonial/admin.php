<?php
/* @var $this TestimonialController */
/* @var $model Testimonial */

$primary_key = Testimonial::model()->tableSchema->primaryKey;
$this->pageTitle = 'Testimonial';
$tableName = Testimonial::model()->tableSchema->name;
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
<?php if(!empty($alldata)){?>
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
                Testimonial
            </h3>
        </div>
    </div>
</div>
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success hide" role="alert" id="crudCreateId">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php Yii::app()->session['controllerName'] = Yii::app()->controller->id; ?>
                <?php
                if(!empty($alldata)){ ?>
                    <div class="pull-right m-b-10" style="margin-right:1%">
                        <?php echo CHtml::link('Create', array('testimonial/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>"margin-right:12%;margin-top:17%")); ?>
                    </div>
                    <div style="margin-right:10px;" class="pull-right m-b-10">
                        <a style="margin-top:10%" lang="en" class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                    </div>
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
                                        case "Title":
                                            echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Title'></td>";
                                            break;
                                        case "category":
                                            echo "<td><input type='text' data-column=" .$key. " class='text-box form-control' style='width:100%' placeholder='Category'></td>";
                                            break;
                                    }
                                }
                                ?>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row"><br/></div>
                <?php }  else {?>
                    <div align="center" style="margin-bottom: 20px;margin-top:  20px;">
                        <img src="<?php echo Yii::app()->baseUrl."/plugins/img/order.png"; ?>" height="20%" width="10%"><br /><br />
                        <h2 lang="en">No Testimonial</h2>
                        <p></p>
                        <?php echo CHtml::link('Create', array('testimonial/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px')); ?>
                        <br />
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!--<script src="<?php /*echo Yii::app()->createUrl('/'); */?>/plugins/js/core/bootbox.min.js"></script>
-->
<script>
    $(document).ready(function() {
        $('.overlay').removeClass("overlayhide");
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
            "order": [[ 6, "desc" ]],
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
                    title: 'Testimonial Data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Testimonial Data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Testimonial Data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Testimonial Data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Testimonial Data export '+currentDate
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
                    return '<a href="<?php echo Yii::app()->createUrl("admin/testimonial/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/testimonial/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);" class="delete-FbFeed" id="'+data[1]+'"><i class="fa fa-times"></i></a>';
                }
            },{
                "visible":false,
                "targets":[1,3,4,6,7,8]
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
        bootbox.confirm("Are you sure you want to delete this from Testimonial?", function(result){
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
</script>