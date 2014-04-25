<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$_SESSION['MM_Username'] = "kellintc";
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}
mysql_select_db($database_cboxConn, $cboxConn);

#echo "<!doctype html>
#<html>
#<head>
#<meta charset=\"utf-8\">
#<title>Untitled Document</title>
#</head>
#<body>";

$t_movement = "";
$t_weight = "";
$t_reps = "";
$t_typeOfWOD = $_POST['wod_type_selector'];
$t_wod_specifics = $_POST['num_of_rounds'];
$t_date = $_POST['date'];
$t_wodID = "";
$t_name_of_wod = "";
$t_buy_in = $_POST['buy_in'];
$t_cash_out = $_POST['cash_out'];
$t_penalty = $_POST['penalty'];
$t_special = $_POST['special'];

$t_string_builder = "";
$rx_wod = "";
$inter_wod = "";
$nov_wod = "";

if(!(empty($t_wod_specifics))) {
	if(strlen($t_special) >0) {
			$rx_wod .= "Special instructions: " . $t_special . " ";
			$inter_wod .= "Special instructions: " . $t_special . " ";
			$nov_wod .= "Special instructions: " . $t_special . " ";
	}
	if(strlen($t_penalty) >0) {
			$rx_wod .= "Penalty: " . $t_penalty . " ";
			$inter_wod .= "Penalty: " . $t_penalty . " ";
			$nov_wod .= "Penalty: " . $t_penalty . " ";
	}
	if(strlen($t_buy_in) >0) {
			$rx_wod .= "Buy in: " . $t_buy_in . " then ";
			$inter_wod .= "Buy in: " . $t_buy_in . " then ";
			$nov_wod .= "Buy in: " . $t_buy_in . " then ";
	}
	if($t_typeOfWOD == "RFT") {
		$rx_wod .= $t_wod_specifics . " rounds for time of: ";
		$inter_wod .= $t_wod_specifics . " rounds for time of: ";
		$nov_wod .= $t_wod_specifics . " rounds for time of: ";
	}
	elseif($t_typeOfWOD == "AMRAP") {
		$rx_wod .= $t_wod_specifics . " minutes of: ";
		$inter_wod .= $t_wod_specifics . " minutes of: ";
		$nov_wod .= $t_wod_specifics . " minutes of: ";
	}
	elseif($t_typeOfWOD == "TABATA") {
		$rx_wod .= $t_wod_specifics . " :20 on :10 off of: ";
		$inter_wod .= $t_wod_specifics . " :20 on :10 off of: ";
		$nov_wod .= $t_wod_specifics . " :20 on :10 off of: ";
	}
}
#echo "RX: ".$rx_wod . ", INTER: " . $inter_wod . ", NOV: " . $nov_wod;
foreach( $_POST['movement'] as $cnt => $mvmnt ) 
{
	$t_movement = $_POST['movement'][$cnt];
	$t_weight = $_POST['weight'][$cnt];
	$t_reps = $_POST['reps'][$cnt];
	
	if(strlen($t_weight) > 0) 
	{
		$rx_wod .= $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs, " ;
	} 
	else 
	{
		$rx_wod .= $t_reps . " reps of " . $t_movement . " @ bodweight, " ;
	}
}

foreach( $_POST['inter_movement'] as $cnt => $mvmnt ) 
{
	$t_movement = $_POST['inter_movement'][$cnt];
	$t_weight = $_POST['inter_weight'][$cnt];
	$t_reps = $_POST['inter_reps'][$cnt];
	
	if(strlen($t_weight) > 0) 
	{
		$inter_wod .= $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs, " ;
	} 
	else 
	{
		$inter_wod .= $t_reps . " reps of " . $t_movement . " @ bodweight, " ;
	}
}

foreach( $_POST['nov_movement'] as $cnt => $mvmnt ) 
{
	$t_movement = $_POST['nov_movement'][$cnt];
	$t_weight = $_POST['nov_weight'][$cnt];
	$t_reps = $_POST['nov_reps'][$cnt];
	
	if(strlen($t_weight) > 0) 
	{
		$nov_wod .= $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs, " ;
	} 
	else 
	{
		$nov_wod .= $t_reps . " reps of " . $t_movement . " @ bodweight, " ;
	}
}
if(strlen($t_cash_out) >0) {
	$rx_wod .= "Cash out: " . $t_cash_out . "";
	$inter_wod .= "Cash out: " . $t_cash_out . "";
	$nov_wod .= "Cash out: " . $t_cash_out . "";
}
#######
#
# MySql insert
#
# Need to get box ID based on user first
#
#######

$query_getAdminWODs = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserWODs}'";

$getAdminWODs = mysql_query($query_getAdminWODs, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getAdminWODs);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getAdminWODs);

$t_wodID = $row[0] . str_replace("-", "", $t_date);

$query_insert_wod = "insert into wods values ('{$t_wodID}', '{$t_name_of_wod}', '{$t_typeOfWOD}', '{$rx_wod}', '{$inter_wod}', '{$nov_wod}', '{$t_date}')";
#echo $query_insert_wod;
$retval = mysql_query( $query_insert_wod, $cboxConn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "Entered data successfully\n";
mysql_close($cboxConn);

#echo "<body>
#</body>
#</html>";
?>