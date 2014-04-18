<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">

    <title>CBOX SIGNIN</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../dist/css/signin.css" rel="stylesheet">


</head>

<body>
    <div class="container">
<div class="username_avail_result" id="username_avail_result"></div>
<div class="password_strength" id="password_strength"></div>
      <form ACTION="<?php echo $loginForm; ?>" METHOD="POST" name="login" class="form-signin" role="form">
        <h2 class="form-signin-heading">CBox User Registration</h2>
        <input type="text" name="first_name"class="form-control" placeholder="First Name" required autofocus>
        <br></br>
        <input type="text" name="last_name"class="form-control" placeholder="Password" required>
        <br></br>
        <input type="text" name="email"class="form-control" placeholder="Email" required>
        <br></br>
        <input type="text" name="box_id"class="form-control" placeholder="box_id" required>
        <br></br>
        <input type="text" name="street_address"class="form-control" placeholder="Street Address" required>
        <br></br>
        <input type="text" name="city"class="form-control" placeholder="City" required>
        <br></br>
		<input type="text" name="state"class="form-control" placeholder="State" required>
        <br></br>
        <input type="text" name="zip_code"class="form-control" placeholder="Zip Code" required>
        <br></br>
        <input type="text" name="region" class="form-control" placeholder="Region" required>
        <br></br>
        <br></br>
        <input type="text" name="username" id="username" class="form-control" placeholder="Desired Username" required>
        <br></br>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <br></br>
        <button name="submit" class="btn btn-lg btn-primary " id="submit" type="submit">Register</button>
      </form>

</div> <!-- /container -->


<script type="text/javascript" src="jquery-1.9.1.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#username').keyup(function(){ // Keyup function for check the user action in input
		var Username = $(this).val(); // Get the username textbox using $(this) or you can use directly $('#username')
		var UsernameAvailResult = $('#username_avail_result'); // Get the ID of the result where we gonna display the results
		if(Username.length > 2) { // check if greater than 2 (minimum 3)
			UsernameAvailResult.html('Loading..'); // Preloader, use can use loading animation here
			var UrlToPass = 'action=username_availability&username='+Username;
			$.ajax({ // Send the username val to another checker.php using Ajax in POST menthod
			type : 'POST',
			data : UrlToPass,
			url  : 'checker.php',
			success: function(responseText){ // Get the result and asign to each cases
				if(responseText == 0){
					UsernameAvailResult.html('<span class="success">Username name available</span>');
				}
				else if(responseText > 0){
					UsernameAvailResult.html('<span class="error">Username already taken</span>');
				}
				else{
					alert('Problem with sql query');
				}
			}
			});
		}else{
			UsernameAvailResult.html('Enter atleast 3 characters');
		}
		if(Username.length == 0) {
			UsernameAvailResult.html('');
		}
	});
	
	$('#password, #username').keydown(function(e) { // Dont allow users to enter spaces for their username and passwords
		if (e.which == 32) {
			return false;
  		}
	});
	$('#password').keyup(function() { // As same using keyup function for get user action in input
		var PasswordLength = $(this).val().length; // Get the password input using $(this)
		var PasswordStrength = $('#password_strength'); // Get the id of the password indicator display area
		
		if(PasswordLength <= 0) { // Check is less than 0
			PasswordStrength.html(''); // Empty the HTML
			PasswordStrength.removeClass('normal weak strong verystrong'); //Remove all the indicator classes
		}
		if(PasswordLength > 0 && PasswordLength < 4) { // If string length less than 4 add 'weak' class
			PasswordStrength.html('weak');
			PasswordStrength.removeClass('normal strong verystrong').addClass('weak');
		}
		if(PasswordLength > 4 && PasswordLength < 8) { // If string length greater than 4 and less than 8 add 'normal' class
			PasswordStrength.html('Normal');
			PasswordStrength.removeClass('weak strong verystrong').addClass('normal');			
		}	
		if(PasswordLength >= 8 && PasswordLength < 12) { // If string length greater than 8 and less than 12 add 'strong' class
			PasswordStrength.html('Strong');
			PasswordStrength.removeClass('weak normal verystrong').addClass('strong');
		}
		if(PasswordLength >= 12) { // If string length greater than 12 add 'verystrong' class
			PasswordStrength.html('Very Strong');
			PasswordStrength.removeClass('weak normal strong').addClass('verystrong');
		}
	});
});
</script>

</body>
</html>