<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

//$_SESSION['MM_Username'] = "kellintc";
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$colname_getUserStrength = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserStrength = $_SESSION['MM_UserID'];
}
mysql_select_db($database_cboxConn, $cboxConn);

$t_user_id = $_SESSION['MM_UserID'];
$t_strength_id = $_POST['strength_id'];
$t_strength_val = $_POST['strength_val'];

$t_string_builder = ""; //nice to have

#######
#
# MySql insert
#
# Make sure that wod_id is built
#
#######

$query_getBoxID = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserStrength}'";

$getBoxID = mysql_query($query_getBoxID, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getBoxID);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getBoxID);

$t_wodID = $row[0] . str_replace("-", "", $t_wod_id);

/*
echo "HI ". $t_user_id;
echo "\nHI ".$t_strength_id;
echo "\nHI ".$t_strength_val;
*/

$query_insert_wod = "insert into athlete_strength values ('{$t_user_id}', '{$t_strength_id}', '{$t_strength_val}')";
//echo $query_insert_wod;
$retval = mysql_query( $query_insert_wod, $cboxConn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "Entered data successfully\n";
mysql_close($cboxConn);
?>