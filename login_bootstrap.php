<?php require_once('Connections/cboxConn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
} else if (isset($_SESSION)) {
  session_destroy();
  session_start();	
}

### For the live server ###
/*
if($_SERVER["HTTPS"] != "on")
{
	header("Location: https://" . "compete-box.com\/" . $_SERVER["REQUEST_URI"]);
	exit();
}
*/
$loginForm = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) 
{
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) 
{
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "Error401UnauthorizedAccess.php";
  $MM_redirectLoginFailed = "login_bootstrap.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_cboxConn, $cboxConn);
  
  $LoginRS__query=sprintf("SELECT user_id, username, password, admin FROM login WHERE username=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $cboxConn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    $row = mysql_fetch_assoc($LoginRS);
	if (PHP_VERSION >= 5.1) {
		session_regenerate_id(true);
	} 
	else {
			ession_regenerate_id();
	}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	
	$_SESSION['MM_UserID'] = $row['user_id'];  
	$_SESSION['MM_Admin'] = $row['admin'];    

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	$returnVal = isAuthorized($_SESSION['MM_Admin'], $_SESSION['MM_Username']);

   	if ($returnVal == "0") {$link = "User_Home_Page.php";} //Default Blank 
	if ($returnVal == "1") {$link = "Admin_home_page.php";;} // COMMENT 
	if ($returnVal == "9") {$link = "Error401UnauthorizedAccess.php";} // COMMENT 

	header("Location: $link"); // Jump to the link
 
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<?php
function isAuthorized($adminCode, $UserName)
{ 
  // For security, start by assuming the visitor is NOT authorized. 
  $value = 9; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) 
  { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login.  
    // Or, you may restrict access to only certain users based on their username. 
    if ($adminCode == 0) { 
      $valid = 0; 
    } 
    else if ($adminCode == 1) { 
      $valid = 1; 
    } 
  } 
  return $valid; 
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

    <title>CBOX SIGNIN</title>
	

    <!-- Bootstrap core CSS -->
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="dist/css/jquery.qtip.css" />
	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
	
    	<!-- Custom styles for this template -->
    <link href="dist/css/signin.css" rel="stylesheet">
	

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form ACTION="<?php echo $loginForm; ?>" METHOD="POST" name="login" class="form-signin" role="form">
        <h2 class="form-signin-heading">CBox sign in</h2>
        <input type="username" name="username"class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password"class="form-control" placeholder="Password" required>
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <button name="submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		<div>
			<a class="forgot_link" id="forgot_username" href="#">Forgot Username?</a>
			<a class="forgot_link" id="forgot_password" href="#">Forgot Password?</a>
		</div>
      </form>
	
    </div> <!-- /container -->

	<div id="forgot_modal">
		<div id="modal_content">
			
		</div>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
<!-- These must be in THIS ORDER! DO NOT FUCK WITH -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<!-- Required for full calendar -->
<script src='dist/lib/moment.min.js'></script>
<script src="dist/jq_ui/js/jquery-1.10.2.js"></script>
<script src="dist/jq_ui/js/jquery-ui-1.10.4.custom.min.js"></script>

<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>

	<script>
	$("#forgot_username").click( function() {
		var html = "";
		
		html += '<form id="username_request">';
		html += 'Box name that you are registered to: <input type="text" name="box_name" id="box_name"/><br/><br/>';
		html += 'Email associated with account: <input type="text" name="email" id="email"/><br/><br/>';
		html += '<input type="button" name="btn_request_un" id="btn_request_un" class="btn btn-lg btn-primary btn-block" onclick="sendUsernameToEmail();" value="Email me">';
		html += '</form>';
		openModal(html);
	
	});
	
	$("#forgot_password").click( function() {
		var html = "";
		
		html += '<form id="password_request">';
		html += 'Box name that you are registered to: <input type="text" name="box_name" id="box_name"/><br/><br/>';
		html += 'Email associated with account: <input type="text" name="email" id="email"/><br/><br/>';
		html += '<input type="button" name="btn_request_pw" id="btn_request_pw" class="btn btn-lg btn-primary btn-block" onclick="sendPasswordToEmail();" value="Email me">';
		html += '</form>';
		openModal(html);
	
	});
	
	function openModal(content) {
		
		$( "#forgot_modal" ).dialog({
			height: 300,
			width: 400,
			modal: true
		});
		
		$("#forgot_modal").dialog();
		$("#modal_content").html(content);
	}
	
	function sendUsernameToEmail() {
		var data = $("#username_request").serializeArray();
		var box_name = "";
		var email = "";
		var boxReg = /^[a-zA-Z0-9\s]*$/;
		var emailReg = /[^\s@]+@[^\s@]+\.[^\s@]+/;
		var valid = false;
		$.each(data, function(i, field) {
			console.log("DATA: "+field.name +" : "+field.value);
			if(field.name == "box_name") {
				console.log("Send box name to validateBox()");
				box_name = field.value;
				
				if(!boxReg.test(box_name))
				{
					$("#box_name").addClass("_input_error");
					valid = false;
					console.log("BOX NAME - valid: " + valid);
				} else {
					
					$("#box_name").removeClass("_input_error");
					valid = true;
					console.log("BOX NAME - valid: " + valid);
				}
			} else {
				email = field.value;
				
				if(!emailReg.test(email))
				{
					
					$("#email").addClass("_input_error");
					valid = false;
					console.log("EMAIL - valid: " + valid);
				} else {
					
					$("#email").removeClass("_input_error");
					valid = true;
					console.log("EMAIL - valid: " + valid);
				}
				
			}
		});
		validateForm(box_name, email, valid, "u");
	}
	
	function sendPasswordToEmail() {
		var data = $("#password_request").serializeArray();
		var box_name = "";
		var email = "";
		var boxReg = /^[a-zA-Z0-9\s]*$/;
		var emailReg = /[^\s@]+@[^\s@]+\.[^\s@]+/;
		var valid = false;
		$.each(data, function(i, field) {
			console.log("DATA: "+field.name +" : "+field.value);
			if(field.name == "box_name") {
				console.log("Send box name to validateBox()");
				box_name = field.value;
				
				if(!boxReg.test(box_name))
				{
					$("#box_name").addClass("_input_error");
					valid = false;
					console.log("BOX NAME - valid: " + valid);
				} else {
					
					$("#box_name").removeClass("_input_error");
					valid = true;
					console.log("BOX NAME - valid: " + valid);
				}
			} else {
				email = field.value;
				
				if(!emailReg.test(email))
				{
					
					$("#email").addClass("_input_error");
					valid = false;
					console.log("EMAIL - valid: " + valid);
				} else {
					
					$("#email").removeClass("_input_error");
					valid = true;
					console.log("EMAIL - valid: " + valid);
				}
				
			}
		});
		validateForm(box_name, email, valid, "p");
	}

	function validateForm(box_name, email, valid, whereToGo) {

		console.log("END CHECKS - valid: " + valid);
		if(valid === true) {
			$.ajax(
			{
				type:"POST",                                     
				url:"validateBoxName.php",         
				data: {"name":box_name}, //insert argumnets here to pass to getAdminWODs
				dataType: "text",                //data format      
				success: function(response) //on recieve of reply
				{
					console.log("response validateBoxName: " + response);
					if(response == "0") {
						console.log('Found box!');
						validateUserEmail(email, box_name, whereToGo);
					} else {
						console.log('Could not find box, please check spelling and try again.');
					}
				},
				error: function(error){
					console.log('Could not find box, please check spelling and try again.' + error);
				}
			});
		} else {
			//openModal("Please correct the highlighted inputs before continuing");
		}
	
	}
	
	function validateUserEmail(email, box_name, whereToGo) {
		$.ajax(
			{
				type:"POST",                                     
				url:"validateEmail.php",         
				data: {"name":email}, //insert argumnets here to pass to getAdminWODs
				dataType: "text",                //data format      
				success: function(response) //on recieve of reply
				{
					console.log("response validateEmail: " + response);
					if(response.substring(0,1) == "0") {
						console.log('Found Email!');
						sendTo(response.substring(1,response.length), box_name, whereToGo);
					} else {
						console.log('Could not find email, please check spelling and try again.');
					}
				},
				error: function(error){
					console.log('Could not find box, please check spelling and try again.' + error);
				}
			});
	}
	
	function sendTo(email, box_name, whereToGo) {
		console.log(email.trim() + ", " + box_name + ", " + whereToGo);
		
		$.ajax(
			{
				type:"POST",                                     
				url:"sendEmail.php",         
				data: { "name":email.trim(),
						 "box":box_name,
						 "whereTo":whereToGo}, 
				dataType: "text",                //data format      
				success: function(response) //on recieve of reply
				{
					console.log("response send Email: " + response);
					openModal(response);
				},
				error: function(error){
					console.log('Could not find box, please check spelling and try again.' + error);
				}
			});
		openModal("Please wait a minute, your information is being requested. (Seriously, wait a full minute)");
	}
	
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50665970-1', 'cboxbeta.com');
  ga('send', 'pageview');

</script>
	
  </body>
</html>
