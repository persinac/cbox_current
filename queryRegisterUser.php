<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
mysql_select_db($database_cboxConn, $cboxConn);

#echo "<!doctype html>
#<html>
#<head>
#<meta charset=\"utf-8\">
#<title>Untitled Document</title>
#</head>
#<body>";

$t_firstname = mysql_real_escape_string($_POST['first_name']);
$t_lastname = mysql_real_escape_string($_POST['last_name']);
$t_email = mysql_real_escape_string($_POST['email']);
$t_boxID = mysql_real_escape_string($_POST['box_id']);
$t_streetAddress = mysql_real_escape_string($_POST['street_address']);
$t_city = mysql_real_escape_string($_POST['city']);
$t_state= mysql_real_escape_string($_POST['state']);
$t_zipCode= mysql_real_escape_string($_POST['zip_code']);
$t_country= mysql_real_escape_string($_POST['country']);
$t_region= mysql_real_escape_string($_POST['region']);

$t_username = mysql_real_escape_string($_POST['username']);
$t_password = mysql_real_escape_string($_POST['password']);
$t_admin = 0;
#######
#
# MySql insert
#
# Need to get box ID based on user first
#
#######

$query_selectMaxUID = "select max(user_id) from athletes";
$selectMax = mysql_query($query_selectMaxUID, $cboxConn) or die(mysql_error());
$row = mysql_fetch_array($selectMax);
$result= $row[0]+1;
$t_user_id=$result;

$query_registerNewUser = "insert into athletes values ('{$t_user_id}', '{$t_firstname}', '{$t_lastname}', '{$t_email}', '{$t_boxID}', '{$t_streetAddress}', '{$t_city}', '{$t_state}', '{$t_country}', '{$t_zipCode}', '{$t_region}', '{$t_admin}')";

$retval = mysql_query($query_registerNewUser, $cboxConn );
if(! $retval )
{
	die('Could not enter data: ' . mysql_error());
}
else
{
	$query_registerNewUser = "insert into login values ('{$t_user_id}', '{$t_username}', '{$t_password}', '{$t_admin}')";
	$retval = mysql_query($query_registerNewUser, $cboxConn );
	if(! $retval )
	{
	 	die('Could not enter data: ' . mysql_error());
	} else { echo 'Registration Successful!'; }
}
mysql_close($cboxConn);
#echo "</body>
#</html>";
?>