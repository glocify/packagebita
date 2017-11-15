<!DOCTYPE html>
<?php 
$base_url = Yii::app()->getBaseUrl(true);
?>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 2.3.1
Version: 1.2.4
Author: KeenThemes
Website: http://www.keenthemes.com/preview/?theme=metronic
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469
-->
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>Admin Dashboard</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <!-- BEGIN GLOBAL MANDATORY STYLES -->
   <link href="<?php echo $base_url;?>/css/admin/bootstrap.min.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/font-awesome.min.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/style-metro.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/style.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/style-responsive.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
   <link href="<?php echo $base_url;?>/css/admin/uniform.default.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/chosen.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/search.css" rel="stylesheet" type="text/css"/>
   <!-- END GLOBAL MANDATORY STYLES -->
   <!-- BEGIN PAGE LEVEL STYLES --> 
   <link href="<?php echo $base_url;?>/css/admin/jquery.gritter.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/css/admin/daterangepicker.css" rel="stylesheet" type="text/css" />
   <link href="<?php echo $base_url;?>/assets/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $base_url;?>/assets/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>
   <link href="<?php echo $base_url;?>/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <!-- END PAGE LEVEL STYLES -->
   <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">

   <!-- BEGIN HEADER -->
   <div class="header navbar navbar-inverse navbar-fixed-top">
      <!-- BEGIN TOP NAVIGATION BAR -->
      <div class="navbar-inner">
         <div class="container-fluid">
            <!-- BEGIN LOGO -->
            <a class="brand" href="#">
            <img src="<?php echo $base_url;?>/images/admin/logo.png1223243" alt="logo" />
            </a>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
            <img src="<?php echo $base_url;?>/images/admin/menu-toggler.png" alt="" />
            </a>          
            <!-- END RESPONSIVE MENU TOGGLER -->            
            <!-- BEGIN TOP NAVIGATION MENU -->              
            <ul class="nav pull-right">
               <!-- BEGIN NOTIFICATION DROPDOWN -->   		   
               <li class="dropdown" id="header_inbox_bar">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="icon-envelope-alt"></i>
					<span class="badge" id="adsnotify">0</span>
                  </a>
                  <ul class="dropdown-menu extended inbox"></ul>
				  <!-- <ul class="dropdown-menu extended inbox">
                     <li>
                        <p>You have 12 new messages</p>
                     </li>
                     <li>
                        <a href="inbox.html?a=view">
                        <span class="photo"><img src="<?php echo $base_url;?>/images/admin/avatar2.jpg" alt="" /></span>
                        <span class="subject">
                        <span class="from">Lisa Wong</span>
                        <span class="time">Just Now</span>
                        </span>
                        <span class="message">
                        Vivamus sed auctor nibh congue nibh. auctor nibh
                        auctor nibh...
                        </span>  
                        </a>
                     </li>
                     <li class="external">
                        <a href="<?php echo $base_url;?>/Admin/Unapproved_ads">See all<i class="m-icon-swapright"></i></a>
                     </li>
                  </ul> --->
               </li>
               <!-- END NOTIFICATION DROPDOWN -->
               <!-- BEGIN INBOX DROPDOWN -->
              
               <!-- END INBOX DROPDOWN -->
               <!-- BEGIN TODO DROPDOWN -->
               
               <!-- END TODO DROPDOWN -->
               <!-- BEGIN USER LOGIN DROPDOWN -->
               <li class="dropdown user">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img alt="" src="<?php echo $base_url;?>/images/admin/avatar1_small.jpg" />
                  <span class="username"><?php echo Yii::app()->session['user_email']; ?></span>
                  <i class="icon-angle-down"></i>
                  </a>
                  <ul class="dropdown-menu">
                     <li><a href="<?php echo $base_url;?>/User/myprofile"><i class="icon-user"></i> My Profile</a></li>
                     
                     <li class="divider"></li>
                     <li><a href="<?php echo $base_url;?>/Admin/logout"><i class="icon-key"></i> Log Out</a></li>
                  </ul>
               </li>
               <!-- END USER LOGIN DROPDOWN -->
            </ul>
            <!-- END TOP NAVIGATION MENU --> 
         </div>
      </div>
      <!-- END TOP NAVIGATION BAR -->
   </div>
   <!-- END HEADER -->
<!-- BEGIN CONTAINER -->
   <div class="page-container">
