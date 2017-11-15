<?php 
$base_url = Yii::app()->getBaseUrl(true);
?>
<script>
	// This is called with the results from from FB.getLoginStatus().
var countSet = 1;	
  function statusChangeCallback(response) {
    //console.log('statusChangeCallback');
    //console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
	  
      testAPI(true);
    } else {
      // The person is not logged into your app or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
			FB.getLoginStatus(function(response) {
			  statusChangeCallback(response);
			});
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1888831041401560',
    cookie     : false,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.8' // use graph api version 2.8
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

/*   FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  }); */

  };



  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
   function testAPI(countSet = null) {
	  if(countSet == true){
		  console.log('Welcome!  Fetching your information.... ');
		FB.api('/me', function(response) {
		  console.log('Successful login for: ' + response.name);
		  document.getElementById('status').innerHTML =
			'Thanks for logging in, ' + response.name + '!';
			jQuery.ajax({
								method: "POST",
								url: base_url+"/Site/fbLogin",
								data: { fbUserID: response }
							}).done(function (msg){
								window.location.href = base_url+'/User/myaccount';
							}); 
		}); 
	  }
    
  }
  
   
</script>
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
		<div class="col-xs-12 col-md-6 col-md-offset-3 formarea">
	
	<form id="login-form" action="<?php echo $base_url; ?>/site/Signin" method="post">  
			<h2>Returning Users</h2>
			<div class="form-group">
				<label for="exampleInputEmail1">Email Address<sup>*</sup></label>
				<input type="email" name="LoginForm[login_email]" required class="form-control" id="exampleInputEmail1" placeholder="Email">
			</div>
			<div class="form-group">
				<label for="exampleInputPassword1">Password <sup>*</sup></label>
				<input type="password" name="LoginForm[password]" required class="form-control" id="exampleInputPassword1" placeholder="Password">
			</div>
			<input type="hidden" value="<?php echo @$redirect; ?>" name="redirecturl" id="redirecturl">

			<button type="submit" class="btn btn-default">login</button> <a href="<?php echo $base_url;?>/Forgot.html" class="forget">Forgot Your Password?</a>
	</form>

<!-- <h1>or</h1>

<div class="login-fb">
<?php //$this->widget('ext.hoauth.widgets.HOAuth'); ?>
<!--<a href="#"><img src="<?php // echo $base_url; ?>/images/fb-login.png"></a> <span>We will never post anything without permission.</span>

</div> -->

	
	</div>
	<div class="user_page"></div>
	
	

	
	</div></div>
</div>
