<?php
require_once('Connections/cboxConn.php');
$my_dir =  __DIR__ . "/BrainTree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');

$customerForm = $_SERVER['PHP_SELF'];

$result = Braintree_Customer::create(array(
    "firstName" => $_POST["first_name"],
    "lastName" => $_POST["last_name"],
    "creditCard" => array(
        "number" => $_POST["number"],
        "expirationMonth" => $_POST["month"],
        "expirationYear" => $_POST["year"],
        "cvv" => $_POST["cvv"],
        "billingAddress" => array(
            "postalCode" => $_POST["postal_code"]
        )
    )
));

if ($result->success) {
    echo("Success! Customer ID: " . $result->customer->id);
	//insert into box_customer table: first_name, last_name, c_id
	echo("<a href='./subscription.php?customer_id=" . $result->customer->id . "'>Create subscription for this customer</a>");
} else {
    echo("Validation errors:<br/>");
    foreach (($result->errors->deepAll()) as $error) {
        echo("- " . $error->message . "<br/>");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">

    <title>Box Billing</title>

    <!-- Bootstrap core CSS -->
	<link href="dist/css/admin_page.css" rel="stylesheet">
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
 	<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="dist/css/jquery.qtip.css" />

    <!-- Custom styles for this template -->
    <link href="dist/css/signin.css" rel="stylesheet">


</head>

<body>
    <div class="container">
	
	<div id="navbar_main">
        <ul id="navbar_main_ul"> 
            <li id="home" ><a href="Admin_home_page.php" >HOME</a></li> 
            <li id="compare"><a href="user_compare_page.php" >COMPARE</a></li> 
            <li id="wod" ><a href="user_wod_page.php" >WOD</a></li> 
            <li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li>
            <li id="admin" ><a href="admin_page.php" >ADMIN</a></li>
            <li id="account" ><a href="tgv2.html" >ACCOUNT</a></li> 
            <li id="logout" ><a href="#" >LOGOUT</a></li>
        </ul> 
    </div>
		  
		  <form action="<?php echo $loginForm; ?>" method="POST" id="braintree-payment-form">
		  <h2>Customer Information</h2>
		  <p>
			<label>First Name as on Credit Card</label>
			<input type="text" name="first_name" id="first_name"></input>
		  </p>
		  <p>
			<label for="last_name">Last Name as on Credit Card</label>
			<input type="text" name="last_name" id="last_name"></input>
		  </p>
		  <p>
			<label for="street_address">Street Address</label>
			<input type="text" name="street_address" id="street_address"></input>
		  </p>
		   <p>
			<label for="city">City</label>
			<input type="text" name="city" id="city"></input>
		  </p>
		   <p>
			<label for="state">State</label>
			<input type="text" name="state" id="state"></input>
		  </p>
		  <p>
			<label for="postal_code">Zip</label>
			<input type="text" name="postal_code" id="postal_code"></input>
		  </p>
		  <h2>Credit Card</h2>
		  <p>
			<label>Card Number</label>
			<input type="text" size="20" autocomplete="off" data-encrypted-name="number" />
		  </p>
		  <p>
			<label>CVV</label>
			<input type="text" size="4" autocomplete="off" data-encrypted-name="cvv" />
		  </p>
		  <p>
			<label>Expiration (MM/YYYY)</label>
			<input type="text" size="2" data-encrypted-name="month" /> / <input type="text" size="4" data-encrypted-name="year" />
		  </p>
		  <input class="submit-button" type="submit" />
		</form>
		  
		  
		</div>
		<div> <a href='./searchforsubs.php'>Search for Subscriptions</a></div>
	</div> <!-- /container -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script id="source" language="javascript" type="text/javascript">

$(document).ready(function() {
	//getUserInfo();
});

function getUserInfo() {
	
	$.ajax({                                     
		url: "getuserinfov2.php", //the script to call to get data             
		success: function(response) //on recieve of reply
		{
			console.log("Billing get user info ajax: "+response);
			loadUserInfo(response);
		} 
	});
	
}

function loadUserInfo(data) {
	
	var fName = "";
	var lName = "";
	var street_address = ""; 
	var city = ""; 
	var state = ""; 
	var zip = "";
	
	var json = JSON.parse(data);
	
	console.log(json.first_name);
	
	fName = json.first_name;
	lName = json.last_name;

	street_address = json.street_address; 
	city = json.city; 
	state = json.state; 
	zip = json.zip; 
	
	console.log("First: "+fName);
	console.log("Last: "+lName);
	console.log("Street Address: " +street_address);
	console.log("City: " +city); 
	console.log("State: " +state); 
	console.log("Zip: " +zip); 
	
	
	$("#first_name").val(fName);
	$("#last_name").val(lName);
	$("#street_address").val(street_address);
	$("#city").val(city); 
	$("#state").val(state); 
	$("#postal_code").val(zip); 
}

function clearForm() {
	$('#register input').each(function(index, element) {
        console.log(index + " : " + $(this).text());
		$(this).val('');
    });
		
}
</script>

<script src="https://js.braintreegateway.com/v1/braintree.js"></script>
    <script>
      var braintree = Braintree.create('MIIBCgKCAQEA2MsdGEE1ziKzoMY4L3VqpqnUEM9PwAonaqGLhUY1K+/ftI/rYwE8eyfNn2P58dz12q0TPkKJ0XDE6UqX3sn13oEN07pclKLY3okek2Ny+90yeVx//xxipqk2bf8jk2E4eLCJmuWHgYZJjg3PKeBL3UWO9RnqNhm4kGRgy1lf+rN66gEin6L8bw3EF4ciLvUFuDOeRpKfoF0H9V2ed7zYs2CmTFZAYKceCW0cHoqIgkIs4DBqY1q/T4WtLujYwpNAqMPbxMKYOhh9XZPdWUBoQMJ7GNieGXTRzfwcviG4vatEh0KWBuJcDD3BtV+jpoDyXtqHpjNQKYdUV82Bk7AMxwIDAQAB');
      braintree.onSubmitEncryptForm('braintree-payment-form');
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