<?php 
$base_url = Yii::app()->getBaseUrl(true);
?>

<div class="login">
 <div class="container">
    <div class="row">
	<div>
		<?php if(Yii::app()->user->hasFlash('login')): ?>
			<div class="flash-success">
				<?php echo Yii::app()->user->getFlash('login'); ?>
			</div>
		<?php endif; ?>
		<?php if(Yii::app()->user->hasFlash('error')): ?>
			<div class="flash-error">
				<?php echo Yii::app()->user->getFlash('error'); ?>
			</div>
		<?php endif; ?>
		<?php if(Yii::app()->user->hasFlash('register')): ?>
			<div class="flash-success">
				<?php echo Yii::app()->user->getFlash('register'); ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="col-xs-12 col-sm-6 new-user">
	<h2>New Users</h2>
	<p>You can post ads, browse ads, and respond to ads without any set up.</p>
	
	<a href="<?php echo $base_url; ?>/site/Signup" class="post-ad">Signup</a>
	
	</div>
		<div class="col-xs-12 col-sm-6 formarea">
	
  <form id="forgotpassword" action="<?php echo $base_url; ?>/site/forgot" method="post">  
  <h2>Forgot Your Password</h2>
  <div id="forgetresponse" style="display:none;"></div>
  <div class="form-group">
    <label for="exampleInputEmail1">Email Address<sup>*</sup></label>
    <input type="email" name="forgotemail" required class="form-control" id="femailid" placeholder="Email">
  </div>
  <button type="submit" class="btn btn-default" id="forgetpassbut" onclick="return forgetpassword();">Forgot Password</button>
</form>

	
	</div>
	
	

	
	</div></div>
</div>
