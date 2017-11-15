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
						User List			
				</h3>
				
				<ul class="breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo $base_url;?>/User/dashboard">Home</a> 
											
								<i class="icon-angle-right"></i>
						</li>
						<li>
							<a href="#">User List</a>
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
                        <div class="caption">User List</div>
                     </div>
					 
                     <div class="portlet-body">
						<div class="clearfix">
						   <div class="btn-group">
							
						
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
                                 <th>First Name</th>
                                 <th>Last Name</th>
								 <th>Email</th>
								 <th>Zip Code</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody id="carList">
						   
							<?php foreach($userlist as $page){ ?>
							  <tr class="odd gradeX">
								<td class="hidden-phone" style="display:none;"><?php echo $page->id; ?></td>
								
								<td class="hidden-phone"><?php if(isset($page->UserMeta->firstname)): echo $page->UserMeta->firstname; endif;?></td>
								<td class="hidden-phone"><?php if(isset($page->UserMeta->lastname)):  echo $page->UserMeta->lastname; endif;?></td>
								<td class="hidden-phone"><?php if(isset($page->login_email)):  echo $page->login_email; endif;?></td>
								<td class="hidden-phone"><?php if(isset($page->UserMeta->zip)):  echo $page->UserMeta->zip; endif;?></td>
								
								
								<td class='tableActs' style="width:15%"> 
								<a class="btn mini green-stripe" href="<?php echo $base_url;?>/User/updatecredit/<?php echo $page->id; ?>">Add Credits</a> 
								<a class="btn mini green-stripe" href="<?php echo $base_url;?>/User/updateuser/<?php echo $page->id; ?>">Update</a> 
								<a class="btn mini red-stripe" href="<?php echo $base_url;?>/User/deleteuser/<?php echo $page->id; ?>" onclick="return confirm('Are you sure about this?');">Delete</span></a>
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
