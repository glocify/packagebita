	jQuery( document ).ready(function() {
	jQuery(document).on( 'click', function(){
	jQuery( "#recommendation_result" ).hide();	
	jQuery( "#cuisine_result" ).hide();	
	jQuery( "#dining_result" ).hide();	
	jQuery( "#res_result" ).hide();
	jQuery( "#res_phone_result" ).hide();
	jQuery( "#res_city_result1" ).hide();
    });	
	jQuery( "#res_result" ).hide();	
	jQuery( "#recommendation_result" ).hide();
	jQuery( "#cuisine_result" ).hide();
	jQuery( "#dining_result" ).hide();
	jQuery( "#res_phone_result" ).hide();
	jQuery( "#res_city_result1" ).hide();
	
		//plugin url http://digitalbush.com/projects/masked-input-plugin/#demo
	   //$("#date").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
	  jQuery("#mask_phone").mask("(999) 999-9999");
	   //$("#tin").mask("99-9999999");
	   //$("#ssn").mask("999-99-9999");
	
	jQuery("#recommendation_search").keyup(function(){
		var searchinput = jQuery( "#recommendation_search" ).val();
		$.ajax({
		method: "POST",
		url: base_url+"/Business/recommendationsearch",
		data: { searchinput: searchinput }
    })
    .done(function( msg ) {
		jQuery( "#recommendation_result" ).html(msg);
		jQuery( "#recommendation_result" ).show();
    });
    });
    
    jQuery( "#recommendationsearch" ).click(function() {
    var search_input = jQuery( "#recommendation_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/searchrecommendation",
    data: { search_input: search_input }
    })
    .done(function( msg ) {
	jQuery( ".recommendationtable" ).html(msg);
    });
    });
	
	function restaurent_page_data(){
		var eveningTimingsForrestauratn = {'0':'12:00 am (midnight)','0.5':'12:30 am','1':'1:00 am','1.5':'1:30 am','2':'2:00 am','2.5':'2:30 am','3':'3:00 am','3.5':'3:30 am','4':'4:00 am','4.5':'4:30 am','5':'5:00 am','5.5':'5:30 am','6':'6:00 am','6.5':'6:30 am','7':'7:00 am','7.5':'7:30 am','8':'8:00 am','8.5':'8:30 am','9':'9:00 am','9.5':'9:30 am','10':'10:00 am','10.5':'10:30 am','11':'11:00 am','11.5':'11:30 am','12':'12:00 pm (noon)','12.5':'12:30 pm','13':'1:00 pm','13.5':'1:30 pm','14':'2:00 pm','14.5':'2:30 pm','15':'3:00 pm','15.5':'3:30 pm','16':'4:00 pm','16.5':'4:30 pm','17':'5:00 pm','17.5':'5:30 pm','18':'6:00 pm','18.5':'6:30 pm','19':'7:00 pm','19.5':'7:30 pm','20':'8:00 pm','20.5':'8:30 pm','21':'9:00 pm','21.5':'9:30 pm','22':'10:00 pm','22.5':'10:30 pm','23':'11:00 pm','23.5':'11:30 pm'};
		var morningTimingsForrestauratn = {'6':'6:00 am','6.5':'6:30 am','7':'7:00 am','7.5':'7:30 am','8':'8:00 am','8.5':'8:30 am','9':'9:00 am','9.5':'9:30 am','10':'10:00 am','10.5':'10:30 am','11':'11:00 am','11.5':'11:30 am','12':'12:00 pm (noon)','12.5':'12:30 pm','13':'1:00 pm','13.5':'1:30 pm','14':'2:00 pm','14.5':'2:30 pm','15':'3:00 pm','15.5':'3:30 pm','16':'4:00 pm','16.5':'4:30 pm','17':'5:00 pm','17.5':'5:30 pm','18':'6:00 pm','18.5':'6:30 pm','19':'7:00 pm','19.5':'7:30 pm','20':'8:00 pm','20.5':'8:30 pm','21':'9:00 pm','21.5':'9:30 pm','22':'10:00 pm','22.5':'10:30 pm','23':'11:00 pm','23.5':'11:30 pm','0':'12:00 am (midnight)','0.5':'12:30 am (next day)','1':'1:00 am (next day)','1.5':'1:30 am (next day)','2':'2:00 am (next day)','2.5':'2:30 am (next day)','3':'3:00 am (next day)','3.5':'3:30 am (next day)','4':'4:00 am (next day)','4.5':'4:30 am (next day)','5':'5:00 am (next day)','5.5':'5:30 am (next day)'};
		
		var restaurantsFormFields = '<div class="ad-usr-secnd"><h4 style="font-weight:bold;">Restaurant Information</h4><div class="control-group"><input id="form_type_restaurants" type="hidden" value="Enable" name="form_type_restaurants"/><input type="hidden" value="restaurant" name="restauranttype"/><label class="control-label">Range</label><div class="controls"><input type="text" class="m-wrap span12" id="Start Range" name="r_range" ></div></div><div class="control-group"><label class="control-label">Price Length</label><div class="controls"><label class="checkbox"><div class="checker" id="uniform-undefined"><input type="checkbox" value="1" name="price_len1" /></div>$</label><label class="checkbox"><div class="checker" id="uniform-undefined"><input type="checkbox" value="1" name="price_len2" /></div>$$</label><label class="checkbox"><div class="checker" id="uniform-undefined"><input type="checkbox" value="1" name="price_len3" /></div>$$$</label><label class="checkbox"><div class="checker" id="uniform-undefined"><input type="checkbox" value="1" name="price_len4" /></div>$$$$</label><label class="checkbox"><div class="checker" id="uniform-undefined"><input type="checkbox" value="1" name="price_len5" /></div>$$$$$</label></div></div><div class="control-group"><label class="control-label">Facilities</label><div class="controls"><input type="text" class="m-wrap span12" id="Facilities" name="r_facilities" /></div></div><div class="control-group"><label class="control-label">Cross Street</label> <div class="controls"><input type="text" class="m-wrap span12" id="Cross Street" name="r_cross_street" /></div></div><div class="control-group"><label class="control-label">Parking</label><div class="controls"><select required id="Parking" class="span12 select2_category select2-offscreen" name="r_parking"><option value="">--Select Here--</option><option value="Yes">Yes</option><option value="No">No</option></select></div></div><div class="control-group"><label class="control-label">Dining Style</label><div class="controls"><input type="text" class="m-wrap span12" id="Dining Style" name="r_dining_style" /></div></div><div class="control-group"><label class="control-label">Type Of Cards Accepted</label><div class="controls"><input type="text" class="m-wrap span12" name="r_accept_cards"></div></div><div class="control-group"><label class="control-label">Payment Policies</label><div class="controls"><textarea rows="8" cols="50" type="text" class="m-wrap span12" name="r_payment" ></textarea></div></div>';
		
		restaurantsFormFields += '<div class="control-group tent-svn"><label class="control-label">Monday Timings</label><div id="week1"><input type="text" name="montxt[]" value="" /><div class="controls"><select name="s_mon[]" class=" select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(eveningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		restaurantsFormFields += '</select><select name="e_mon[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(morningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select></div></div><span class="btn purple-stripe add_input" onclick="morehead('+1+')">Add More</span></div><div class="control-group tent-svn"><label class="control-label">Tuesday Timings</label><div id="week2"><input type="text" name="tuetxt[]" value="" /><div class="controls"><select name="s_tue[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(eveningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select><select name="e_tue[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';

		jQuery.each(morningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select></div></div><span class="btn purple-stripe add_input" onclick="morehead('+2+')">Add More</span></div><div class="control-group tent-svn"><label class="control-label">Wednesday Timings</label><div id="week3"><input type="text" name="wedtxt[]" value="" /><div class="controls"><select name="s_wed[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(eveningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select><select name="e_wed[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(morningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select></div></div><span class="btn purple-stripe add_input" onclick="morehead('+3+')">Add More</span></div><div class="control-group tent-svn"><label class="control-label">Thursday Timings</label><div id="week4"><input type="text" name="thutxt[]" value="" /><div class="controls"><select name="s_thu[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(eveningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select><select name="e_thu[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(morningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		restaurantsFormFields += '</select></div></div><span class="btn purple-stripe add_input" onclick="morehead('+4+')">Add More</span></div><div class="control-group tent-svn"><label class="control-label">Friday Timings</label><div id="week5"><input type="text" name="fritxt[]" value="" /><div class="controls"><select name="s_fri[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(eveningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select><select name="e_fri[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(morningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select></div></div><span class="btn purple-stripe add_input" onclick="morehead('+5+')">Add More</span></div><div class="control-group tent-svn"><label class="control-label">Saturday Timings</label> <div id="week6"><input type="text" name="sattxt[]" value="" /><div class="controls"><select name="s_sat[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(eveningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select><select name="e_sat[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(morningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select></div></div><span class="btn purple-stripe add_input" onclick="morehead('+6+')">Add More</span></div><div class="control-group tent-svn"><label class="control-label">Sunday Timings</label><div id="week7"><input type="text" name="suntxt[]" value="" /><div class="controls"><select name="s_sun[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(eveningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select><select name="e_sun[]" class="select2_category select2-offscreen"><option value="" selected="true">Select</option>';
		
		jQuery.each(morningTimingsForrestauratn, function(key, value){
			restaurantsFormFields += '<option value="'+key+'">'+value+'</option>';	
		});
		
		restaurantsFormFields += '</select></div></div><span class="btn purple-stripe add_input" onclick="morehead('+7+'")">Add More</span> </div></div>';
		return restaurantsFormFields;
	}
	
	function hotel_page_data(){
		var hotelFormFields = '<div class="ad-usr-secnd"><h4 style="font-weight:bold;">Hotel Information</h4><div class="control-group"><input type="hidden" name="form_type_hotels" id="form_type_hotels" value="Enable" /><input type="hidden" value="hotel" name="hotelType"/><label class="control-label">Brand</label><div class="controls"><input type="text" name="brand" placeholder="Brand" required class="m-wrap span12" /></div></div><div class="control-group"><label class="control-label">Currency Code</label><div class="controls"><input type="text" name="currency" required placeholder="" class="m-wrap span12" maxlength="3" /></div></div><div class="control-group"><label class="control-label">Lowest Rate</label><div class="controls"><input type="text" name="low_rate" placeholder="Lowest Rate" class="m-wrap span12" /></div></div><div class="control-group"><label class="control-label">Highest Rate</label><div class="controls"><input type="text" name="high_rate" placeholder="Highest Rate" class="m-wrap span12" /></div></div><div class="controls"><label class="control-label">Year Opened</label><div class="controls"><input type="text" name="year_opened" placeholder="" class="m-wrap span12 h_year_open" /></div></div><div class="control-group"><label class="control-label">Year Renovated</label><div class="controls"><input type="text" name="year_renovated" placeholder="" class="m-wrap span12 h_year_renovated" /></div></div><div class="control-group"><label class="control-label">Number of rooms</label><div class="controls"><input type="number" min="1" name="num_rooms" placeholder="" class="m-wrap span12" /></div></div><div class="control-group"><label class="control-label">Number of Suites</label><div class="controls"><input type="number" min="1" name="num_suites" placeholder="" class="m-wrap span12" /></div></div><div class="control-group"><label class="control-label">Number of Floors</label><div class="controls"><input type="number" min="1" name="num_floors" placeholder="" class="m-wrap span12" /></div></div></div>';	
		return hotelFormFields;
	}
	
	jQuery("#cat_req_update").chosen({max_selected_options: 3});
	jQuery("#cat_req_update").on('change', function(){ 
		var selctionCount = jQuery('#cat_req_update :selected').length;
		if(selctionCount <= 3){
			jQuery("#features_of_category").html('');
			switch(selctionCount){
									case 1: 
											jQuery("#categorySelctionMessage").html('You Can Associates Your Business With 2 More Categories.');
											break;
									case 2: 
											jQuery("#categorySelctionMessage").html('You Can Associates Your Business With 1 More Category.');
											break;	
									case 3: 
											jQuery("#categorySelctionMessage").html('You Cannot Associates Your Business With More Categories.');
											break;					
								}
			jQuery('#cat_req_update :selected').each(function(i, selected){ 
				jQuery("#form_type_restaurants").val('Disable');
				jQuery("#restaurents_extra_fields").css('display', 'none');
				jQuery("#form_type_hotels").val('Disable');
				jQuery("#hotels_extra_fields").css('display', 'none');
				
				var foo = jQuery(selected).val(); 
				var categoryName = jQuery(selected).text();
				var business_id = jQuery("#business_hidden_id").val();
				if(foo != ''){
						jQuery.ajax({
							method: "POST",
							url: base_url+"/Business/getFeatureSelctionforUpdate",
							data: { searchinput: foo, business_id: business_id }
						}).done(function (msg){		
								jQuery("#features_of_category").append(msg);
								if(categoryName == 'Restaurants' || categoryName == 'Restaurant' || categoryName == 'restaurants' || categoryName == 'restaurant'){
									if(jQuery("#isset_restaurants").length){
										jQuery("#restaurents_extra_fields").css("display", "block");
										jQuery("#form_type_restaurants").val('Enable');
									}else{
										jQuery("#restaurents_extra_fields").css("display", "block");
										jQuery("#restaurents_extra_fields").html(restaurent_page_data());
									}
								}
								if(categoryName == 'Hotels' || categoryName == 'Hotel' || categoryName == 'hotels' || categoryName == 'hotel'){
									if(jQuery("#isset_hotel").length){
										jQuery("#hotels_extra_fields").css("display", "block");
										jQuery("#form_type_hotels").val('Enable');
									}else{
										jQuery("#hotels_extra_fields").css("display", "block");
										jQuery("#hotels_extra_fields").html(hotel_page_data());
										jQuery("#form_type_hotels").val('Enable');
									}
								}	
						});
				}else{
					alert("Please select Category!");
					return false;
				}
			});					
		}else{
			alert("You cannot Select more then 3 Categories.");
			return false;
		}	
	});
	jQuery(document).ready(function(){
		var selctionCount = jQuery('#cat_req_update :selected').length;
		if(selctionCount <= 3){
			switch(selctionCount){
									case 1: 
											jQuery("#categorySelctionMessage").html('You Can Associates Your Business With 2 More Categories.');
											break;
									case 2: 
											jQuery("#categorySelctionMessage").html('You Can Associates Your Business With 1 More Category.');
											break;	
									case 3: 
											jQuery("#categorySelctionMessage").html('You Cannot Associates Your Business With More Categories.');
											break;					
								}
			jQuery('#cat_req_update :selected').each(function(i, selected){ 
				var foo = jQuery(selected).val(); 
				var categoryName = jQuery(selected).text();
				var business_id = jQuery("#business_hidden_id").val();
				if(foo != ''){
						jQuery.ajax({
							method: "POST",
							url: base_url+"/Business/getFeatureSelctionforUpdate",
							data: { searchinput: foo, business_id: business_id }
						}).done(function (msg){		
								jQuery("#features_of_category").append(msg);
								// if(categoryName == 'Restaurants' || categoryName == 'Restaurant' || categoryName == 'restaurants' || categoryName == 'restaurant'){
									// jQuery("#restaurents_extra_fields").html(restaurent_page_data());
								// }
								// if(categoryName == 'Hotels' || categoryName == 'Hotel' || categoryName == 'hotels' || categoryName == 'hotel'){
									// jQuery("#hotels_extra_fields").html(hotel_page_data());
								// }	
						});
				}else{
					alert("Please select Category!");
					return false;
				}
			});					
		}else{
			alert("You cannot Select more then 3 Categories.");
			return false;
		}	
		
	});
	
	jQuery("#cat_req").chosen({max_selected_options: 3});
    jQuery("#cat_req").on('change', function(){ 
		var selctionCount = jQuery('#cat_req :selected').length;
		if(selctionCount <= 3){
			jQuery("#restaurents_extra_fields").html('');
			jQuery("#hotels_extra_fields").html('');
			jQuery("#features_of_category").html('');
			switch(selctionCount){
									case 1: 
											jQuery("#categorySelctionMessage").html('You Can Associates Your Business With 2 More Categories.');
											break;
									case 2: 
											jQuery("#categorySelctionMessage").html('You Can Associates Your Business With 1 More Category.');
											break;	
									case 3: 
											jQuery("#categorySelctionMessage").html('You Cannot Associates Your Business With More Categories.');
											break;					
								}
			jQuery('#cat_req :selected').each(function(i, selected){ 
				var foo = jQuery(selected).val(); 
				var categoryName = jQuery(selected).text();
				if(foo != ''){
						jQuery.ajax({
							method: "POST",
							url: base_url+"/Business/getFeatureSelction",
							data: { searchinput: foo }
						}).done(function (msg){		
								jQuery("#features_of_category").append(msg);
								if(categoryName == 'Restaurants' || categoryName == 'Restaurant' || categoryName == 'restaurants' || categoryName == 'restaurant'){
									jQuery("#restaurents_extra_fields").html(restaurent_page_data());
								}
								if(categoryName == 'Hotels' || categoryName == 'Hotel' || categoryName == 'hotels' || categoryName == 'hotel'){
									jQuery("#hotels_extra_fields").html(hotel_page_data());
								}	
						});
				}else{
					alert("Please select Category!");
					return false;
				}
			});
		}else{
			alert("You cannot Select more then 3 Categories.");
			return false;
		}
	});
	
	jQuery("#categoryonfeature").on("change", function(){
		var categoryId = jQuery("#categoryonfeature").val();
		if(categoryId != ''){
			$.ajax({
				method: "POST",
				url: base_url+"/Business/getFeatureList",
				data: { searchinput: categoryId }
			})
			.done(function( msg ) {
				jQuery(".selectFeaturesdropdown").html(msg);
			});
		}else{
			alert("Please select Category!");
			return false;
		}
	});
	
	
    jQuery("#cuisine_search").keyup(function(){
    var searchinput = jQuery( "#cuisine_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/cuisinesearch",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#cuisine_result" ).html(msg);
    jQuery( "#cuisine_result" ).show();
    });
    });
    
    jQuery( "#cuisinesearch" ).click(function() {
    var search_input = jQuery( "#cuisine_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/searchcuisine",
    data: { search_input: search_input }
    })
    .done(function( msg ) {
	jQuery( ".cuisinetable" ).html(msg);
    });
    });
    
    
    jQuery("#dining_search").keyup(function(){
    var searchinput = jQuery( "#dining_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/diningsearch",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#dining_result" ).html(msg);
    jQuery( "#dining_result" ).show();
    });
    });
    
    
    jQuery( "#diningsearch" ).click(function() {
    var search_input = jQuery( "#dining_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/searchdining",
    data: { search_input: search_input }
    })
    .done(function( msg ) {
	jQuery( ".diningtable" ).html(msg);
    });
    });
	
	jQuery("#active_busines_name").keyup(function(){
		var searchinput = jQuery( "#active_busines_name" ).val();
			$.ajax({
				method: "POST",
				url: base_url+"/Business/ActiveBussSearchbyname",
				data: { searchinput: searchinput }
			})
			.done(function( msg ) {
				jQuery( "#res_result" ).html(msg);
				jQuery( "#res_result" ).show();
			});
    });
	
	jQuery("#pending_busines_name").keyup(function(){
		var searchinput = jQuery( "#pending_busines_name" ).val();
			$.ajax({
				method: "POST",
				url: base_url+"/Business/PendingBussSearchbyname",
				data: { searchinput: searchinput }
			})
			.done(function( msg ) {
				jQuery( "#res_result" ).html(msg);
				jQuery( "#res_result" ).show();
			});
    });
	
	
	jQuery("#res_search").keyup(function(){
    var searchinput = jQuery( "#res_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/ressearchbyname",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#res_result" ).html(msg);
    jQuery( "#res_result" ).show();
    });
    });
	
	jQuery("#res_city_search1").keyup(function(){
    var searchinput = jQuery( "#res_city_search1" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/ressearchbycity",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#res_city_result1" ).html(msg);
    jQuery( "#res_city_result1" ).show();
    });
    });
	
	jQuery("#res_phone_search").keyup(function(){
    var searchinput = jQuery("#res_phone_search").val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/ressearchbynumber",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#res_phone_result" ).html(msg);
    jQuery( "#res_phone_result" ).show();
    });
    });

	jQuery("#ressearch").click(function(){
		var searchinput = jQuery( "#res_search" ).val();
		var category = jQuery( "#category" ).val();
		// if(searchinput == '' && category == ''){
			// alert('You have not selected or enter value for search');
		// }else{
			$.ajax({
				method: "POST",
				url: base_url+"/Business/featuresearch",
				data: { searchinput: searchinput , category: category  }
			})
			.done(function( msg ) {
				jQuery( ".restauranttable" ).html(msg);
			});
		// }
	});


     
});

