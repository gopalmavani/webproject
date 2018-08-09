<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
?>
<main id="main-container">
	<!-- Hero Content -->
	<div class="bg-primary-dark">
		<section class="content no-padding content-full content-boxed overflow-hidden">
			<div class="push-100-t push-50 text-center">
				<h1 class="h2 text-white push-10 " data-toggle="appear" data-class="animated fadeInDown">Log in to your dashboard.</h1>
			</div>
		</section>
	</div>
<!-- Log In Form -->
<div class="bg-white">
	<section class="content content-boxed">
		<!-- Section Content -->
		<div class="row items-push push-50-t push-30">
			<div class="col-md-6 col-md-offset-3">

					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'login-form',
						'enableClientValidation'=>true,
						'clientOptions'=>array(
							'validateOnSubmit'=>true,
						),
						'htmlOptions' =>[
							'class' => 'form-horizontal'
						],
					)); ?>
						<div class="form-group">
						<div class="col-xs-12">
							<div class="form-material form-material-primary">
								<label for="frontend-login-username"><?php echo $form->labelEx($model,'Email'); ?></label>
								<?php echo $form->textField($model,'username', array('placeholder'=>'Enter your Email..','class'=>'form-control','autofocus'=>'true')); ?>
								<?php echo $form->error($model,'username'); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12">
							<div class="form-material form-material-primary">
								<label for="frontend-login-password"><?php echo $form->labelEx($model,'password'); ?></label>
								<?php echo $form->passwordField($model,'password', array('class'=>'form-control','placeholder'=>'Enter your password')); ?>
								<?php echo $form->error($model,'password'); ?>
							</div>
							<a href="<?php echo Yii::app()->createUrl('/user/forgot'); ?>"> Forgot Password? </a>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 text-center">
							<?php echo $form->checkBox($model,'rememberMe'); ?>
							<?php echo $form->label($model,'rememberMe'); ?>
							<?php echo $form->error($model,'rememberMe'); ?>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-6 col-sm-offset-3">
							<?php echo CHtml::submitButton('Login', array('class'=>'btn btn-block btn-primary')); ?>
							<!--<button class="btn btn-block btn-primary" type="submit"><i class="fa fa-arrow-right pull-right"></i> Log in</button>-->
						</div>
					</div>
					<?php $this->endWidget(); ?>
			</div>
		</div>
	</section>
</div>
</main>