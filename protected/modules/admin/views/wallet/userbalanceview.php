<?php
    /* @var $this WalletController */
    /* @var $model Wallet */

if(count($model->search()->getData()) == 0)
{
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/../plugins/js/core/jquery.min.js');
}
$sql = 'SELECT first_name from user_info where user_id  ='.$id;
$result = Yii::app()->db->CreateCommand($sql)->queryAll();
$fullname = 'User';
if(!empty($result)){
    $fullname = $result[0]['first_name'];
}
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

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                <?= $fullname."'s wallet" ?>            </h3>
            <h3 class="m-subheader__title">
                <?= "Total balance : ". $CashBalance. " Credits"; ?>            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="col-md-12">

                <div style="margin-right:10px;" class="pull-right m-b-10">
                    <a  style="margin-top: 10%;" class="btn btn-outline-primary" lang="en" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                </div>

                <div id="wallet-grid">
                    <table id="wallet-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                        <thead class="custom-table-head">
                        <tr>
                            <th class="custom-table-head" lang="en">Action</th>
                             <?php
					$array_cols = Yii::app()->db->schema->getTable('wallet')->columns;
					foreach($array_cols as $key=>$col){
						if($col->name == 'user_id'){
							?>
                            <th  class="custom-table-head">User name</th>
                             <?php
						}
						else if($col->name == 'wallet_type_id'){
							?>
                            <th  class="custom-table-head">Wallet type</th>
                             <?php
						}
						else if($col->name == 'denomination_id'){
							?>
                            <th  class="custom-table-head">Denomination</th>
                             <?php
						}
						else{
							?>
                            <th  class="custom-table-head"><?php echo ucfirst(str_replace('_',' ',$col->name)); ?></th>
                             <?php
						}
					}
					?>
                        </tr>
                        </thead>

                        <thead>
                        <tr>
                             <?php
					$arr = array_values($array_cols);
					foreach($arr as $key=>$col){
						switch($col->name)
						{
							case 'transaction_type':
							    echo "<td></td>";
								echo "<td>
                                    <select class='drop-box form-control' data-column='3' style='width:100%'>
                                    <option value=''>Select</option>
                                    <option value='0'>Credit</option>
                                    <option value='1'>Debit</option>
                                </select>
                                </td>";
								//						echo "<td><input type='text' data-column='3' class='text-box'></td>";
								break;

							case 'transaction_comment':
								echo "<td><input type='text' data-column='6' class='text-box form-control' placeholder='Comment' style='width:100%'></td>";
								break;

							case 'denomination_id':
								$denominationsql = "select denomination_id,denomination_type from denomination";
								$denominations = Yii::app()->db->createCommand($denominationsql)->queryAll();
								?><td><select class='drop-box form-control' data-column='7' style='width:100%'>
                                    <option value=''>Select</option>
                                     <?php foreach($denominations as $key=>$value){ ?>
                                    <option value=' <?php echo $value['denomination_id']; ?>'> <?php echo $value['denomination_type']; ?></option>
                                     <?php } ?>
                                </select>
                            </td>
                             <?php

								//						echo "<td><input type='text' data-column='7' class='text-box form-control'></td>";
								break;

							case 'transaction_status':
								echo "<td><select class='drop-box form-control' data-column='8' style='width:100%'>
                                   <option value=''>Select</option>
                                   <option value='0'>Pending</option>
                                   <option value='1'>On Hold</option>
                                   <option value='2'>Approved</option>
                                   <option value='3'>Rejected</option>
                                   <option value='4'>Expired</option>
                                   </select>
                                   </td>";
								break;

							case 'amount':
								echo "<td><input type='text' data-column='10' class='text-box form-control' style='width:100%' placeholder='Amount'></td>";
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
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->createUrl('/'); ?>/plugins/js/core/bootbox.min.js"></script>
<script>
    //$(".overlay").removeClass("hide");
    $(document).ready(function() {
        if (localStorage.getItem('msg')){
            $("#delete").removeClass("hide");
            setTimeout(
                function(){
                    $("#delete").addClass("hide");
                },5000
            )
            localStorage.removeItem('msg');
        }

        var d = new Date();

        var month = d.getMonth()+1;
        var day = d.getDate();

        var currentDate = d.getFullYear() + '/' +
            ((''+month).length<2 ? '0' : '') + month + '/' +
            ((''+day).length<2 ? '0' : '') + day;


        var datatable = $('#wallet-table').DataTable({
            "fnDrawCallback":function(){
                if($('#wallet-table td').hasClass('dataTables_empty')){
                    $('div.dataTables_paginate').hide();
                    $('div#wallet-table_info').hide();
                } else {
                    $('div#wallet-table_info').show();
                    $('div.dataTables_paginate').show();
                }
            },
            "order" : [[12,"DESC"]],
            "pageLength":20,
            "lengthMenu": [[20,50,100,200], [20,50,100,200]],
            "scrollX" : true,
            "sScrollX": "100%",
            /*responsive: {
             details: {
             renderer: $.fn.dataTable.Responsive.renderer.tableAll({tableClass : 'ui table'})
             }
             },*/
            "processing": true,
            "serverSide": true,
            "bFilter": false,
            "searching": true,
            "dom":'l,B,t,p',
            "buttons": [
                {
                    extend: 'copyHtml5',
                    title: 'Wallet Data export '+currentDate
                },
                {
                    extend: 'excelHtml5',
                    title: 'Wallet Data export '+currentDate
                },
                {
                    extend: 'csvHtml5',
                    title: 'Wallet Data export '+currentDate
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Wallet Data export '+currentDate
                },
                {
                    extend: 'print',
                    title: 'Wallet Data export '+currentDate
                }
            ],
            "ajax": {
                "type" : "GET",
                "url" : "<?= Yii::app()->createUrl('/admin/wallet/Particularuser').'/'.$id; ?>",
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
                    $('.overlay').addClass("overlayhide");
                    return json.data;
                }
            },
            "columnDefs": [ {
                "targets": 0,
                "data": null,
                "render" : function(data, type, row) {
                    return '<a href=" <?php echo Yii::app()->createUrl("admin/wallet/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href=" <?php echo Yii::app()->createUrl("admin/wallet/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)"><i class="fa fa-times delete-wallet" id="'+data[1]+'"></i></a>';
                }
                //"defaultContent": "<a href=''><i class='fa fa-eye'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-times'></i></a>"
            },{
                "visible":false,
                "targets":[1,2,3,5,6,10,12,13,14,15]
            } ]

        });

        $('.text-box').on( 'keyup', function () {   // for text boxes
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

        $(' body ').on('click','.delete-wallet',function() {
            var id = $(this).attr('id');
            bootbox.confirm("Are you sure you want to delete this wallet?", function(result){
                if (result === true){
                    $.ajax({
                        url: "<?= Yii::app()->createUrl('/admin/wallet/Delete'); ?>",
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