function selectrecommendation(target)
{
	var recommendation = target.id;
	jQuery( "#recommendation_search" ).val(recommendation);
	jQuery( "#recommendation_result" ).hide();
}

function selectcuisine(target)
{
	var cuisine = target.id;
	jQuery( "#cuisine_search" ).val(cuisine);
	jQuery( "#cuisine_result" ).hide();
}

function selectdining(target)
{
	var dining = target.id;
	jQuery( "#dining_search" ).val(dining);
	jQuery( "#dining_result" ).hide();
}

function restaurantlanglong()
{
var street=$("#res_street").val();
	var city=$("#res_city").val();
	if(city!="" && street!="")
	{ 
		$.ajax({
			type: 'post',
			url: base_url+'/Business/getlatlong',
			data: {city: city,street: street},
			success:function(result)
			{
				var latlong =$.trim(result).split(',');
				$("#Latitude").val(latlong[0]); 
				$("#Longitude").val(latlong[1]); 
			}
		});
	}
}

function getresstate(target)
{
	var countryval = target.value;
	$.ajax({
	method: "POST",
	url: base_url+'/Business/getstateres',
	data: { countryval: countryval }
})
  .done(function( msg ) {
	jQuery( ".res_state" ).html(msg);
  });
}

function getrescity(target)
{
	
	var stateval = target.value;
	$.ajax({
  method: "POST",
  url: base_url+'/Business/getcityres',
  data: { stateval: stateval }
})
  .done(function( msg ) {
	  jQuery( ".res_city" ).html(msg);
  });
}

