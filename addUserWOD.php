<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

//$_SESSION['MM_Username'] = "kellintc";
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}
mysql_select_db($database_cboxConn, $cboxConn);

echo "<!doctype html>
<html>
<head>
<meta charset=\"utf-8\">
<title>Untitled Document</title>
</head>
<body>";

$t_user_id = $_SESSION['MM_UserID'];
$t_wod_id = $_POST['wod_id'];
$t_wod_descrip = $_POST['wod_descrip'];
$t_level_perf = $_POST['level_perf'];
$t_rounds_compl = $_POST['rounds_compl'];
$t_time = $_POST['time'];
$t_pwod_id = $_POST['pwod_id'];
$t_strength_id = $_POST['strength_id'];
$t_actual_time = $_POST['actualTime'];
$t_wod_type = $_POST['wod_type'];
$t_mixed_score = "-";

if($t_wod_type == "MIXED") {
	$t_mixed_score = $_POST['mixed_score'];
}

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
WHERE user_id = '{$colname_getUserWODs}'";

$getBoxID = mysql_query($query_getBoxID, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getBoxID);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getBoxID);

$t_wodID = $row[0] . "_" . str_replace("-", "", $t_wod_id);

$query_getGirlID = "select name_of_wod
 from wods
WHERE wod_id = '{$t_wodID}' AND name_of_wod LIKE 'grl_%'";

$getGirlID = mysql_query($query_getGirlID, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getGirlID);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getGirlID);

$t_girlID = $row[0];

/*
echo " ". $t_user_id;
echo ", ".$t_wodID;
echo ", ".$t_wod_descrip;
echo ", ".$t_level_perf;
echo ", ".$t_rounds_compl;
echo ", ".$t_time;
echo ", ".$t_pwod_id;
echo ", ".$t_strength_id;
echo ", ".$t_actual_time;
echo " GirlID: " . $t_girlID;
echo " WOD Type: " . $t_wod_type;
if($t_wod_type == "MIXED") {
	echo " Mixed score:  ".$t_mixed_score;
}
*/

if($t_wod_type == "RFT") {
	$query_get_rounds = "select rounds from wods where wod_id = '{$t_wodID}'";
	$getRounds = mysql_query($query_get_rounds, $cboxConn) or die(mysql_error());
	$row = mysql_fetch_array($getRounds);
	$t_rounds_compl = $row[0];
} else if ($t_wod_type == "AMRAP") {
	$query_get_time = "select time from wods where wod_id = '{$t_wodID}'";
	$getTime = mysql_query($query_get_time, $cboxConn) or die(mysql_error());
	$row = mysql_fetch_array($getTime);
	$t_time = $row[0];
} else if ($t_wod_type == "MIXED") {
	$t_time = "-";
	$t_rounds_compl = "-";
}

echo " ". $t_user_id;
echo ", ".$t_wodID;
echo ", ".$t_wod_descrip;
echo ", ".$t_level_perf;
echo ", ".$t_rounds_compl;
echo ", ".$t_time;
echo ", ".$t_pwod_id;
echo ", ".$t_strength_id;
echo ", ".$t_actual_time;
echo ", GirlID: " . $t_girlID;
echo ", WOD Type: " . $t_wod_type;
if($t_wod_type == "MIXED") {
	echo ", Mixed score:  ".$t_mixed_score;
}

$query_insert_wod = "insert into athlete_wod values ('{$t_user_id}', '{$t_wodID}', '{$t_wod_descrip}', '{$t_level_perf}', '{$t_rounds_compl}', '{$t_time}', '{$t_pwod_id}', '{$t_strength_id}', '{$t_actual_time}', '{$t_mixed_score}')";
echo $query_insert_wod;
$retval = mysql_query( $query_insert_wod, $cboxConn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
  
} else {
	if(strlen($t_girlID) > 0) {
		  if($t_wod_type == "RFT") {
			$query_insert_benchmark = "insert into benchmarks values ('{$t_user_id}', '{$t_girlID}', '', '', '{$t_actual_time}', '{$t_wod_id}', '', 'r')";
		  } else {
			 $query_insert_benchmark = "insert into benchmarks values ('{$t_user_id}', '{$t_girlID}', '', '', '', '{$t_wod_id}', '{$t_actual_time}', 'r')"; 
		  }
			echo $query_insert_benchmark;
			$retval = mysql_query( $query_insert_benchmark, $cboxConn );
			if(! $retval )
			{
				die('Could not enter data: ' . mysql_error());
			}
	  }	
}
echo "Entered data successfully\n";
mysql_close($cboxConn);

echo "<body>
</body>
</html>";
?>