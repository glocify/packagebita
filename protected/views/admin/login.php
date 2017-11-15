<!DOCTYPE html>
<?php 
$base_url = Yii::app()->getBaseUrl(true);
?>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8" />
  <title>Admin Login</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  <!-- BEGIN GLOBAL MANDATORY STYLES -->

  <link href="<?php echo $base_url;?>/css/admin/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  
  <link href="<?php echo $base_url;?>/css/admin/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo $base_url;?>/css/admin/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo $base_url;?>/css/admin/style-metro.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo $base_url;?>/css/admin/style.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo $base_url;?>/css/admin/style-responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php echo $base_url;?>/css/admin/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
  <link href="<?php echo $base_url;?>/css/admin/uniform.default.css" rel="stylesheet" type="text/css"/>
  
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL STYLES -->
  <link href="<?php echo $base_url;?>/css/admin/login.css" rel="stylesheet" type="text/css"/>
  <!-- END PAGE LEVEL STYLES -->
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
  <!-- BEGIN LOGO -->
  <div class="logo">
    <img src="<?php echo $base_url;?>/images/admin/logo.png1312" alt="Logo" /> 
  </div>
  <!-- END LOGO -->
  <!-- BEGIN LOGIN -->
  <div class="content">
    <!-- BEGIN LOGIN FORM -->
    <?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			)); ?>
      <h3 class="form-title">Login to your account</h3>
      <div class="alert alert-error hide">
        <button class="close" data-dismiss="alert"></button>
        <span>Enter any username and passowrd.</span>
      </div>
      <div class="control-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Username</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-user"></i>
            
             <?php echo $form->textField($model,'login_email',array('class'=>'m-wrap placeholder-no-fix','placeholder'=>'Email')); ?>
          </div>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            
            <?php echo $form->passwordField($model,'password',array('class'=>'m-wrap placeholder-no-fix','placeholder'=>'Password')); ?>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <label class="checkbox">
        <input type="checkbox" name="remember" value="1"/> Remember me
        </label>
       
        <?php echo CHtml::submitButton('Login',array('class'=>'btn green pull-right')); ?>          
      </div>
      <div class="forget-password">
        <h4>Forgot your password ?</h4>
        <p>
          no worries, click <a href="javascript:;" class="" id="forget-password">here</a>
          to reset your password.
        </p>
      </div>
      
    <?php $this->endWidget(); ?>
    <!-- END LOGIN FORM -->        
   
   
  </div>
  <!-- END LOGIN -->
  <!-- BEGIN COPYRIGHT -->
  <div class="copyright">
    Copyright © <?php echo date('Y');?> Glocify, LLC. All rights reserved.
  </div>
  <!-- END COPYRIGHT -->
  <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
  <!-- BEGIN CORE PLUGINS -->
  
  <!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->  
  <script src="<?php echo $base_url;?>/js/admin/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>    
  <script src="<?php echo $base_url;?>/js/admin/bootstrap.min.js" type="text/javascript"></script>
  <!--[if lt IE 9]>
  <script src="assets/plugins/excanvas.js"></script>
  <script src="assets/plugins/respond.js"></script> 
  <![endif]-->  
  <script src="<?php echo $base_url;?>/js/admin/breakpoints/breakpoints.js" type="text/javascript"></script>  
  <!-- IMPORTANT! jquery.slimscroll.min.js depends on jquery-ui-1.10.1.custom.min.js -->  
  <script src="<?php echo $base_url;?>/js/admin/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/js/admin/jquery.blockui.js" type="text/javascript"></script> 
  <script src="<?php echo $base_url;?>/js/admin/jquery.cookie.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/js/admin/jquery.uniform.min.js" type="text/javascript" ></script>  
  <!-- END CORE PLUGINS -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <script src="<?php echo $base_url;?>/js/admin/dist/jquery.validate.min.js" type="text/javascript"></script>
  <!-- END PAGE LEVEL PLUGINS -->
  <!-- BEGIN PAGE LEVEL SCRIPTS -->
  <script src="<?php echo $base_url;?>/js/admin/app.js" type="text/javascript"></script>
  <script src="<?php echo $base_url;?>/js/admin/login.js" type="text/javascript"></script>      
  <!-- END PAGE LEVEL SCRIPTS --> 
  <script>
    jQuery(document).ready(function() {     
      App.init();
      Login.init();
    });
  </script>
  <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
