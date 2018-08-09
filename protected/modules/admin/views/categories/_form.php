<?php
/* @var $this CategoriesController */
/* @var $model Categories */
/* @var $form CActiveForm */
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Category
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
                        Category information
                    </h3>
                </div>
            </div>
            <div class="pull-right" style="margin: 16px -10px 0px 0px;">
                <?php echo CHtml::link('Go to list', array('categories/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?> 
            </div>
        </div>
        <!--begin::Form-->
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
				'id'=>'categories-form',
				'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
				'enableAjaxValidation'=>false,
				'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                )
			)); ?>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="form-group <?php echo $model->hasErrors('category_name') ? 'has-error' : ''; ?> ">
                        <?php echo $form->textFieldControlGroup($model, 'category_name', array('size' => 50, 'maxlength' => 50, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Category Name')); ?>
                        <div id="Categories_category_error" class="custom-error help-block text-right"></div>
                    </div>
                </div>
                <div class="col-md-6"><br/></div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="form-group <?php echo $model->hasErrors('description') ? 'has-error' : ''; ?> ">
                        <label class="">
                            <?php echo $form->labelEx($model, 'description', array('class' => 'control-label')); ?>
                        </label>
                        <?php echo $form->textArea($model, 'description', array('size'=>60, 'autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Category Description')); ?>
                        <span class="help-block"><?php echo $form->error($model, 'description'); ?> </span>
                    </div>
                </div>
                <div class="col-md-6"><br/></div>
            </div>


            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="form-group <?php echo $model->hasErrors('parent_id') ? 'has-error' : ''; ?>">
                        <?php
                        $list = CHtml::listData(Categories::model()->findAll(), 'category_id', 'category_name');
                        echo $form->dropDownListControlGroup($model, 'parent_id', $list, array('class' => 'form-control',
                            'empty' => 'Select Parent')); ?>
                        <?php echo $form->error($model, 'parent_id'); ?>                    </div>
                </div>
                <div class="col-md-6"><br/></div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="form-group <?php echo $model->hasErrors('is_active') ? 'has-error' : ''; ?> ">
                        <?php echo $form->dropDownListControlGroup($model, 'is_active', array('1' => 'Yes', '0' => 'No'), array('class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'is_active'); ?>
                    </div>
                </div>
                <div class="col-md-6"><br/></div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-md-6">
                    <div class="form-group <?php echo $model->hasErrors('image') ? 'has-error' : ''; ?>">
                        <label class="">
                            <?php echo $form->labelEx($model, 'image', array('class' => 'control-label')); ?>
                        </label><br/>
                        <?php if(!empty($model->image)) { ?>
                        <div class="col-xs-4 m-b-10 image-preview-box-update"  id="imgPreviewBox" >
                            <img src="<?php echo Yii::app()->baseUrl . $model->image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width:250px">
                        </div>
                        <?php echo $form->fileField($model, 'image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Category Image')); ?>
                        <?php }else{ ?>
                        <div class="col-xs-4 m-b-10 image-preview-box" id="imgPreviewBox">
                            <img src="<?php echo Yii::app()->baseUrl . $model->image; ?>" class="image-preview" id="imagePreview" data-holder-rendered="true" style="width:250px">
                        </div><br/>
                        <?php echo $form->fileField($model, 'image', array('autofocus' => 'on', 'class' => 'form-control', 'placeholder' => 'Category Image')); ?>
                        <div class="help-block" id="imageTypeError"></div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-md-9"></div>
                <div class="col-md-3" align="right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array(
								'class' => 'btn btn-primary cat-create',
							)); ?>
                    <?php echo CHtml::link('Cancel', array('categories/admin'),
								array(
									'class' => 'btn btn-default'
								)
							);
							?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function (e) {
		$("#Categories_category_name").focusout(function () {
			//$(".cat-create").click(function () {
			var cat_name = $('#Categories_category_name').val();
            var name = '<?php echo $model->category_name;?>';
			$.ajax({
				url: '<?php  echo Yii::app()->createUrl('admin/categories/CheckCat');  ?>',
				type: 'POST',
				dataType: "json",
				data: {category: cat_name,name : name},
				success: function (response) {
					if (response.token === 1) {
						$("#Categories_category_error").html(response.msg);
						$('.cat-create').attr('disabled','disabled');
					} else {
						$("#Categories_category_error").html(" ");
						$('.cat-create').removeAttr('disabled');
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				}
			});
		});
	});

    $("#Categories_image").on("change", function()
    {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) {
            $("#imgPreviewBox").css("display","none");
            return;
        } // no file selected, or no FileReader support
        $("#imgPreviewBox").css("display","none");
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function(e){ // set image data
                $("#imagePreview").attr('src', e.target.result);
                $("#imgPreviewBox").css("display","block");
            }
        }
    });
</script>