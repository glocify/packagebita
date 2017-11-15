<?php 

$base_url = Yii::app()->getBaseUrl(true); ?>
<section class="free_ad">
<div class="container">
<div class="row">
<?php if(Yii::app()->user->hasFlash('success')): ?>
	<div class="flash-success">
		<h4 style="color:red;" ><?php echo Yii::app()->user->getFlash('success'); ?></h4>
	</div>	 
<?php endif; ?>
<h1><?php echo $ad['post_title']; ?></h1>
<form action="<?php echo $base_url; ?>/User/EditAdd/<?php echo @$ad['id']; ?>" method="post"  enctype="multipart/form-data" >
<input type="hidden" name="business_hidden_id" id="business_hidden_id" value="<?php echo @$ad['id']; ?>"/>
<div class="post_form">
	<div class="selection">
		<div class="form-group edit_field">
			<label class="control-label">Category Group:</label>	
			<select name="multi_cat[]" multiple="multiple" id="cat_req_update" required class="form-control">
				<option value="">----Select Here----</option>
				<?php foreach($categories as $category){
						$Checkcat=$category['id'];
						if (in_array($Checkcat,$Adsselectedcat)):
							echo "<option value=".$category['id']." selected='selected'>".$category['category_name']."</option>";
						else:
							echo "<option value=".$category['id'].">".$category['category_name']."</option>";
						endif;
					//echo '<option value="'.$category['id'].'">'.$category['category_name'].'</option>';
				}?>	
			</select>
			<!--<label id="categorySelctionMessage">You Can Associate Your Business With 3 Categories.</label>	-->	
		</div>
		<div class="form-group edit_field" id="features_of_category"></div>
	</div>
	<div id="addFormFieldsbasic" class="contact" style="display:none;">
									<div class="col-sm-6 topFormfields" >
										<div class="form-group">
											<label for="title">AD Title</label>
											<input type="text"value="<?= $ad['post_title']; ?>" name="title" class="form-control" id="title" placeholder="">
										</div>
										
										<label for="Description">Description <span class="gray"><img src="<?php echo $base_url;?>/images/qtion.png" alt="Description" data="Create or paste your description here.<br/><br/>Please Do not paste your post multiple times.<br/><br/>Try to include keywords that people search might for"></span></label>
										<textarea class="form-control textdescrip description" name="description" rows="3" placeholder=""><?= $ad['description']; ?></textarea>
									
																				<div class="form-group">                                            <label for="textName">URL</label>                                            <input type="url" name="url" class="form-control" id="url" value="<?= $ad['url']; ?>">                                        </div>
									
                                        <div class="form-group">
                                            <label for="textName">Your Name</label>
                                            <input type="text" name="fname" class="form-control" id="name" placeholder="" value="<?= $ad['contact_person']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="phonenumber">Phone</label>
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="" value="<?= $ad['phone']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="postalcode">Postal Code</label>
                                            <input type="text" name="postalcode" class="form-control" id="postalcode" placeholder="" value="<?= $ad['postal_code']; ?>">
                                        </div>
                                        <div class="g-recaptcha" data-sitekey="6LexDAgUAAAAADKIEFG4OBWetVcLv03lrzG5bgBN"></div>
										
                                        <div class="form-group">
											<label for="phonenumber">city,town, or neighborhood</label>
											<input type="text" autocomplete="off" id="city_search" class="form-control" placeholder="city,town" value="<?php echo @$location; ?>">
											<input type="hidden" name="city_id" id="city_search1" value="<?php echo @$ad['city_id']; ?>">	
											<input type="hidden" name="city_name" id="city_name">
											<input type="hidden" name="r_lattitude" value="<?= $ad['latitude']; ?>" id="lati">
											<input type="hidden" name="r_longitude" value="<?= $ad['longitude']; ?>" id="longi">
											<div class="result_lst" style="display: none;" id="city_result"></div>
										</div>
										
										
                                    </div>
									
									<div  class="col-sm-6 newFieldsData" id="featureslist_of_category_1">
										
									
									</div>
									<div class="image_display_area">
										<div class="form-group-new">
											<label for="viewcode">Images <span class="gray"><img src="<?php echo $base_url;?>/images/qtion.png" alt="Images" data="You can upload JPG, GIF, and PNG images.<br/><br/>Images must be less than 8MB each.<br><br>Maximum 5 Images."></span></label>
											<p>Watch our quick <a href="#">Tutorial Video</a> on "How to Upload a photo"</p>
											<input name="adsimage[]" type="file" class="multi" multiple="multiple">
										</div>	
										<div class="form-group">
											<label for="viewcode">Exist Images <span class="gray">:</span></label>
											<div id="filterImgId" class="belo-img-restadmin">
												<ul>
													<li class="upimage" id="successimg" style="color:green;display:none;"></li>
													<?php if(!empty($imgs)){ 
													foreach($imgs as $img){
													?>	
													<li class="upimage" id="imgid-<?php echo $img['id'];?>">
														<img src="<?php echo $base_url;?>/images/postAds/thumb_<?php echo $img['image'];?>" />
														<a href="javascript:void(0);" onclick="return DeleteAdsUploadImg('<?php echo $img['id'];?>', '<?php echo $ad['id'];?>');"><img src="<?php echo $base_url;?>/images/delet.png"></a>
													</li>
													<?php }
													}?>
												</ul>
											</div>
										</div>
									</div>	
									<!--
									<div class="col-sm-6 newFieldsData" id="featureslist_of_category_2">
									</div>
									<div class="col-sm-6 newFieldsData" id="featureslist_of_category_3">
									</div>-->
									
									
                                    <h5>By posting an ad on ClassifiedAds.com, you agree to to be bound by our <span><a target="_blank" href="<?php echo $base_url;?>/terms-conditions.html">Terms of Use</a></span> and our <span><a target="_blank" href="<?php echo $base_url;?>/privacy-policy.html">Privacy Policy</a>.</span></h5>
									
                                    <input class="btn btn-submit" type="submit" name="submit" value="post this ad">
                                </div>


</form>
</div> 



</div>
</div>
</section>
<script>
function DeleteAdsUploadImg(imgid,adid){
	if(confirm('Delete This Message')){
		var Adsid = adid;
		var Imgid = imgid;
		$.ajax({
			type: 'post',
			url: base_url+'/User/DeleteAdsimage/'+Imgid,
			data: {	imageid: Imgid, addsid: Adsid},		
			success: function(data)
			{			
				$('#imgid-'+Imgid).remove();
				$('#successimg').css('display','block');
				$('#successimg').html(data);
				setTimeout(function(){
					$('#successimg').fadeOut('slow') 
					}, 3000
				);
				//$('#successimg').fadeOut('slow');
			},
			error: function()
			{
				alert("System Error");
			},
		});
		return false;
	}
	
}
</script>
