<?php
	$base_url = Yii::app()->getBaseUrl(true);
?>
<section section class="listing_page_inner">
<?php if(Yii::app()->user->hasFlash('success')):?>
	<div class="info">
		<h3 style="color:green;"><?php echo Yii::app()->user->getFlash('success'); ?></h3>
	</div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('error')):?>
	<div class="info">
		<h3 style="color:red;"><?php echo Yii::app()->user->getFlash('error'); ?></h3>
	</div>
<?php endif; ?>
<div class="container">
<!-- adding sorting function start here -->
<form name="sort" method="get" id="indexsort">
	<div class="row text-right">
		<div class="col-md-6"></div>
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-6">
					<label for="rsort" style="padding-top:8px;">Sorting:</label>
				</div>
				<div class="col-md-6">
					<select name="Rsorting" id="rsort" class="rsorting form-control">
						<option value="0"> Select </option>
						<option value="1" <?php if(isset($_GET['Rsorting']) && $_GET['Rsorting'] == 1){ echo 'selected="selected"'; } ?>> price -- Low to high </option>
						<option value="2" <?php if(isset($_GET['Rsorting']) && $_GET['Rsorting'] == 2){ echo 'selected="selected"'; } ?>> price -- Hight to low </option>
					</select>
				</div>
			</div>
		</div>	
	</div>
</form>
<hr/>
<div class="row">
	<div class="col-md-6"><h2 class="page_hed">Profiles:</h2></div>
	<div class="col-md-4 col-md-offset-1"><h2 class="page_hed">Special Offers:</h2></div>
</div>


<?php
	$countSet = 0;
	foreach($Userdata as $user){
		$userID = $user['id'];
		$statuschecked = Yii::app()->commonFnc->checkPackageActive($userID);
		if($statuschecked == true):
		$countSet++;
?>

<div class="row">
	<div class="col-md-6 lancer">
		<div class="col-md-5 face_l"><img src="<?php echo $base_url; ?>/images/userimages/<?php echo $user['UserMeta']['user_image']; ?>" alt="Money Media Solution"></div>
		<div class="col-md-7 details_l">
		<h3><?php echo $user['UserMeta']['firstname'].' '.$user['UserMeta']['lastname']; ?></h3>
		<table class="emp_details">
		<tr><td>Name:</td><td class="rt"><?php echo $user['UserMeta']['firstname'].' '.$user['UserMeta']['lastname']; ?></td></tr>
		<tr><td>Position:</td><td class="rt"><?php echo $user['UserMeta']['position']; ?></td></tr>
		<tr><td>Speciality:</td><td class="rt"><?php echo $user['UserMeta']['speciality']; ?></td></tr>
		<tr><td>Hourly Rate:</td><td class="rt">$<?php echo $user['UserMeta']['Hour_rate']; ?></td></tr>
		<tr class="emp_button"><td><a onclick="addCountNumber(<?php echo $userID; ?>);" href="<?php echo $user['UserMeta']['url']; ?>" target="_blank">Website</a></td><td class="rt"><a href="#"  onclick="addSendid(<?php echo $userID;?>);" data-toggle="modal" data-target="#qoute" >Get Quote</a></td></tr>
		</table>
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="col-md-4 col-md-offset-1">
		<div class="emp_offers"><div class="block showmore_one"><?php echo $user['UserMeta']['offer']; ?></div></div>
	</div>
</div>
<?php
		endif;
	}
	if($countSet == 0):
?>
	<h1>Not available any active profile!</h1>
<?php
	endif;
?>
</div>
<div class="pagination_list">	<?php $this->widget('CLinkPager', array('pages' => $pages,)) ?></div>

</section>
<div class="modal fade" id="qoute" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog my_modal">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			<h3 class="modal-title" id="lineModalLabel">Quote Form</h3>
		</div>
