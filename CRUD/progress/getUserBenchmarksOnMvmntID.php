<?php require_once('../../Connections/cboxConn.php'); ?>
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

/*

THIS QUERY IS WRONG...but sends back correct data
Need to fix...

*/
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
} else if($movement_id == "hro") {
	$query_getUserCFBenchmarks = "select cfha.hero_id,  
	cfha.level, 
	cfha.score
	from cf_heroes cfha
	INNER JOIN (SELECT MIN(score) as minscore, 
		hero_id, user_id
		from cf_heroes
		where user_id = $colname_getUserBenchmarks
		group by hero_id) cfhb
		ON cfha.hero_id = cfhb.hero_id
		AND cfha.score = cfhb.minscore
		AND cfha.user_id = cfhb.user_id
	where cfha.user_id = $colname_getUserBenchmarks
	ORDER BY CAST(cfha.hero_id AS UNSIGNED);
";
} else { //to select by personal best 1RM
	$query_getUserCFBenchmarks = "select max(bs.weight) weight, bs.mvmnt_id
from benchmarks bs
WHERE bs.user_id = {$colname_getUserBenchmarks} AND bs.mvmnt_id LIKE '{$movement_id}%'
group by bs.mvmnt_id
ORDER BY bs.mvmnt_id; ";
}

/******************
Select by most recent 1RM (by date)

SELECT max(bs.date_achieved), bs.mvmnt_id, bb.weight
FROM benchmarks bs 
	JOIN (SELECT weight, mvmnt_id, date_achieved 
		FROM benchmarks where user_id = {$colname_getUserBenchmarks} 
		) bb ON bs.mvmnt_id = bb.mvmnt_id AND bs.date_achieved = bb.date_achieved
WHERE bs.user_id = 1 AND bs.mvmnt_id LIKE '{$movement_id}%' 
group by bs.mvmnt_id
ORDER BY bs.mvmnt_id; 
******************/

$getUserCFBenchmarks = mysql_query($query_getUserCFBenchmarks, $cboxConn) or die(mysql_error());
#$rows = mysql_fetch_assoc($getUserBenchmarks);
$totalRows_getUserBenchmarks = mysql_num_rows($getUserCFBenchmarks);
$results = array();

for($i = 0; $i < $totalRows_getUserBenchmarks; $i++)
{
	$results[] = mysql_fetch_assoc($getUserCFBenchmarks);
}
echo json_encode($results);	
?>