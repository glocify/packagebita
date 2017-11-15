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

						Edit Credit			

				</h3>

				

				<ul class="breadcrumb">

					<li>

						<i class="icon-home"></i>

							<a href="<?php echo $base_url;?>/User/dashboard">Home</a> 

											

								<i class="icon-angle-right"></i>

						</li>

						<li>

							<a href="#">Edit Credit</a>

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

                  <div class="portlet box blue">

                     <div class="portlet-title">

                        <div class="caption">Edit Credit</div>

                     </div>

					 

                     <div class="portlet-body form_section_aadpage aadpage_user_up">

                        					

                        <?php //echo($userdata['UserMeta']['firstname']); ?>

						<div class="portlet-body">

              <div class="row-fluid">

                <div class="span12">
                 
                  <form class="form-horizontal" id="addpageformId" action="<?php echo $base_url;?>/User/updatecredit/<?php echo $userdata; ?>" enctype="multipart/form-data" method="post" name="creditadd">

				  

                  <input class="span9" id="id" name="id" type="hidden" value="<?php echo $userdata; ?>"/>

				  

					<div class="form-row row-fluid">

						<div class="span12">

							<div class="row-fluid">

								<span id="errormsgpage"></span>

							</div>

						</div>

					</div> 

                    <div class="span6">

					

                        <div class="control-group">

                          <label class="control-label">Credit <span>*</span></label>

                          <div class="controls">

                            <input type="text" name="clicks" placeholder="Credit" class="m-wrap span12"  required value="<?php echo($click_no['clicks']); ?>"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>

                          </div>

                        </div>

                        

                                               

                        <div class="control-group">

                          

						   <div class="control-group restsubarea">

                      <div class="controls span6">

                        <input class="btn green big btn-block" type="submit" value="Submit" name="update">

                      </div>

					

                      <div class="controls span6"> <a href="<?php echo $base_url;?>/User/userlist" class="btn red big btn-block">Cancel</a> </div>

                  </div>

                        </div>

                      </div>

                      <!--span6--></div>

                   
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