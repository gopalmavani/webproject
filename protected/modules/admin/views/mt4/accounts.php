<?php
/* @var $this Mt4Controller */
/* @var $model ApiAccounts */

$primary_key = ApiAccounts::model()->tableSchema->primaryKey;
$tableName = ApiAccounts::model()->tableSchema->name;
$sql = "SELECT * FROM $tableName";
$result = Yii::app()->db->createCommand($sql)->queryAll();?>
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
                Accounts
            </h3>
        </div>
    </div>
</div>
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="col-md-12">
                <?php Yii::app()->session['controllerName'] = Yii::app()->controller->id; ?>
                <?php
                if(!empty($result)){
                    ?>
                    <div style="margin-right:10px;margin-top: 1%;" class="pull-right">
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
                                switch($col->name){
                                    case 'Login':
                                        echo "<td><input type='text' data-column='0' class='text-box form-control' placeholder='Login-Id' style='width:100%'></td>";
                                        break;

                                    case 'Name':
                                        echo "<td><input type='text' data-column='1' class='text-box form-control' placeholder='Name' style='width:100%'></td>";
                                        break;

                                    case 'Balance':
                                        echo "<td><input type='text' data-column='3' class='text-box form-control' placeholder='Balance' style='width:100%'></td>";
                                        break;

                                    case 'Equity':
                                        echo "<td><input type='text' data-column='4' class='text-box form-control' placeholder='Equity' style='width:100%'></td>";
                                        break;

                                    case 'EmailAddress':
                                        echo "<td><input type='text' data-column='5' class='text-box form-control' placeholder='Email-Id' style='width:100%'></td>";
                                        break;

                                    case 'Group':
                                        echo "<td><input type='text' data-column='6' class='text-box form-control' placeholder='Group' style='width:100%'></td>";
                                        break;

                                    case 'Leverage':
                                        echo "<td><input type='text' data-column='9' class='text-box form-control' placeholder='Leverage' style='width:100%'></td>";
                                        break;

                                    case 'Country':
                                        echo "<td><input type='text' data-column='14' class='text-box form-control' placeholder='Country' style='width:100%'></td>";
                                        break;

                                    default :
                                        break;
                                }
                                //echo "<td><input type='text' data-column=" .$key. " class='text-box' style='width:100%'></td>";
                            }
                            ?>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="row"><br/></div>
            </div>
        </div>
    </div>
</div>
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
                    title: 'Api Data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Api Data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Api Data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Api Data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Api Data export '+currentDate
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
                    return '<a href="<?php echo Yii::app()->createUrl("admin/mt4/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>';
                }
            },{
                "visible":false,
                "targets":[3,8,9,11,12,13,14,16,17,18]
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
</script>