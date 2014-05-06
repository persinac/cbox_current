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

$colname_getUserBenchmarks = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserBenchmarks = $_SESSION['MM_UserID'];
}
########
#REMOVE ME
########
#if (!(isset($_SESSION['MM_UserID']))) {
#  $colname_getUserBenchmarks = 1;
#  $_SESSION['MM_Username']= "persinac";
#}
mysql_select_db($database_cboxConn, $cboxConn);

###
# Defualt view is Crossfit->Foundamental benchmarks
###
$movement_id = $_POST['dataString'];
if($movement_id == "grl") {
	$query_getUserCFBenchmarks = "select mvmnt_id, max(date_achieved) date_achieved,
	case when (wod_type = 'r') then min(time)
	when (wod_type = 'a') then '00:00'
	end as time, 
	case when (wod_type = 'a') then max(reps)
	when (wod_type = 'r') then '0'
	end as reps,
	wod_type
	from benchmarks where user_id = $colname_getUserBenchmarks AND mvmnt_id LIKE '{$movement_id}%'
	group by mvmnt_id ";
} else {
	$query_getUserCFBenchmarks = "select bs.mvmnt_id, bs.weight, bs.date_achieved from benchmarks bs join (select user_id, mvmnt_id, max(weight) weight, max(date_achieved) date_achieved from benchmarks where user_id = $colname_getUserBenchmarks group by user_id, mvmnt_id) bb on bs.mvmnt_id = bb.mvmnt_id AND bs.weight = bb.weight AND bs.date_achieved = bb.date_achieved
WHERE bs.user_id = $colname_getUserBenchmarks AND bs.mvmnt_id LIKE '{$movement_id}%' 
ORDER BY bs.mvmnt_id ";
}
$getUserCFBenchmarks = mysql_query($query_getUserCFBenchmarks, $cboxConn) or die(mysql_error());
#$rows = mysql_fetch_assoc($getUserBenchmarks);
$totalRows_getUserBenchmarks = mysql_num_rows($getUserCFBenchmarks);
$results = array();



for($i = 0; $i < $totalRows_getUserBenchmarks; $i++)
{
#	$rows = mysql_fetch_row($getUserCFBenchmarks);
#	$mvmntID = $rows[0];
#	$weight = $rows[1];
	
#	echo '<p>Movement: ' . $mvmntID . ' ';
#	echo ' Weight: ' . $weight . '</p>';
	
	$results[] = mysql_fetch_assoc($getUserCFBenchmarks);
}
echo json_encode($results);	
#echo $movement_id;
?>