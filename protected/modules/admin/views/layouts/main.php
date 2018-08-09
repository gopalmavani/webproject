<?php /* @var $this Controller */
Yii::import('application.components.NavCheck');
$theme =  Test::GetTheme();
$menu = array();
$menus = NavCheck::MenuItems();
$notifications = NotificationHelper::ShowNotitication();

$path = Yii::app()->params['basePath']."/".Yii::app()->params['applicationName']."/";
if (isset($_COOKIE['firsttime'])) {
    $firsttimevalue = "no";
}else{
    if(Yii::app()->controller->id == "home"){
        setcookie("firsttime", "no", time()+315360000,"'$path'");
        $firsttimevalue = "yes";
    }
    else{
        $firsttimevalue = "no";
    }
}


foreach ($menus as $key => $value){
    $childMenu = [];
    $child = CylTables::model()->findAll(
        array(
            'condition'=>'display_status ="1" AND parent_item ='.$value->table_id,
            'order'=>'menu_order'
        ));
    if($child){
        foreach ($child as $k => $val) {
            $grandchild = CylTables::model()->findAll(
                array(
                    'condition'=>'display_status ="1" AND parent_item ='.$val->table_id,
                    'order'=>'menu_order'
                ));
            if ($grandchild) {
                foreach ($grandchild as $v) {
                    $controllerNameGrandChild = strtolower(str_replace('_', ' ', $v->table_name));
                    $controllerGrandChild = str_replace(' ', '', $controllerNameGrandChild);
                    $grandchildMenu[] = array('label' => '<i class="' . $v->menu_icon . '"><span></span></i> <span class="m-menu__link-text"> ' . $v->menu_name . ' </span>', 'url' => array('' . $v->action . ''),'active'=>isItemActive($this->route, $v->action));
                }
                $childMenu[] = array('label' => '<i class="'.$val->menu_icon.'"><span></span></i> <span class="m-menu__link-text"> '.$val->menu_name.' </span><i class="m-menu__ver-arrow la la-angle-right"></i>', 'linkOptions' => ['class' => 'm-menu__link m-menu__toggle', 'data-toggle' => "nav-submenu"], 'url' => 'javascript::void(0);','itemOptions'=>array('class' => 'm-menu__item m-menu__item--submenu','m-menu-submenu-toggle' => 'hover','m-menu-link-redirect' => "1"), 'items' => $grandchildMenu);
            }else{
                $controllerNameChild = strtolower(str_replace('_', ' ', $val->table_name));
                $controllerChild = str_replace(' ', '', $controllerNameChild);
                $childMenu[] = array('label' => '<i class="' . $val->menu_icon . '"><span></span></i> <span class="m-menu__link-text"> ' . $val->menu_name . ' </span>', 'linkOptions' => ['class' => 'm-menu__link'], 'url' => array('' . $val->action . ''),'active'=>isItemActive($this->route,$val->action),'itemOptions'=>array('class' => 'm-menu__item','m-menu-link-redirect'=>"1"));
            }
        }
        $menu[] = array('label' => '<i class="'.$value->menu_icon.'"></i> <span class="m-menu__link-text"> '.$value->menu_name.' </span><i class="m-menu__ver-arrow la la-angle-right"></i>', 'linkOptions' => ['class' => 'm-menu__link m-menu__toggle'], 'url' => 'javascript::void(0);','itemOptions'=>array('class' => 'm-menu__item m-menu__item--submenu','m-menu-submenu-toggle' => 'hover','m-menu-link-redirect' => "1"),'linkLabelWrapper'=>'div','submenuOptions' => array('class' => 'm-menu__subnav') , 'items' => $childMenu);
    }else {
        $controllerName = strtolower(str_replace('_', ' ', $value->table_name));
        $controller = str_replace(' ', '', $controllerName);
        $menu[] = array('label' => '<i class="' . $value->menu_icon . '"></i> <span class="m-menu__link-text"> ' . $value->menu_name . ' </span>', 'linkOptions' => ['class' => 'm-menu__link'], 'url' => array('' . $value->action . ''),'active'=>isItemActive($this->route,$value->action),'itemOptions'=>array('class' => 'm-menu__item'));
    }
}
$todaydate = strtotime(date('Y-m-d H:i:s'));
$folder = Yii::app()->params['basePath'];
if (strpos($folder, 'cyclone') !== false) {
    if(isset(Yii::app()->user->username)){
        $email = Yii::app()->user->username;
        $useridsql = "SELECT id from cyclone_leads where email = "."'$email'";
        $userid = Yii::app()->sitedb->createCommand($useridsql)->queryAll();
        if(!empty($userid)){
            $id = $userid[0]['id'];
            $expdatesql = "SELECT expiring_at from app_management where user_id = "."'$id'";
            $expdateresult = Yii::app()->sitedb->createCommand($expdatesql)->queryAll();
            $expdate = strtotime($expdateresult[0]['expiring_at']);
            $today = strtotime(date('Y-m-d H:i:s'));
            $diff  = (int)($expdate - $today)/(60*60*24);
            $diff = ceil($diff);
            if($diff == 0 || $diff < 0 ){
                $this->redirect(Yii::app()->createUrl('home/logout'));
            }
        }
    }
}

