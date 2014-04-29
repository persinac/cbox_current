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

if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

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
$t_date = "";
$t_temp_date = $_POST['date'];
$t_area = $_POST['area_to_compare'];
$t_level = $_POST['level_selector'];
$t_wod_type = $_POST['wod_type_selector'];
$t_wod_descrip = "";

if($compare_id == "WOD") {
	if(strlen($t_area) > 0) {
		
		if($t_area == "box") {
			$query_getBoxID = "select box_id
			 from athletes
			WHERE user_id = '{$colname_getUserID}'";
			
			$getBoxID = mysql_query($query_getBoxID, $cboxConn) or die(mysql_error());
			$totalRows_getAdminWODs = mysql_num_rows($getBoxID);
			
			$row = mysql_fetch_row($getBoxID);
			
			$box_id = $row[0];
		
			$t_area = " a.box_id = '{$box_id}'";

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
		
	}
	
	if(strlen($t_temp_date) > 0) {
		$t_date = " w.date_of_wod = '{$t_temp_date}'";
	}
	if(strlen($t_level) > 0) {
		if($t_level == "RX") {
			$t_wod_descrip = " w.rx_descrip";
		} else if($t_level == "INTER") {
			$t_wod_descrip = " w.inter_descrip";
		} else {
			$t_wod_descrip = " w.nov_descrip";
		}
	}
	if(strlen($t_wod_type) > 0) {
		$t_wod_type = $_POST['wod_type_selector'];
	}
	
	$query_getUserWODs = "SELECT CONCAT(a.first_name, ' ',a.last_name) AS name
	, w.date_of_wod
	, {$t_wod_descrip}
	, aw.level_perf
	, aw.time_comp
	FROM wods w 
	JOIN athlete_wod aw ON aw.wod_id = w.wod_id
	JOIN athletes a ON a.user_id = aw.user_id
	WHERE {$t_area} AND {$t_date} AND aw.level_perf = '{$t_level}'
	ORDER BY aw.time_comp";
	
}

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