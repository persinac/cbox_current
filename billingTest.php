<?php
session_start();

if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
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
	<link href="dist/css/jquery.datepick.css" rel="stylesheet">
	
	<!-- Calendar stuff -->
	<link href='dist/fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='dist/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link href="dist/css/bryce_main.css" rel="stylesheet">
	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

    <!-- Custom styles for this template -->
    <link href="dist/css/signin.css" rel="stylesheet">
	<link href="dist/css/billing.css" rel="stylesheet">


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
		
		</div>
		<div id="cust_cc">
			<h1>Credit Cards</h1>
			<div id="cc_table_div">

			</div>
			<button class="btn btn-success btn-large" id="add_cc">Add Credit Card</button>

		</div>
        <div id="cust_subscriptions">
			<h1>Subscriptions</h1>
        	<div id="cust_subscriptions_content">
            
            </div>
			<button class="btn btn-success btn-large" id="add_sub">Add Subscription</button>
        </div>
	</div> <!-- /container -->

	<div id="dialog-modal">
		<div id="content"></div>
	</div>
	
	<div id="remove-modal" title="Are you sure?">
		<div id="rem_content">
			
		</div>
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
	getUserInfo(false);
	window.setTimeout(function(){checkForCustInfo()}, 500);
});

$("#navbar_main_ul li").click(function() {
	//event.preventDefault();
	var id = jQuery(this).attr("id");
	if(id=="logout" || id=="LOGOUT") {
		alert("LOGGING OUT");
		
		console.log("logging out...");
	
		$.ajax(
		{ 
			url: "cbox_logout.php", //the script to call to get data  
			success: function(response) //on recieve of reply
			{
				console.log("logged out...");
				window.location.replace("http://compete-box.com/login_bootstrap.php");
			} 
		});
	}
});	
var radio_val = "";
$( "#content" ).on("change", 'input[name="billing_info"]', function() {
	radio_val = $('input[name="billing_info"]:checked').val();
	console.log(radio_val);
	
	if(radio_val == "exists") {
		$("#cc_bill_fname").val(first_name);
		$("#cc_bill_lname").val(last_name);
		$("#cc_bill_address").val(address);
		$("#cc_bill_ext_address").val('');
		$("#cc_bill_city").val(city);
		$("#cc_bill_state").val(state);
		$("#cc_bill_zip").val(zip);
		$("#cc_bill_country").val(country);
	} else {
		$("#cc_bill_fname").val('');
		$("#cc_bill_lname").val('');
		$("#cc_bill_address").val('');
		$("#cc_bill_ext_address").val('');
		$("#cc_bill_city").val('');
		$("#cc_bill_state").val('');
		$("#cc_bill_zip").val('');
		$("#cc_bill_country").val('');
	}
	
});

$("#add_cc").click(function() {
		
	var form = '<form id="add_cc_form">';
	form += '<h3>New Credit Card</h3>';
	form += 'Name on Credit Card: <input type="text" name="cc_name" id="cc_name" required><p></p>';
	form += 'Credit Card Number: <input type="text" name="cc_num" id="cc_num" required><p></p>';
	form += 'CVV Number: <input type="text" name="cc_cvv" id="cc_cvv" required><p></p>';
	form += 'Expiration Date: <input type="text" name="cc_exp_month" class="new_cc_exp" id="cc_exp_month" required> / ';
	form += '<input type="text" name="cc_exp_year" class="new_cc_exp" id="cc_exp_year" required>';
	form += '<br/><h3>Billing Information</h3>';
	form += '<input type="radio" name="billing_info" value="exists">Use the information listed ';
	form += '<input type="radio" name="billing_info" value="new">New Billing Information';
	form += '<br/>First Name: <input type="text" name="cc_bill_fname" class="cc_bill_fname" id="cc_bill_fname" required>';
	form += '<br/>Last Name: <input type="text" name="cc_bill_lname" class="cc_bill_lname" id="cc_bill_lname" required>';
	form += '<br/>Address: <input type="text" name="cc_bill_address" class="cc_bill_address" id="cc_bill_address" required>';
	form += '<br/>Extended Address: <input type="text" name="cc_bill_ext_address" class="cc_bill_ext_address" id="cc_bill_ext_address" required>';
	form += '<br/>City: <input type="text" name="cc_bill_city" class="cc_bill_city" id="cc_bill_city" required>';
	form += '<br/>State: <input type="text" name="cc_bill_state" class="cc_bill_state" id="cc_bill_state" required>';
	form += '<br/>Zip: <input type="text" name="cc_bill_zip" class="cc_bill_zip" id="cc_bill_zip" required>';
	form += '<br/>Country: <input type="text" name="cc_bill_country" class="cc_bill_country" id="cc_bill_country" required>';
	form += '</form><button onclick="addNewCreditCard();" class="btn btn-success btn-large" id="submit_new_cc">Submit</button>';
	
	openModal("Add New Credit Card",form,"", 700,450);
	
});

