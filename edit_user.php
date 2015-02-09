<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}
//
$userID = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $userID = $_SESSION['MM_UserID'];
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);

#echo "<!doctype html>
#<html>
#<head>
#<meta charset=\"utf-8\">
#<title>Untitled Document</title>
#</head>
#<body>";

/*
$t_firstname = "happy";  
mysql_real_escape_string($_POST['first_name']);
$t_lastname = mysql_real_escape_string($_POST['last_name']);
$t_email = mysql_real_escape_string($_POST['email']);

$t_boxID = mysql_real_escape_string($_POST['box_id']);

$t_streetAddress = mysql_real_escape_string($_POST['street_address']);
$t_city = mysql_real_escape_string($_POST['city']);
$t_state= mysql_real_escape_string($_POST['state']);
$t_zipCode= mysql_real_escape_string($_POST['zip_code']);
$t_country= mysql_real_escape_string($_POST['country']);

$t_region= mysql_real_escape_string($_POST['region']);
$t_gender = mysql_real_escape_string($_POST['gender']);

$t_username = mysql_real_escape_string($_POST['username']);

$t_password = mysql_real_escape_string($_POST['password']);
*/
$t_admin = 0;
#######
#
# MySql insert
#
# Using ID of User
#
#######


/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$t_firstname = mysqli_real_escape_string($mysqli, $_POST['first_name']);
$t_lastname = mysqli_real_escape_string($mysqli, $_POST['last_name']); 
$t_email = mysqli_real_escape_string($mysqli, $_POST['email']);
$t_streetaddress = mysqli_real_escape_string($mysqli, $_POST['street_address']); 
$t_city = mysqli_real_escape_string($mysqli, $_POST['city']); 
$t_zip =  mysqli_real_escape_string($mysqli, $_POST['zip_code']); 
$t_country = mysqli_real_escape_string($mysqli, $_POST['country']); 
$t_region = mysqli_real_escape_string($mysqli, $_POST['region']); 

$t_password = mysqli_real_escape_string($mysqli, $_POST['password']); 

/**** OLD *******
 * mysql_select_db($database_cboxConn, $cboxConn);
 ****************/


$stmt = $mysqli->prepare("UPDATE athletes SET first_name = ?, last_name = ?, email = ?, street_address = ?, city = ?, zip = ?, country = ?, region = ? WHERE user_id = ?"); 

$stmt->bind_param("sssssssss", $t_firstname, $t_lastname, $t_email, $t_streetaddress, $t_city, $t_zip, $t_country, $t_region, $userID); 
if ($result = $stmt->execute()) {
	echo "Success!";  
	
	$stmt->close();
}else{
	echo "1"; 
}
echo $t_password; 

$stmt = $mysqli->prepare("UPDATE login SET password = ? WHERE user_id = ?");
$stmt->bind_param("ss",$t_password, $userID); 
if ($result = $stmt->execute()) {
	echo "Successs!";
	$stmt->close();
}else{
	echo "1"; 
}



$mysqli->close(); 

/*
class user {
	//set your class object variable to the post variables
	public $firstname = "";
	public $lastname = ""; 
	pulbic $email = ""; 
	public $address = ""; 
	public $city = ""; 
	public $state = ""; 
	public $zip = ""; 
	public $country = ""; 
	//public $password = ""; 
}

$user = new user(); 
//set all the user attributes to the post variables 
$user->firstname=$t_firstname; 
$user->lastname=$t_lastname; 
$user->email=$t_email;
$user->address=$t_streetAddress; 
$user->zip=$t_zipCode;
$user->country=$t_country;
//user->password=$t_password; 
//$user-> rest of variables
*/


#echo "</body>
#</html>";
?>