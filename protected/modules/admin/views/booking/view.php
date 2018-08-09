<?php
/* @var $this BookingController */
/* @var $model Booking */
$this->pageTitle = "<span lang='en'>View Booking</span>";
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                View Booking
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet" id="m_blockui_2_portlet">
        <div class="m-portlet__body">
            <div align="right">
                <?php echo CHtml::link('Go to list', array('booking/admin'), array('class' => 'btn btn-minw btn-square btn-primary')); ?>
                <p></p>
            </div>
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                        'data'=>$model,
                        'htmlOptions' => array('class' => 'table table-striped m-table'),
                        'attributes'=>array(
                            /*'booking_id',*/
                            /*'event_id',*/
                            [
                                'name'=>'event title',
                                'type'=>'raw',
                                'value'=>function($model){
                                    if($model->event_id != ""){
                                        $event = Events::model()->findByAttributes(['event_id'=>$model->event_id]);
                                        return CHtml::link($event->event_title, Yii::app()->createUrl("/admin/events/eventview/")."/".$model->event_id);
                                    }
                                    else{
                                        return "No event";
                                    }
                                },
                            ],
                            /*[
                                'name'=>'Service title',
                                'type'=>'raw',
                                'value'=>function($model){
                                    $service = Services::model()->findByAttributes(['service_id'=>$model->service_id]);
                                    if($model->service_id != "" && !(empty($service))){
                                        return CHtml::link($service->service_name, Yii::app()->createUrl("/admin/services/view/")."/".$model->service_id);
                                    }
                                    else{
                                        return "No service";
                                    }
                                },
                            ],*/
                            'number_of_people',
                            'username',
                            'email',
                            'mobile_number',
                            [
                                'name' => 'id file',
                                'value' => function($model) {
                                    if ($model->id_file_1 == "") {
                                        return '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">' .
                                            CHtml::image(Yii::app()->params["siteUrl"] . '/plugins/demo/images/logo.png', 'No Image',
                                                ['height' => 100, 'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                                            ) . '</div>';
                                    } else {
                                        return '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">' .
                                            CHtml::image(Yii::app()->baseUrl . $model->id_file_1, 'No Image',
                                                ['height' => 100, 'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                                            ) . '</div>';
                                    }
                                },
                                'type' => 'raw'

                            ],
                            'building',
                            'street',
                            'region',
                            'city',
                            'postcode',
                            'country',
                            'is_member',
                            [
                                'name' => 'status',
                                'value' => function($model) {
                                    if ($model->status == "approved") {
                                        return '<span class="m-badge  m-badge--primary m-badge--wide">Approved</span>';
                                    } elseif ($model->status == "pending") {
                                        return '<span class="m-badge  m-badge--brand m-badge--wide">Pending</span>';
                                    }elseif ($model->status == "rejected") {
                                        return '<span class="m-badge  m-badge--danger m-badge--wide">Rejected</span>';
                                    }elseif ($model->status == "waitlist") {
                                        return '<span class="m-badge  m-badge--info m-badge--wide">Waitlist</span>';
                                    }elseif ($model->status == "cancelled" || "cancel") {
                                        return '<span class="m-badge  m-badge--metal m-badge--wide">Cancelled</span>';
                                    }elseif ($model->status == "success") {
                                        return '<span class="m-badge  m-badge--primary m-badge--wide">Success</span>';
                                    }
                                },
                                'type' => 'raw'

                            ],
                            'price',
                            'coupon_code',
                            'created_at',
                            'modified_at',
                        ),
                    )); ?>
                </div>
            </div>
            <!--end::Section-->
        </div>
    </div>
</div>