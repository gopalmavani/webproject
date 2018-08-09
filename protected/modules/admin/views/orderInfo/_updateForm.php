<?php
/* @var $this OrderInfoController */
/* @var $model OrderInfo */
/* @var $form CActiveForm */
?>
<style>
    .selected-address {
        padding: 10px 15px;
        border: 1px solid #eee;
        border-radius: 10px;
    }
</style>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Update Order
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <!--<span class="m-portlet__head-icon m--hide">
                        <i class="la la-gear"></i>
                    </span>-->
                    <h3 class="m-portlet__head-text">
                        Order information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('orderInfo/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
        </div>
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'order-info-form',
            'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
            'enableAjaxValidation'=>false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        )); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
				<?php echo $form->errorSummary($model); ?>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php
                            $usersList = CHtml::listData(UserInfo::model()->findAll(["order" => "full_name"]), "user_id",
                                function ($data){
                                    return "{$data->first_name}  {$data->last_name}";
                                });
                            echo $form->dropDownListControlGroup($model, "user_id", $usersList, [
                                "prompt" => "Select User",
                                "class" => "js-select2 form-control",
                                'disabled' => 'disabled',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group <?php echo $orderPayment->hasErrors('payment_status') ? 'has-error' : ''; ?> ">
                            <?php
                            echo $form->dropDownListControlGroup($orderPayment, 'payment_status', ["2" =>"Pending","1" => "Success","0" =>"Failure"], [
                                'prompt' => 'Select',
                                'class' => 'js-select2 form-control',
                                'options' => array($orderPayment->payment_status =>array('selected'=>true))
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php
                            echo $form->dropDownListControlGroup($model, "order_status",["2" =>"Pending","1" => "Success","0" =>"Cancelled"], [
                                "prompt" => "Select",
                                "class" => "js-select2 form-control",
                                'options' => array($model->order_status =>array('selected'=>true))
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <h4>Address</h4>
            <div class="form-group m-form__group row">
					<div class="col-md-6">
						<div class="col-md-12 ">
							<div class="form-group <?php echo $model->hasErrors('vat_number') ? 'has-error' : ''; ?>">
								<?php echo $form->textFieldControlGroup($model, 'vat_number', array('autofocus' => 'on', 'class' => 'form-control')); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group <?php echo $model->hasErrors('company') ? 'has-error' : ''; ?>">
								<?php echo $form->textFieldControlGroup($model, 'company', array('autofocus' => 'on', 'class' => 'form-control')); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group <?php echo $model->hasErrors('building') ? 'has-error' : ''; ?>">
								<?php echo $form->textFieldControlGroup($model, 'building', array('autofocus' => 'on', 'class' => 'form-control')); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group <?php echo $model->hasErrors('street') ? 'has-error' : ''; ?>">
								<?php echo $form->textFieldControlGroup($model, 'street', array('autofocus' => 'on', 'class' => 'form-control')); ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">

						<div class="col-md-12">
							<div class="form-group <?php echo $model->hasErrors('city') ? 'has-error' : ''; ?>">
								<?php echo $form->textFieldControlGroup($model, 'city', array('autofocus' => 'on', 'class' => 'form-control')); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group <?php echo $model->hasErrors('region') ? 'has-error' : ''; ?>">
								<?php echo $form->textFieldControlGroup($model, 'region', array('autofocus' => 'on', 'class' => 'form-control')); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group <?php echo $model->hasErrors('country') ? 'has-error' : ''; ?>">
								<?php echo $form->dropDownListControlGroup($model, 'country', Yii::app()->ServiceHelper->getCountry(), array('prompt' => 'Select Country', 'class' => 'js-select2 form-control')); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group <?php echo $model->hasErrors('postcode') ? 'has-error' : ''; ?>">
								<?php echo $form->textFieldControlGroup($model, 'postcode', array('autofocus' => 'on', 'class' => 'form-control')); ?>
							</div>
						</div>
					</div>
				</div>
            <div class="">
                <h4>Product</h4>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th>Product Name</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Discount</th>
                                <th class="text-center">Price</th>
                            </tr>
                            </thead>
                            <tbody class="table" id="productControl">
                            <?php
                            $count = 1;
                            foreach($orderItem as $key => $item){
                                $count++;
                                ?>
                                <tr class="addMoreProduct">
                                    <td class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <?php
                                                $productList = CHtml::listData(ProductInfo::model()->findAll(['order' => 'name']), 'product_id', 'name');
                                                echo $form->dropDownList($item, 'product_id[]', $productList, [
                                                    'prompt' => 'Select Product',
                                                    'class' => 'form-control',
                                                    'disabled' => 'disabled',
                                                    'options' => array($item['product_id'] =>array('selected'=>true))
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-md-2">
                                        <div class="col-md-12">
                                            <div class="form-group <?php echo $item->hasErrors('item_qty') ? 'has-error' : ''; ?>">
                                                <input autofocus="autofocus" class="form-control" placeholder="Qty" name="OrderLineItem[item_qty][]" id="OrderLineItem_item_qty" value="<?php echo $item->attributes['item_qty'];  ?>" disabled="disabled" type="text">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-md-2">
                                        <div class="col-md-12">
                                            <div class="form-group ">
                                                <input autofocus="autofocus" readonly="readonly" class="form-control" placeholder="Discount" name="OrderLineItem[item_disc][]" id="OrderLineItem_item_disc" value="<?php echo $item->attributes['item_disc'];  ?>" disabled="disabled" type="text">
                                                <?php //echo $form->textField($orderItem, 'item_disc[]', array('autofocus' => 'on','readonly' => 'readonly', 'class' => 'form-control', 'placeholder' => 'Discount')); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-md-2 text-center">
                                        <div class="col-md-12">
                                            <div class="form-group ">
                                                <input autofocus="autofocus" readonly="readonly" class="form-control" placeholder="Price" id="itemPrice" name="OrderLineItem[item_price][]" value="<?php echo $item->attributes['item_price'];  ?>" disabled="disabled" type="text">
                                                <!--													-->												</div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr id="beforePrice">
                                <td colspan="3" class="text-right"><strong>Total Price:</strong></td>
                                <td class="text-right"  id="totalPrice"><?php echo $model['orderTotal']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total Discount:</strong></td>
                                <td class="text-right"  id="totalDiscount">0</td>
                            </tr>
                            <tr class="success">
                                <td colspan="3" class="text-right text-uppercase"><strong>Net Total:</strong></td>
                                <td class="text-right"><strong  id="netTotal"><?php echo $model['netTotal']; ?></strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" name="OrderInfo[vat_percentage]" id="myvatpercentage" />
            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
                        'class' => 'btn btn-primary',
                    )); ?>
                    <?php echo CHtml::link('Cancel', array('productInfo/admin'),
                        array(
                            'class' => 'btn btn-default'
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<script>
	/*

	 */
	function ProductPrice(productDetails) {
		var validQtyFlag = 0;
		// get selected product
		var productItem = productDetails.find('#OrderLineItem_product_id').val();
		// get entered quantity
		var productQty = productDetails.find('#OrderLineItem_item_qty').val();
		var validNum = /[^\d].+/;

		if(productItem != '' && productQty != ''){
			var data = {qty : productQty,productId : productItem};
			$.ajax({
				type : "POST",
				url : '<?php echo Yii::app()->getUrlManager()->createUrl('admin/OrderInfo/loadprice'); ?>',
				data : data,
				success : function(result){
					var productData = JSON.parse(result);
					if(productData.result == true){
						productDetails.find('#itemPrice').val(productData.productPrice);
						setPriceTotal();
					}
				}
			});
		}else{
			productDetails.find('#itemPrice').val('');
		}
	}

	// Select Address
	$(document).on('change','#orderAddress',function (e) {
		var data = {addressMapId : $(this).val()};
		$.ajax({
			type : "POST",
			url : '<?php echo Yii::app()->createUrl('admin/OrderInfo/getaddress'); ?>',
			data : data,
			success : function(result){
				var addressData = JSON.parse(result);
				if(addressData.result == true){
					$('#OrderInfo_vat_number').val(addressData['orderAddress']['vat_number']);
					$('#OrderInfo_company').val(addressData['orderAddress']['company_name']);
					$('#OrderInfo_building').val(addressData['orderAddress']['building_no']);
					$('#OrderInfo_street').val(addressData['orderAddress']['street']);
					$('#OrderInfo_city').val(addressData['orderAddress']['city']);
					$('#OrderInfo_region').val(addressData['orderAddress']['region']);
					$('#OrderInfo_country').val(addressData['orderAddress']['country']);
					$('#OrderInfo_postcode').val(addressData['orderAddress']['postcode']);
				}
			}
		});
	});

	//    function checkQty(productName,productQty){
	//        console.info();
	//        var data = {sku : productName,qty : productQty};
	//        $.ajax({
	//            type : "POST",
	//            url : '//',
	//            data : data,
	//            success : function(result){
	//                var productData = JSON.parse(result);
	//                if(productData.result == true){
	//                    productDetails.find('#itemPrice').val(productData.productPrice);
	//                    console.info('true');
	//                }else{
	//                    console.info('false');
	//                }
	//            },
	//        });
	//    }
</script>