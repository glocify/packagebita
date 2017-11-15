<?php
	$base_url = Yii::app()->getBaseUrl(true);
?>
<section class="listing_front">
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
<div class="clearfix"></div>
<!-- ends here -->
	<div class="row ">
<?php
	$countSet = 0;
	foreach($Userdata as $user){
		$userID = $user['id'];
		$statuschecked = Yii::app()->commonFnc->checkPackageActive($userID);
		if($statuschecked == true):
		$countSet++;
?>

			<div class="col-md-6 lancer">
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
			<div class="col-md-7 details_l">
			<h3><?php echo ucwords($user['UserMeta']['company']); ?></h3>
			<table class="emp_details">
			<tr><td>Name:</td><td class="rt"><?php echo $user['UserMeta']['firstname'].' '.$user['UserMeta']['lastname']; ?></td></tr>
			<tr><td>Position:</td><td class="rt"><?php echo $user['UserMeta']['position']; ?></td></tr>
			<tr><td>Speciality:</td><td class="rt"><?php echo $user['UserMeta']['speciality']; ?></td></tr>
			<tr><td>Hourly Rate:</td><td class="rt">$<?php echo $user['UserMeta']['Hour_rate']; ?></td></tr>
			<tr class="emp_button"><td><a onclick="addCountNumber(<?php echo $userID; ?>);" href="<?php echo $user['UserMeta']['url']; ?>" target="_blank">Website</a></td><td class="rt"><a href="#" onclick="addSendid(<?php echo $user['id'];?>)" data-toggle="modal" data-target="#qoute">Get Quote</a></td></tr>
			</table>
			</div>
			<div class="clearfix"></div>
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
</div>
<div class="pagination_list">	
	<?php $this->widget('CLinkPager', array('pages' => $pages,)) ?>
</div>
</section>



<!-- line modal -->
<div class="modal fade" id="qoute" tabindex="" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog my_modal">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			<h3 class="modal-title" id="lineModalLabel">Quote Form</h3>
		</div>
		<div class="modal-body ">


<div class="form-holder contact_form">

<form action="<?php echo $base_url; ?>/Site/jobadd" method="post" name="job_form" id="job_form" enctype="multipart/form-data" >
<input name="sent_to_user" id="sent_to_user" type="hidden" value=""/>
<input name="redirect_url" type="hidden" value="<?php echo Yii::app()->request->requestUri;;?>" />

<section class="sec_2">
<h2>Describe The Job</h2>
<div class="form-group">
<label >Your email address</label>
<input type="email"  data-validation="email" name="email_address" required placeholder="abc@abc.com">
</div>
<div class="form-group">
<label>Name your Job</label>
<input type="text" name="job_name" required placeholder="EXAPMLE need help in web Design">
</div>
<div class="form-group">
<label>Discribe the work to be done</label>
<textarea name="job_description" required ></textarea>
</div>
<!--<div class="form-group">
<label>Discribe the work to be done</label>
<input type="file" placeholder="Drag or upload your files">
</div>-->
<div class="form-group">
<label>What type of project do you have ?</label>
<span class="radio"><input name="project_type" required value="One Time Project" type="radio"><label>One Time Project</label></span>
<span class="radio"><input name="project_type" value="Ongoing Project" type="radio"><label>Ongoing project</label></span>
<span class="radio"><input name="project_type" value="I am not sure" type="radio"><label>I am not sure</label></span>
</div>
<div class="form-group">
<label>Enter Skill needed</label>
<input type="text" name="skills" placeholder="e.g. HTML, CSS, PHP">
</div>
<div class="form-group">
<label>Start Date</label>
<input type="date" class="startDateJOBs" name="start_date" placeholder="Start Date">
</div>
<label>File Upload</label>
<input style="margin-bottom:10px;" name="pic" type="file" required >
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

	<option  data-img-src="<?php echo $base_url; ?>/images/userimages/<?php echo $user['UserMeta']['user_image']; ?>" value="<?php echo $userID; ?>" id ="profile_<?php echo $userID; ?>" for="im1"><?php echo $user['UserMeta']['firstname'].' '.$user['UserMeta']['lastname']; ?></option>

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
	<select name="like_to_pay" required >
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
<!--<section class="sec_4">
<h2>Check the avaible Frelancers</h2>
<div class="form-group">
<label>View Available Developers</label>
 <div class="text-left"><button data-toggle="modal" data-target="#squarespaceModal" class="btn btn-primary View_dev ">View all</button></div>



<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog my_modal">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			<h3 class="modal-title" id="lineModalLabel">Developers</h3>
		</div>
		<div class="modal-body ">


			<form>
              <div class="container">
	<div class="row ">


			<div class="col-md-6 lancer">
			<div class="col-md-5 face_l"><img src="http://package.glocify.org/images/userimages/1507812719Koala.jpg" alt="Money Media Solution"></div>
			<div class="col-md-7 details_l">
			<h3>Ajay Rana</h3>
			<table class="emp_details">
			<tbody><tr><td>Name:</td><td class="rt">Ajay Rana</td></tr>
			<tr><td>Position:</td><td class="rt">Developer</td></tr>
			<tr><td>Speciality:</td><td class="rt">HTML, PHP, CSS</td></tr>
			<tr><td>Hourly Rate:</td><td class="rt">$9.99</td></tr>
			<tr class="emp_2"><td>Select</td><td class="rt"><input type="checkbox" value="choose"></td></tr>
			</tbody></table>
			</div>
			<div class="clearfix"></div>
			</div>

			<div class="col-md-6 lancer">
			<div class="col-md-5 face_l"><img src="http://package.glocify.org/images/userimages/1507813279Koala.jpg" alt="Money Media Solution"></div>
			<div class="col-md-7 details_l">
			<h3>Sanjeev Kumar</h3>
			<table class="emp_details">
			<tbody><tr><td>Name:</td><td class="rt">Sanjeev Kumar</td></tr>
			<tr><td>Position:</td><td class="rt">Developer</td></tr>
			<tr><td>Speciality:</td><td class="rt">HTML, PHP, CSS</td></tr>
			<tr><td>Hourly Rate:</td><td class="rt">$9.99</td></tr>
			<tr class="emp_2"><td>Select</td><td class="rt"><input type="checkbox" value="choose"></td></tr>
			</tbody></table>
			</div>
			<div class="clearfix"></div>
			</div>


	</div>
</div>
            </form>

		</div>
	<div class="row padd_btn"><button data-toggle="modal" data-target="#squarespaceModal" class="btn btn-primary View_dev ">select</button></div>
	</div>
  </div>
</div>

</div>

</section>-->
<section class="actions">
	<input type="submit" name="submit" value="Post this Job">
<!--<input type="submit" value="save draft">-->

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
