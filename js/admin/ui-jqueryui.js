var UIJQueryUI = function () {

    var handleDialogs = function () {

	    $( "#basic_opener4").click(function() {
	      $( "#dialog_basic4" ).dialog( "open" );	
	      $('.ui-dialog button').blur();// avoid button autofocus     
	    });

	    $("#dialog_info").dialog({
	      dialogClass: 'ui-dialog-blue',
	      autoOpen: true,
	      resizable: false,
	      height: 600,
	      width: 600,
	      modal: true,
	      buttons: [
	      	{
	      		"text" : "OK",
	      		'class' : 'btn green',
	      		click: function() {
        			$(this).dialog( "close" );
      			}
	      	}
	      ]
	    });


    }

    return {
        //main function to initiate the module
        init: function () {
            handleDialogs();
        }

    };

}();