function selectres(target)
{
	var res = target.id;
	jQuery( "#res_search" ).val(res);
	jQuery( "#res_result" ).hide();
}
function selectActiveBussiness(target)
{
	var res = target.id;
	jQuery( "#active_busines_name" ).val(res);
	jQuery( "#res_result" ).hide();
}
function selectPendingBussiness(target)
{
	var res = target.id;
	jQuery( "#pending_busines_name").val(res);
	jQuery( "#res_result" ).hide();
}

function selectresnumber(target)
{
	var resnumber = target.id;
	jQuery( "#res_phone_search" ).val(resnumber);
	jQuery( "#res_phone_result" ).hide();
}

function selectrescity(target)
{
	var rescity = target.id;
	
	var city_id  = jQuery("#"+rescity).data('ref');
	jQuery( "#city_id" ).val(city_id);
	jQuery( "#res_city_search1" ).val(rescity);
	jQuery( "#res_city_search1" ).val(rescity);
	jQuery( "#res_city_result1" ).hide();
}

jQuery("#select_menu_name_for_item").on("change", function(){
		var categoryId = jQuery("#select_menu_name_for_item").val();
		if(categoryId != ''){
			$.ajax({
				method: "POST",
				url: base_url+"/Business/GetSelectionList",
				data: { searchinput: categoryId }
			})
			.done(function( msg ) {
				jQuery(".selectMenuSelectiondropdown").html(msg);
			});
		}else{
			alert("Please select Any Menu!");
			return false;
		}
	});
