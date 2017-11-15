jQuery( document ).ready(function() {
	jQuery( "#hotel_result" ).hide();
	jQuery( "#phone_result" ).hide();
	jQuery("#hotel_search").keyup(function(){
		var searchinput = jQuery( "#hotel_search" ).val();
    $.ajax({
  method: "POST",
  url: base_url+"/Business/gethotelname",
  data: { searchinput: searchinput }
})
  .done(function( msg ) {
    jQuery( "#hotel_result" ).html(msg);
    jQuery( "#hotel_result" ).show();
  });
	});
	
	jQuery("#phone_search").keyup(function(){
		var searchinput = jQuery( "#phone_search" ).val();
    $.ajax({
  method: "POST",
  url: base_url+"/Business/gethotelnumber",
  data: { searchinput: searchinput }
})
  .done(function( msg ) {
	 // alert(msg);
    jQuery( "#phone_result" ).html(msg);
    jQuery( "#phone_result" ).show();
  });
	});
});

jQuery("#city_search1").keyup(function(){
    var searchinput = jQuery( "#city_search1" ).val();
   
    $.ajax({
  method: "POST",
  url: base_url+"/Business/hotelcitysearch",
  data: { searchinput: searchinput }
})
  .done(function( msg ) {
    jQuery( "#city_result" ).html(msg);
    jQuery( "#city_result" ).show();
  });
});


function selecthotel(target)
{
	var hotelname = target.id;
	jQuery( "#hotel_search" ).val(hotelname);
	 jQuery( "#hotel_result" ).hide();
}
function selectcitys(target)
{
	var id= target.id;
	//alert(id);
	var ctyname = $('#'+id).text();
	//alert(id+"===="+ctyname);
	//return false;
	jQuery( "#city_search1" ).val(ctyname);
	jQuery( "#city_searchs" ).val(id);
	 jQuery( "#city_result" ).hide();
}
function selectnumber(target)
{
	var hotelname = target.id;
	jQuery( "#phone_search" ).val(hotelname);
	 jQuery( "#phone_result" ).hide();
}
jQuery( "#hotelsearch" ).click(function() {
	var hotelname = jQuery( "#hotel_search" ).val();
	var city = jQuery( "#city_searchs" ).val();
	//alert(city);
	//return false;
	var phone_number = jQuery( "#phone_search" ).val();
	$.ajax({
  method: "POST",
  url: base_url+"/Business/searchhotels",
  data: { hotelname: hotelname, city: city, phone_number: phone_number}
})
  .done(function( msg ) {
	 // alert(msg);
	//  return false;
   jQuery( "#hotelList" ).html(msg);
  });
});

function getlanglong()
{
	var street=$("#street").val();
	var city=$("#city").val();
	if(city!="" && street!="")
	{ 
		$.ajax({
			type: 'post',
			url: base_url+'/Business/getlatlong',
			data: {city: city,street: street},
			success:function(result)
			{
				var latlong =$.trim(result).split(',');
				$("#req8").val(latlong[0]); 
				$("#req11").val(latlong[1]); 
			}
		});
	}
}

