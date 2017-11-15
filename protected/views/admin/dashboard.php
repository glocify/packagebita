<?php 
$base_url = Yii::app()->getBaseUrl(true);
?>
<!-- BEGIN PAGE -->
      <div class="page-content">
         <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         <div id="portlet-config" class="modal hide">
            <div class="modal-header">
               <button data-dismiss="modal" class="close" type="button"></button>
               <h3>Widget Settings</h3>
            </div>
            <div class="modal-body">
               Widget settings form goes here
            </div>
         </div>
		<ul class="breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="<?php echo $base_url;?>/Admin/dashboard">Home</a>
				<i class="icon-angle-right"></i>
			</li>
			<li><a href="#">Dashboard</a></li>
			<li class="pull-right no-text-shadow">
				<div id="dashboard-report-range" class="dashboard-date-range tooltips no-tooltip-on-touch-device responsive" data-tablet="" data-desktop="tooltips" data-placement="top" data-original-title="Change dashboard date range">
					<i class="icon-calendar"></i>
					<span></span>
					<i class="icon-angle-down"></i>
				</div>
			</li>		
		</ul>
         <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         <!-- BEGIN PAGE CONTAINER-->
		 <style>
.serv-box{float:left; border:1px solid #ccc; background:#eee; float:left; width:100%; text-align:center; min-height:150px; margin-top:25px;}
.serv-box:hover{border:1px solid #e02222; cursor:pointer; background:#eee;}
.serv-box h3{float: left;font-size:20px;font-weight:400;vertical-align:middle;width:100%;}
.serv-box h3 a{float:left; display:block;width:100%; line-height:150px; color:#fff;}
.serv-box h3 a:hover{text-decoration:none;color:#e02222;}
.mu-box{background:#00aa30; border:1px solid #008e28;}
.bm-box{background:#e02222; border:1px solid #cc0000;}
.bcf-box{background:#005abf; border:1px solid #0051ac;}
.mp-box{background:#1a1a1a; border:1px solid #000;}
.faq-box{background:#fe7800; border:1px solid #ef7000;}
.ml-box{background:#00a983; border:1px solid #009674;}



		 </style>
		 
         <div class="container-fluid">
			<div class="row-fluid">
				<h3 class="page-title">Dashboard	<small>statistics and more</small></h3>
			</div>
			<div class="row-fluid">
				<div class="span4">
					<div class="mu-box serv-box">
						<h3><a href="#">Manage Users</a></h3>
					</div>
				</div>
				<div class="span4">
					<div class="bcf-box serv-box">
						<h3><a href="<?php echo $base_url;?>/Admin/">Categories</a></h3>
					</div>
				</div>
				<div class="span4">
					<div class="bm-box serv-box">
						<h3><a href="<?php echo $base_url;?>/Admin/">Subcategories</a></h3>
					</div>
				</div>				
			</div>		 
			<div class="row-fluid">
				<div class="span4">
					<div class="ml-box serv-box">
						<h3><a href="<?php echo $base_url;?>/Admin/">Manage Locations </a></h3>
					</div>
				</div>
				<div class="span4">
					<div class="mp-box serv-box">
						<h3><a href="#">Manage Pages</a></h3>
					</div>
				</div>
				<div class="span4">
					<div class="faq-box serv-box">
						<h3><a href="#">FAQ</a></h3>
					</div>
				</div>


			</div>
         <!-- END PAGE CONTAINER-->    
      </div>
      <!-- END PAGE -->