?>
<!DOCTYPE html>
<!--
   Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
   Author: KeenThemes
   Website: http://www.keenthemes.com/
   Contact: support@keenthemes.com
   Follow: www.twitter.com/keenthemes
   Dribbble: www.dribbble.com/keenthemes
   Like: www.facebook.com/keenthemes
   Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
   Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
   License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
   -->
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" type="image/png" href="<?php echo Yii::app()->baseUrl."/plugins//imgs/favicon.png"; ?>" />
    <title>Cryptotrain</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <?php
    Yii::app()->clientScript->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/custom.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/bootstrap.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/select2/select2.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/css/jquery.treegrid.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/style.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/fullcalendar.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/slick/slick.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/font-awesome-4.7.0/css/font-awesome.min.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/fontawesome-iconpicker.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/nested-menu/jqtree.css');
    /*Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-tour/build/css/bootstrap-tour.min.css');*/
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/plugins/js/plugins/jQuery-Impromptu-master/src/jquery-impromptu.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/fullcalendar/fullcalendar.bundle.css');
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/base/vendors.bundle.css');
    /*    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/demo7/base/style.bundle.css');*/
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/style.bundle.css');

    ?>
    <!--end::Base Styles -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="shortcut icon" href="assets/demo/default/media/img/logo/favicon.ico" />
    <style>
        .dataTables_length{
            margin-right:2%;
            margin-left:1%;
        }
        .custom-table-head{
            background-color: #ef963f;
            font-size:14px;
            color:#fff;
        }
        .dataTables_wrapper{
            padding:1%;
        }
    </style>
