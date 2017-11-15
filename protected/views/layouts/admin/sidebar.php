<?php 
$base_url = Yii::app()->getBaseUrl(true);
//$urls = Yii::app()->request->getUrl();
//$burls = explode("Admin/",$urls);?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar nav-collapse collapse">
  <!-- BEGIN SIDEBAR MENU -->        	
  <ul>
    <li>
      <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
      <div class="sidebar-toggler hidden-phone">
      </div>
      <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
    </li>  	        		
    <li class="start active ">
      <a href="<?php echo $base_url;?>/Admin/dashboard">
        <i class="icon-home">
        </i> 
        <span class="title">Dashboard
        </span>
        <span class="selected">
        </span>
      </a>
    </li>        		
    <li class="">
      <a href="<?php echo $base_url;?>/User/userlist">
        <i class="icon-group">
        </i> 
        <span class="title">Manage Users
        </span>
      </a>
    </li>
	 <li class="">
      <a href="<?php echo $base_url;?>/Package/packagelist">
        <i class="icon-book">
        </i> 
        <span class="title">Manage Packages
        </span>
      </a>
    </li>
	 <li class="">
      <a href="<?php echo $base_url;?>/Admin/orders">
        <i class="icon-book">
        </i> 
        <span class="title">Orders
        </span>
      </a>
    </li>
	<!--<<li class="">
      <a href="<?php //echo $base_url;?>/Admin/PostAds">
        <i class="icon-briefcase">
        </i> 
        <span class="title">Ads Managment
        </span>
      </a>
    </li>
	
	li  class="">
		<a class="active" href="javascript:;" onclick="toggleContent1()">
			<i class="icon-briefcase"></i> 
			<span class="title">Ads Managment</span>
			<span id="arrowicon1" class="arrow ">
			</span>
		</a>
		<ul class="sub-menu" id="content1">
			<li>
				<a href="<?php// echo $base_url;?>/Admin/PostAds">Approved Ads</a>
			</li>
			<li>
				<a href="<?php //echo $base_url;?>/Admin/Unapproved_ads">Unapproved Ads</a>
			</li>
		</ul>
    </li>
	
	
	
	
	
	<li  class="">
	  <a class="active" href="javascript:;" onclick="toggleContent()">
		<i class="icon-briefcase">
		</i> 
		<span class="title">Categories & Subcategories</span>
		<span id="arrowicon" class="arrow ">
		</span>
	  </a>
	  <ul class="sub-menu" id="content">
		<li>
			<a href="<?php //echo $base_url;?>/Admin/categories"> Jobs Categories</a>
		</li>
		<li>
			<a href="<?php //echo $base_url;?>/Admin/subcategories">Jobs Subcategories</a>
		</li>
	</ul>
    </li>
 <li class="">
      <a href="<?php //echo $base_url;?>/Admin/locations">
        <i class="icon-map-marker">
        </i> 
        <span class="title">Manage Locations
        </span>
      </a>
    </li>  <li class="">
      <a href="<?php //echo $base_url;?>/Page/pagelist">
        <i class="icon-book">
        </i> 
        <span class="title">Manage Pages
        </span>
      </a>
    </li>
	
	
	<!--<li class="">
      <a href="<?php ///echo $base_url;?>/Page/managefields">
        <i class="icon-book">
        </i> 
        <span class="title">Manage Ad Form Fields
        </span>
      </a>
    </li>
	
	
	
    <li class="">
      <a href="<?php// echo $base_url;?>/Faq/faqlist">
        <i class="icon-book">
        </i> 
        <span class="title">FAQ
        </span>
      </a>
    </li>
	<li class="">
      <a href="<?php //echo $base_url;?>/Admin/messages">
        <i class="icon-group">
        </i> 
        <span class="title">Jobs Management</span>
      </a>
    </li>
  </ul>-->
  <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