$("#add_sub").click(function() {
		
	
	
	var form = '<form id="add_sub_form">';
	form += '<h3>Add Subscription</h3>';
	form += '<div id="sub_description">';
	form += 'Subscription Details:<br/>';
	form += '- $1.50/member and free for the first 10 members<br/>';
	form += '- Access to all features<br/>';
	form += '- Billed monthly based on current members<br/>';
	form += '- Future updates and upgrades at no cost<br/>';
	form += '- 24/7 support<br/>';
	form += '- 1 month free trial!<br/>';
	form += 'Number of current members: <input type="text" name="sub_num_members" class="subscription_form" id="sub_num_members" required>';
	form += '<br/>Startup fee: $2.00 ';
	form += '<br/>Total Charge: <input type="text" name="sub_total" class="subscription_form" id="sub_total" readonly>';
	form += '<br/>Monthly Bill hereafter: <input type="text" name="sub_total_monthly" class="subscription_form" id="sub_total_monthly" readonly>';
	form += '<div id="chooseCC"></div>'
	form += '</div></form><button onclick="subscribe();" class="btn btn-success btn-large" id="sub_choose_CC">Subscribe</button>';

	openModal("Subscribe",form,"", 500,600);
	
});

$( this ).focusout(function (event) {
	var id = event.target.id;
	var total = "";
	var weightReg = /^[0-9\/]*$/;
	/* RX Form */
	if ( id.indexOf("sub_num_members") >= 0 )
	{
		var value = $("#" + id).val();
		console.log(value);
		if(!weightReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
			if(value > 10) { 
			total = 2 + (value * 1.5);
			} else {
				total = 2;
			}
			var n = total.toFixed(2);
			$("#sub_total").val(n);
			n = n - 2;
			var m = n.toFixed(2);
			$("#sub_total_monthly").val(m);
		}
	}
});

function subscribe() {
	console.log("Clicked subscribe");
	var form_data = $("#add_sub_form").serializeArray();
	
	form_data.push({ name:"c_id", value:cust_id});
	
	$.each(form_data, function(i, field) {
		console.log("DATA: " + field.name +":"+field.value);
	});
	
	$.ajax({      
		type: "POST",
		url: "setupNewSubscription.php", //the script to call to get data 
		data: form_data,
		success: function(response) //on recieve of reply
		{
			console.log("Setup new subscription ajax: "+response);
			if(response.substring(0,1) == "T") {
				closeModal();
				openModal("Success", response, "", 300, 300);
				getCreditCardSubscriptions();
			} else {
				closeModal();
				openModal("Error!", response, "", 300, 300);
			}
		} 
	});
	
}

var box_id = -1;
var cust_id = 0;

var first_name;
var last_name;
var address;
var city;
var state;
var zip;
var country;


