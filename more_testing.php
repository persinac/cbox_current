<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="dist/css/BoxRegistration.css">
</head>
<body>
<h1>Box Registration</h1>
<form method="post" id="boxregform">
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
	</div>
</form>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script id="source" language="javascript" type="text/javascript">

$(function() {
	$("input#submit").click(function() {
    	//alert("CLICKEDDDDD!!!!!");
		var datastring = $("#boxregform").serializeArray();
		
		$.each(datastring, function(i, field){
    	  alert("DATA: " +field.name + ":" + field.value + " ");
  		});
		
		$.ajax({
			type: "POST",
			url: "queryBoxRegistration.php",
			data: datastring,
			success: function(data){
				alert("Return val: " + data);
				 overlay(); 
			}
		});
		alert("POST AJAX");
    });
});

function overlay() {
	alert("Great success");
}

</script>
</body>
</html>