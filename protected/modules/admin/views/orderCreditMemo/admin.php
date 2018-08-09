<?php
/* @var $this OrderCreditMemoController */
/* @var $model OrderCreditMemo */

$this->pageTitle = 'CreditMemo';
$sql = 'SELECT * FROM order_credit_memo';
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
<?php  if(!empty($result)){ ?> 
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
                View Order Credit memo
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="col-md-12">
                <?php  if(!empty($result)){ ?> 
                <div class="pull-right m-b-10" style="margin-right:1%">
                    <a style="margin-top:10%" lang="en" class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                </div>
                <div id="order-info-grid">
                    <table id="order-info-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                        <thead class="custom-table-head">
                        <tr>
                            <th class="custom-table-head">Action</th>
                            <?php $array_cols = Yii::app()->db->schema->getTable('order_credit_memo')->columns;
                                    foreach($array_cols as $key=>$col){ ?>
                            <th class="custom-table-head"><?php echo ucfirst(str_replace('_',' ',$col->name)); ?></th>
                            <?php } ?>
                        </tr>
                        </thead>

                        <thead>
                        <tr>
                            <?php $arr = Yii::app()->db->schema->getTable('order_credit_memo')->columns;
                    foreach($arr as $key){
                        switch($key->name) {
                            case 'credit_memo_id':
                                echo "<td></td>";
                                echo "<td><input type='text' data-column='0' class='text-box form-control' placeholder='Id' style='width:100%'></td>";
                                break;

                            case 'order_info_id':
                                echo "<td><input type='text' data-column='1' class='text-box form-control' placeholder='Order Id' style='width:100%'></td>";
                                break;

                            case 'invoice_number':
                                echo "<td><input type='text' data-column='2' class='text-box form-control' placeholder='Invoice Number' style='width:100%'></td>";
                                break;
                                
                            case 'refund_amount':
                                echo "<td><input type='text' data-column='5' class='text-box form-control' placeholder='Refund Amount' style='width:100%'></td>";
                                break;
                                
                            case 'vat':
                                echo "<td><input type='text' data-column='4' class='text-box form-control' placeholder='Vat' style='width:100%'></td>";
                                break;   
                            
                            case 'order_total':
                                echo "<td><input type='text' data-column='3' class='text-box form-control' placeholder='Order Total' style='width:100%'></td>";
                                break;

                            default:
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
                    <img src="<?php echo Yii::app()->baseUrl."/plugins/img/order.png"; ?>" height="20%" width="10%"><br /><br />
                    <h2 lang="en">No Order Credit Memo</h2>
                    <p></p>
                    <br />
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<script>
    //$(".overlay").removeClass("hide");
    var d = new Date();

    var month = d.getMonth()+1;
    var day = d.getDate();

    var currentDate = d.getFullYear() + '/' +
        ((''+month).length<2 ? '0' : '') + month + '/' +
        ((''+day).length<2 ? '0' : '') + day;
    $(document).ready(function() {
        var datatable = $('#order-info-table').DataTable({
            "fnDrawCallback":function(){
                if($('#order-info-table td').hasClass('dataTables_empty')){
                    $('div.dataTables_paginate').hide();
                    $('div#order-info-table_info').hide();
                } else {
                    $('div#order-info-table_info').show();
                    $('div.dataTables_paginate').show();
                }
            },
            "order": [[ 7, "desc" ]],
            "pageLength":20,
            "lengthMenu": [[20,50,100,200], [20,50,100,200]],
            "scrollX" : true,
            "sScrollX": "100%",
            'colReorder':true,
            "processing": true,
            "bFilter": false,
            "searching": true,
            "dom":'l,B,t,p',
            "buttons": [
                {
                    extend: 'copyHtml5',
                    title: 'Order credit memo Data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Order credit memo  Data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Order credit memo  Data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Order credit memo Data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Order credit memo Data export '+currentDate
                }
            ],
            "serverSide": true,
            "ajax": {
                "type" : "GET",
                "url" : "serverdata",
                "dataSrc": function ( json ) {
                    /*$(".overlay").addClass("hide");
                    console.info(json.data);*/
                    $('.overlay').addClass("overlayhide");
                    return json.data;
                }
            },
            "columnDefs": [ {
                "targets": 0,
                "data": null,
                "render" : function(data, type, row) {
                    return '<a href="<?php echo Yii::app()->createUrl("admin/orderCreditMemo/view/").'/';?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/orderCreditMemo/creditDownload/").'/';?>'+data[1]+'"><i class="fa fa-download"></i></a>&nbsp;&nbsp;<a href="javascript:void(0);"><i id="'+data[1]+'" class="fa fa-times delete-creditmemo"></i></a>';
                }
            },{
                "visible":false,
                "targets":[7,8,9]
//
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
    });
    $(' body ').on('click','.delete-creditmemo',function() {
        var id = $(this).attr('id');
        bootbox.confirm("Are you sure you want to delete this credit memo?", function(result){
            if (result === true){
                $.ajax({
                    url: "Delete/"+id,
                    type: "POST",
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

</script>
<script src="/cyclone/admin/plugins/js/core/bootbox.min.js"></script>
