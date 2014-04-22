<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">

    <title>CBOX User Registration</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dist/css/signin.css" rel="stylesheet">


</head>

<body>
    <div class="container">
      <form METHOD="POST" name="register" class="form-signin" id="register"role="form">
        <h2 class="form-signin-heading">CBox User Registration</h2>
        <input type="text" name="first_name"class="form-control" placeholder="First Name" required autofocus>
        <br></br>
        <input type="text" name="last_name"class="form-control" placeholder="Last Name" required>
        <br></br>
        <input type="email" name="email"class="form-control" placeholder="Email" required>
        <br></br>
        <input type="text" name="box_id"class="form-control" placeholder="box id" required>
        <br></br>
        <input type="text" name="street_address"class="form-control" placeholder="Street Address" required>
        <br></br>
        <input type="text" name="city"class="form-control" placeholder="City" required>
        <br></br>
		<input type="text" name="state"class="form-control" placeholder="State" required>
        <br></br>
        <input type="text" name="zip_code"class="form-control" placeholder="Zip Code" required>
        <br></br>
        <input type="text" name="country" class="form-control" placeholder="Country" required>
        <br></br>
        <input type="text" name="region" class="form-control" placeholder="Region" required>
        <br></br>
        <br><h3>Login Info</h3></br>
        <input type="text" name="username" id="username" class="form-control" placeholder="Desired Username" required>
        <br></br>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <br></br>
        <button name="submit" class="btn btn-lg btn-primary " id="submit_new_user" type="submit">Register</button>
      </form>

</div> <!-- /container -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script id="source" language="javascript" type="text/javascript">

$(function() {
	//twitter bootstrap script
	$("button#submit_new_user").click(function(e){
		//alert("HELLO");
		e.preventDefault();
		submit_new_user(e);
	});
});

function submit_new_user()
{
	//alert("HELLO");
	var datastring = $("#register").serializeArray();
	var sendRequest = true;
	var username = "";
	//alert("HELLO");
	$.each(datastring, function(i, field){
    	//alert("DATA: " +field.name + ":" + field.value + " ");
		if(field.value == "") {
			sendRequest = false;	
		}
		if(field.name == "username") {
			username = field.value;	
			//alert("Username: " + username);
		}
  	});
	//alert("Username 2 : " + username);

	
	$.ajax({
		type: "POST",
		url: "checkForUsername.php",
		data: {"username" : username},
		success: function(data) {
			//alert("DATA: " + data);
			if(data > 0){
				alert("Username is taken");
				sendRequest = false;
			}
			else if(data == 0){
				
				if(sendRequest == true)
				{
					registerUser(datastring);
				}
			}
			else{
				alert('Problem with sql query');
			}
		}
	});	
	username = "";
}

function registerUser(data)
{
	//alert("Let's see...");
	$.each(data, function(i, field){
    	//alert("DATA: " +field.name + ":" + field.value + " ");
		if(field.value == "") {
			sendRequest = false;	
		}
  	});
	$.ajax({
            type: "POST",
            url: "queryRegisterUser.php",
            data: data,
            success: function(data) {
                 alert(data);
            }
        });	
}

</script>

</body>
</html>