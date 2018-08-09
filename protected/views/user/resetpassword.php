<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Login';
?>
<main id="main-container">
    <!-- Hero Content -->
    <div class="bg-primary-dark">
        <section class="content no-padding content-full content-boxed overflow-hidden">
            <div class="push-100-t push-50 text-center">
                <h1 class="h2 text-white push-10 " data-toggle="appear" data-class="animated fadeInDown">Enter New Password</h1>
            </div>
        </section>
    </div>
    <!-- Log In Form -->
    <div class="bg-white">
        <section class="content content-boxed">
            <!-- Section Content -->
            <div class="row items-push push-50-t push-30">
                <div class="col-md-6 col-md-offset-3">
                    <form class="form-horizontal" id="reset-form" action="<?php echo Yii::app()->getBaseUrl(true); ?>/user/passwordreset" method="post">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary">
                                    <label for="frontend-login-username">Enter new password</label>
                                    <input type="password" id="new_pass" name="new-password" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary">
                                    <label for="frontend-login-username">Confirm new password</label>
                                    <input type="password" id="c_pass" name="confirm-password" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 8px;">
                            <div id="mismatch" class="mismatch"></div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                                <button id="changePass" class="btn btn-block btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                        <input type="hidden" id="user_id" name="user-id" value="<?php echo (isset($user->user_id)) ? $user->user_id : '' ?>">
                    </form>
                </div>
            </div>
        </section>
    </div>
</main>
<script>
    $("#changePass").click(function () {
        if($("#new_pass").val() != $("#c_pass").val()){
            $("#mismatch").html('Password do not match');
            return false;
        }
    });
</script>