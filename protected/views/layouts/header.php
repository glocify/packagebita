<!DOCTYPE html>
<html lang="en">
   <?php $base_url = Yii::app()->getBaseUrl(true);?>
   <head>
      <meta charset="utf-8">
      <?php echo Yii::app()->seo->run(Yii::app()->language);?> 
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <?php Yii::app()->seo->run(); ?>
      <link rel="icon" href="<?php echo $base_url;?>/images/favicon-tk.png" type="image/x-icon"/>
      <link rel="shortcut icon" href="<?php echo $base_url;?>/images/favicon-tk.png" type="image/x-icon"/>
      <!-- Bootstrap --><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="<?php echo $base_url;?>/css/bootstrap.css" rel="stylesheet"/>
      <link href="<?php echo $base_url;?>/css/font-awesome.css" rel="stylesheet"/>
      <link href="<?php echo $base_url;?>/css/font-awesome.min.css" rel="stylesheet"/>
      <link href="<?php echo $base_url;?>/css/chosen.css" rel="stylesheet"/>
      <link href="<?php echo $base_url;?>/css/style.css" rel="stylesheet"/>
      <link rel="stylesheet"  href="<?php echo $base_url;?>/css/lightslider.css"/>
      <link href="<?php echo $base_url;?>/css/bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo $base_url;?>/css/style1.css" rel="stylesheet">
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
   </head>
   <body>
      <div id="fb-root"></div>
      <header>
         <div class="container">
     
               <div class="col-xs-12 col-sm-2 logo"> <a href="<?php echo $base_url;?>"><img src="<?php echo $base_url;?>/images/logo-n.png" alt="Money Media Solution"></a> <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> </div>
               <div class="col-xs-12 col-md-8 heder_content">
                <!--  <h1 class="bnr-hding">Free Classifieds <span>Ads</span></h1>-->
           <div class="header_title"> <h1>Money Media Solutions</h1>
<h4>When Nerds Compete, it's Good For You!</h4>               
       </div>
 
         <nav class="navbar navbar-default">
            <div class="container-fluid">
               <!-- Collect the nav links, forms, and other content for toggling -->            
               <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  				    <ul class="nav navbar-nav ">
                     <?php if(isset(Yii::app()->session['userid'])){ ?>					
                     <li><a href="javascript:void(0);" onclick="logoutFrom(true);">Sign out</a></li>
                     <li><a href="<?php echo $base_url; ?>/User/Myaccount">Myaccount </a></li>
                    <li><a href="<?php echo $base_url; ?>/site/listing">Offers</a>
                     <!--<li><a href="<?php //echo $base_url; ?>/Site/Contactus">Contact Us </a></li>-->
                     <?php } else { ?>					
                     <li><a href="<?php echo $base_url; ?>/Site/Signin"> log in </a></li>
                     <li><a href="<?php echo $base_url; ?>/Site/Signup">Signup </a></li>
                    <!-- <li><a href="<?php //echo $base_url; ?>/help.html">Help</a></li>
                     <li><a href="javascript:void(0);" onclick="logged();">POST FREE AD </a></li>
					   <li><a href="http://package.glocify.org/">Home</a>
                     </li>			
                     <li><a href="<?php //echo $base_url; ?>/about-us.html">About</a>
                     </li>-->			
                     <li><a href="<?php echo $base_url; ?>/site/listing">Specials</a>
                     </li>			
                     <!--<li><a href="<?php //echo $base_url; ?>/Site/Contactus">Contact Us</a>
                     </li>	-->
                     <?php } ?>					            				
                  </ul>
               </div>
               <!-- /.navbar-collapse -->           
            </div>
            <!-- /.container-fluid -->         
         </nav>      </div>  </div>
      </header>
      <input type="hidden" id="limo_url" name="limo_url" value="<?php echo $base_url.'/Site/PostAds';?>" />
	  <input type="hidden" id="main_url" name="main_url" value="<?php echo $base_url.'/Site/index';?>" />
<script>
var base_url = "<?php echo $base_url; ?>";
</script>	  

