<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
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
} #end function
//$_SESSION['MM_Username'] = "kellintc";
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}
########
#REMOVE ME
########
#if (!(isset($_SESSION['MM_UserID']))) {
#  $colname_getUserWODs = 1;
#  $_SESSION['MM_Username']= "persinac";
#}
mysql_select_db($database_cboxConn, $cboxConn);

###
# Defualt view is Crossfit->Foundamental benchmarks
###

$query_getBoxID = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserWODs}'";

$getBoxID = mysql_query($query_getBoxID, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getBoxID);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getBoxID);

$box_id = $row[0];#$_POST['dataString'];
$query_getAdminWODs = "select wod_id
, CASE WHEN (name_of_wod = '') THEN '-'
	ELSE name_of_wod
	END AS name_of_wod
, type_of_wod
, rx_descrip
, date_of_wod
 from wods
WHERE SUBSTRING(wods.wod_id, 1, 1) = '{$box_id}'";
$getAdminWODs = mysql_query($query_getAdminWODs, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getAdminWODs);
//echo $totalRows_getAdminWODs;
$results = array();

for($i = 0; $i < $totalRows_getAdminWODs; $i++)
{
	$results[] = mysql_fetch_assoc($getAdminWODs);
}
echo json_encode($results);	
?>