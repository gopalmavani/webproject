<?php
/* @var $this UserInfoController */
/* @var $model UserInfo */

$this->pageTitle = 'Users';
?>
<?php $tableName = UserInfo::model()->tableSchema->name;?>
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
<!--Begin loader-->
<div class="overlay" style="opacity:0.1 !important;position:unset !important;">
    <div class="loader">
        <div class="m-loader m-loader--lg m-loader--success" style="width: 30px; display: inline-block;"></div>
    </div>
</div>
<!--End loader-->
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Customers
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="row">
            <div class="alert alert-success hide" id="delete" align="center">
                <h4>User deleted successfully</h4>
            </div>
            <div class="col-md-12">
                <?php Yii::app()->session['controllerName'] = Yii::app()->controller->id; ?>
                <?php if(!empty($alldata)){ ?>
                <div class="pull-right" style="margin-right: 1%;margin-top: 1%">
                    <?php echo CHtml::link('Create', array('UserInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                </div>
                <div style="margin-right:10px;margin-top: 1%" class="pull-right m-b-10">
                    <a class="btn btn-outline-primary" id="clearfilters">Clear Filters <i class="fa fa-times"></i></a>
                </div>
                <div id="user-info-grid">
                    <table id="user-info-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                        <thead class="custom-table-head">
                        <tr>
                            <th class="custom-table-head">Action</th>
                            <?php $array_cols = Yii::app()->db->schema->getTable('user_info')->columns;
					foreach($array_cols as $key=>$col){ ?>
                            <th class="custom-table-head"><?php echo ucfirst(str_replace('_',' ',$col->name)); ?></th>
                            <?php } ?>
                        </tr>
                        </thead>

                        <thead>
                        <tr>
                            <?php $arr = array_values($array_cols);
					foreach($arr as $key=>$col){
						switch($col->name)
						{
							case 'full_name':
								echo "<td></td>";
								echo "<td><input type='text' data-column='1' class='text-box form-control' placeholder='Username' style='width:100%'></td>";
								break;

							case 'email':
								echo "<td><input type='text' data-column='4' class='text-box form-control' placeholder='Email' style='width:100%'></td>";
								break;
								
							case 'gender':
                                echo "<td><select class='drop-box  form-control' data-column='7' placeholder='Gender' style='width:100%'>
                                        <option>select</option>
                                        <option value='1'>Male</option>
                                        <option value='2'>Female</option>
                                        </select></td>";
                                break;

                            case 'sponsor_id':
                                echo "<td><input type='text' data-column='8' class='text-box form-control' placeholder='Sponsor_id' style='width:100%'></td>";
                                break;

							/*case 'created_at':
								echo "<td><input type='date' data-column='12' data-date-inline-picker='true' class='date-field' style='width:100%' /></td>";
								break;*/

							case 'city':
								echo "<td><input type='text' data-column='26' class='text-box  form-control' placeholder='City' style='width:100%'></td>";
								break;

							/*case 'postcode':
                                echo "<td><input type='text' data-column='27' class='text-box'></td>";
                                break;*/

							case 'country':
								$codesql = "select country_code,country_name from countries";
								$country = Yii::app()->db->createCommand($codesql)->queryAll();
								?><td><select class='drop-box  form-control' data-column='28' style='width:100%'>
                                    <option value="">Select</option>
                                    <?php foreach($country as $key=>$value){ ?>
                                    <option value='<?php echo $value['country_code']; ?>'><?php echo $value['country_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php
								break;

							/*case 'phone':
								echo "<td><input type='text' data-column='29' class='text-box  form-control' style='width:100%'></td>";
								break;

							case 'auth_level':
								$codesql = "select role_title from roles";
                                $roles = Yii::app()->db->createCommand($codesql)->queryAll();
                                ?><td><select class='drop-box  form-control' data-column='33' style='width:100%'>
                                    <option value="">Select</option>
                                    <?php foreach($roles as $key=>$value){ ?> 
                                    <option value='<?php echo $value['role_title']; ?>'><?php echo $value['role_title']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php
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
                <?php }  else { ?>
                <div class="row">
                    <div align="center">
                        <img src="<?php echo Yii::app()->baseUrl."/plugins/img/order.png"; ?>" height="20%" width="10%"><br /><br />
                        <h2 lang="en">No User</h2>
                        <p></p>
                        <div class="row">
                            <?php echo CHtml::link('Create', array($tableName.'/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px')); ?>
                        </div>
                        <br />
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!--begin can not delete user Modal -->
<div class="modal fade" id="nodelete" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b>Not allowed..</b>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </h5>
            </div>
            <div class="modal-body">
                <p>This is the main admin of the application so you can not delete this user.</p>
                <p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--End can not delete user Modal-->

<?php"\n" ; ?>
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
		var datatable = $('#user-info-table').DataTable({
			"order" : [[12,"DESC"]],
			"pageLength":20,
			"lengthMenu": [[20,50,100,200], [20,50,100,200]],
			"scrollX" : true,
			"sScrollX": "100%",
            "bFilter": false,
            "searching": true,
            "dom":'l,B,t,p',
			/*responsive: {
			 details: {
			 renderer: $.fn.dataTable.Responsive.renderer.tableAll({tableClass : 'ui table'})
			 }
			 },*/
			"processing": true,
			"serverSide": true,
			"buttons": [
				{
					extend: 'copyHtml5',
					title: 'Users Data export '+currentDate
				},
				{
					extend: 'excelHtml5',
					title: 'Users Data export '+currentDate
				},
				{
					extend: 'csvHtml5',
					title: 'Users Data export '+currentDate
				},
				{
					extend: 'pdfHtml5',
					title: 'Users Data export '+currentDate
				},
				{
					extend: 'print',
					title: 'Users Data export '+currentDate
				}
			],
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
                    $('.overlay').addClass("overlayhide");
					return json.data;
				}
			},
			"columnDefs": [ {
				"targets": 0,
				"data": null,
				"render" : function(data, type, row) {
					return '<a href="<?php echo Yii::app()->createUrl("admin/userInfo/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/userInfo/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a id='+data[1]+' class="userdelete" href="javascript:void(0)"><i class="fa fa-times"></i></a>';
				}
				//"defaultContent": "<a href=''><i class='fa fa-eye'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-times'></i></a>"
			},{
				"visible":false,
				"targets":[1,3,4,6,7,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,27,29,30,31,32]
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

        $('#user-info-table').on('click','.userdelete',function(){
            var id = $(this).attr('id');
            if(id == 1){
                $('#nodelete').modal('show');
                return false;
            }
        });

        $(' body ').on('click','.userdelete',function() {
            var id = $(this).attr('id');
            bootbox.confirm("Are you sure you want to delete this user?", function(result){
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