function checkForCustInfo() {
	
	$.ajax({                                     
		url: "checkForCustBilling.php", //the script to call to get data             
		success: function(response) //on recieve of reply
		{
			console.log("Checked for customer billing: "+response);
			if(response == "1") {
				askToCreateNewCustomer();
			} else {
				loadCustomerInformation(response);
				getUserInfo(true);
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
	html += '<p><label>First Name</label>';
	html += '<input type="text" name="first_name" id="first_name"></input>';
	html += '</p><p><label for="last_name">Last Name</label>';
	html += '<input type="text" name="last_name" id="last_name"></input>';
	html += '</p><p><label for="email">Email</label>';
	html += '<input type="text" name="email" id="email"></input>';
	html += '</p><p><label for="phone">Phone</label>';
	html += '<input type="text" name="phone" id="phone"></input>';
	html += '</p><p><label for="fax">Fax</label>';
	html += '<input type="text" name="fax" id="fax"></input>';
	html += '</p><p><label for="company">Company</label>';
	html += '<input type="text" name="company" id="company"></input>';
	html += '</p>';
	/**********************************
	html += '</p><h2>Credit Card</h2><p><label>Card Number</label><div id="new_cust_form_cc">';
	html += '<input type="text" size="20" autocomplete="off" data-encrypted-name="number" />';
	html += '</p><p><label>CVV</label>';
	html += '<input type="text" size="4" autocomplete="off" data-encrypted-name="cvv" />';
	html += '</p><p><label>Expiration (MM/YYYY)</label>';
	html += '<input type="text" size="2" data-encrypted-name="month" /> / <input type="text" size="4" data-encrypted-name="year" />';
	*************************************/
	html += '</form>';
	html += '<button onclick="submitNewCustomer();" class="btn btn-success" id="submitNewCustomer">Submit</button>';
	console.log(html);
	openModal("New Customer Form",html);
}


function getUserInfo(variable) {
	
	$.ajax({                                     
		url: "getuserinfov2.php", //the script to call to get data             
		success: function(response) //on recieve of reply
		{
			console.log("Billing get user info ajax: "+response);
			if(variable == true) {
				loadUserInfo(response); 
				getUserCreditCards();
				getCreditCardSubscriptions();
			} else {
				var json = JSON.parse(response);
				box_id = json.box_id;
			}
		} 
	});
	
}

function getUserCreditCards(someString) {
	var opt_var = (typeof someString === "undefined") ? "n" : "yes";
	var t_c_id = cust_id;
	console.log("JS: "+t_c_id + ", variable: "+opt_var);
	
	$.ajax({      
		type: "POST",
		url: "getUserCreditCards.php", //the script to call to get data 
		data: { "c_id":t_c_id,
				"optional":opt_var },
		success: function(response) //on recieve of reply
		{
			console.log("Billing get user credit card(s) ajax: "+response);
			loadUserCreditCards(response, opt_var);
		} 
	});
}

function getCreditCardSubscriptions() {
	var t_c_id = cust_id;
	$.ajax({      
		type: "POST",
		url: "getUserSubscriptions.php", //the script to call to get data 
		data: { "c_id":t_c_id },
		success: function(response) //on recieve of reply
		{
			console.log("Get user subscription(s) ajax: "+response);
			loadUserSubscriptions(response);
		} 
	});
}

function loadUserInfo(data) {
	
	var fName = "";
	var lName = "";
	var street_address = ""; 
	var t_city = ""; 
	var t_state = ""; 
	var t_zip = "";
	
	var json = JSON.parse(data);
	
	fName = json.first_name;
	lName = json.last_name;

	street_address = json.street_address; 
	t_city = json.city; 
	t_state = json.state; 
	t_zip = json.zip; 
	
	console.log("First: "+fName);
	console.log("Last: "+lName);
	console.log("Street Address: " +street_address);
	console.log("City: " +t_city); 
	console.log("State: " +t_state); 
	console.log("Zip: " +t_zip); 
	
	
	$("#fName").val(fName);
	$("#lName").val(lName);
	$("#street_address").val(street_address);
	$("#city").val(t_city); 
	$("#state").val(t_state); 
	$("#zip").val(t_zip); 
	
	first_name = fName;
	last_name = lName;
	address = street_address;
	city = t_city
	state = t_state;
	zip = t_zip;
	country = "US";
}

function loadCustomerInformation(data) {
	var u_id = "";
	var b_id = "";
	var c_id = ""; 
	var s_id = ""; 
	
	var json = JSON.parse(data);
	
	u_id = json.user_id;
	b_id = json.box_id;
	c_id = json.customer_id; 
	s_id = json.subscription_id; 
	
	cust_id = c_id;
	
	console.log("User: "+u_id);
	console.log("Box: "+b_id);
	console.log("Customer: " +c_id);
	console.log("Subscription: " +s_id); 
	
	loadHTML();
	
	$("#c_id").val(c_id);
}

function loadHTML() {
	var html = "";
	
	html +='<p><label>Customer ID:</label><input type="text" size="20" autocomplete="off" id="c_id" readonly/>';
	html +='</p><p><label>First Name</label><input type="text" autocomplete="off" id="fName" />';
	html +='</p><p><label>Last Name</label><input type="text"  name="lName" id="lName"/>';
	html +='</p><p><label>Street Address</label><input type="text"  name="st_address" id="street_address"/>'; 
	html +='</p><p><label>City</label><input type="text"  name="city" id="city"/>';
	html +='</p><p><label>State</label><input type="text"  name="state" id="state"/>';			  
	html +='</p><p><label>Zip</label><input type="text"  name="zip" id="zip"/></p>';
	
	$("#cust_billing").html(html);
}


function loadUserCreditCards(data, opt) {
	if(opt == "n") {
		$("#cc_table_div").html(data);
	} else {
		chooseCreditCard(data);
	}
}

function loadUserSubscriptions(data) {

		$("#cust_subscriptions_content").html(data);

}

function openModal(title, info, shouldFormat, height, width) {
    
	var opt_height = (typeof height === "undefined") ? 600 : height;
	var opt_width = (typeof width === "undefined") ? 500 : width;
	console.log(info);
    $( "#dialog-modal" ).dialog({
      height: opt_height,
	  width: opt_width,
      modal: true
    });
	
	$( "#dialog-modal" ).dialog('option', 'title', title);
	$("#content").html(info);
	if(title == "Subscribe") {
		console.log("OPEN MODAL SUB");
		getUserCreditCards("yes");
	}
}


function clearForm() {
	$('#register input').each(function(index, element) {
        console.log(index + " : " + $(this).text());
		$(this).val('');
    });
		
}

function addNewCreditCard() {
	var data = $("#add_cc_form").serializeArray();
	data.push({ name:"customer_id", value:cust_id});
	sendRequest = true;
	$.each( data, function(i, field) {
		console.log("DATA: " + field.name +":"+field.value);
		if(field.value.length < 1 && field.name != "cc_bill_ext_address") {
			console.log(field.name + " is empty, please fill in");
			sendRequest = false;
		}
	});	
	
	if(sendRequest == true) {
		$.ajax({    
			type: "POST",
			url: "addNewCreditCard.php", //the script to call to get data  
			data: data,
			success: function(response) //on recieve of reply
			{
				console.log("Created new credit card: "+response);
				getUserCreditCards();
				closeModal();
				openModal("Success","Created new card success!", "", 200, 200);
			} 
		});
	}
	
}

function promptToRemoveCC(rnum) {
	
	var title = "Are you sure?";
	var html = '<p>Are you sure you wish to remove this card?</p>';
	html += '<p>(canceling this card will also cancel all subscriptions tied to this card)</p>';
	html += '<p><button onclick="removeCreditCard('+rnum+');" class="btn btn-success">OK</button><button onclick="closeRemoveModal();" class="btn btn-success">Cancel</button></p>';
		
	$( "#remove-modal" ).dialog({
      height: 400,
	  width: 400,
      modal: true
    });
	
	$( "#remove-modal" ).dialog();
	$("#rem_content").html(html);
}

function promptToRemoveSub(rnum) {
	
	var title = "Are you sure?";
	var html = '<p>Are you sure you wish to cancel this subscription?</p>';
	html += '<p><button onclick="cancelSubscription('+rnum+');" class="btn btn-success">OK</button><button onclick="closeRemoveModal();" class="btn btn-success">Cancel</button></p>';
		
	$( "#remove-modal" ).dialog({
      height: 400,
	  width: 400,
      modal: true
    });
	
	$( "#remove-modal" ).dialog();
	$("#rem_content").html(html);
}

function removeRow(rnum) {
	
	promptToRemoveCC(rnum);

}

function removeSubRow(rnum) {
	
	promptToRemoveSub(rnum);

}

function removeCreditCard(rnum) {
	var value = document.getElementById("uni_"+rnum).value;
	console.log("VALUE: " + value);
	$('#'+rnum).remove();
	$.ajax({    
			type: "POST",
			url: "removeCreditCard.php", //the script to call to get data  
			data: { "cc_token":value },
			success: function(response) //on recieve of reply
			{
				console.log("Removed credit card: "+response);
				closeRemoveModal();
				getUserCreditCards();
				getCreditCardSubscriptions();
			} 
		});
	
}

function cancelSubscription(rnum) {
	var value = document.getElementById("uni_sub_"+rnum).value;
	console.log("VALUE: " + value);
	$('#sub_'+rnum).remove();
	$.ajax({    
			type: "POST",
			url: "cancelSubscription.php", //the script to call to get data  
			data: { "sub_token":value },
			success: function(response) //on recieve of reply
			{
				console.log("Canceled subscription: "+response);
				closeRemoveModal();
				getCreditCardSubscriptions();
			} 
		});
	
}

function submitNewCustomer() {
	var data = $("#braintree-payment-form").serializeArray();
	
	data.push({ name:"box_id" , value:box_id});
	
	$.each( data, function(i, field) {
		console.log("DATA: " + field.name +":"+field.value);
	});
	
	$.ajax({    
		type: "POST",
		url: "createNewCustomer.php", //the script to call to get data  
		data: data,
		success: function(response) //on recieve of reply
		{
			console.log("Created new customer: "+response);
			redirectNewUserResponse(response);
		} 
	});
}

function chooseCreditCard(msg) {
	console.log("Choose credit card!");
	var html = "<h3>Choose a credit card:</h3>";
	html += "<select name=\"cc_selector\" id=\"cc_selector\">";
	html += msg;
	html += "</select>";

	console.log(html);
	$("#chooseCC").html(html);
}

function redirectNewUserResponse(data) {
	var t_string = "";
	var title = "Create Customer Failed";
	var success = true;
	if(data.substring(0, 1) == "0") {
		//console.log("redirect: "+data)
		t_string = "SQL insert successful<p></p>";
	} else if(data.substring(0, 1) == "1") {
		//console.log("redirect: "+data)
		t_string = "SQL insert unsuccessful<p></p>";
		success = false;
	}
	
	if(data.substring(1, 2) == "S") {
		//console.log("redirect: "+data)
		t_string += "New customer created<p></p>";

	} else if(data.substring(1, 2) == "V") {
		//console.log("redirect: "+data)
		t_string += "New customer not created<p></p>";
		success = false;
	}
	
	if(success == true) {
		title = "Create Customer Success";
		checkForCustInfo();		
	}
	
	console.log(t_string);
	openModal(title, t_string, "", 300, 300);
}

function closeRemoveModal() {
	$("#remove-modal").dialog("close");
}

</script>

<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
    <script>
      var braintree = Braintree.create('MIIBCgKCAQEAvXsKKRf+ydZzSxI4KEJQ31RWfUYN0DuMPxjWYFW3jL1b6aSFWoKDDrh341W+IOT28Mng776bVEBpeuQkbJGzfv15NRhIW9F96JltJluwuqovFRaTxzrK50nzmx6us9Oz0MOxvJFggjFaNSyWZWQ1iujYF1/5fFiQRboxZyy0jYpzFU0JykvQ+PqhhKmczzAyNrS6qrupWb3ggmtzxVs8yCdTPCPOEyKUf8GpG/LnPj/9yANPPaN+HDcovUpS9DXGtb01A0ai4C86o3f8X1nxZGHKV068l/fhlX89YrGjJmnsjJl4SalXJO//14hxdTpfnXZoc09SflNEjaznBu8qgwIDAQAB');
	  braintree.onSubmitEncryptForm('braintree-payment-form');
    </script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50665970-1', 'compete-box.com');
  ga('send', 'pageview');

</script>

</body>
</html>