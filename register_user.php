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
	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

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
        Male <input type="radio" name="gender" class="radio_butts" value="M">
                Female <input type="radio" name="gender" class="radio_butts" value="F">
          <br></br> 
          <br></br>     
        <input type="email" name="email"class="form-control" placeholder="Email" required>
        <br></br>
        <input type="text" name="box_id" id="box_id" class="form-control" placeholder="box id" required>
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
		Are you an administrator?
		Yes <input type="radio" name="admin" class="admin_butts" value="Y">
                No <input type="radio" name="admin" class="admin_butts" value="N">
          <br></br> 
        <br><h3>Login Info</h3></br>
        <input type="text" name="username" id="username" class="form-control" placeholder="Desired Username" required>
        <br></br>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <br></br>
        <button name="submit" class="btn btn-lg btn-primary " id="submit_new_user" type="submit">Register</button>
      </form>

	  <div id="admin_modal">
		<div id="modal_content">
			
		</div>
	</div>
	  
</div> <!-- /container -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<!-- Required for full calendar -->
<script src='dist/lib/moment.min.js'></script>
<script src="dist/jq_ui/js/jquery-1.10.2.js"></script>
<script src="dist/jq_ui/js/jquery-ui-1.10.4.custom.min.js"></script>

<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>

<script id="source" language="javascript" type="text/javascript">

adminKey = false;
globalCount = 0;

$(function() {
	//twitter bootstrap script
	$("button#submit_new_user").click(function(e){
		e.preventDefault();
		submit_new_user(false);
	});
});

$( "#modal_content" ).on("click","#submit_admin_key",function(){
	var key = $("#modal_content :text").val();
	//check key against the key in the DB
	checkKey(key);
	//set window time out before continuing
	setTimeout(function(){
				if(adminKey === true) {
					console.log("It worked!");
					$("#admin_modal").dialog('close');
					submit_new_user(true);
					
				} else {
					console.log("It didn't work...");
					$("#admin_modal :input").addClass("_input_error");
					/*$('#admin_modal :input').qtip({ 
						content: 'Invalid Key, please try again. It IS case sensitive.'
					});*/
					globalCount = globalCount+1;
				}
	}, 500);
});

function submit_new_user(bool)
{
	var datastring = $("#register").serializeArray();
	var sendRequest = true;
	var username = "";
	var content = "";
	var isAdmin = false;
	console.log("BOOL: " + bool);
	$.each(datastring, function(i, field){
		if(field.value == "") {
			sendRequest = false;	
		}
		if(field.name == "username") {
			username = field.value;	
		} 
		if(field.name == "admin") {
			if(bool === false) {
				if(field.value == "Y") {
					isAdmin = true;
					content = "Please enter the Admin key we emailed you:<br/><br/>";
					content += "<input type=\"text\" name=\"admin_key\" id=\"admin_key\" /><br/><br/>";
					content += "<button name=\"submit_admin_key\" class=\"btn btn-lg btn-primary\" id=\"submit_admin_key\" type=\"submit\">Submit</button>";
					//openModal(content);
				}
			} else {
				//just in case...
			}
		}
  	});

	if(bool === false && isAdmin === true) {
		openModal(content);
	} else {
		$.ajax({
			type: "POST",
			url: "checkForUsername.php",
			data: {"username" : username},
			success: function(data) {
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
	}	
	username = "";
}

function registerUser(data)
{
	var isAdmin = "0";
	var sendRequest = true;
	$.each(data, function(i, field){
    	console.log("DATA: " +field.name + ":" + field.value + " ");
		if(field.value == "") {
			sendRequest = false;	
		}
		if(field.name == "admin") {
			if(field.value == "Y") {
				isAdmin = "1";
			}
		}
  	});
	console.log(isAdmin);
	data.push({name:"isAdmin", value:isAdmin});
	$.each(data, function(i, field){
    	console.log("DATA TWO: " +field.name + ":" + field.value + " ");
  	});
	if(sendRequest == true) {
	$.ajax({
            type: "POST",
            url: "queryRegisterUser.php",
            data: data,
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

function overlay(data) {
	console.log(data.substring(0,1) + ", " + data.substring(1,2));
	if(data.substring(0,1) == "1" && data.substring(1,2) == "1") {
		alert("Registration Successful");
		clearForm();
	} else { alert("Contact System Administrator");}
}

function clearForm() {
	$('#register input').each(function(index, element) {
        console.log(index + " : " + $(this).text());
		$(this).val('');
    });
}

function openModal(content) {

	$( "#admin_modal" ).dialog({
		height: 300,
		width: 400,
		modal: true
	});
	
	$("#forgot_modal").dialog();
	$("#modal_content").html(content);
}

function checkKey(keyToCheck) {
	var b_id = $("#box_id").val();
	console.log("check key values: " + keyToCheck + ", " + b_id);
	$.ajax({
		type:"POST",
		url: "checkAdminKey.php",
		data: {
			"key":keyToCheck,
			"box_id":b_id
			},
		dataType: "text",
		success: function(response) {
			console.log(response);
			if(response.substring(0,1) == "1") {
				adminKey = true;
			} else {
				alert(response);
				adminKey = false;
			}
		}
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