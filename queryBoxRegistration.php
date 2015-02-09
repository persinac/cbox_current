<?php require_once('Connections/cboxConn.php'); ?>
<?php

mysql_select_db($database_cboxConn, $cboxConn);
//echo 0;
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

//echo "PHP DATA: " . $t_firstname .", " . $t_lastname .", " . $t_phoneNumber .", " . $t_boxName .", " . $t_email .", " . $t_streetAddress .", " . $t_city .", " . $t_state .", " . $t_zipCode .", " . $t_country;

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
$t_admin_id = "cboxadmin00$t_box_id";
$query_registerNewBox = "insert into box values ('{$t_box_id}', '{$t_boxName}', '{$t_streetAddress}', '{$t_city}', '{$t_state}', '{$t_zipCode}', '{$t_country}', '{$t_phoneNumber}', '{$t_firstname}', '{$t_lastname}', '{$t_email}', '{$t_admin_id}')";

$retval = mysql_query($query_registerNewBox, $cboxConn );
if(! $retval )
{
 die('Could not enter data: ' . mysql_error());
}
echo "1";
mysql_close($cboxConn);

$from = "admin@cboxbeta.com"; // sender
$subject = "CBOX - Your Box ID and Admin ID";
$new_message = "$t_firstname,\n\n";

$firstpart = wordwrap("First off, thank you for registering your box with CBox. We hope you enjoy your stay and wish that you take your training as seriously as we take our product. If at any time you have questions or problems, do not hesitate contact us at $from.",70,"\n");
$secondpart= wordwrap("To get you started, here is your unique box ID: $t_box_id. You and all of your members will need this unique ID to start using CBox, so please do not lose it!",70,"\n");
$thirdpart = wordwrap("Second, to register as an administrator of your box and start prescribing workouts, here is your unique administrative key that you will enter upon registering as a user: cboxadmin00$t_box_id",70,"\n");
$fourthpart = wordwrap("Now that you've got all the necessary information, go and register as an administrator and start tracking your stats more competitively!",70,"\n");
				
$message = $new_message . $firstpart . "\n" . $secondpart . "\n" . $thirdpart . "\n" . $fourthpart . "\n\nBest,\nAlex Persinger \nCBox Co-Founder and Administrator";
				
if(@mail($t_email,$subject,$message,"From: $from\n")) {
	echo "Mail Sent Successfully";
} else {
	echo "Mail Not Sent";
}

?>