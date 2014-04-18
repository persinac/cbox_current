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
	} #end function
} #end if

if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}

mysql_select_db($database_cboxConn, $cboxConn);
$today = $_POST['datastring'];
$level = $_POST['lvl_perf'];


###
# grab box id first
###
$query_getUserBoxID = "select box_id
FROM athletes 
WHERE user_id = '{$colname_getUserWODs}%'";
$getUserBoxID = mysql_query($query_getUserBoxID, $cboxConn) or die(mysql_error());
#$rows = mysql_fetch_assoc($getUserBenchmarks);
$totalRows_getUserWODs = mysql_num_rows($getUserBoxID);
$row = mysql_fetch_array($getUserBoxID);
#row[0] = the box name
$t_box_id = $row[0];

//echo 'Box ID: '. $t_box_id . ' ';
if($level == "rx") {
$query_getWOD = "select CASE WHEN (name_of_wod = '') THEN '-' ELSE name_of_wod END AS name_of_wod, 
type_of_wod, 
rx_descrip, 
date_of_wod 
from wods 
WHERE SUBSTRING(wods.wod_id, 1, 1) = '{$t_box_id}' 
AND date_of_wod = '{$today}'";
} else if ($level == "intermediate") {
$query_getWOD = "select CASE WHEN (name_of_wod = '') THEN '-' ELSE name_of_wod END AS name_of_wod, 
type_of_wod, 
inter_descrip, 
date_of_wod 
from wods 
WHERE SUBSTRING(wods.wod_id, 1, 1) = '{$t_box_id}' 
AND date_of_wod = '{$today}'";
} else {
$query_getWOD = "select CASE WHEN (name_of_wod = '') THEN '-' ELSE name_of_wod END AS name_of_wod, 
type_of_wod, 
nov_descrip, 
date_of_wod 
from wods 
WHERE SUBSTRING(wods.wod_id, 1, 1) = '{$t_box_id}' 
AND date_of_wod = '{$today}'";
}
//echo 'query: ' . $query_getWOD . ' ';
$getWOD = mysql_query($query_getWOD, $cboxConn) or die(mysql_error());
$totalRows_getWOD = mysql_num_rows($getWOD);
//echo 'Total Rows: ' . $totalRows_getWOD . ' ';
// ####echo $totalRows_getAdminWODs;
$results = array();

for($i = 0; $i < $totalRows_getWOD; $i++)
{
	//echo 'Results['.$i.']: ' . $results[$i] . ' ';
	$results[] = mysql_fetch_assoc($getWOD);
}

echo json_encode($results);	
mysql_close($cboxConn);

?>