<?php require_once('Connections/cboxConn.php'); ?>
<?php 
session_start();
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$link = "index.html";
if ($_SESSION['MM_Admin'] == "0") {$link = "User_Home_Page.php";} //Default Blank 
else if ($_SESSION['MM_Admin'] == "1") {$link = "Admin_home_page.php";} // COMMENT 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">

    <title>CBOX User Information</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dist/css/signin.css" rel="stylesheet">

</head>

<body>
	<div class ="container" style="text-align:center">
	<form action="http://compete-box.com/<?php echo $link; ?>">
    <input type="submit" class = "btn btn-lg btn-primary" value="Homepage">
	

</form>

</div>
    <div class="container">
      <form METHOD="POST" name="register" class="form-signin" id="register"role="form">
        <h2 class="form-signin-heading">User Information</h2>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required autofocus>
        <br></br>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" required>
        <br></br>
        Male <input type="radio" name="gender" id = "genderM" class="radio_butts" value="M" disabled>
                Female <input type="radio" name="gender" id = "genderF" class="radio_butts" value="F" disabled>
          <br></br> 
          <br></br>     
        <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
        <br></br>
        <input type="text" name="box_id" id="box_id" class="form-control" placeholder="box id" disabled required>
        <br></br>
        <input type="text" name="street_address" id = "street_address" class="form-control" placeholder="Street Address" required>
        <br></br>
        <input type="text" name="city" id = "city" class="form-control" placeholder="City" required>
        <br></br>
		<input type="text" name="state" id = "state" class="form-control" placeholder="State" required>
        <br></br>
        <input type="text" name="zip_code" id = "zip" class="form-control" placeholder="Zip Code" required>
        <br></br>
        <input type="text" name="country" id = "country" class="form-control" placeholder="Country" required>
        <br></br>
        Region: <select id="region_selector" name="region" class="selector">
        <option value="Africa">Africa</option>
        <option value="Asia">Asia</option>
        <option value="Australia">Australia</option>
        <option value="Canada East">Canada East</option>
        <option value="Canada West">Canada West</option>
        <option value="Central East">Central East</option>
        <option value="Europe">Europe</option>
        <option value="Latin America">Latin America</option>
        <option value="Mid-Atlantic">Mid-Atlantic</option>
        <option value="Northern California">Northern California</option>
        <option value="North Central">North Central</option>
        <option value="North East">North East</option>
        <option value="North West">North West</option>
        <option value="South Central">South Central</option>
        <option value="South East">South East</option>
        <option value="South West">South West</option>
        <option value="Southern California">Southern California</option>
        </select>
        <br></br>
        <br><h3>Login Info</h3></br>

        <input type="text" name="username" id="username" class="form-control" placeholder="Desired Username" disabled required>
        <br></br>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <br></br>

        <button name="submit" class="btn btn-lg btn-primary" id="edit_user" type="submit">Edit Information</button>
	
</div> <!-- /container -->

<!jQuery>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>


<!-- Required for full calendar -->
<script src='dist/lib/moment.min.js'></script>
<script src="dist/jq_ui/js/jquery-1.10.2.js"></script>
<script src="dist/jq_ui/js/jquery-ui-1.10.4.custom.min.js"></script>

<!-- Required for date picker -->
<script src="dist/js/jquery.plugin.min.js"></script>

<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>
<script id="source" language="javascript" type="text/javascript">

$(document).ready(function() {
	getUserInfo();
	getUserInfov2(); 
});

$(function() {
	//twitter bootstrap script
	$("button#edit_user").click(function(e){
		alert("Thank You");
		e.preventDefault();
		var e =$("#register").serializeArray(); 
		edit_user(e);
	});
});


//Method to call edit_user
function edit_user(data)
{
	var sendRequest = true;
	//alert("Let's see...");
	$.each(data, function(i, field){
    	console.log("DATA: " +field.name + ":" + field.value + " ");
		if(field.value == "") {
			sendRequest = false;	
		}
  	});
	//sendRequest = false;
	
	if(sendRequest == true) 

	{
	$.ajax({
            type: "POST",
            url: "edit_user.php",
            data: data,
	    dataType: "text", 
            success: function(data) {
            	console.log("Return val: " + data);
				 overlay(data); 
			},
			error: function(data) {
				console.log("Error?" + data);
			}
        });	
	}
	
}


function getUserInfo() {
	$.ajax({
		type:"POST",                                     
		url:"getuserinfov2.php",         
		dataType: "json",                //data format      
		success: function(response) //on recieve of reply
		{
			console.log(response);
			console.log("loaded user information successfully!");
			loadUserInfo(response);
		},
		error: function(){
			alert('error loading user info!');
		}
	});
}

function getUserInfov2() {
	$.ajax({
		type:"POST",                                     
		url:"getuserinfov3.php",         
		dataType: "json",                //data format      
		success: function(response) //on recieve of reply
		{
			console.log(response);
			console.log("loaded user information successfully!");
			loadUserInfo2(response);
		},
		error: function(){
			alert('error loading user info part 2');
		}
	});
}

function loadUserInfo(data) {

	var fName = "";
	var lName = "";

	var gender = ""; 

	var email = "";
	var box_id = "";
	var street_address = ""; 
	var city = ""; 
	var state = ""; 
	var zip = ""; 
	var country= ""; 

	fName = data.first_name;
	lName = data.last_name;

	gender = data.gender; 

	email = data.email;
	box_id = data.box_id;
	street_address = data.street_address; 
	city = data.city; 
	state = data.state; 
	zip = data.zip; 
	country = data.country; 
	region = data.region; 
	
	console.log("First: "+fName);
	console.log("Last: "+lName);

	console.log("Gender: " +gender); 

	console.log("Email: "+email);
	console.log("Box: "+box_id);
	console.log("Street Address: " +street_address);
	console.log("City: " +city); 
	console.log("State: " +state); 
	console.log("Zip: " +zip); 
	console.log("country: " +country);  
	console.log("Region: " +region); 
	
	
	$("#first_name").val(fName);
	$("#last_name").val(lName);

	setRadioButton(gender); 
	
	
	$("#email").val(email);
	$("#box_id").val(box_id);
	$("#street_address").val(street_address);
	$("#city").val(city); 
	$("#state").val(state); 
	$("#zip").val(zip); 
	$("#country").val(country); 
	
	SelectElement(region); 
		 
}

function SelectElement(valueToSelect)
{    
    console.log("Select Element: " + valueToSelect);  
	var element = document.getElementById("region_selector");
	console.log("Element: " + element); 
    element.value = valueToSelect;
}

function setRadioButton(valueToSet)
{
	if (valueToSet == "M") 
	{
		document.getElementById("genderM").checked = true; 
	}
	else
	{
		document.getElementById("genderF").checked = true; 
	}
}

function loadUserInfo2(data) {
	var username =data.username; 
	var password =data.password; 
	 
	console.log("Username: " +username);
	console.log("Password: " +password); 
	$("#username").val(username);
	$("#password").val(password);  
	
}


function overlay(data) {
	if(data == "1") {
		alert("Contact System Administrator");
		
	} else { alert("User Information Edited");}
	clearForm();
}

function clearForm() {
	$('#register input').each(function(index, element) {
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
