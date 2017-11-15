<!DOCTYPE html>
<html lang="en">
<?php $base_url = Yii::app()->getBaseUrl(true);?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Tripken Classified</title>
<link rel="icon" href="<?php echo $base_url;?>/images/favicon-tk.png" type="image/x-icon"/>
<link rel="shortcut icon" href="<?php echo $base_url;?>/images/favicon-tk.png" type="image/x-icon"/>
<!-- Bootstrap -->
<link href="<?php echo $base_url;?>/css/bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $base_url;?>/css/font-awesome.css" rel="stylesheet"/>
<link href="<?php echo $base_url;?>/css/font-awesome.min.css" rel="stylesheet"/>
<link href="<?php echo $base_url;?>/css/chosen.css" rel="stylesheet"/>
<link href="<?php echo $base_url;?>/css/style.css" rel="stylesheet"/>
 <link rel="stylesheet"  href="<?php echo $base_url;?>/css/lightslider.css"/>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-52353008-3', 'auto');
  ga('send', 'pageview');
</script>
<!-- HeedUser feedback widget code starts here -->
<script>
(function () { var hu = document.createElement("script"); hu.type = "text/javascript"; hu.async = true; hu.src = "//www.heeduser.com/supfiles/Script/widget.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hu, s); })()
</script>
<script>
var _heeduser = {
type: "button",
community: "classifieds",
url: "https://classifieds.heeduser.com",
placement: "middle-right",
color: "#202021",
widget_layout: "full",
sso_token: ""
}
var heeduser_url = _heeduser.url + "/FeedbackWidget/Widget.aspx?sso_token=" + encodeURIComponent(_heeduser.sso_token);
document.write('<a id="heeduser_wb" href="JavaScript:heeduser_openwidget(heeduser_url,\'' + _heeduser.widget_layout + '\')" class="' + _heeduser.placement + '" style="background:' + _heeduser.color + '"><img src="https://www.heeduser.com/supfiles/Images/feedback_button_en_rm.png"></a>');
</script>
<!-- HeedUser feedback widget code ends here -->
</head>
<body>

<header>
<div class="innerheader">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-3 logo logo_inner"> <a href="<?php echo $base_url;?>"><img src="<?php echo $base_url;?>/images/logo.png"></a> <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> </div>
      <div class="newhdsearchbar">
	  <div class="nhdserch-inner">
		<div class="search_two for-inner-search">
			<form method="POST" action="<?php echo $base_url;?>/Site/Search">
			<input id="category_search1" name="cat_id" value="" type="hidden">
			<input id="category_type" name="category_type" value="" type="hidden">
			<input id="adcity" name="cityid" value="" type="hidden">
			<input id="adcityname" name="cityname" value="" type="hidden">
			<div class="form-group">
			<input type="text" class="form-control" name="searchcat" id="srchcatnsubcat" placeholder="I'm looking For" autocomplete="off" required/>
			<div class="result_lst" style="display: none;" id="category_result"></div>
			</div>
			<div class="form-group">
			<input type="text" class="form-control" name="state_ctycountry" id="newsrchlocation" placeholder="City Name" autocomplete="off" value="" required>
			<div id="newcityreslt" style="display:none;"></div>
			</div>
			<button type="submit" class="btn btn-default">Submit</button> 
			</form>
		</div>
	  </div>
	  </div>
	  <div class="menus inner_menus">
        <nav class="navbar navbar-default">
          <div class="container-fluid"> 
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
             
            </div>
            
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
				<?php if(isset(Yii::app()->session['userid'])){ ?>
					<li><a href="<?php echo $base_url; ?>/Site/signout">Sign out</a></li>
					<li><a href="<?php echo $base_url; ?>/User/Myaccount">Myaccount </a></li>
					<li><a href="<?php echo $base_url; ?>/help.html">Help</a></li>
					<li><a href="<?php echo $base_url; ?>/Site/PostAds">POST FREE AD</a></li>
				<?php } else { ?>
					<li><a href="<?php echo $base_url; ?>/Site/Signin"> log in </a></li>					
					<li><a href="<?php echo $base_url; ?>/Site/Signup">Signup </a></li>
					<li><a href="<?php echo $base_url; ?>/help.html">Help</a></li>
					<li><a href="javascript:void(0);" onclick="logged();">POST FREE AD</a></li>
				<?php } ?>
					              
				</ul>            
            </div>
            <!-- /.navbar-collapse --> 
          </div>
          <!-- /.container-fluid --> 
        </nav>
      </div>
    </div>
  </div>
   </div>
</header>
<input type="hidden" id="limo_url" name="limo_url" value="<?php echo $base_url.'/Site/PostAds';?>" />