<div class="modal-body ">

	<div class="form-holder contact_form">

		<form action="<?php echo $base_url; ?>/Site/jobadd" method="post" name="job_form" id="job_form" >
		<input name="sent_to_user" id="sent_to_user" type="hidden" value=""/>
		

		<input name="redirect_url" type="hidden" value="<?php echo Yii::app()->request->requestUri;?>" />
		<section class="sec_2">
		<h2>Describe The Job</h2>
		<div class="form-group">
		<label >Your email address</label>
		<input type="email"  data-validation="email" name="email_address" required="true" placeholder="abc@abc.com">
		</div>
		<div class="form-group">
		<label>Name your Job</label>
		<input type="text" name="job_name" required="true" placeholder="EXAPMLE need help in web Design">
		</div>
		<div class="form-group">
		<label>Discribe the work to be done</label>
		<textarea name="job_description" required="true" ></textarea>
		</div>

		<div class="form-group">
		<label>What type of project do you have ?</label>
		<span class="radio"><input name="project_type" required="true" value="One Time Project" type="radio"><label>One Time Project</label></span>
		<span class="radio"><input name="project_type" value="Ongoing Project" type="radio"><label>Ongoing project</label></span>
		<span class="radio"><input name="project_type" value="I am not sure" type="radio"><label>I am not sure</label></span>
		</div>
		<div class="form-group">
		<label>Enter Skill needed</label>
		<input type="text" name="skills" placeholder="e.g. HTML, CSS, PHP">
		</div>
		<div class="form-group">
		<label>Start Date</label>
		<input type="date" class="startDateJOBs" required="true" name="start_date" placeholder="Start Date">
		</div>
		<label>File Upload</label>
		<input style="margin-bottom:10px;" name="pic" type="file" required="true" >
<div class="form-group developer_thumb">
<label>you can send this job to 3 more developer as well <button id="addMOreFreeLancher" type="button" class="add_more" data-toggle="modal" data-target="#freelancer_add">Add more <i class="fa fa-plus" aria-hidden="true"></i> 

</button><span id="selecte_count"></span></label>
	<select style="display:none;" id="more_prifiles_selected" name="moreDevelopers[]" multiple="multiple" >
		<option>------Select Here-------</option>
<?php
	foreach($UserForProfiledata as $user){
		$userID = $user['id'];
		$statuschecked = Yii::app()->commonFnc->checkPackageActive($userID);
		if($statuschecked == true):	
?>		

<option   data-img-src="<?php echo $base_url; ?>/images/userimages/<?php echo $user['UserMeta']['user_image']; ?>" value="<?php echo $userID; ?>" value="<?php echo $userID; ?>" id ="<?php echo $userID; ?>" for="im1"><?php echo $user['UserMeta']['firstname'].' '.$user['UserMeta']['lastname']; ?></option>

<?php 
		endif; 
	}
?>
	</select>

