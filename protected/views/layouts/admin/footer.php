<?php 
$base_url = Yii::app()->getBaseUrl(true);
?>
</div>
   <!-- END CONTAINER -->
   <!-- BEGIN FOOTER -->
   <div class="footer">
     Copyright © <?php echo date('Y');?> package.glocify.org, LLC. All rights reserved.
      <div class="span pull-right">
         <span class="go-top"><i class="icon-angle-up"></i></span>
      </div>
   </div>
   <!-- END FOOTER -->
   
   
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<script src="<?php echo $base_url;?>/js/admin/jquery-1.8.3.min.js" type="text/javascript"></script>   
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
<script type="text/javascript" src="<?php echo $base_url;?>/assets/ckeditor/ckeditor.js"></script> 
<script src="<?php echo $base_url;?>/assets/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>   
<script src="<?php echo $base_url;?>/assets/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>  
<script src="<?php echo $base_url;?>/assets/flot/jquery.flot.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/flot/jquery.flot.resize.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/js/admin/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/bootstrap-daterangepicker/date.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>     
<script src="<?php echo $base_url;?>/assets/gritter/js/jquery.gritter.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js" type="text/javascript"></script>
<script src="<?php echo $base_url;?>/js/admin/jquery.sparkline.min.js" type="text/javascript"></script>  
<script src="<?php echo $base_url;?>/js/admin/chosen.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $base_url;?>/js/jquery.MultiFile.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo $base_url;?>/js/admin/fieldsSelection.js" type="text/javascript"></script>  
  <!------------- Below Scripts for USA Phone Number Validation --------------------------->
<script src="<?php echo $base_url;?>/js/jquery.maskedinput-1.3.js"></script> 
<script type="text/javascript" src="<?php echo $base_url;?>/js/demotelphoneusa.js"></script>  
<script type="text/Javascript">
var base_url = "<?php echo $base_url; ?>";
/*   Start Country Search on Country listing  */

jQuery( document ).ready(function() { 
	jQuery("#phone").mask("(999) 999-9999");
	jQuery(document).click(function (event) {            
		jQuery('#category_result').hide();
		jQuery('#country_result').hide();
		jQuery('#state_result').hide();
		jQuery('#city_result').hide();
		jQuery('#cat_result').hide();
	});
	
	
	jQuery("#category_id").chosen({max_selected_options: 3});
	jQuery("#subcategory_id").chosen();
	
	jQuery("#expire_in").datepicker({ dateFormat: 'yy-mm-dd' , changeMonth: true,
            changeYear: true});
	
	
});


/*  End Search State in State section for location using search field  */



function selectuser(target)
{
	var category = target.id;
	var getTextuser = jQuery( "#"+category).text();
	jQuery( "#user_search" ).val(getTextuser);
	jQuery( "#user_id" ).val(category);
	jQuery( "#category_result" ).hide();
}


jQuery( document ).ready(function() {
	
	jQuery( "#usersearch" ).click(function() {
	var search_input = jQuery( "#user_id" ).val();
	if(search_input !== ''){
		$.ajax({
		method: "POST",
		url: base_url+"/user/searchuser",
		data: { search_input: search_input }
		})
		.done(function( msg ) {
		jQuery( "#sample_editable_1" ).html(msg);
		jQuery( "#pagination-div" ).css('display','none');
		
		});
	}else{
		alert("Please Enter any keyword first for search!");
	}
		
	});
	
	jQuery("#user_search").keyup(function(){
		var searchinput = jQuery( "#user_search" ).val();
		$.ajax({
			method: "POST",
			url: base_url+"/user/usersearch",
			data: { searchinput: searchinput }
    })
    .done(function( msg ) {
		jQuery( "#category_result" ).html(msg);
		jQuery( "#category_result" ).show();
		});
    });
});


var current_url = "<?php echo $base_url; ?>";
$(document).ready(function() {
	countryPaginationList(1);
	$('.paginationcoun li.active').live('click',function(){
		var page = $(this).attr('p');
		countryPaginationList(page);
	});
	$('.paginationRest li.active').live('click',function(){
		var page = $(this).attr('p');
		StatePaginationList(page);
	});
	$('.pagination li.active').live('click',function(){
		var page = $(this).attr('p');
		cityPaginationList(page);
	});
});
</script>
</body>
<!-- END BODY -->
</html>


	


