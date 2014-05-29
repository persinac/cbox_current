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
$movement_id = "wod";#$_POST['dataString'];
$query_getUserWODs = "SELECT aw.wod_id 
	, w.date_of_wod
	, CASE WHEN (w.name_of_wod = '') THEN '-'
		ELSE w.name_of_wod
	END AS 'WOD_Name'
	, aw.level_perf 
	, w.type_of_wod
	, aw.time_comp
	, aw.rounds_compl
	, aw.mixed_score
	, CASE WHEN (aw.level_perf = 'RX') THEN w.rx_descrip
		WHEN (aw.level_perf = 'IN') THEN w.inter_descrip
		WHEN (aw.level_perf = 'NO') THEN w.nov_descrip
		ELSE '' 
	end AS Description
	, CASE WHEN (str.movement = '') THEN '-'
		WHEN (str.movement IS NULL) THEN '-'
		ELSE str.movement
	END AS 'str_mov'
	, CASE WHEN (str.descrip = '') THEN '-'
		WHEN (str.descrip IS NULL) THEN '-'
		ELSE str.descrip
	END AS 'str_des'
	,CASE WHEN (p.type_of_pwod = '') THEN '-'
		WHEN (p.type_of_pwod IS NULL) THEN '-'
		ELSE p.type_of_pwod
	END AS 'pos_wod'
	, CASE WHEN (p.descrip = '') THEN '-'
		WHEN (p.descrip IS NULL) THEN '-'
		ELSE p.descrip
	END AS 'pos_des'
FROM athlete_wod AS aw
	JOIN wods AS w ON aw.wod_id=w.wod_id
	LEFT OUTER JOIN strength AS str ON aw.str_id = str.str_id
	LEFT OUTER JOIN post_wod AS p ON aw.pwod_id = p.pwod_id
WHERE aw.user_id = '{$colname_getUserWODs}'";
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