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
}

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
        <button name="submit"class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
