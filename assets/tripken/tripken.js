 jQuery(window).ready(function(){
	jQuery('.h_year_renovated').datepicker({
											changeMonth: true,
											changeYear: true,
											yearRange: '1900:2016',
											showButtonPanel: true,	
											});
	jQuery('.h_year_open').datepicker({
											changeMonth: true,
											changeYear: true,
											yearRange: '1900:2016',
											showButtonPanel: true,	
										});
 });