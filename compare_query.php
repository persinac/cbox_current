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

$colname_getUserID = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserID = $_SESSION['MM_UserID'];
}

mysql_select_db($database_cboxConn, $cboxConn);

###
# Defualt view is non_existent
###
$compare_id = $_POST['compare_selector'];

$t_table = "";
$t_temp_month = $_POST['months'];;
$t_temp_year = $_POST['year'];
$t_month = "";
$t_year = "";
$t_area = "";
$t_temp_wod_type = $_POST['wod_type_selector'];
$t_wod_type = "";
$t_wod_descrip = "";
$t_order_by = "";


$query_getBoxID = "select box_id
from athletes
WHERE user_id = '{$colname_getUserID}'";

$getBoxID = mysql_query($query_getBoxID, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getBoxID);

$row = mysql_fetch_row($getBoxID);

$box_id = $row[0];
$length_of_box_id = strlen($box_id);
//$t_area = " a.box_id = '{$box_id}'";

if($compare_id == "WOD") {
	$t_table = "wods";
	/*if(strlen($t_area) > 0) {
		
		if($t_area == "box") {
			

		} elseif($t_area == "reg") {
			$query_getRegion = "select region
			 from athletes
			WHERE user_id = '{$colname_getUserID}'";
			
			$getRegion = mysql_query($query_getRegion, $cboxConn) or die(mysql_error());
			$row = mysql_fetch_row($getRegion);
			
			$region = $row[0];
		
			$t_area = " a.region = '{$region}'";
		} elseif($t_area == "cou") {
			$query_getCountry = "select country
			 from athletes
			WHERE user_id = '{$colname_getUserID}'";

			$getCountry = mysql_query($query_getCountry, $cboxConn) or die(mysql_error());
			$row = mysql_fetch_row($getCountry);

			$country = $row[0];
		
			$t_area = " a.country = '{$country}'";
		}
		
	}*/
	
	if($t_temp_month == "ALL") {
		$t_months = " SUBSTRING(wod_id, 7, 2) > '00'";
	} else {
		$t_months = " SUBSTRING(wod_id, 7, 2) = '{$t_temp_month}'";
	}
	if($t_temp_year == "ALL") {
		$t_year = " SUBSTRING(wod_id, 3, 4) > '1900'";
	} else {
		$t_year = " SUBSTRING(wod_id, 3, 4) = '{$t_temp_year}'";
	}
	if($t_temp_wod_type == "ALL") {
		$t_wod_type = " type_of_wod LIKE '%'";
	
	} else {
		$t_wod_type = " type_of_wod = '{$t_temp_wod_type}'";
	}
	
	$query_getUserWODs = "SELECT wod_id
	, date_of_wod
	, type_of_wod
	, rx_descrip
	, time
	, rounds
	FROM {$t_table}
	WHERE {$t_months} AND {$t_year} AND {$t_wod_type} AND SUBSTRING(wods.wod_id, 1, {$length_of_box_id}) = '{$box_id}'
	ORDER BY date_of_wod";
	
}
//echo "query: ".$query_getUserWODs;
$getUserWODs = mysql_query($query_getUserWODs, $cboxConn) or die(mysql_error());
#$rows = mysql_fetch_assoc($getUserBenchmarks);
$totalRows_getUserWODs = mysql_num_rows($getUserWODs);
$results = array();

for($i = 0; $i < $totalRows_getUserWODs; $i++)
{
	$results[] = mysql_fetch_assoc($getUserWODs);
}
echo json_encode($results);	
?>