</head>
<!-- end::Head -->
<!-- end::Body -->
<body  class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <!-- BEGIN: Header -->
    <header id="m_header" class="m-grid__item    m-header "  m-minimize-offset="200" m-minimize-mobile-offset="200" >
        <div class="m-container m-container--fluid m-container--full-height">
            <div class="m-stack m-stack--ver m-stack--desktop">
                <!-- BEGIN: Brand -->
                <div class="m-stack__item m-brand  m-brand--skin-dark ">
                    <div class="m-stack m-stack--ver m-stack--general">
                        <div class="m-stack__item m-stack__item--middle m-brand__logo">
                            <a href="<?= Yii::app()->createUrl('admin/home/index'); ?>" class="m-brand__logo">
                                <img alt="Logo" src="<?php echo Yii::app()->baseUrl."/plugins/imgs/logo-white.png"; ?>" style="width: 100px;height: 70px;"/>
                            </a>
                        </div>
                        <div class="m-stack__item m-stack__item--middle m-brand__tools">
                            <!-- BEGIN: Left Aside Minimize Toggle -->
                            <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block
                              ">
                                <span></span>
                            </a>
                            <!-- END -->
                            <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                            <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                                <span></span>
                            </a>
                            <!-- END -->
                            <!-- BEGIN: Responsive Header Menu Toggler -->
                            <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                                <span></span>
                            </a>
                            <!-- END -->
                            <!-- BEGIN: Topbar Toggler -->
                            <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                                <i class="flaticon-more"></i>
                            </a>
                            <!-- BEGIN: Topbar Toggler -->
                        </div>
                    </div>
                </div>
                <!-- END: Brand -->
                <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                    <!-- BEGIN: Horizontal Menu -->
                    <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
                        <i class="la la-close"></i>
                    </button>
                    <!-- END: Horizontal Menu -->								<!-- BEGIN: Topbar -->
                    <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                        <div class="m-stack__item m-topbar__nav-wrapper">
                            <ul class="m-topbar__nav m-nav m-nav--inline">
                                <li class="m-nav__item m-topbar__notifications m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center 	m-dropdown--mobile-full-width" m-dropdown-toggle="click" m-dropdown-persistent="1">
                                    <a href="#" class="m-nav__link m-dropdown__toggle">
                                        <span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger hide" id="dot"></span>
                                        <span class="m-nav__link-icon" id="notiicon">
                                 <span class="m-nav__link-icon-wrapper">
                                 <i class="flaticon-music-2"></i>
                                 </span>
                                 </span>
                                    </a>
                                    <div class="m-dropdown__wrapper" style="margin-left:-660%">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__header m--align-center">
                                          <span class="m-dropdown__header-title" id="noticount">
                                          0
                                          </span>
                                                <span class="m-dropdown__header-subtitle">
                                          New Notifications
                                          </span>
                                            </div>
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">
                                                        <li class="nav-item m-tabs__item">
                                                            <a class="nav-link m-tabs__link active" data-toggle="tab" href="#topbar_notifications_notifications" role="tab">
                                                                Alerts
                                                            </a>
                                                        </li>
                                                        <li class="nav-item m-tabs__item">
                                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_events" role="tab">
                                                                Events
                                                            </a>
                                                        </li>
                                                        <!--<li class="nav-item m-tabs__item">
                                                           <a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_logs" role="tab">
                                                               Logs
                                                           </a>
                                                           </li>-->
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="topbar_notifications_notifications" role="tabpanel">
                                                            <div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
                                                                <div class="m-list-timeline m-list-timeline--skin-light">
                                                                    <div class="m-list-timeline__items" id="alert">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
                                                            <div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
                                                                <div class="m-list-timeline--skin-light">
                                                                    <div class="m-list-timeline__items" id="event">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--<div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
                                                           <div class="m-stack m-stack--ver m-stack--general" style="min-height: 180px;">
                                                               <div class="m-stack__item m-stack__item--center m-stack__item--middle">
                                                           <span class="">
                                                           All caught up!
                                                           <br>
                                                           No new logs.
                                                           </span>
                                                               </div>
                                                           </div>
                                                           </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="m-nav__item m-topbar__user-profile  m-dropdown m-dropdown--medium m-dropdown--arrow  m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" m-dropdown-toggle="click">
                                    <a href="#" class="m-nav__link m-dropdown__toggle">
                                 <span class="m-topbar__userpic m--hide">
                                 <img src="" class="m--img-rounded m--marginless m--img-centered" alt=""/>
                                 </span>
                                        <span class="m-nav__link-icon m-topbar__usericon">
                                 <span class="m-nav__link-icon-wrapper">
                                 <i class="flaticon-user-ok"></i>
                                 </span>
                                 </span>
                                        <span class="m-topbar__username m--hide">
                                 Nick
                                 </span>
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__header m--align-center">
                                                <div class="m-card-user m-card-user--skin-light">
                                                    <?php
                                                    $sql = "SELECT first_name,email,image from user_info where user_id = ".Yii::app()->user->id;
                                                    $result = Yii::app()->db->createCommand($sql)->queryAll();
                                                    ?>
                                                    <!--<div class="m-card-user__pic">
                                                        <?php
                                                    /*                                                        if(isset($result[0]['image'])){
                                                                                                                */?>
                                                            <img src="<?/*= Yii::app()->baseUrl.$result[0]['image']; */?>" class="m--img-rounded m--marginless" alt=""/>
                                                        <?php /*} */?>
                                                    </div>-->
                                                    <div class="m-card-user__details">
                                                <span class="m-card-user__name m--font-weight-500">
                                                <?= $result[0]['first_name']; ?>
                                                </span>
                                                        <a href="" class="m-card-user__email m--font-weight-300 m-link">
                                                            <?= $result[0]['email']; ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav m-nav--skin-light">
                                                        <li class="m-nav__section m--hide">
                                                   <span class="m-nav__section-text">
                                                   Section
                                                   </span>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="<?= Yii::app()->createUrl("/admin/userInfo/view")."/".Yii::app()->user->id; ?>" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                                <span class="m-nav__link-title">
                                                         <span class="m-nav__link-wrap">
                                                            <span class="m-nav__link-text">
                                                            My Profile
                                                            </span>
                                                             <!--<span class="m-nav__link-badge">
                                                                <span class="m-badge m-badge--success">
                                                                    2
                                                                </span>
                                                                </span>-->
                                                         </span>
                                                      </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="<?= Yii::app()->createUrl("/home/index"); ?>" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-paper-plane"></i>
                                                                <span class="m-nav__link-text">
                                                   Go to frontend
                                                   </span>
                                                            </a>
                                                        </li>
                                                        <!--<li class="m-nav__item">
                                                           <a href="javascript:void(0);" class="m-nav__link">
                                                               <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                               <span class="m-nav__link-text">
                                                           Messages
                                                           </span>
                                                           </a>
                                                           </li>-->
                                                        <!--<li class="m-nav__separator m-nav__separator--fit"></li>-->
                                                        <!--<li class="m-nav__item">
                                                           <a href="javascript:void(0);" class="m-nav__link">
                                                               <i class="m-nav__link-icon flaticon-info"></i>
                                                               <span class="m-nav__link-text">
                                                           FAQ
                                                           </span>
                                                           </a>
                                                           </li>-->
                                                        <!--<li class="m-nav__item">
                                                           <a href="javascript:void(0);" class="m-nav__link">
                                                               <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                               <span class="m-nav__link-text">
                                                           Support
                                                           </span>
                                                           </a>
                                                           </li>-->
                                                        <li class="m-nav__separator m-nav__separator--fit"></li>
                                                        <li class="m-nav__item">
                                                            <a href="<?php echo Yii::app()->createUrl('home/logout'); ?>" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                                Logout
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- END: Topbar -->
                </div>
            </div>
        </div>
    </header>
    <!-- END: Header -->
    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        <!-- BEGIN: Left Aside -->
        <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
            <i class="la la-close"></i>
        </button>
        <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
            <!-- BEGIN: Aside Menu -->
            <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="0" m-menu-dropdown-timeout="500">
                <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow hide" id="menu">
                    <li class="m-menu__item" aria-haspopup="true" >
                        <a  href="<?= Yii::app()->createUrl('admin/home/index'); ?>" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-dashboard"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                        Dashboard
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
                    <li class="m-menu__section">
                        <h4 class="m-menu__section-text">
                            Modules
                        </h4>
                        <i class="m-menu__section-icon flaticon-more-v3"></i>
                    </li>
                    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  m-menu-link-redirect="1">
                        <a  href="<?= Yii::app()->createUrl('/admin/userInfo/admin'); ?>" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-users"></i>
                            <span class="m-menu__link-text">
                                Customers
                            </span>
                        </a>
                    </li>
                    <li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-calendar"></i>
                            <span class="m-menu__link-text">
                                    Bookings
                                </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl("/admin/booking/admin"); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> All Bookings </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl("/admin/booking/calendarview"); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Calendar Schedule </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-event-calendar-symbol"></i>
                            <span class="m-menu__link-text"> Events </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl("/admin/events/view"); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Events </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl("/admin/events/calendarview"); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Events Calendar View </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-share"></i>
                            <span class="m-menu__link-text"> Content </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl('/admin/Sliders/admin'); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Slider </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl('/admin/testimonial/admin'); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Testimonial </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="m-menu__section">
                        <h4 class="m-menu__section-text">
                            Ecommerce
                        </h4>
                        <i class="m-menu__section-icon flaticon-more-v3"></i>
                    </li>
                    <li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-cart"></i>
                            <span class="m-menu__link-text"> Products </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl("/admin/productInfo/admin"); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Product </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl("/admin/categories/admin"); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Products Categories </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-open-box"></i>
                            <span class="m-menu__link-text"> Orders </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl("/admin/orderInfo/admin"); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Orders </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl("/admin/orderCreditMemo/admin"); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Order Credit Memo </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl('/admin/subscription/admin') ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Subscription </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl('/admin/orderInfo/settings') ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Settings </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!--<li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-coins"></i>
                            <span class="m-menu__link-text"> Wallets </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?/*= Yii::app()->createUrl('/admin/wallet/userbalance') */?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> User balance </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?/*= Yii::app()->createUrl('/admin/wallet/admin') */?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> All Transactions </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?/*= Yii::app()->createUrl('/admin/walletTypeEntity/admin') */?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Wallet Settings </span>
                                </a>
                            </li>
                        </ul>
                    </li>-->
                    <li class="m-menu__section">
                        <h4 class="m-menu__section-text">
                            MANAGEMENT
                        </h4>
                        <i class="m-menu__section-icon flaticon-more-v3"></i>
                    </li>
                    <li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-business"></i>
                            <span class="m-menu__link-text"> Business Settings </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl('/admin/events/serviceProviderview'); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Service Provider </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl('/admin/Resources/admin'); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Resources </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-attachment"></i>
                            <span class="m-menu__link-text"> Support Tickets </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl('/admin/ticketing/admin'); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Tickets </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="<?= Yii::app()->createUrl('/admin/ticketing/settings'); ?>">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Settings </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  m-menu-link-redirect="1">
                        <a  href="<?= Yii::app()->createUrl("/admin/notificationManager/view"); ?>" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-settings"></i>
                            <span class="m-menu__link-text">
                                Notification Settings
                            </span>
                        </a>
                    </li>
                    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  m-menu-link-redirect="1">
                        <a  href="<?= Yii::app()->createUrl("/admin/notificationManager/emailView"); ?>" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-email"></i>
                            <span class="m-menu__link-text">
                                Email Settings
                            </span>
                        </a>
                    </li>
                    <li class="m-menu__item m-menu__item--submenu" m-menu-submenu-toggle="hover" m-menu-link-redirect="1">
                        <a class="m-menu__link m-menu__toggle" href="javascript::void(0);">
                            <i class="m-menu__link-icon flaticon-info"></i>
                            <span class="m-menu__link-text"> Help </span>
                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <ul class="m-menu__subnav">
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="#">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Support </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="#">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Blog </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="#">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Documentation </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="#">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Pricing </span>
                                </a>
                            </li>
                            <li class="m-menu__item" m-menu-link-redirect="1">
                                <a class="m-menu__link" href="#">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                    <span class="m-menu__link-text"> Terms </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- END: Aside Menu -->
        </div>

        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <?php if(isset($_SESSION['delete'])){
                ?>
                <div class="alert alert-success" role="alert" id="autoalert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading text-center"><?php if(isset($_SESSION['delete'])){ echo $_SESSION['delete']; unset($_SESSION['delete']); }; ?></h4>
                </div>
            <?php } ?>
            <?= $content; ?>
        </div>
    </div>
    <!-- end:: Body -->
    <!-- begin::Footer -->
    <footer class="m-grid__item		m-footer ">
        <div class="m-container m-container--fluid m-container--full-height m-page__container">
            <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
                <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                    <div class="col-md-2">
                        <img src="<?php echo Yii::app()->baseUrl."/images/logo.png"; ?>" style="width:80px;height: 22px;margin-top: -7px;" />
                    </div>
                    <div class="col-md-6">
                        <h6>Powered by Cyclone</h6>
                    </div>
                </div>
                <div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
                    <i class="fa fa-code" aria-hidden="true"></i> with <i class="fa fa-heart"></i> by
                    <a class="font-w600" href="http://abptechnologies.com/" target="_blank">ABP Technologies</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- end::Footer -->
