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
	<link href="dist/css/jquery.datepick.css" rel="stylesheet">
	
	<!-- Calendar stuff -->
	<link href='dist/fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='dist/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link href="dist/css/bryce_main.css" rel="stylesheet">
	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

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
	
		<h1>Customer Billing Information</h1>
		<div id="cust_billing">
		  <!--<form action="transaction.php" method="POST" id="braintree-payment-form">
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
			  <input type="text" size="2" name="month" /> / <input type="text" size="4" name="year" />
			</p>
			<input type="submit" id="submit" />
		  </form>-->
		  
		<div> <a href='./searchforsubs.php'>Search for Subscriptions</a></div>
	</div> <!-- /container -->

	<div id="dialog-modal">
		<div id="content"></div>
	</div>
	
<!-- These must be in THIS ORDER! DO NOT FUCK WITH -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>


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

$(document).ready(function() {
	checkForCustInfo();
	//getUserInfo();
});

function checkForCustInfo() {
	
	$.ajax({                                     
		url: "checkForCustBilling.php", //the script to call to get data             
		success: function(response) //on recieve of reply
		{
			console.log("Checked for customer billing: "+response);
			if(response == "1") {
				askToCreateNewCustomer();
			} else {
				//loadCustomerInformation();
			}
		} 
	});
	
}

function askToCreateNewCustomer() {
	var modal = "dialog-modal";
	var prompt = "You have not set up a Customer account yet, would you like to now?<p></p><p></p>";
	prompt += '<button onclick="createNewCustomer();" class="btn btn-success" id="createAsNewCustY">Yes</button>';
	prompt += '<button onclick="closeModal();" class="btn btn-success" id="createAsNewCustN">No</button>';
	
	openModal("New Customer",prompt, "", 400, 300);
}

function closeModal() {
	$("#dialog-modal").dialog("close");
}

function createNewCustomer() {
	$("#cust_billing").empty();
	closeModal("dialog-modal");
	var html = "";
	
	html = '<form id="braintree-payment-form">';
		  html += '<h2>Customer Information</h2>';
		  html += '<p><label>First Name as on Credit Card</label>';
	html += '<input type="text" name="first_name" id="first_name"></input>';
	html += '</p><p><label for="last_name">Last Name as on Credit Card</label>';
	html += '<input type="text" name="last_name" id="last_name"></input>';
	html += '</p><p><label for="street_address">Street Address</label>';
	html += '<input type="text" name="street_address" id="street_address"></input>';
	html += '</p><p><label for="city">City</label>';
	html += '<input type="text" name="city" id="city"></input>';
	html += '</p><p><label for="state">State</label>';
	html += '<input type="text" name="state" id="state"></input>';
	html += '</p><p><label for="postal_code">Zip</label>';
	html += '<input type="text" name="postal_code" id="postal_code"></input>';
	html += '</p><h2>Credit Card</h2><p><label>Card Number</label>';
	html += '<input type="text" size="20" autocomplete="off" data-encrypted-name="number" />';
	html += '</p><p><label>CVV</label>';
	html += '<input type="text" size="4" autocomplete="off" data-encrypted-name="cvv" />';
	html += '</p><p><label>Expiration (MM/YYYY)</label>';
	html += '<input type="text" size="2" data-encrypted-name="month" /> / <input type="text" size="4" data-encrypted-name="year" />';
	html += '</p></form>';
	html += '<button class="btn btn-success" id="submitPartToText">Submit</button>';
	console.log(html);
	openModal("New Customer",html);
}


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

function openModal(title, info, shouldFormat, height, width) {
    
	var opt_height = (typeof height === "undefined") ? 800 : height;
	var opt_width = (typeof width === "undefined") ? 600 : width;
	console.log(info);
    $( "#dialog-modal" ).dialog({
      height: opt_height,
	  width: opt_width,
      modal: true
    });
	
	$( "#dialog-modal" ).dialog('option', 'title', title);
	$("#content").html(info);
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