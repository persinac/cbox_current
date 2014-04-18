
<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
mysql_select_db($database_cboxConn, $cboxConn);

$t_firstname = mysql_real_escape_string($_POST['firstName']);
$t_lastname = mysql_real_escape_string($_POST['lastName']);
$t_phoneNumber = mysql_real_escape_string($_POST['phoneNumber']);
$t_boxName = mysql_real_escape_string($_POST['boxName']);
$t_email = mysql_real_escape_string($_POST['email']);
$t_streetAddress = mysql_real_escape_string($_POST['streetAddress']);
$t_city = mysql_real_escape_string($_POST['city']);
$t_state= mysql_real_escape_string($_POST['state']);
$t_zipCode= mysql_real_escape_string($_POST['zipcode']);
$t_country= mysql_real_escape_string($_POST['country']);

#######
#
# MySql insert
#
# Need to get box ID based on user first
#
#######

$query_selectMax = "select max(box_id) from box";
$selectMax = mysql_query($query_selectMax, $cboxConn) or die(mysql_error());
$row = mysql_fetch_array($selectMax);
$result= $row[0]+1;
$t_box_id=$result;

$query_registerNewBox = "insert into box values ('{$t_box_id}', '{$t_boxName}', '{$t_streetAddress}', '{$t_city}', '{$t_state}', '{$t_zipCode}', '{$t_country}', '{$t_phoneNumber}', '{$t_firstname}', '{$t_lastname}')";

$retval = mysql_query($query_registerNewBox, $cboxConn );
if(! $retval )
{
 die('Could not enter data: ' . mysql_error());
}

mysql_close($cboxConn);
?>