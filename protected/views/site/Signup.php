<?php $base_url = Yii::app()->getBaseUrl(true);?>
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
	  if(getSetValue == true){
		  FB.getLoginStatus(function(response) {
			  statusChangeCallback(response);
			}); 
	  }
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
								url: base_url+"/Site/Signin",
								data: { fbUserID: response }
							}).done(function (msg){
								window.location.href = base_url+'/User/myaccount';
							}); 
		});
	  }
    
  }
  
   
</script>
<section class="sign_up">
<div class="container">
  <div class="account">
    
<div id="status">

    <div class="col-sm-6 col-md-offset-3">
      <div class="create_id">
        <h2 class="text-center">Sign Up</h2>
		<?php if(Yii::app()->user->hasFlash('signup')): ?>
		<div class="flash-success" style="color:green;">
			<?php echo Yii::app()->user->getFlash('signup'); ?>
		</div>
		<?php endif; ?>
		<?php if(Yii::app()->user->hasFlash('error')): ?>
		<div class="flash-error" style="color:red;">
			<?php echo Yii::app()->user->getFlash('error'); ?>
		</div>
		<?php endif; ?>
        <form enctype="multipart/form-data" name="signupform" id="signupform" action="<?php echo $base_url;?>/Site/Signup" method="post">
          <div class="form-group">
            <input type="text" class="form-control" id="firstname" placeholder="First Name" name="firstname" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="lastname" placeholder="Last Name" name="lastname" required>
          </div>
		  
		  <div class="form-group">
            <input type="text" class="form-control" id="position" placeholder="Position : e.g.(Developer, Designer etc)" name="position" required>
          </div>
		  <div class="form-group">
            <input type="text" class="form-control" id="speciality" placeholder="Speciality : e.g.(HTML, Mobile Development etc)" name="speciality" required>
          </div>
		  <div class="form-group">
            <input type="number" class="form-control" id="hour_rate" placeholder="Hourly Rate" name="hour_rate" required>
          </div> 
		   <div class="form-group">
            <input type="url" class="form-control" id="url" placeholder="Your Website URL" name="url" required>
          </div> 
		  <div class="form-group">
             Select Your Profile Picture  <input name="pic" type="file" required />
          </div>
		 
		  
          <div class="form-group">
            <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" id="password" pattern=".{5,}" placeholder="Password(at least 5 keys)" name="password" required>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" id="password2" pattern=".{5,}" placeholder="Confirm Password(at least 5 keys)" name="cpassword" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="zip-code" placeholder="Zip code" pattern=".{5,}" name="zip" required>
          </div>
          <div class="form-group">
            <div class="checkbox">
              <label>
				<input type="checkbox" name="recommendation" value="1">
				<h2>Yes ! Get inside scoop on business recommendations and local events.</h2>
              </label>
            </div>
          </div>
			<h3>By joining us you agree to our <span><a href="<?php echo $base_url;?>/terms-conditions.html">Terms of Service</a></span><br>
			and <span><a href="<?php echo $base_url;?>/privacy-policy.html">Privacy Policy</a>.</span> We value your privacy, and will not post to your wall<br>
			without your permission. </h3>
			<button type="submit" name="submit" id="submit" class="btn btn-send">Submit</button>
			<h4>Already have an account? <span><a href="<?php echo $base_url;?>/site/signin">Sign in »</a></span></h4>
        </form>
      </div>
    </div>
  </div>
  </div>
</section>
