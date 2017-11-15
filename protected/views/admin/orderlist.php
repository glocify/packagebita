<?php 
$base_url = Yii::app()->getBaseUrl(true);
// echo '<pre>';
// print_r($userlist);
// die();
?>
<!-- BEGIN PAGE -->
      <div class="page-content">
         <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         <div id="portlet-config" class="modal hide">
            <div class="modal-header">
               <button data-dismiss="modal" class="close" type="button"></button>
               <h3>portlet Settings</h3>
            </div>
            <div class="modal-body">
               <p>Here will be a configuration form</p>
            </div>
         </div>
		 
         <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         <!-- BEGIN PAGE CONTAINER-->
		 
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN STYLE CUSTOMIZER -->
                  <div class="color-panel hidden-phone">
                     <div class="color-mode-icons icon-color"></div>
                     <div class="color-mode-icons icon-color-close"></div>
                     <div class="color-mode">
                        <p>THEME COLOR</p>
                        <ul class="inline">
                           <li class="color-black current color-default" data-style="default"></li>
                           <li class="color-blue" data-style="blue"></li>
                           <li class="color-brown" data-style="brown"></li>
                           <li class="color-purple" data-style="purple"></li>
                           <li class="color-white color-light" data-style="light"></li>
                        </ul>
                        <label class="hidden-phone">
                        <input type="checkbox" class="header" checked value="" />
                        <span class="color-mode-label">Fixed Header</span>
                        </label>                   
                     </div>
                  </div>	
				  
				<h3 class="page-title">
						Orders			
				</h3>
				
				<ul class="breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo $base_url;?>/User/dashboard">Home</a> 
											
								<i class="icon-angle-right"></i>
						</li>
						<li>
							<a href="#">Orders</a>
						</li>		
				</ul>
               </div>
			 </div>
			 
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid profile">
			
               <div class="span12">
                  <!--BEGIN TABS-->
                  <div class="tabbable tabbable-custom tabbable-full-width">
                     
					 
                     <div class="tab-content">
                        <div>
							<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<?php if(Yii::app()->user->hasFlash('register')):?>
						<div class="info">
							<h4 style="color:red;"><?php echo Yii::app()->user->getFlash('register'); ?></h4>
						</div>
					<?php endif; ?>
					<?php if(Yii::app()->user->hasFlash('error')):?>
						<div class="info">
							<h4 style="color:red;"><?php echo Yii::app()->user->getFlash('error'); ?></h4>
						</div>
					<?php endif; ?>
                  <div class="portlet box blue">
                     <div class="portlet-title">
                        <div class="caption">Orders</div>
                     </div>
					 
                     <div class="portlet-body">
					
						<div class="clearfix">
						   <div class="btn-group">
							 <!--<a id="sample_editable_1_new" class="btn green" href="<?php //echo $base_url;?>/Package/addpackage">Add New Package<i class="icon-plus"></i></a>-->
						
							<div id="searchcountry" class="serch_countdiv">
								<div class="city_country_div cit_countdiv">
									<input style="width: 500px;" type="text" autocomplete="off" name="user_search" id="user_search"/>
									<input type="hidden" value="" id="user_id" />
								  <div id="category_result"></div>
									
								</div>
								<button type="submit" name="submit" id="usersearch">Search </button>
							</div>
					
						   </div>
						</div>
						
                        <table class="table table-striped table-hover table-bordered cartable" id="sample_editable_1">
                           <thead>
                              <tr>
                                 <th>Order Id</th>
								 <th>Price</th>
                                 <th>Buyer Name</th>
								 <th>Paypal Id</th>
								 <th>Paypal Status</th>
								  <th>Order Status</th>
								 <th>Created Date</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody id="carList">
						   
							<?php foreach($OrderList as $order){ ?>
							  <tr class="odd gradeX">
								<td class="hidden-phone"><?php echo $order->id; ?></td>
								<td class="hidden-phone"><?php if(isset($order->order_price)):  echo $order->order_price; endif;?></td>
								<td class="hidden-phone"><?php if(isset($order->userid)):  echo Yii::app()->commonFnc->getUserName($order->userid); endif;?></td>
								<td class="hidden-phone"><?php if(isset($order->payment_refrence_id)):  echo $order->payment_refrence_id; endif;?></td>
								<td class="hidden-phone"><?php if(isset($order->payment_status)):  echo $order->payment_status; endif;?></td>
								<td class="hidden-phone"><?php if(isset($order->order_status)):  if($order->order_status == true): echo '<span style="color:green;"> Successful</span>'; else: echo '<span style="color:red;">Pending</span>'; endif; endif;?></td>
								
								<td class="hidden-phone"><?php if(isset($order->date_created)):  echo $order->date_created; endif;?></td>
								
								
								<td class='tableActs' style="width:15%"> 
								<a class="btn mini green-stripe" href="<?php echo $base_url;?>/Admin/updateorder/<?php echo $order->id; ?>">Update</a> 
								<a class="btn mini red-stripe" href="<?php echo $base_url;?>/Admin/deleteorder/<?php echo $order->id; ?>" onclick="return confirm('Are you sure about this?');">Delete</span></a>
								</td>
							  </tr>
<?php 
							}
?>
                           </tbody>
                        </table>
						<div id="pagination-div">
						<?php $this->widget('CLinkPager', array('pages' => $pages,)) ?>
						</div>
                     </div>
				
                  </div>
                  <!-- END EXAMPLE TABLE PORTLET-->
  
                        </div>
                        <!--end tab-pane-->
                     </div>
                  </div>
                  <!--END TABS-->
               </div>
            </div>
            <!-- END PAGE CONTENT-->
         </div>
         <!-- END PAGE CONTAINER--> 
      </div>
      <!-- END PAGE -->