</div>
<!-- end:: Page -->
<!-- begin::Scroll Top -->
<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>
<!-- end::Scroll Top -->
<!--begin::Base Scripts -->
<?php
$string = Yii::app()->controller->id.Yii::app()->controller->action->id ;
if ($string != 'orderInfocreate'){
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/css/base/vendors.bundle.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/css/scripts.bundle.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/css/fullcalendar/fullcalendar.bundle.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/css/app/js/dashboard.js', CClientScript::POS_END);
}
?>
<!--end::Page Snippets -->
</body>
<!-- end::Body -->
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<?php
// if ($this->uniqueid != 'admin/events' && Yii::app()->controller->action->id != 'admin'){
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.min.js');
// }
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/ckeditor.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/config.js', CClientScript::POS_HEAD);*/
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/bootstrap.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.slimscroll.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.scrollLock.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.appear.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.countTo.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.placeholder.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/js.cookie.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/app.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/datatables/jquery.dataTables.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/pages/base_tables_datatables.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/jquery.validate.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jquery-validation/additional-methods.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/custom.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/select2/select2.full.min.js', CClientScript::POS_END);
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js', CClientScript::POS_END);*/
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/core/jquery.treegrid.js', CClientScript::POS_END);*/
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/moment.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/fullcalendar.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fullcalendar/gcal.min.js', CClientScript::POS_END);*/
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/fontawesome-iconpicker.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/nested-menu/jquery-nested.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/nested-menu/tree.jquery.js');

