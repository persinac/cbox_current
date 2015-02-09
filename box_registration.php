<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>CBOX Box Registration</title>

    <!-- Bootstrap core CSS -->
    <!-- Bootstrap core CSS and Custom CSS -->
	
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="dist/css/jquery.qtip.css" />

	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
	<link rel="stylesheet" type="text/css" href="dist/css/BoxRegistration.css">
    <link href="dist/css/signin.css" rel="stylesheet">
</head>
<body>
<div class="container">
	<h1>Box Registration</h1>
	<form id="boxregform" >
		<div>
			<label for="First Name" class="title">First Name:</label>
			<input type="text" id="firstName" name="firstName" />
		</div>
		<div>
			<label for="Last Name" class="title">Last Name:</label>
			<input type="text" id="lastName" name="lastName" />
		</div>
		<div>
			<label for="Phone Number" class="title">Phone Number:</label>
			<input type="text" id="phoneNumber" name="phoneNumber" />
		</div>
		<div>
			<label for="Box Name" class="title">Box Name:</label>
			<input type="text" id="boxName" name="boxName" />
		</div>
		<div>
			<label for="Email" class="title">Email:</label>
			<input type="text" id="email" name="email" />
		</div>
		<div>
			<label for="Street Address" class="title">Street Address:</label>
			<input type="text" id="streetAddress" name="streetAddress" />
		</div>
		<div>
			<label for="City" class="title">City:</label>
			<input type="text" id="city" name="city" />
		</div>
		<div>
			<label for="State" class="title">State:</label>
			<input type="text" id="state" name="state" />
		</div>
		<div>
			<label for="Zip Code" class="title">Zip Code:</label>
			<input type="text" id="zipcode" name="zipcode" />
		</div>
		<div>
			<label for="Country" class="title">Country:</label>
			<input type="text" id="country" name="country" />
		</div>
	</form>
	<div>	
		<button type="submit" onclick="submitBox()" id="submit">Register</button>
	</div>
	<div id="dialog-modal" class="modal" style="display:none;">
		<div id="modal_content" style="width:100%;height:100%;">
			
		</div>
	</div>
</div>
<!--<img src='http://i.stack.imgur.com/FhHRx.gif' />-->

<!-- These must be in THIS ORDER! DO NOT FUCK WITH -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<!-- Required for full calendar -->
<script src='dist/lib/moment.min.js'></script>
<script src="dist/jq_ui/js/jquery-1.10.2.js"></script>
<script src="dist/jq_ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src='dist/fullcalendar/fullcalendar.min.js'></script>

<!-- Required for date picker -->
<script src="dist/js/jquery.plugin.min.js"></script>
<script src="dist/js/jquery.datepick.min.js"></script> 

<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>