</div>

		</section>
		<section class="section_2">
		<h2>Rate and availaility</h2>

		<div class="form-group">
		<label>How would like to pay?</label>
			<select name="like_to_pay" required="true" >
				<option>------Select Here-------</option>
				<option value="Pay by an hour">Pay by an hour</option>
				<option value="Fix Price">Fix Price</option>
			</select>
		</div>

		<div class="form-group">
		<label>Desired Experienced Level</label>
		<ul class="tabs_forms a">
		<input type="hidden" id="experience_level" value="" name="experience_level" />
		<li><a href="javascript:void(0);" onclick="experience_level('Entry Level')"><span><i class="fa fa-usd" aria-hidden="true"></i></span> Entry Level</a></li>
		<li><a href="javascript:void(0);" onclick="experience_level('Internediate')"><span><i class="fa fa-usd" aria-hidden="true"></i> <i class="fa fa-usd" aria-hidden="true"></i></span>Internediate</a></li>
		<li><a href="javascript:void(0);" onclick="experience_level('Expert')"><span><i class="fa fa-usd" aria-hidden="true"></i><i class="fa fa-usd" aria-hidden="true"></i><i class="fa fa-usd" aria-hidden="true"></i></span>Expert</a></li>
		</ul>
		</div>
		<div class="form-group">
		<label>how long do you expect this job to last?</label>
		<ul class="tabs_forms b">
		<input type="hidden" id="project_duration" value="" name="project_duration" />
		<li><a href="javascript:void(0);" onclick="project_duration('More than 6 months');"><span><i class="fa fa-calendar" aria-hidden="true"></i>
		</span> More than 6 months</a></li>
		<li><a href="javascript:void(0);" onclick="project_duration('between 3 to 6 Months');"><span><i class="fa fa-calendar" aria-hidden="true"></i>
		 </span>between 3 to 6 Months</a></li>
		<li><a href="javascript:void(0);" onclick="project_duration('between 1 to 3 Months');"><span><i class="fa fa-calendar" aria-hidden="true"></i>
		</span>between 1 to 3 Months</a></li>
		<li><a href="javascript:void(0);" onclick="project_duration('Less than One Month');"><span><i class="fa fa-calendar" aria-hidden="true"></i>
		</span>Less than One Month</a></li>

		<li><a href="javascript:void(0);" onclick="project_duration('Less than One week');"><span><i class="fa fa-calendar" aria-hidden="true"></i>
		</span>Less than One week</a></li>

		</ul>
		</div>
		<div class="form-group">
		<label>what time commitment is required for this job?</label>
		<input type="hidden" id="time_commitment" value="" name="time_commitment" />
		<ul class="tabs_forms c">
		<li><a href="javascript:void(0);" onclick="time_commitment('More than 30 hrs/Week');"><span><i class="fa fa-clock-o" aria-hidden="true"></i>
		</span>More than 30 hrs/Week</a></li>
		<li><a href="javascript:void(0);" onclick="time_commitment('Less than 30 hrs/Week');"><span><i class="fa fa-clock-o" aria-hidden="true"></i>
		</span>Less than 30 hrs/Week</a></li>
		<li><a href="javascript:void(0);" onclick="time_commitment('I don\'t Know Yet');"><span><i class="fa fa-clock-o" aria-hidden="true"></i>
		</span>I don't Know Yet</a></li>

		</ul>
		</div>

		</section>
		<section class="actions">
			<input type="submit" name="submit" value="Post this Job">
		</section>
		</form>
  </div>
</div>
</div>
</div>
</div>

 <!-- Modal -->
  <div class="modal fade" id="freelancer_add" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Choose From Following freelancers</h4>
        </div>
        <div class="modal-body_pad_top">
		<?php
			$countSet = 0;
			foreach($UserForProfiledata as $user){
		$userID = $user['id'];
		$statuschecked = Yii::app()->commonFnc->checkPackageActive($userID);
				if($statuschecked == true):
				echo '';
				$countSet++;
        ?>
		<div class="col-md-6 lancer poplancer <?php echo $userID;?>">
		<div class="lancer_holder" id="<?php echo $userID;?>">
	
			<div class="col-md-5 face_l">
	<?php
	if($user['UserMeta']['user_image'] !== '0'):
?>		
<img src="<?php echo $base_url; ?>/images/userimages/<?php echo $user['UserMeta']['user_image']; ?>" alt="Money Media Solution">
<?php
	else:
?>
<img src="<?php echo $base_url; ?>/images/img.jpg" alt="Money Media Solution">
<?php
	endif;
?>
			</div>
			<input type="hidden" name="id[]" value="" id="userid"/>
			<div class="col-md-7 details_l">
			<h3><?php echo ucwords($user['UserMeta']['company']); ?></h3>
			<table class="emp_details">
			<tbody><tr><td>Name:</td><td class="rt"><?php echo $user['UserMeta']['firstname'].' '.$user['UserMeta']['lastname']; ?></td></tr>
			<tr><td>Position:</td><td class="rt"><?php echo $user['UserMeta']['position']; ?></td></tr>
			<tr><td>Speciality:</td><td class="rt"><?php echo $user['UserMeta']['speciality']; ?></td></tr>
			<tr><td>Hourly Rate:</td><td class="rt">$<?php echo $user['UserMeta']['Hour_rate']; ?></td></tr>
			
			</tbody></table>
			</div>
			<div class="clearfix"></div></div>
			</div>
			<?php
		endif;
	}
	if($countSet == 0):
?>
	<h1>Not available any active profile!</h1>
<?php
	endif;
?>
			
	<div class="clearfix"></div>
        </div>
        <div class="modal-footer">
		
       <button type="button" class="btn btn-default round_select" data-dismiss="modal" id="chselect">choose</button>
        </div>
      </div>
    </div>
  </div>