//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/bootstrap-tour/build/js/bootstrap-tour.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/plugins/js/plugins/jQuery-Impromptu-master/src/jquery-impromptu.js',CClientScript::POS_END);
?>
<!--Begin not allowed action Modal -->
<div class="modal fade shop-login-modal" id="trialexpired" data-keyboard="false" data-backdrop="static" tabindex="-1" aria-hidden="true" role="dialog" aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Unauthorized action</h5>
            </div>
            <div class="modal-body">
                You are unauthorized to perform this action.
                <p></p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" onclick="history.go(-1)">Go back</a>
            </div>
        </div>
    </div>
</div>
<!--End of not allowed action Modal-->
<!-- Modal -->
<div class="modal fade" id="exportmodal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b>How to use exported application..</b>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </h5>
            </div>
            <div class="modal-body">
                <p>1. Extract the archive and put it in the folder you want.</p>
                <p>2. Find database .sql file in the folder and setup database.</p>
                <p>3. You can login with email and password with which you created application.</p>
                <p>And that's it, go to your domain and login:</p>
                <p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button
            </div>
        </div>
    </div>
</div>
</html>
<script>
    $("#exportbutton").click(function(){
        $('#exportmodal').modal('show');
    });

    $(document).ready(function(){
        var denied = $('#unauth').val();
        console.info(denied);
        if(denied == 'denied'){
            $('#trialexpired').modal('show');
        }
    });
    $(document).ready(function () {
        $(".activeO").parent().parent().addClass('open');
        $(".activeO a").addClass('active');
        $(".active-li a").addClass('active');
    });

    $(document).ready(function(){
        var updated = localStorage.getItem('updatedpassword');
        if(updated == 'yes'){
            $('#myHideEffect').removeClass('hide');
            $('#myHideEffect').fadeOut(4000);
            localStorage.clear();
        }
    });

    $(document).ready(function () {
        $('#autoalert').fadeOut(5500);
    });

