<?php
/* @var $this ProductInfoController */
/* @var $model ProductInfo */
$this->pageTitle = 'Products';
$sql = 'SELECT * FROM product_info';
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
<?php if(!empty($result)){ ?>
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
                Products Detail
            </h3>
        </div>
    </div>
</div>
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <!--<div class="alert alert-success hide" id="delete" align="center">
                <h4 lang="en">Product deleted successfully</h4>
            </div>-->
            <div class="col-md-12">
                <?php if(!empty($result)){ ?>
                <div class="pull-right m-b-10">
                    <?php echo CHtml::link('Create', array('productInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'margin-left:-12%;margin-top:17%')); ?>
                </div>
                <div style="margin-right:16px;" class="pull-right m-b-10">
                    <a style="margin-top:10%" lang="en" class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                </div>
                <div id="product-info-grid">
                    <table id="product-info-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                        <thead class="custom-table-head">
                        <tr>
                            <th class="custom-table-head">Action</th>
                            <?php
						$array_cols = Yii::app()->db->schema->getTable('product_info')->columns;
						foreach($array_cols as $key=>$col){
							?>
                            <th class="custom-table-head"><?php echo ucfirst(str_replace('_',' ',$col->name)); ?></th>
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

								case 'sku':
									echo "<td></td>";
									echo "<td><input type='text' data-column='1' class='text-box form-control' placeholder='Sku' style='width:100%;'></td>";
									break;

								case 'name':
									echo "<td><input type='text' data-column='2' class='text-box form-control' placeholder='Name' style='width:100%;'></td>";
									break;

								case 'price':
									echo "<td><input type='text' data-column='3' class='text-box form-control' placeholder='Price' style='width:100%;'></td>";
									break;

								case 'short_description':
									echo "<td><input type='text' data-column='10' class='text-box form-control' placeholder='Short Description' style='width:100%;'></td>";
									break;

								/*case 'created_at':
									echo "<td><input type='date' data-column='7' data-date-inline-picker='true' class='date-field' style='width:100%' /></td>";
									break;*/

								default :
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
                    <img src="<?php echo Yii::app()->baseUrl.'/plugins/img/product.png'; ?>" height="20%" width="10%"><br /><br />
                    <h2 lang="en">No Product</h2>
                    <p></p>
                    <?php echo CHtml::link('Create', array('productInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px')); ?>
                    <br />
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/core/bootbox.min.js"></script>
<script>
    $(document).ready(function() {
        if (localStorage.getItem('msg')){
            $("#delete").removeClass("hide");
            setTimeout(
                function(){
                    $("#delete").addClass("hide");
                },5000
            );
            localStorage.removeItem('msg');
        }
        var d = new Date();

        var month = d.getMonth()+1;
        var day = d.getDate();

        var currentDate = d.getFullYear() + '/' +
            ((''+month).length<2 ? '0' : '') + month + '/' +
            ((''+day).length<2 ? '0' : '') + day;

        var datatable = $('#product-info-table').DataTable({
            "fnDrawCallback":function(){
                if($('#product-info-table td').hasClass('dataTables_empty')){
                    $('div.dataTables_paginate').hide();
                    $('div#product-info-table_info').hide();
                } else {
                    $('div#product-info-table_info').show();
                    $('div.dataTables_paginate').show();
                }
            },
            "order":[[7,"DESC"]],
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
                    title: 'Products Data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Products Data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Products Data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Products Data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Products Data export '+currentDate
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
                    return '<a href="<?php echo Yii::app()->createUrl("admin/productInfo/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/productInfo/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)"><i class="fa fa-times delete-product" id="'+data[1]+'"></i></a>';
                }
            },{
                "visible":false,
                "targets":[1,5,6,7,8,9,10,12,13,14,15]
            } ]
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

        $('#clearfilters').on('click',function() {
            datatable.columns().search('').draw();
            datatable.search('').draw();
            $('input[type=text]').val('');
            $('.drop-box').val('');
            $('.date-field').val('');
        });

        $(' body ').on('click','.delete-product',function() {
            var id = $(this).attr('id');
            bootbox.confirm("Are you sure you want to delete this product?", function(result){
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
                                localStorage.setItem('msg','success');
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