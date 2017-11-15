<?php
$base_url = Yii::app()->getBaseUrl(true);
?>
<div class="body-pattren-bg detail-page">
	<div class="container">
    	<div class="row">
            <div class="col-sm-16 col-md-16 account-left">
            	<div class="inner-left my-account">
                	<div class="inner-header"><h3 class="rest-h">Your Profile</h3></div>
						<?php if(Yii::app()->user->hasFlash('success')): ?>
							<div class="flash-success">
								<h4 style="color:red;" ><?php echo Yii::app()->user->getFlash('success'); ?></h4>
							</div>
						<?php endif; ?>
						<?php if(Yii::app()->user->hasFlash('adnotur')):?>
							<div class="flash-success">
								<h2 style="color:red;font-size:15px;margin:10px 0 10px 0;float:left;"><?php echo Yii::app()->user->getFlash('adnotur'); ?></h2>
							</div>
						<?php endif; ?>
					<div class="pending_clicks">Pending Credits : <?php echo Yii::app()->commonFnc->getPendingCounts(Yii::app()->session['userid']); ?></div>
                    <div class="inner-content">
                        	<ul class="nav nav-tabs responsive-tabs">
                                <li class="active"><a class="Settings-bt" href="#settings1">Settings</a></li>
                                  <!--<li><a class="reviews-bt" href="#reviews1"><span></span>Messages</a></li>
                              <li><a class="mytrip-bt" href="#mytrip1"><span></span> My trip</a></li>
                                <li><a class="mylist-bt" href="#mylist1"><span></span> My Posts</a></li>  -->								<li><a class="mylist-bt" href="#myorder1"><span></span> My Orders</a></li>
								<li><a class="mylist-bt" href="#mylist1"><span></span> Packages</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="settings1" class="tab-pane active">
                                	<div class="my-setting">
                                    	<h3>Personal details <span style="text-align:right; color:#0C3; font-style:italic; margin-left:40px;" id="UpdateMsg"></span></h3>
                                        <div class="row">
                                            <div class="col-sm-4 col-md-4 user_up">
                                                <div class="profile-pic">
													<form action="<?php echo $base_url;?>/User/UpdateProfile" method="post" enctype="multipart/form-data">
													<?php if(($userdata['user_image']!='0')){ ?>
														<img src="<?php echo $base_url; ?>/images/userimages/<?php echo $userdata['user_image']; ?>" alt="profile-img" />
													<?php } else{ ?>
                                                    <img src="<?php echo $base_url; ?>/images/img.jpg" alt="profile-img" />
                                                    <?php } ?>
                                                    <p class="text-center"><?php echo $userdata['firstname']." ".$userdata['lastname']; ?></p>
                                                    <input style="margin-bottom:10px;" name="pic" type="file" required="true" >
                                                    <input type="submit" class="btn" value="Upload" />
													</form>
													<br/><br/><br/><br/><a class="btn" href="<?php echo $base_url; ?>/Site/ChangePassword">Change Your Password</a>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 col-md-8 user_upload">
                                            	<div class="table-responsive">
                                                <table class="tablesectnarea">
                                                	<tbody>
                                                    	<tr>
                											<th>First name:</th>
                											<td>
                                                            	<div id="up1"><?php echo $userdata['firstname']; ?></div>
                                                                	<div class="form-group edit_field" id="field1">
                                                                    	<label>Your new name:</label>
                                                                        <input class="form-control" name="p_first_name"  id="p_first_name" type="text" value="<?php echo $userdata['firstname']; ?>">
                                                                        <input class="btn" type="button" value="save" onClick="UpdateProfile(1);">

                                                                 </div>
                                                             </td>
                                                            <!-- <td><a href="#field1" class="btn">Edit</a></td> -->
              											</tr>
              											<tr>
                                                            <th>Last name:</th>
                                                            <td>
                                                            	<div id="up2"><?php echo $userdata['lastname']; ?></div>
                                                                <div class="form-group edit_field">
                                                                	<label>Your new last name:</label>
                                                                	<input type="text" name="p_last_name"  id="p_last_name" class="form-control" value="<?php echo $userdata['lastname']; ?>">
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(2);">

                                                                </div>
                                                            </td>
                                                            <!-- <td><a href="#" class="btn">Edit</a></td> -->
                                                          </tr>
                                                          <tr>
                                                            <th>E-mail address: </th>
                                                            <td>
                                                            	<div id="up3"><?php echo $userdata['login_email']; ?></div>
                                                              	<div class="form-group edit_field" id="field3">
                                                                	<label>Your new email:</label>
                                                                	<input type="text" id="p_email" name="p_email" class="form-control" value="<?php echo $userdata['login_email']; ?>">
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(3);">

                                                                </div>
                                                             </td>
                                                                <!-- <td><a href="#" class="btn">Edit</a></td> -->
                                                          </tr>
                                                          <tr>
                                                            <th>Street Address</th>
                                                            <td>
                                                            	<div id="up4"><?php echo $userdata['address']; ?></div>
                                                              	<div class="form-group edit_field">
                                                                	<label>Your new address:</label>
                                                                	<textarea class="form-control" id="p_address" name="p_address"><?php echo $userdata['address']; ?></textarea>
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(4);">

                                                                </div>
                                                             </td>
                                                               <!-- <td><a href="#" class="btn">Edit</a></td> -->
                                                          </tr>
                                                          <tr>
                                                            	<th>Phone</th>
                                                                <td><div id="up5"><?php echo $userdata['user_contact']; ?> </div>
                                                                <div class="form-group edit_field">
                                                                	<label>Your new phone-no:</label>
                                                                	<input type="text" id="p_phone" name="p_phone" class="form-control" value="<?php echo $userdata['user_contact']; ?>">
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(5);">

                                                                </div>
                                                                </td>

                                                          </tr>
                                                           <tr>
                                                            	<th>Website</th>
                                                                <td><div id="up8"><?php echo $userdata['url']; ?> </div>
                                                                <div class="form-group edit_field">
                                                                	<label>Website:</label>
                                                                	<input type="text" id="p_url" name="p_url" class="form-control" value="<?php echo $userdata['url']; ?>">
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(8);" />
                                                                </div>
                                                                </td>
                                                          </tr>
                                                           <tr>
                                                            	<th>Hourly Rate</th>
                                                                <td><div id="up9">$<?php echo $userdata['Hour_rate']; ?> </div>
                                                                <div class="form-group edit_field">
                                                                	<label>Website:</label>
                                                                	<input type="number" id="p_Hour_rate" name="p_Hour_rate" class="form-control" value="<?php echo $userdata['Hour_rate']; ?>">
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(9);" />
                                                                </div>
                                                                </td>
                                                          </tr>
                                                           <tr>
                                                            	<th>Speciality</th>
                                                                <td><div id="up10"><?php echo $userdata['speciality']; ?> </div>
                                                                <div class="form-group edit_field">
                                                                	<label>Speciality:</label>
                                                                	<input type="text" id="p_speciality" name="p_Hour_rate" class="form-control" value="<?php echo $userdata['speciality']; ?>">
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(10);" />
                                                                </div>
                                                                </td>
                                                          </tr>
                                                          <tr>
                                                            	<th>Position</th>
                                                                <td><div id="up11"><?php echo $userdata['position']; ?> </div>
                                                                <div class="form-group edit_field">
                                                                	<label>Position:</label>
                                                                	<input type="text" id="p_position" name="p_position" class="form-control" value="<?php echo $userdata['position']; ?>">
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(11);" />
                                                                </div>
                                                                </td>
                                                          </tr>
                                                          <tr>
                                                            	<th>Company</th>
                                                                <td><div id="up12"><?php echo $userdata['company']; ?> </div>
                                                                <div class="form-group edit_field">
                                                                	<label>Company:</label>
                                                                	<input type="text" id="p_company" name="p_company" class="form-control" value="<?php echo $userdata['company']; ?>">
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(12);" />
                                                                </div>
                                                                </td>
                                                          </tr>
														   <tr>
                                                            	<th>Offer : </th>
                                                                <td><div id="up6"><?php /*echo $userdata['offer'];*/ ?> </div>
                                                                <div class="form-group edit_field">
                                                                	<label>Your Offer for Clients :</label>
																	<textarea id="p_offer" name="p_offer" class="offerforprofile form-control"><?php echo $userdata['offer']; ?></textarea>
                                                                	<input type="submit" value="save" class="btn" onclick="UpdateProfile(6);">

                                                                </div>
                                                                </td>

                                                          </tr>
                                                        </tbody>
            									</table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                             </div>
                             <div id="reviews1" class="tab-pane">
                                  <div id="NoPagination">
										<div id="hotelPaginationIID">
											<article class="myreviews" style="border-top: medium none;">
												<h1>Your Messages </a></h1>
												<div class="table-responsive">
                                                <table width="100%" border="1" class="tablemsgarea">
													<tr>
														<td>From</td>
														<td>To</td>
														<td>Message</td>
														<td>Date</td>
														<td>Ads ID</td>
													</tr>
													<?php if(!empty($Messages)){
														foreach($Messages as $Message):?>
														<tr id="msg<?php echo $Message['id'];?>">
															<td><?php if(isset($Message["user_name"])){ echo $Message["user_name"]; }?></td>
															<td>You</td>
															<td><?php if(isset($Message["message_text"])){
															$msttext = substr(strip_tags($Message["message_text"],""), 0, 20) . '...';?>
															<a id="msg<?php echo $Message["id"];?>" style="cursor:pointer;" onclick="return showmessage('<?php echo $Message["id"];?>');">
															<?php echo $msttext."</a>"; }?></td>
															<td><?php if(isset($Message["message_date"])){
															$msgdate = new DateTime($Message["message_date"]);
															echo $msgdate->format('M j,Y'); }?></td>
															<td><?php if(isset($Message["ads_id"])){
																echo "#adx".$Message["ads_id"];
															}?></td>
														</tr>
													<?php endforeach;
													} else{ ?>
														<tr>
															<td colspan="5">No Messages Found!</td>
														</tr>
													<?php } ?>
												</table>
												<br/><br/>
                                                </div>
												<div id="openmsg" style="display:none;">
												</div>
											</article>

										</div>
										<div id="status"></div>
										<!--pagination-->
										<div class="pagination">
											<div style="float:left;" id="paginate"></div>
										</div>
										<!--/pagination-->
									</div>
                            	</div>

                                <div id="mytrip1" class="tab-pane">
                              		<h3>Data Awaited...</h3>

                            	</div>

                                <div id="mylist1" class="tab-pane">

									<?php
										
									foreach($Packages as $package){
										
									?>
										<div class="hold-sde1">
										<div class="side_2"><i class="fa fa-usd" aria-hidden="true"></i>
										</div><div class="side_8"><h3><?php echo ucwords($package->name); ?> <span class="cli_k">(Available Credits : <?php echo $package->click;?> )</span></h3><p class="site_p"><?php echo $package->description;?></p><div class="detail"><div class="price"><span class="price_label">Price :</span> $<?php echo $package->price;?></div>

									 <?php if(isset(Yii::app()->session['userid'])){ ?>
										<div class="check_btn ">
											<form name="bpackage" method="post" action="<?php echo $base_url;?>/User/BuyPackage">
												<input type="hidden" value="<?php echo $package->en_key; ?>" name="packageID">
												<input type="submit" name="submit" value= "Buy Package">
											</form>
											<!--<form method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
											<input type="hidden" value="onkar.blissit-facilitator@gmail.com" name="business"> 
											<input type="hidden" value="_xclick" name="cmd">
											<input type="hidden" value="<?php echo $package->name;?>" name="item_name">
											<input type="hidden" value="<?php echo $package->price;?>" name="amount">
											<input type="hidden" name="currency_code" value="USD">
											<input type="hidden" value="<?php echo $package->id;?>" name="item_number">
											<input type="hidden" name="return" value="<?php echo $base_url;?>/site/thankyou">
											<input type="hidden" name="notify_url" value="<?php echo $base_url;?>/site/thankyou">
											<input type="hidden" name="cancel_return" value="<?php echo $base_url;?>/site/thankyou">
											<input type="submit" name="submit" value= "Buy Package">
											<img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
											</form>-->
										</div>
									<?php } else { ?>
										<div class="check_btn"><a href="javascript:void(0);" onclick="loggedcheck();" >Buy Package</a></div>
									<?php }?>
										</div></div>
										<div class="clearfix"></div>
										</div>
									<?php
									}
									?>

                                </div>




                 <div id="myorder1" class="tab-pane">
					<div class="my-list">
						<article class="myreviews" style="border-top: medium none;">
							<h1>Your Orders</a></h1>
								<div id="adsdeltsmsg" style="display:none;"></div>
								<div class="table-responsive">
									<table width="99%" border="1" cellspacing="2" cellpadding="2" class="tableAdsarea">					<tr>
										<td>Order ID</td>
										<td>Payment ID</td>
										<td>Package</td>
										<td>Credits</td>
										<td>Price</td>
										<td>Payment status</td>
										<td>Prchase Date</td>
										<!--<td>Action</td>	-->
									</tr>
									<?php if(!empty($orders)){
									foreach($orders as $order):?>
									<tr id="Ads<?php echo $order['id'];?>">
									<td><?php if(isset($order["id"])){ echo $order["id"]; }?></td>
									<td><?php if(isset($order["payment_refrence_id"])){ echo $order["payment_refrence_id"]; }?></td>
									<td>
										<?php
											if(isset($order["id"])){
												$get_package_detail = Yii::app()->commonFnc->getPackageDetailBasedonOrder($order["id"]);
												echo $get_package_detail['name'];

											}
										?>
									</td>
									<td>
										<?php
										if(isset($order["id"])){
										$get_package_detail = Yii::app()->commonFnc->getPackageDetailBasedonOrder($order["id"]);
										echo $get_package_detail['click'];

										}
										?>
									</td>
									<td>
										<?php
										if(isset($order["order_price"])){
										echo $order['order_price'];

										}
										?>
									</td>
									<td>
										<?php
										if(isset($order["payment_status"]) && $order["payment_status"] =='Completed'){
										echo '<span style="color:green;">Completed</span>';

										}else{
											echo '<span style="color:red;">Pending</span>';
										}
										?>
									</td>
									<td><?php if(isset($order["date_created"])){
									$msgdate = new DateTime($order["date_created"]);
									echo $msgdate->format('M j,Y'); }?></td>

							
								</tr>
									<?php endforeach;												} else{ ?>													<tr>														<td colspan="5">No Ads Found!</td>													</tr>												<?php } ?>											</table>																							<br/><br/>											</div>										</article>                                        <div class="tab-content">                                             <div id="rest-list1" class="tab-pane active">													<div id="rest">														<div id="NoPagination">															<div id="hotelPaginationIID1">															</div>															<div id="status"></div>															<!--pagination-->															<div class="pagination">																<div style="float:left;" id="paginate"></div>															</div>																<!--/pagination-->															</div>														</div>                                            </div>                                        </div>                                     </div>                                                                                                                                    	</div>

                         	</div>


                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