</script>
<?php
function isItemActive($route,$id)
{
    if($id == "Roles/admin" || $id == "Modules/index") {
        $id = lcfirst($id);
    }

    $menu = explode("/", $route);

    return $menu[1] . "/" . $menu[2] == $id ? true : false;
}
?>
<script>
    var tourSubmitFunc = function(e,v,m,f){
            if(v === -1){
                $.prompt.prevState();
                return false;
            }
            else if(v === 1){
                $.prompt.nextState();
                return false;
            }
        },

        tourStates = [
            {
                title: '<b>Step 1: Menus</b>',
                html: 'These are your menus. By default you have User management installed, but can add more menus (also called CRUDs).',
                buttons: {Next: 1},
                focus: 0,
                position: { container: '#firststep', x: 240, y:5 , width: 300, arrow: 'lt' },
                submit: tourSubmitFunc
            },
            {
                title: '<b>Step 2: New Menus/Modules</b>',
                html: ' You can create new Menus (CRUDs) and/or install ready-made Modules here.',
                buttons: { Prev: -1, Next: 1 },
                focus: 1,
                position: { container: '#secondstep', x: 240, y: 5, width: 300, arrow: 'lt' },
                submit: tourSubmitFunc
            },
            {
                title: '<b>Step 3: Trial period</b>',
                html: 'You have 14 days to try out Cyclone with limited features, if you want to purchase full version - click Upgrade here!',
                buttons: { Prev: -1,Next: 1 },
                focus: 1,
                position: { container: '#daysleft', x: -50, y:50 , width: 250, arrow: 'tc' },
                submit: tourSubmitFunc
            },
            {
                title: '<b>Step 4: Quicktour</b>',
                html: ' You can trigger this quick tour by clicking on Help inside this dropdown.',
                buttons: { Done: 2 },
                focus: 0,
                position: { container: '#fourthstep', x: -80, y:50 , width: 230, arrow: 'tc' },
                submit: tourSubmitFunc
            }
        ];

    $(document).ready(function(){
        var value = $('#firsttime').val();
        if(value =='yes'){
            $.prompt(tourStates);
            $('.jqimessage').css('margin-top','-20px');
            $('.jqimessage').css('font-size','13px');
        }
    });

    $('#question').on("click",function(){
        $.prompt(tourStates);
        $('.jqimessage').css('margin-top','-20px');
        $('.jqimessage').css('font-size','13px');
    });

    $("#gotoAdmin").click(function () {
        var appname = '<?php echo Yii::app()->params['applicationName']; ?>';
        $.ajax({
            url: "<?php echo Yii::app()->params['siteUrl']; ?>/admin/app/autologin",
            type: "POST",
            data: {'appname':appname},
            beforeSend: function () {
                $(".overlay").removeClass("hide");
            },
            success: function (response) {
                var Result = JSON.parse(response);
                if (Result.token == 1){
                    window.location.href = '<?php echo Yii::app()->params['siteUrl']; ?>/admin/app/admin';
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    });

    $("#view_all").on('click', function () {
        window.location.href = '<?= Yii::app()->createUrl('admin/notificationManager/view');?>';
    });
    $(".notify").on('click',function () {
        var attrId = $(this).attr('id');
        var id = attrId.split('_');
        $.ajax({
            url: "<?= Yii::app()->createUrl('admin/notificationManager/Read')?>"+'/'+id[1],
            type: "POST",
            data:{'id':id},
            success: function (response) {
                var Result = JSON.parse(response);
                if (Result.token == 1){
                    window.location.href = Result.url;
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    });

    $(document).ready(function(){
        $( ".m-menu__subnav" ).wrap(function() {
            return "<div class='m-menu__submenu'></div>";
        });
        $('#menu').removeClass('hide');
    });

    /*datatable responsive solution*/
    $("#m_aside_left_minimize_toggle").on("click",function(){
        $('.dataTable').DataTable().ajax.reload();
    });
</script>