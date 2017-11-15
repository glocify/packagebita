jQuery( document ).ready(function() {
	jQuery("#inputType").on('change', function(){
		jQuery("#htmlAccSelection").html('');		
		jQuery("#htmlAccValEnter").html('');
		var selectionValue = jQuery("#inputType").val();
		
		if(selectionValue !== ''){
			var htmlForSelection = '';
				switch(selectionValue) {
										case "SelectDropdown":
											htmlForSelection = '<label class="control-label">How Many Options will be for selection ? <span>*</span></label><div class="controls"><input type="text" maxlength="2" id="optionsValue" name="optionsValue" placeholder="ex(4)" class="m-wrap span12"required /></div>';
											break;
										case "RadioButton":
											htmlForSelection = '<label class="control-label">Please Enter Labal For Radio Buttons<span>*</span></label><div class="controls"><input type="text" id="firstRadioBttnLabal" name="firstRadioBttnLabal" placeholder="ex(Male)" class="m-wrap span12"required /><input type="text" id="secondRadioBttnLabal" name="secondRadioBttnLabal" placeholder="ex(Female)" class="m-wrap span12"required /></div>';
											break;
										case "Checkboxes":
											htmlForSelection = '<label class="control-label">How Many checkbox will be for selection ? <span>*</span></label><div class="controls"><input type="text" maxlength="2" id="checkOptionsValue" name="checkOptionsValue" placeholder="ex(3)" class="m-wrap span12"required /></div>';
											break;	
										default:
											htmlForSelection = "";
									}
			jQuery("#htmlAccSelection").html(htmlForSelection);
		}
	});
	
	/* jQuery("#optionsValue").on("change", function(){
		alert("Hello World");
		
	}); */
	
	$(document).on("keyup", "#optionsValue", function(){
		var optionvalueSelection = jQuery("#optionsValue").val();
		if(optionvalueSelection !== '' ){
			var htmlForValue = '<label class="control-label">Please Enter Options <span>*</span></label><div class="controls">';
			for(count = optionvalueSelection; count > 0; count--){
				
				htmlForValue += '<input type="text" id="selctionVal'+count+'" name="selctionVal'+count+'" class="m-wrap span12" required />';
			}
			htmlForValue += '</div>';
			jQuery("#htmlAccValEnter").html(htmlForValue);
		}else{
			console.log("Please Enter Numeric Value");	
		}
	});
	
	$(document).on("keyup ", "#checkOptionsValue", function(){
		var optionvalueCheck = jQuery("#checkOptionsValue").val();
		
		if(optionvalueCheck !== '' ){
			var htmlForValue = '<label class="control-label">Please Enter Checkboxes labels <span>*</span></label><div class="controls">';
			for(count = optionvalueCheck; count > 0; count--){
				
				htmlForValue += '<input type="text" id="checkVal'+count+'" name="checkVal'+count+'" class="m-wrap span12" required />';
			}
			htmlForValue += '</div>';
			jQuery("#htmlAccValEnter").html(htmlForValue);
		}else{
			console.log("Please Enter Numeric Value");
		}
		
	});
	
	
	
	/* jQuery("#checkOptionsValue").on("change", function(){
		alert("Hello World");
		
	}); */
	
});