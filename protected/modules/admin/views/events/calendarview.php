<?php
/**
 * Created by PhpStorm.
 * User: kushal
 * Date: 5/3/18
 * Time: 7:18 PM
 */
$this->pageTitle = "Events Calendar View";
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">
                Events Calendar View
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <!--begin::Portlet-->
        <div class="m-portlet " id="m_portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon">
                                            <i class="flaticon-map-location"></i>
                                        </span>
                        <h3 class="m-portlet__head-text">
                            Calendar
                        </h3>
                    </div>
                </div>
                <?php  if(!empty($hosts) && Yii::app()->user->role == "admin") { ?>
                    <div class="m-portlet__head-tools">
                        <label style="margin-top:4%;margin-right:1%;font-weight:500;">Event Host</label>
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                                <a href="javascript:void(0);" class="m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">
                                    <?php if(isset($selectedhost)){ echo $selectedhost; }else { echo "All"; } ?>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 36px;"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    <li class="m-nav__item">
                                                        <a href="<?php echo Yii::app()->createUrl("/admin/events/calendarview"); ?>" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                            <span class="m-nav__link-text">
                                                            All
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <?php foreach ($hosts as $key=>$value) {
                                                        if($value['first_name']){?>
                                                            <?php $url =  Yii::app()->createUrl("/admin/events/eventhosts/")."/".$value['event_host']; ?>
                                                            <li class="m-nav__item">
                                                                <a href="<?php echo $url; ?>" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                    <span class="m-nav__link-text">
                                                                <?php echo $value['first_name']; ?>
                                                            </span>
                                                                </a>
                                                            </li>
                                                        <?php }
                                                    } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
            <div class="m-portlet__body">
                <?php
                $sql = "SELECT * FROM events";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($result)){ ?>
                    <!--Begin Calendar view-->
                    <?php $this->widget('ext.fullcalendar.EFullCalendarHeart', array(
                        //'themeCssFile'=>'cupertino/jquery-ui.min.css',
                        'options' => array(
                            'eventStartEditable' => false,
                            'header' => array(
                                'right' => 'prev,next,today',
                                'center' => 'title',
                                'left' => 'month,agendaWeek,agendaDay,listWeek'
                            ),
                            'events' => $events,
                            'eventClick' => 'js:function(event){
                           window.location.href = "eventview/" + event.id;
                            if (!$("#DeletePanel").hasClass("hide")) {
                                
                            }else{ 
                                
                                $("#updateLabel").addClass("hide");
                                $("#updateform").removeClass("hide");
                                
                                $("#update_title").attr("value", event.title);
                                $("#update_start").attr("value", event.start.format("YYYY-MM-DD"));
                                $("#update_end").attr("value",  event.end.format("YYYY-MM-DD"));
                                $("#update_user_list").attr("value",  event.end.format("YYYY-MM-DD"));
                            }
                        }',
                            'eventDrop' => 'js:function(event, delta, revertFunc) {
                                        var startDate = event.start.format("YYYY-MM-DD"),
                                        endDate = event.start.format("YYYY-MM-DD"),
                                        params = {"from":startDate, "to":endDate, "title":event.title};
                                            if (confirm("Are you sure about this change?")) {
                                                $.ajax({
                                                    url: "UpdateEvent",
                                                    type: "POST",
                                                    data: params,
                                                    success: function (response) {
                                                        
                                                    },
                                                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                    
                                                    }
                                                });
                                            }else{
                                                revertFunc();
                                            }
                        }',
                            'startEditable' => true,
                            'dayClick' => "js:function(date, allDay, jsEvent, view) {
                            $(\"#AddPanel\").removeClass(\"hide\");
                            var cur_time = new Date();
                            var check = date.format();
                            var today = moment(cur_time).format(\"YYYY-MM-DD\");
                            
                            if(check >= today){
                                var currentTime = new Date($.now()); 
                                var retVal = formatAMPM(currentTime);
                                //console.info(retVal); return false;
                                //var cur_time = currentTime.getHours() +':'+ (currentTime.getMinutes()<10?'0':'') + currentTime.getMinutes() +':'+ (currentTime.getSeconds()<10?'0':'') + currentTime.getSeconds();
                                $('#leftPanel').slideDown('slow');
                                $('#event_title').val('');
                                $('#event_start').val(date.format('MM/DD/YYYY') + ' ' + retVal );
                                $('#event_end').val('');
                                $('#user_list').val('');
                            }
                        }",
                            'defaultDate' =>  date('Y-m-d'),
                            'navLinks' => true, // can click day/week names to navigate views
                            'editable' => true,
                            'eventLimit' => true, // allow "more" link when too many events
                            'defaultView' => 'month',
                            'selectable' => true,   //permite sa selectezi mai multe zile
                            'selectHelper' => true,  //coloreaza selctia ta
                        )));

                    ?>
                    <!--End calender view-->
                <?php } else { ?>
                    <div align="center" style="margin-bottom: 20px;margin-top:  20px;">
                        <img src="<?php echo Yii::app()->baseUrl."/plugins/img/calendar.png"; ?>" height="20%" width="10%"><br /><br />
                        <h2 lang="en">No events</h2>
                        <p></p>
                        <?php
                        if(ServiceProvider::model()->findByAttributes(array()) == null){ ?>
                            <span lang="en">You can only create event after creating service provider to create service provider <a href="<?php echo Yii::app()->createUrl("/admin/events/serviceProvider"); ?>" lang="en">Click here.</a></span>
                        <?php }else{
                            echo CHtml::link('Create', array('events/create'), array('class' => 'btn btn-minw btn-square btn-primary','style'=>'width:270px;font-size:18px'));
                        } ?>
                        <br />
                    </div>
                <?php } ?>
            </div>
        </div>
        <!--end::Portlet-->
    </div>
</div>

<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/moment.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/fullcalendar.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/gcal.min.js', CClientScript::POS_END);
?>

<script>
    $('#host').on("change",function () {
        var host = $('#host').val();
        console.info(host);
        var url = "<?php echo Yii::app()->createUrl("/admin/events/eventhosts"); ?>";
        $.ajax({
            url: "<?php echo Yii::app()->createUrl("/admin/events/eventhosts"); ?>",
            type: "POST",
            data: {'event_host': host},
            success: function (response) {
                window.location.href = url;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    });
</script>
