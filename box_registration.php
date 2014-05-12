<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>CBOX Box Registration</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	<link rel="stylesheet" type="text/css" href="dist/css/BoxRegistration.css">
    <link href="dist/css/signin.css" rel="stylesheet">
</head>
<body>
<div class="container">
	<h1>Box Registration</h1>
	<form id="boxregform" class="form-signin" role="form">
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
		<div>
			<input type="submit" value="Register" id="submit" />
			<button type="submit" value="Register" id="submit">Register</button>
		</div>
	</form>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<!--<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
<script id="source" language="javascript" type="text/javascript">

$(function() {
	$("input#submit").click(function(event) {
    	event.preventDefault();
		var datastring = $("#boxregform").serializeArray();
		
		$.each(datastring, function(i, field){
    	  console.log("DATA: " +field.name + ":" + field.value + " ");
  		});
		
		$.ajax({
			type: "POST",
			url: "queryBoxRegistration.php",
			data: datastring,
			datatype: "text",
			success: function(data)
			{
				console.log("Return val: " + data);
				 //overlay(data); 
				 window.setTimeout(function(){ overlay(data)}, 500);
			},
			error: function(data) {
				console.log("Error?" + data);
			}
		});
		//alert("POST AJAX");
    });
});

function overlay(data) {
	if(data == "1") {
		alert("Registration Successful");
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
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50665970-1', 'cboxbeta.com');
  ga('send', 'pageview');

</script>

</body>
</html>