<?php
/* @var $this Mt4Controller */
/* @var $model ApiAccounts */

$this->pageTitle = 'View Api Account';
$id = $model->Login;
?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Api Account
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right" style="margin-bottom: 1%">
                <?php echo CHtml::link('Go to list', array('mt4/accounts'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            'Login',
                            'Name',
                            'Currency',
                            'Balance',
                            'Equity',
                            'EmailAddress',
                            'Group',
                            'Agent',
                            'RegistrationDate',
                            'Leverage',
                            'Address',
                            'City',
                            'State',
                            'PostCode',
                            'Country',
                            'PhoneNumber',
                            'created_at',
                            'modified_at'
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
</div>
