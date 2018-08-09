<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 12/4/18
 * Time: 4:43 PM
 */
$this->pageTitle = "View serviceProvider";
?>


<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Service Provider View
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->


<div class="m-content">
    <?php if(empty($model)){ ?>
        <div class="m-portlet" id="m_blockui_2_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text" lang="en">
                            No ServiceProvider
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <!--begin::Section-->
                <div class="m-section">
                    <div class="m-section__content" align="center">
                        <img src="<?php echo Yii::app()->baseUrl."/plugins/img/calendar.png"; ?>" height="20%" width="10%"><br /><br />
                        <?php
                        echo CHtml::link('Create', array('events/serviceProvider'), array('class' => 'btn btn-primary','style'=>'width:270px;font-size:18px'));
                        ?>
                    </div>
                </div>
                <!--end::Section-->
                <div class="m-separator m-separator--dashed"></div>
            </div>
        </div>
    <?php } else { ?>
        <div class="m-portlet" id="m_blockui_2_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text" lang="en">
                            Configure your business by using following features
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div align="right">
                    <?php echo CHtml::link('Edit', array('events/serviceProvider'), array('class' => 'btn btn-info',"style"=>"margin-top:-3%")); ?>
                </div>
                <!--begin::Section-->
                <div class="m-section">
                    <div class="m-section__content">
                        <?php $this->widget('zii.widgets.CDetailView', array(
                            'data'=>$model,
                            'htmlOptions' => array('class' => 'table table-striped m-table'),
                            'attributes'=>array(
                                'name',
                                [
                                    'name' => 'description',
                                    'value' => (($model->description == null) ? "No Description": $model->description),
                                    'type' => 'raw'
                                ],
                                'email',
                                'phone_no',
                                [
                                    'name' => 'image',
                                    'value' => (($model->image == null) ? "No Image": '<div style="display: block; width: 150px; height: 150px; position: relative; overflow: hidden; border: 1px solid; text-align: center;">'.
                                        CHtml::image(Yii::app()->baseUrl.$model->image,'No Image',
                                            ['height' => 100,'width' => 150, 'style' => "position: absolute; margin: auto; top: -9999px; bottom: -9999px; left: -9999px; right: -9999px; width: 100%; display: block;"]
                                        ).'</div>'),
                                    'type' => 'raw'

                                ],
                                'created_at',
                            ),
                        )); ?>
                        <!--Begin display for timings-->
                        <table class="table table-striped m-table">
                            <tbody>
                            <tr class="odd">
                                <th>Timing</th>
                                <td>
                                    <table class="table table-bordered m-table" style="width:50%;margin-left: 217px;">
                                        <?php foreach ($timings as $key=>$value){
                                            $mytimings = explode(";",$value['timing']);
                                            $min = $mytimings[0];
                                            $max = $mytimings[1];?>
                                            <tr>
                                                <td>
                                                    <?= ucfirst($value['day']); ?>
                                                </td>
                                                <td>
                                                    <?= $min.":00"." to ".$max.":00" ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Section-->
            </div>
        </div>
    <?php } ?>
</div>