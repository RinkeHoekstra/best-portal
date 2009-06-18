// $('mappingForm').addEvent( 'submit', function(evt){
// 	// Stops the submission of the form.
// 	evt.stop();
// 
// 	// Sends the form to the action path,
// 	// which is 'script.php'
// 	this.send();
// } );



window.addEvent('domready', function() {
	$('mappingForm').addEvent('submit', function(e) {
		//Prevents the default submit event from loading a new page.
		e.stop();
		//Empty the log and show the spinning indicator.
		var log = $('log_res').empty().addClass('ajax-loading');
		//Set the options of the form's Request handler. 
		//("this" refers to the $('myForm') element).
		this.set('send', {onComplete: function(response) { 
			log.removeClass('ajax-loading');
			log.set('html', response);
		}});
		//Send the form.
		this.send();
	});
});



// 
// var div = document.getElementById('container');
// 
// var handleSuccess = function(o){
// 	YAHOO.log("The success handler was called.  tId: " + o.tId + ".", "info", "example");
// 	if(o.responseText !== undefined){
// 		div.innerHTML = "<li>Transaction id: " + o.tId + "</li>";
// 		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
// 		div.innerHTML += "<li>Status code message: " + o.statusText + "</li>";
// 		div.innerHTML += "<li>HTTP headers received: <ul>" + o.getAllResponseHeaders + "</ul></li>";
// 		div.innerHTML += "<li>PHP response: " + o.responseText + "</li>";
// 		div.innerHTML += "<li>Argument object: Array ([0] => " + o.argument[0] +
// 						 " [1] => " + o.argument[1] + " )</li>";
// 	}
// };
// 
// var handleFailure = function(o){
// 		YAHOO.log("The failure handler was called.  tId: " + o.tId + ".", "info", "example");
// 
// 	if(o.responseText !== undefined){
// 		div.innerHTML = "<li>Transaction id: " + o.tId + "</li>";
// 		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
// 		div.innerHTML += "<li>Status code message: " + o.statusText + "</li>";
// 	}
// };
// 
// var callback =
// {
//   success:handleSuccess,
//   failure:handleFailure,
//   argument:['foo','bar']
// };
// 
// var sUrl = "addmapping.php";
// var postData = "username=anonymous&userid=0";
// 
// function makeRequest(){
// 
// 	var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData);
// 	
// 	YAHOO.log("Initiating request; tId: " + request.tId + ".", "info", "example");
// 
// }
