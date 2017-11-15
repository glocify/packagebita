jQuery( document ).ready(function() {
jQuery(document).on( 'click', function(){
	jQuery( "#category_result" ).hide();
	jQuery( "#car_result" ).hide();	
	jQuery( "#car_city_result" ).hide();
	jQuery( "#car_phone_result" ).hide();
	});
jQuery( "#car_result" ).hide();
jQuery( "#category_result" ).hide();
jQuery( "#car_city_result" ).hide();
jQuery( "#car_phone_result" ).hide();
jQuery("#category_search").keyup(function(){
    var searchinput = jQuery( "#category_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/categorysearch",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#category_result" ).html(msg);
    jQuery( "#category_result" ).show();
    });
    });
	
	jQuery( "#categorysearch" ).click(function() {
	var search_input = jQuery( "#category_search" ).val();
	$.ajax({
    method: "POST",
    url: base_url+"/Business/searchcategory",
    data: { search_input: search_input }
    })
    .done(function( msg ) {
	jQuery( ".categorytable" ).html(msg);
    });
	});
	
	jQuery("#car_search").keyup(function(){
    var searchinput = jQuery( "#car_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/carsearchbyname",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#car_result" ).html(msg);
    jQuery( "#car_result" ).show();
    });
    });
    
    jQuery("#car_city_search1").keyup(function(){
    var searchinput = jQuery( "#car_city_search1" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/carsearchbycity",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#car_city_result" ).html(msg);
    jQuery( "#car_city_result" ).show();
    });
    });
	
	jQuery("#car_phone_search").keyup(function(){
    var searchinput = jQuery( "#car_phone_search" ).val();
    $.ajax({
    method: "POST",
    url: base_url+"/Business/carsearchbynumber",
    data: { searchinput: searchinput }
    })
    .done(function( msg ) {
	jQuery( "#car_phone_result" ).html(msg);
    jQuery( "#car_phone_result" ).show();
    });
    });
	
	jQuery("#carsearch").click(function(){
	var searchinput = jQuery( "#car_search" ).val();
	var numberinput = jQuery( "#car_phone_search" ).val();
	var cityinput = jQuery( "#car_city_search1" ).val();
	$.ajax({
    method: "POST",
    url: base_url+"/Business/carsearch",
    data: { searchinput: searchinput , numberinput: numberinput , cityinput: cityinput }
    })
    .done(function( msg ) {
	jQuery( ".cartable" ).html(msg);
    });
	});
    
    
});

function selectcategory(target)
{
var category = target.id;
	jQuery( "#category_search" ).val(category);
	jQuery( "#category_result" ).hide();
}

function getcarlatlong()
{
	//var street=$("#street").val();
	var city=$("#car_city").val();
	if(city!="")
	{ 
		$.ajax({
			type: 'post',
			url: base_url+'/Business/getcarlatlong',
			data: {city: city},
			success:function(result)
			{
				var latlong =$.trim(result).split(',');
				$("#car_lat").val(latlong[0]); 
				$("#car_long").val(latlong[1]); 
			}
		});
	}
}

function selectcar(target)
{
	var car = target.id;
	jQuery( "#car_search" ).val(car);
	jQuery( "#car_result" ).hide();
}

function selectcarcity(target)
{
	var carcity = target.id;
	jQuery( "#car_city_search1" ).val(carcity);
	jQuery( "#car_city_result" ).hide();
}

function selectcarnumber(target)
{
	var carnumber = target.id;
	jQuery( "#car_phone_search" ).val(carnumber);
	jQuery( "#car_phone_result" ).hide();
}
