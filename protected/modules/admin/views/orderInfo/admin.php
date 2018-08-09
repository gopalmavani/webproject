<?php
/* @var $this OrderInfoController */
/* @var $model OrderInfo */

$this->pageTitle = 'Orders';
$sql = "SELECT * FROM order_info";
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
<?php
		if(!empty($result)){
			?> 
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
                Orders
            </h3>
        </div>
    </div>
</div>
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="col-md-12">
                <?php
		if(!empty($result)){
			?> 
                <div class="pull-right m-b-10">
                    <?php echo CHtml::link('Create', array('orderInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'margin-left:-12%;margin-top:17%')); ?>
                </div>

                <div style="margin-right:16px;" class="pull-right m-b-10">
                    <a style="margin-top:10%" class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                </div>
                <div id="order-info-grid">
                    <table id="order-info-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                        <thead class="custom-table-head">
                        <tr>
                            <th class="custom-table-head">Action</th>
                            <?php $array_cols = Yii::app()->db->schema->getTable('order_info')->columns;
                    foreach($array_cols as $key=>$col){ ?>
                            <th class="custom-table-head"><?php echo ucfirst(str_replace('_',' ',$col->name)); ?></th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <thead>
                        <tr>
                            <?php
					$array_cols = Yii::app()->db->schema->getTable('order_info')->columns;

			        $arr = array_values($array_cols);
					foreach($arr as $key=>$col){
					switch($col->name) {

                        case 'order_id':
						    echo "<td></td>";
						    echo "<td><input type='text' data-column='1' class='text-box form-control' style='width:100%' placeholder='Order Id'></td>";
						    break;

					    case 'user_id':
					        echo "<td><input type='text' data-column='21' class='text-box form-control' style='width:100%' placeholder='User Name'></td>";
					        break;
					
					    /*case 'email':
						    echo "<td><input type='text' data-column='22' class='text-box form-control' style='width:100%'></td>";
						    break;*/

					    case 'order_status':
						    echo "<td><select class='drop-box form-control' data-column='6' style='width:100%'>
                                        <option value=''>select</option>
                                        <option value='1'>Success</option>
                                        <option value='0'>Cancelled</option>
                                        <option value='2'>Pending</option>
                                    </select></td>";
						    break;
						    						
						case 'netTotal':
                                echo "<td><input type='text' data-column='15' class='text-box form-control' style='width:100%'  placeholder='Net Total'></td>";
                                break;

					    case 'invoice_number':
						    echo "<td><input type='text' data-column='16' class='text-box form-control' style='width:100%' placeholder='Invoice No'></td>";
						    break;

					    case 'invoice_date':
						    echo "<td>
                                <p class='date_filter'>
                                    <span class='date-range-span'><input class='date_range_filter form-control' type='text-box form-control' id='invoice_min' data-column='<?= $key ?>' placeholder='From' style='width:100%' /></span>
                                    <br/><span class='date-range-span'><input class='date_range_filter form-control' type='text-box' id='invoice_max' data-column='<?= $key ?>' placeholder='To' style='width:100%' /></span>
                                </p>
                            </td>";
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
                <div class="row"><br/></div>
                <?php } else { ?> 
                <div align="center" style="margin-bottom: 20px;margin-top:  20px;">
                    <img src="<?php echo Yii::app()->baseUrl.'/plugins/img/order.png'; ?>" height="20%" width="10%"><br /><br />
                    <h2>No Order</h2>
                    <p></p>
                    <?php echo CHtml::link('Create', array('orderInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px')); ?>
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
            "order": [[ 18, "desc" ]],
            "pageLength":20,
            "lengthMenu": [[20,50,100,200], [20,50,100,200]],
            "scrollX" : true,
            "sScrollX": "100%",
            'colReorder':true,
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
                    title: 'Order data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Order data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Order data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Order data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Order data export '+currentDate
                }
            ],
            "serverSide": true,
//			"ajax": "serverdata",
            "ajax": {
                "type" : "GET",
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
                    $(".overlay").addClass("hide");
                    console.info(json.data);
                    return json.data;
                }
            },
            "columnDefs": [ {
                "targets": 0,
                "data": null,
                "render" : function(data, type, row) {
                    return '<a href="<?php echo Yii::app()->createUrl("admin/orderInfo/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/orderInfo/creditMemo/").'/'; ?>'+data[1]+'"><i class="fa fa-external-link"></i></a>';
                }
                //"defaultContent": "<a href=''><i class='fa fa-eye'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-times'></i></a>"
            },{
                "visible":false,
                "targets":[1,4,5,6,8,9,10,11,12,13,14,15,19,20,21,22,23,24,25,26,27,28,29,30]
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

</script>