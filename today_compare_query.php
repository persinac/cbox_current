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

$colname_getUserBoxID = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserBoxID = $_SESSION['MM_UserID'];
}

$t_temp_wod_id = $_POST['date'];
$t_wod_id = ""; 
$t_level_perf = $_POST['compare_selector'];
$t_type_of_wod = "";
$t_gender = $_POST['gender_to_compare'];

mysql_select_db($database_cboxConn, $cboxConn);

###
# Defualt view is Crossfit->Foundamental benchmarks
###

$query_getBoxID = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserBoxID}'";

$getBoxID = mysql_query($query_getBoxID, $cboxConn) or die(mysql_error());
$totalRows_getBoxID = mysql_num_rows($getBoxID);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getBoxID);

$box_id = $row[0];
$t_wod_id = $box_id . "_" . str_replace("-","",$t_temp_wod_id);

$query_getWODType = "select type_of_wod
 from wods
WHERE wod_id = '{$t_wod_id}'";

$getWODType = mysql_query($query_getWODType, $cboxConn) or die(mysql_error());
$totalRows_getWODType = mysql_num_rows($getWODType);
####echo $totalRows_getAdminWODs;
$row_wod = mysql_fetch_array($getWODType);

$t_type_of_wod = $row_wod[0];
$order_by = "";

$t_gender_condition = "";
if(strlen($t_gender) < 1) {
	$t_gender_condition = "";
} else if ($t_gender == 'A') {
	$t_gender_condition = "";
} else {
	$t_gender_condition = "AND a.gender = '{$t_gender}'";	
}

if($t_type_of_wod == "AMRAP") {
	$order_by = "DESC";
}
$t_descrip = "";
$t_level_condition = "";
if($t_level_perf == "ALL") {
	$t_descrip = "w.rx_descrip";
	$t_level_condition = "";
} else if ($t_level_perf == "RX") {
	$t_descrip = "w.rx_descrip";
	$t_level_condition = "AND aw.level_perf = 'RX'";
} else if ($t_level_perf == "INTER") {
	$t_descrip = "w.inter_descrip";
	$t_level_condition = "AND aw.level_perf = 'INTER'";
} else if ($t_level_perf == "NOV") {
	$t_descrip = "w.nov_descrip";
	$t_level_condition = "AND aw.level_perf = 'NOV'";
}
echo "Variables: description: " . $t_descrip . ", wodID: " . $t_wod_id . ", boxID: " . $box_id .", gender: " . $t_gender . ", order by: " . $order_by;
$query_getLeaderBoardContent = "select {$t_descrip} AS descrip, 
CONCAT(a.first_name, ' ', a.last_name) AS name, 
CASE WHEN (w.type_of_wod = 'RFT') THEN aw.time_comp
 WHEN (w.type_of_wod = 'AMRAP') THEN aw.rounds_compl
END AS score,
a.user_id AS user_id
from wods w
JOIN athlete_wod aw ON aw.wod_id = w.wod_id
JOIN athletes a ON a.user_id = aw.user_id
where w.wod_id = '{$t_wod_id}'
AND a.box_id = {$box_id} 
{$t_gender_condition} 
{$t_level_condition}
ORDER BY score {$order_by}";

echo "Main query: ".$query_getLeaderBoardContent . " ";

$getLeaderBoardContent = mysql_query($query_getLeaderBoardContent, $cboxConn) or die(mysql_error());
$totalRows_getLeaderBoardContent = mysql_num_rows($getLeaderBoardContent);
//echo $totalRows_getAdminWODs;
$results = array();

for($i = 0; $i < $totalRows_getLeaderBoardContent; $i++)
{
	$results[] = mysql_fetch_assoc($getLeaderBoardContent);
}
echo json_encode($results);	
?>