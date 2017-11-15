<?php 
$base_url = Yii::app()->getBaseUrl(true);
?>
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
						Update Package			
				</h3>
				
				<ul class="breadcrumb">
					<li>
						<i class="icon-home"></i>
							<a href="<?php echo $base_url;?>/User/dashboard">Home</a> 
											
								<i class="icon-angle-right"></i>
						</li>
						<li>
							<a href="#">Update Package</a>
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
							<!-- BEGIN EXAMPLE TABLE PORTLET-->
                  <div class="portlet box blue">
                     <div class="portlet-title">
                        <div class="caption">Update Package</div>
                     </div>
					 
                     <div class="portlet-body form_section_aadpage aadpage_user_up">
                        					
                        <?php //echo($userdata['UserMeta']['firstname']); ?>
						<div class="portlet-body">
              <div class="row-fluid">
                <div class="span12">
                  <form class="form-horizontal" id="addpageformId" action="<?php echo $base_url;?>/Package/updatepackage/<?php echo($packageData['id']); ?>" enctype="multipart/form-data" method="post">
				  
                  <input class="span9" id="id" name="id" type="hidden" value="<?php echo($packageData['id']); ?>"/>
				  
					<div class="form-row row-fluid">
						<div class="span12">
							<div class="row-fluid">
								<span id="errormsgpage"></span>
							</div>
						</div>
					</div> 
                    <div class="span6">
					
                        <div class="control-group">
                          <label class="control-label">Name <span>*</span></label>
                          <div class="controls">
                            <input type="text" name="name" value="<?php if(isset($packageData)){ echo  $packageData['name']; } ?>" placeholder="First Name" class="m-wrap span12" required />
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label">Description <span>*</span></label>
                          <div class="controls">
							<textarea  name="description" placeholder="Something about package" class="m-wrap span12" required ><?php if(isset($packageData)){ echo  $packageData['description']; } ?>" </textarea>
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label">Price</label>
                          <div class="controls">
							<div id="editor1_div"></div>
							<input type="text" value="<?php if(isset($packageData)){ echo  $packageData['price']; } ?>"  name="price" placeholder="e.g(10.00)" pattern="^\d{0,8}(\.\d{1,4})?$"  class="m-wrap span12"  required />
                          </div>
                        </div>
						<div class="control-group">
                          <label class="control-label">How Many Credits for this package</label>
                          <div class="controls">
							<div id="editor1_div"></div>
							<input type="text" value="<?php if(isset($packageData)){ echo  $packageData['click']; } ?>"  name="clicks" placeholder="e.g(50)" pattern="[0-9]*" maxlength="6" class="m-wrap span12"  required />
                          </div>
                        </div>

						<div class="control-group">
							<label class="control-label">Package Image <span>*</span></label>
								<div class="controls">
								<div id="filterImgId" class="belo-img-restadmin">
								<ul>
									<?php if(!empty($packageData['image'])){ ?>	
									<li class="upimage" > <img src="<?php echo $base_url;?>/images/packagesimage/<?php echo $packageData['image'];?>" /></li>
									<?php } ?>
								</ul>
							</div>
								<input type="file" id="iconid" name="packageimage" class="m-wrap span12"/>
							  </div>
						</div>
						<div class="control-group">
							<label class="control-label" for="status">Status: </label>
							<div class="controls">
								<select class="form-control" name="status" required="">
									<option <?php if(isset($packageData)){ if($packageData['status'] == 1) echo 'selected' ; } ?>  value="1">Activate </option>
									<option <?php if(isset($packageData)){ if($packageData['status'] == 0) echo 'selected' ; } ?> value="0" >Deactivate</option>
								</select>
							</div>
						</div>
						   <div class="control-group restsubarea">
                      <div class="controls span6">
                        <input class="btn green big btn-block" type="submit" value="Submit" name="update">
                      </div>
					
                      <div class="controls span6"> <a href="<?php echo $base_url;?>/Package/packagelist" class="btn red big btn-block">Cancel</a> </div>
                  </div>
                        </div>
                      </div>
                      <!--span6--></div>
                    <!--ad-usr-first-->
                    <!--<div class="span6">
                      <div class="ad-usr-secnd">
						  
						  	<div class="control-group">
								<div class="controls">
									<button class="btn green" type="submit" name="update">Update</button>
									<button class="btn" type="reset" name="cancel">Cancel</button>
								</div>
							</div>
                          
                        </div>
                      </div>-->
                      <!--ad-usr-secnd--></div>
					
                  </form>
                </div>
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