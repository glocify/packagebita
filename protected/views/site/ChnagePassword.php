<?php 
$base_url = Yii::app()->getBaseUrl(true);
?>

<div class="login">
 <div class="container">
    <div class="row">
	<div>
		<?php if(Yii::app()->user->hasFlash('error')): ?>
			<div class="flash-error">
				<font color="#d30000;"><?php echo Yii::app()->user->getFlash('error'); ?></font>
			</div>
		<?php endif; ?>
	</div>
	<div class="col-xs-12 col-sm-6 new-user">
		<h2>Chnage Your Password:</h2>
		<ol style="padding: 10px; margin-left: 10px;">
			<li>New and Old Password should not be empty.</li>
			<li>Enter Your Correct Old Password.</li>
			<li>New Password should not be same as Old Password.</li>
		</ol>	
	</div>
	<div class="col-xs-12 col-sm-6 formarea">	
		<form id="forgotpassword" action="<?php echo $base_url; ?>/site/ChangePassword" method="post">  
			<h2>Change Your Password</h2>
			<div id="forgetresponse" style="display:none;"></div>
			<div class="form-group">
			<label for="exampleInputEmail1">Old Password:<sup>*</sup></label>
			<input type="text" name="oldpassword" required class="form-control" id="femailid" placeholder="Old Password" value="<?php echo @$_POST['oldpassword'];?>" />
			</div>
			<div class="form-group">
			<label for="exampleInputEmail1">New Password:<sup>*</sup></label>
			<input type="text" name="newpassword" required class="form-control" id="femailid" placeholder="New Password" value="<?php echo @$_POST['newpassword'];?>" />
			</div>
			<button type="submit" class="btn btn-default" name="Changepassbut" id="changepassword">Change Password</button>
		</form>	
	</div>
	
	

	
	</div></div>
</div>