<script id="source" language="javascript" type="text/javascript">
function submitBox() {
	
	var alphaNumericRegex = /^[A-Za-z0-9\s\']+$/;
	var cityRegex = /^[A-Za-z\s]+$/;
	var streetRegex = /(^[0-9]+\s)[a-zA-Z ]+$/;
	var zipRegex = /^[0-9]{5}/;
	var emailRegex = /[^\s@]+@[^\s@]+\.[^\s@]+/;
	var phoneRegex = /^[0-9]+$/;
	
	var datastring = $("#boxregform").serializeArray();
	var sendRequest = true;
	
	$.each(datastring, function(i, field){
		console.log("DATA: " +field.name + ":" + field.value + " ");
		if(sendRequest == true) {
			if(field.value.length > 0) {
				if(field.name.indexOf("email") > -1) {
					if(!emailRegex.test(field.value))
					{
						$("#"+field.name).addClass("_input_error");
						sendRequest = false;
						console.log("EMAIL - invalid: " + sendRequest);
					} else {
						
						$("#"+field.name).removeClass("_input_error");
						sendRequest = true;
						console.log("EMAIL - valid: " + sendRequest);
					}
				} else if(field.name.indexOf("zip") > -1) {
					if(!zipRegex.test(field.value))
					{
						$("#"+field.name).addClass("_input_error");
						sendRequest = false;
						console.log("ZIP - invalid: " + sendRequest);
					} else {
						if(field.value.length < 5 || field.value.length > 5) {
							$("#"+field.name).addClass("_input_error");
							sendRequest = false;
							console.log("ZIP - invalid: " + sendRequest);
						} else {
							$("#"+field.name).removeClass("_input_error");
							sendRequest = true;
							console.log("ZIP - valid: " + sendRequest);
						}
					}
				} else if(field.name.indexOf("street") > -1) {
					if(!streetRegex.test(field.value))
					{
						$("#"+field.name).addClass("_input_error");
						sendRequest = false;
						console.log("STREET - invalid: " + sendRequest);
					} else {
						
						$("#"+field.name).removeClass("_input_error");
						sendRequest = true;
						console.log("STREET - valid: " + sendRequest);
					}
				} else if(field.name.indexOf("phone") > -1) {
					var temp_phone = field.value.replace(/-/g,"");
					console.log("temp_phone: " + temp_phone + ", length: " + temp_phone.length);
					if(!phoneRegex.test(temp_phone))
					{
						$("#"+field.name).addClass("_input_error");
						sendRequest = false;
						console.log("PHONE - invalid char: " + sendRequest);
					} else {
						if(temp_phone.length < 10 || temp_phone.length > 10) {
							$("#"+field.name).addClass("_input_error");
							sendRequest = false;
							console.log("PHONE - invalid length: " + sendRequest);
						} else {
							$("#"+field.name).removeClass("_input_error");
							sendRequest = true;
							console.log("PHONE - valid: " + sendRequest);
						}
					}
				} else if(field.name.indexOf("city") > -1) {
					if(!cityRegex.test(field.value))
					{
						$("#"+field.name).addClass("_input_error");
						sendRequest = false;
						console.log(field.name+" - invalid: " + sendRequest);
					} else {
						$("#"+field.name).removeClass("_input_error");
						sendRequest = true;
						console.log(field.name+" - valid: " + sendRequest);
					}
				} else {
					if(!alphaNumericRegex.test(field.value))
					{
						$("#"+field.name).addClass("_input_error");
						sendRequest = false;
						console.log(field.name+" - invalid: " + sendRequest);
					} else {
						
						$("#"+field.name).removeClass("_input_error");
						sendRequest = true;
						console.log(field.name+" - valid: " + sendRequest);
					}
				}
			} else {
				sendRequest = false;
			}
		}
	});
	if(sendRequest == true) {
		$.ajax({
			type: "POST",
			url: "queryBoxRegistration.php",
			data: datastring,
			dataType: "text",
			beforeSend: function() {
					console.log("LOADING"); 
					$( "#dialog-modal" ).dialog({
					  height: 400,
					  width: 300,
					  modal: true
					});
					$("#dialog-modal").dialog(); 
					$("#modal_content").html("<p> Please wait, we're sending your email</p>");
					$(".ui-dialog-titlebar").hide();
				},
			complete: function() { console.log("FINSIHED"); $("#dialog-modal").dialog("close"); },
			success: function(data)
			{
				console.log("Return val: " + data);
				 window.setTimeout(function(){ overlay(data)}, 500);
			},
			error: function(data) {
				console.log("Error?" + data);
			}
		});
	} else {
		$( "#dialog-modal" ).dialog({
			height: 400,
			width: 300,
			modal: true
		});
		$("#dialog-modal").dialog(); 
		$("#modal_content").html("<p>Please input valid characters!</p>");
	}
}

function overlay(data) {
	if(data.substring(0,1) == "1") {
		alert("Registration Successful and your email has been sent! The email contains your unique box ID "+
			"and an administration ID that you'll need for user registration! Check your spam folder.");
		clearRegistrationForm();
	} else { 
		alert("Contact System Administrator");
	}
}

function clearRegistrationForm() {
	$('#boxregform input').each(function(index, element) {
        console.log(index + " : " + $(this).text());
		$(this).val('');
    });
}

</script>

<script>
/*
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50665970-1', 'cboxbeta.com');
  ga('send', 'pageview');
*/
</script>

</body>
</html>