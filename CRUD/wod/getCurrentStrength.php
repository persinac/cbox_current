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

$colname_getUserStrength = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserStrength = $_SESSION['MM_UserID'];
}

mysql_select_db($database_cboxConn, $cboxConn);
$today = $_POST['datastring'];

###
# grab box id first
###
$query_getUserBoxID = "select box_id
FROM athletes 
WHERE user_id = '{$colname_getUserStrength}%'";
$getUserBoxID = mysql_query($query_getUserBoxID, $cboxConn) or die(mysql_error());
#$rows = mysql_fetch_assoc($getUserBenchmarks);
$totalRows_getUserStrength = mysql_num_rows($getUserBoxID);
$row = mysql_fetch_array($getUserBoxID);
#row[0] = the box name
$t_box_id = $row[0];
$length_of_box_id = strlen($t_box_id);

//echo "Box ID: ". $t_box_id . "\n";
$query_getStrength = "select movement, descrip, special_instructions
from strength 
WHERE SUBSTRING(strength.str_id, 1, {$length_of_box_id}) = '{$t_box_id}' 
AND date_of_strength = '{$today}'";

//echo "query: " . $query_getStrength . "\n";
$getStrength = mysql_query($query_getStrength, $cboxConn) or die(mysql_error());
$totalRows_getStrength = mysql_num_rows($getStrength);
//echo 'Total Rows: ' . $totalRows_getWOD . ' ';
// ####echo $totalRows_getAdminWODs;
$results = array();

for($i = 0; $i < $totalRows_getStrength; $i++)
{
	//echo 'Results['.$i.']: ' . $results[$i] . ' ';
	$results[] = mysql_fetch_assoc($getStrength);
}

echo json_encode($results);	
mysql_close($cboxConn);


?>