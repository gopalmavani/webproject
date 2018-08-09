<?php
/* @var $this UserInfoController */
/* @var $model UserInfo */

$this->pageTitle = 'View User';
$id = $model->user_id; 
$admin = Yii::app()->params['mandatoryFields']['admin_id'];
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View <?php echo $model->first_name; ?>
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet m-portlet--tabs">
        <div class="m-portlet__head">
            <div class="m-portlet__head-tools">
                <ul class="nav nav-tabs m-tabs-line m-tabs-line--danger m-tabs-line--2x" data-toggle="tabs">
                    <li class="nav-item m-tabs__item">
                        <a lang="en" class="nav-link m-tabs__link active show" data-toggle="tab" href="#profile" role="tab" aria-selected="false">
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                            Profile
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" lang="en" data-toggle="tab" href="#address" role="tab" aria-selected="true">
                            <i class="fa fa-bar-chart" aria-hidden="true"></i>
                            Addresses
                        </a>
                    </li>
                    <?php if(Yii::app()->db->schema->getTable('order_info')) { ?>
                    <li class="nav-item m-tabs__item">
                        <a lang="en" class="nav-link m-tabs__link" data-toggle="tab" href="#order" role="tab" aria-selected="false">
                            <i class="fa fa-tags" aria-hidden="true"></i>
                            Orders
                        </a>
                    </li>
                    <?php }
		                    if(Yii::app()->db->schema->getTable('wallet')) { ?>
                    <li class="nav-item m-tabs__item">
                        <a lang="en" class="nav-link m-tabs__link" data-toggle="tab" href="#wallet" role="tab" aria-selected="false">
                            <i class="fa fa-tags" aria-hidden="true"></i>
                            Wallet
                        </a>
                    </li>
                    <?php } ?>
                    <li class="nav-item m-tabs__item">
                        <a lang="en" class="nav-link m-tabs__link" data-toggle="tab" href="#changepassword" role="tab" aria-selected="false">
                            <i class="fa fa-tags" aria-hidden="true"></i>
                            Change password
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="tab-content">
                <div class="tab-pane active" id="profile">
                    <div class="pull-right">
                        <?php echo CHtml::link('Go to list', array('userInfo/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                        <?php echo CHtml::link('Create', array('userInfo/create'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                        <?php if ($id != $admin){ echo CHtml::link('Update', array('userInfo/update/'.$id), array('class' => 'btn btn-minw btn-square btn-primary')); }?>
                        <?php /*echo CHtml::link('Change Password', array('userInfo/changePassword/'.$id), array('class' => 'btn btn-minw btn-square btn-primary')); */?>
                        <?php
				            $user = UserInfo::model()->findByPk(['user_id' => $id]);
				            $userTable = CylTables::model()->findByAttributes(['table_name' => 'user_info']);
				            $activeField = CylFields::model()->findByAttributes(['table_id' => $userTable->table_id, 'field_name' => 'is_active']);
				            if($user->is_active == 1){ $userActive = 0; $label = 'Inactive'; }else { $userActive = 1; $label = 'Active';}
				            $fieldValue = CylFieldValues::model()->findByAttributes(['field_id' => $activeField->field_id, 'predefined_value' => $userActive]);
				            if ($id != $admin) { echo CHtml::link($label, array('userInfo/userActive/', 'id' => $id, 'is_active' => $user->is_active), array('class' => 'btn btn-minw btn-square btn-primary'));}?>
                        <p></p>
                    </div>
                    <?php $this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'htmlOptions' => array('class' => 'table table-striped m-table'),
				'attributes'=>array(
					/*'user_id',*/
					'full_name',
					'first_name',
					'last_name',
					'email',
					/*'password',*/
					'date_of_birth',
					[
						'name' => 'gender',
						'value' => function($model){
							$tableName = CylTables::model()->findByAttributes(['table_name' => 'user_info']);
							$fieldName = CylFields::model()->findByAttributes(['field_name' => 'gender', 'table_id' => $tableName->table_id]);
							$fieldValue = CylFieldValues::model()->findByAttributes(['field_id' => $fieldName->field_id, 'predefined_value' => $model->gender ]);
							return $fieldValue->field_label;
						}
					],
					[
						'name' => 'is_enabled',
						'value' => function($model){
							$tableName = CylTables::model()->findByAttributes(['table_name' => 'user_info']);
							$fieldName = CylFields::model()->findByAttributes(['field_name' => 'is_enabled', 'table_id' => $tableName->table_id]);
							$fieldValue = CylFieldValues::model()->findByAttributes(['field_id' => $fieldName->field_id, 'predefined_value' => $model->is_enabled ]);
							return $fieldValue->field_label;
						}
					],
					[
						'name' => 'is_active',
						'value' => function($model){
							$tableName = CylTables::model()->findByAttributes(['table_name' => 'user_info']);
							$fieldName = CylFields::model()->findByAttributes(['field_name' => 'is_active', 'table_id' => $tableName->table_id]);
							$fieldValue = CylFieldValues::model()->findByAttributes(['field_id' => $fieldName->field_id, 'predefined_value' => $model->is_active ]);
							return $fieldValue->field_label;
						}
					],
					'created_at',
					'modified_at',
					/*'business_name',
                    'vat_number',
                    'busAddress_building_num',
                    'busAddress_street',
                    'busAddress_region',
                    'busAddress_city',
                    'busAddress_postcode',
                    'busAddress_country',
                    'business_phone',
                    'building_num',
                    'street',
                    'region',
                    'city',
                    'postcode',
                    'country',
                    'phone',
                    'is_delete',
                    'image',*/
					'auth_level',
				),
			)); ?>
		</div>
                <!--End Profile tab-->

                <!--Start Addresses tab-->
                <div class="tab-pane" id="address">
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Shipping Address -->
                            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <span class="m-portlet__head-icon m--hide">
                                                <i class="flaticon-statistics"></i>
                                            </span>
                                            <h2 class="m-portlet__head-label m-portlet__head-label--info">
                                                <span>Personal Address</span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="h4 push-5"><?php echo $model->full_name; ?></div>
                                    <address>
                                        <?php if($model->building_num){echo $model->building_num;} if($model->street){echo $model->street."<br>";}?>
                                        <?php if($model->region){echo $model->region."<br>" ;}?>
                                        <?php if($model->city){echo $model->city."<br>";} ?>
                                        <?php $codesql = "select country_name from countries where country_code = "."'$model->country'";
										$country = Yii::app()->db->createCommand($codesql)->queryAll();
										if(!empty($country)){
											echo $country[0]['country_name'].",".$model->postcode.".";?><br><br>
                                        <?php
										}
										else{
											echo $model->country.",".$model->postcode; ?><br><br>
                                        <?php
										}
										?>
                                        <i class="fa fa-phone"></i> <?php echo $model->phone; ?><br>
                                        <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)"><?php echo $model->email; ?></a>
                                    </address>
                                </div>
                            </div>
                            <!-- END Shipping Address -->
                        </div>
                        <div class="col-lg-6">
                            <?php if($model->business_name != ''){?>
                            <!-- Business Address -->
                            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <span class="m-portlet__head-icon m--hide">
                                                <i class="flaticon-statistics"></i>
                                            </span>
                                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                                <span>Business Address</span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="h4 push-5"><?php echo $model->business_name; ?></div>
                                    <address>
                                        <?php if($model->busAddress_building_num){echo $model->busAddress_building_num;}if($model->busAddress_street){echo $model->busAddress_street."<br>" ; }?>
                                        <?php if($model->busAddress_region){echo $model->busAddress_region."<br>" ;}?>
                                        <?php if($model->busAddress_city){echo $model->busAddress_city."<br>";} ?>
                                        <?php $codesql = "select country_name from countries where country_code = "."'$model->busAddress_country'";
											$country = Yii::app()->db->createCommand($codesql)->queryAll();
											if(!empty($country)){
												echo $country[0]['country_name'].",".$model->busAddress_postcode; ?><br><br>
                                        <?php
											}
											else{
												echo $model->busAddress_country.",".$model->busAddress_postcode; ?><br><br>
                                        <?php
											}
											?>
                                        <i class="fa fa-phone"></i> <?php echo $model->business_phone; ?><br>
                                        <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)"><?php echo $model->email; ?></a>
                                    </address>
                                </div>
                            </div>
                            <!-- END Billing Address -->
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!--End Addresses tab-->

                <!--Start Order tab-->
                <?php if(Yii::app()->db->schema->getTable('order_info')) { ?>
                <div class="tab-pane" id="order">
                    <a class="btn btn-outline-primary pull-right" id="clearfiltersorder">Clear Filters <i class="fa fa-times"></i></a>
                    <div id="order-info-grid">
                        <table id="order-info-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                            <thead class="custom-table-head">
                            <tr>
                                <th class="custom-table-head">Action</th>
                                <?php
							$array_cols = Yii::app()->db->schema->getTable('order_info')->columns;
							foreach($array_cols as $key=>$col){
								if($col->name == 'user_id'){
									?>
                                <th class="custom-table-head">Username</th>
                                <?php
								}
								else{
									?>
                                <th class="custom-table-head"><?php echo ucfirst($col->name); ?></th>
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

									case 'order_id':
										echo "<td></td>";
										echo "<td><input type='text' data-column='1' placeholder='Order Id' class='text-box form-control' style='width:100%'></td>";
										break;

									case 'company':
										echo "<td><input type='text' data-column='5' placeholder='Company' class='text-box form-control' style='width:100%'></td>";
										break;

									case 'order_status':
										echo "<td><select class='drop-box form-control' data-column='6' style='width:100%'>
                                        <option value=''>select</option>
                                        <option value='1'>Success</option>
                                        <option value='0'>Canceled</option>
                                    </select></td>";
										break;

									case 'city':
										echo "<td><input type='text' data-column='9' placeholder='City' class='text-box form-control' style='width:100%'></td>";
										break;

									case 'country':
										$codesql = "select country_code,country_name from countries";
										$country = Yii::app()->db->createCommand($codesql)->queryAll();
										?><td><select class='drop-box form-control' data-column='11' style='width:100%'>
                                        <option value=''>select</option>
                                        <?php foreach($country as $key=>$value){ ?>
                                        <option value='<?php echo $value['country_code']; ?>'><?php echo $value['country_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <?php
										break;

									case 'invoice_number':
										echo "<td><input type='text' data-column='16' placeholder='Invoice Number' class='text-box form-control' style='width:100%'></td>";
										break;

									case 'invoice_date':
										echo "<td><input type='date' placeholder='Invoice Date' data-column='17' data-date-inline-picker='true' class='date-field form-control' style='width:100%' /></td>";
										break;

									case 'is_subscription_enabled':
										echo "<td><select class='drop-box form-control' data-column='20' style='width:100%'>
                                        <option value=''>select</option>
                                        <option value='1'>Yes</option>
                                        <option value='0'>No</option>
                                    </select></td>";
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
                <?php } ?>
                <!--Start Order tab-->

                <!--Start Wallet tab-->
                <?php if(Yii::app()->db->schema->getTable('wallet')) { ?>
                <div class="tab-pane" id="wallet">
                    <div id="wallet-grid">
                        <a class="btn btn-outline-primary pull-right" id="clearfiltersorder">Clear Filters <i class="fa fa-times"></i></a>
                        <table id="wallet-table" class="table table-striped table-bordered" width="100%" style="font-size:13px;" cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th>Action</th>
                                <?php
							$array_cols = Yii::app()->db->schema->getTable('wallet')->columns;
							foreach($array_cols as $key=>$col){
								if($col->name == 'wallet_type_id'){
									?>
                                <th>Wallet type</th>
                                <?php
								}
								else if($col->name == 'denomination_id'){
									?>
                                <th>Denomination</th>
                                <?php
								}
								else{
									?>
                                <th><?php echo ucfirst($col->name); ?></th>
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

									case 'wallet_type_id':
										$wallettypesql = "select wallet_type_id,wallet_type from wallet_type_entity";
										$wallettypenames = Yii::app()->db->createCommand($wallettypesql)->queryAll();
										?>
                                <td></td>
                                <td><select class='drop-box' data-column='2' style='width:100%'>
                                        <option value=''>Select</option>
                                        <?php foreach($wallettypenames as $key=>$value){ ?>
                                        <option value='<?php echo $value['wallet_type_id']; ?>'><?php echo $value['wallet_type']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <?php
										//	echo "<td><input type='text' data-column='2' class='text-box form-control'></td>";
										break;

									case 'transaction_type':
										echo "<td>
                                    <select class='drop-box  form-control' data-column='3' style='width:100%'>
                                    <option value=''>Select</option>
                                    <option value='0'>Credit</option>
                                    <option value='1'>Debit</option>
                                </select>
                                </td>";
										//echo "<td><input type='text'  data-column='3' class='text-box form-control'></td>";
										break;

									case 'transaction_comment':
										echo "<td><input type='text' data-column='6' placeholder='Commment' class='text-box form-control' style='width:100%'></td>";
										break;

									case 'denomination_id':
										$denominationsql = "select denomination_id,denomination_type from denomination";
										$denominations = Yii::app()->db->createCommand($denominationsql)->queryAll();
										?><td><select class='drop-box form-control' data-column='7' style='width:100%'>
                                        <option value=''>Select</option>
                                        <?php foreach($denominations as $key=>$value){ ?>
                                        <option value='<?php echo $value['denomination_id']; ?>'><?php echo $value['denomination_type']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <?php

										//	echo "<td><input type='text' data-column='7' class='text-box form-control'></td>";
										break;

									case 'transaction_status':
										echo "<td><select class='drop-box form-control' data-column='8' style='width:100%'>
                                   <option value=''>Select</option>
                                   <option value='0'>Pending</option>
                                   <option value='1'>On Hold</option>
                                   <option value='2'>Approved</option>
                                   <option value='3'>Rejected</option>
                                   </select>
                                   </td>";
										break;

									case 'updated_balance':
										echo "<td><input type='text' data-column='11' class='text-box form-control' placeholder='Balance' style='width:100%'></td>";
										break;

									case 'created_at':
										echo "<td><input type='date' data-column='12' data-date-inline-picker='true' class='date-field  form-control' style='width:100%' /></td>";
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
                <?php } ?>
                <!--End Wallet tab-->

                <!--Start Change password tab-->
                <div class="tab-pane" id="changepassword">
                    <div class="m-portlet">
                        <div class="m-portlet__body">
                            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
						'id' => 'usersChangePassword',
						'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
						'enableAjaxValidation' => false,
					));
					?>
                            <div class="form-group m-form__group row">
                                <div class="col-md-12">
                                    <div class="form-material has-error">
                                        <p id="passwordError" class="help-block has-error" style="display: none;"></p>
                                    </div>
                                    <div class="form-material has-success">
                                        <p id="passwordMessage" class="help-block " style="display: none;"></p>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            <div class="control-group">
                                                <label class="control-label required" for="UserInfo_newPassword">New Password <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input maxlength="50" class="form-control input-50" name="UserInfo[newPassword]" id="UserInfo_newPassword" type="password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            <div class="control-group">
                                                <label class="control-label required" for="UserInfo_confirmPassword">Confirm Password <span class="required">*</span></label>
                                                <div class="controls">
                                                    <input maxlength="50" class="form-control input-50" name="UserInfo[confirmPassword]" id="UserInfo_confirm_password" type="password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3" align="right">
                                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Change' : 'Save', array(
								'class' => 'btn btn-primary',
							)); ?>
                                    <?php echo CHtml::link('Cancel', array('userInfo/admin'),
								array(
									'class' => 'btn btn-default'
								)
							);
							?>
                                </div>
                            </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                </div>
                <!--Start Change Password tab-->
            </div>
        </div>
	</div>
</div>

<script>
	jQuery(function () {
		// Init page helpers (Appear + CountTo plugins)
		App.initHelpers(['appear', 'appear-countTo']);
	});
	var changePassword = '<?php  echo Yii::app()->createUrl('admin/userInfo/changePassword/'.$model->user_id);  ?>';

	var userorders = '<?php  echo Yii::app()->createUrl('admin/userInfo/userorders/'.$model->user_id);  ?>';

	//$(".overlay").removeClass("hide");
	$(document).ready(function() {
		var datatable = $('#order-info-table').DataTable({
			"order": [[ 18, "desc" ]],
			"pageLength":20,
			"lengthMenu": [[20,50,100,200], [20,50,100,200]],
			"scrollX" : true,
			"sScrollX": "100%",
            "bFilter": false,
            "searching": true,
            "dom":'l,B,t,p',
			/*"pageLength" : 10,
			 responsive: {
			 details: {
			 renderer: $.fn.dataTable.Responsive.renderer.tableAll({tableClass : 'ui table'})
			 }
			 },*/
			"processing": true,
			"serverSide": true,
//			"ajax": "serverdata",
			"ajax": {
				"type" : "GET",
				"url" : userorders,
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
					//$(".overlay").addClass("hide");
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
				"targets":[1,3,4,5,8,9,11,13,14,15,16,19,20,22,23]
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

		$('#clearfiltersorder').on('click',function() {
			datatable.columns().search('').draw();
			datatable.search('').draw();
			$('input[type=text]').val('');
			$('.drop-box').val('');
			$('.date-field').val('');
		});

	});
</script>
<script>
	var userwallet = '<?php  echo Yii::app()->createUrl('admin/userInfo/userwallet/'.$model->user_id);  ?>';
	//$(".overlay").removeClass("hide");
	$(document).ready(function() {
		var datatable = $('#wallet-table').DataTable({
			"order": [[ 12, "desc" ]],
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
//			"ajax": "serverdata",
			"ajax": {
				"type" : "GET",
				"url" : userwallet,
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
					//$(".overlay").addClass("hide");
					return json.data;
				}
			},
			"columnDefs": [ {
				"targets": 0,
				"data": null,
				"render" : function(data, type, row) {
					return '<a href="<?php echo Yii::app()->createUrl("admin/wallet/view/").'/'; ?>'+data[1]+'"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/wallet/update/").'/'; ?>'+data[1]+'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl("admin/wallet/delete/").'/'; ?>'+data[1]+'"><i class="fa fa-times"></i></a>';
				}
				//"defaultContent": "<a href=''><i class='fa fa-eye'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;<a href=''><i class='fa fa-times'></i></a>"
			},{
				"visible":false,
				"targets":[1,2,5,6,10,11,14]
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

	});
</script>