<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 9/2/18
 * Time: 6:23 PM
 */
$settingsql = "SELECT * from settings where module_name = 'jira'";
$result = Yii::app()->db->createCommand($settingsql)->queryAll();
if(empty($result)){
    $class = "stopjira";
}
else{
    $class = "";
}
$update = "noupdate";
if(!empty($result)){
    $update = "update";
    foreach ($result as $key=>$value)
    {
        if($value['settings_key'] == 'url'){
            $url = $value['value'];
        }
        if($value['settings_key'] == 'username'){
            $username = $value['value'];
        }
        if($value['settings_key'] == 'password'){
            $password = $value['value'];
        }
    }
}
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Jira Ticket Settings
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->


<div class="m-content">
    <div class="m-portlet">
        <form method="POST" action="<?php echo Yii::app()->createUrl('/admin/ticketing/savecredentials') ?>" id="stopjiraform">
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <input type="text" name="update" value="<?php if(isset($update)){ echo $update; } ?>" hidden>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Create jira issue url*</label>
                            <div>
                                <input type="text" class="form-control" name="createissueurl" id="createissueurl" value="<?php if(isset($url)){ echo $url; } ?>">
                                <span class="help-block hide" id="urlerror">Please enter url.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Username*</label>
                            <div>
                                <input type="text" class="form-control" name="username" id="username" value="<?php if(isset($username)){ echo $username; } ?>">
                                <span class="help-block hide" id="usernameerror">Please enter username.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Password*</label>
                            <div>
                                <input type="password" class="form-control" name="password" id="password"  value="<?php if(isset($password)){ echo $password; } ?>">
                                <span class="help-block hide" id="passworderror">Please enter password.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="history.go(-1);">Cancel</button>
                            <button type="submit" id="stopjirasubmit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#stopjirasubmit').on("click",function () {
        var url = $('#createissueurl').val();
        var username = $('#username').val();
        var password = $('#password').val();
        $('#createissueurl').parent().parent().removeClass('has-error');
        $('#username').parent().parent().removeClass('has-error');
        $('#password').parent().parent().removeClass('has-error');
        $('#urlerror').addClass('hide');
        $('#usernameerror').addClass('hide');
        $('#passworderror').addClass('hide');
        if(url == ""){
            $('#createissueurl').parent().parent().addClass('has-error');
            $('#createissueurl').parent().parent().css('margin-bottom','5%');
            $('#urlerror').removeClass('hide');

            if(username == ""){
                $('#username').parent().parent().addClass('has-error');
                $('#username').parent().parent().css('margin-bottom','5%');
                $('#usernameerror').removeClass('hide');
                if(password == ""){
                    $('#password').parent().parent().addClass('has-error');
                    $('#password').parent().parent().css('margin-bottom','5%');
                    $('#passworderror').removeClass('hide');
                    return false;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        else{
            if(username == ""){
                $('#username').parent().parent().addClass('has-error');
                $('#username').parent().parent().css('margin-bottom','5%');
                $('#usernameerror').removeClass('hide');

                if(password == ""){
                    $('#password').parent().parent().addClass('has-error');
                    $('#password').parent().parent().css('margin-bottom','5%');
                    $('#passworderror').removeClass('hide');
                    return false;
                }
                else{
                    return false;
                }
            }
            else{
                if(password == ""){
                    $('#password').parent().parent().addClass('has-error');
                    $('#password').parent().parent().css('margin-bottom','5%');
                    $('#passworderror').removeClass('hide');
                    return false;
                }
            }
        }
        $('#stopjiraform').submit();
    });
</script>