<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}

//mysql_select_db($database_cboxConn, $cboxConn);

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
$t_girl_wod = $_POST['girl_selector'];
$t_girl_id = $_POST['girl_id'];
$t_wod_specifics = $_POST['num_of_rounds'];
$t_date = $_POST['date'];
$t_wodID = "";
$t_name_of_wod = "";
$t_buy_in = $_POST['buy_in'];
$t_cash_out = $_POST['cash_out'];
$t_penalty = $_POST['penalty'];
$t_special = $_POST['special'];
$t_amrap_time = "";
$t_rft_rounds = "";
$t_is_custom = "0";

$t_string_builder = "";
$rx_wod = "";
$inter_wod = "";
$nov_wod = "";

$mystring = $t_wod_specifics;
$findme   = '-';
$pos = strpos($mystring, $findme);

echo "Parts: " . $t_num_of_parts . ", Position: " . $t_position;

if($t_typeOfWOD == "GIRLS") {
	$t_typeOfWOD = $t_girl_wod;	
}


if(!(empty($t_typeOfWOD))) {
	if(strlen($t_special) > 0) {
	//echo "SPECIAL";
			$rx_wod .= "Special instructions: " . $t_special . "; ";
			$inter_wod .= "Special instructions; " . $t_special . "; ";
			$nov_wod .= "Special instructions: " . $t_special . "; ";
	}
	if(strlen($t_penalty) > 0) {
	//echo "PENALTY";
			$rx_wod .= "Penalty: " . $t_penalty . ";";
			$inter_wod .= "Penalty: " . $t_penalty . "; ";
			$nov_wod .= "Penalty: " . $t_penalty . "; ";
	}
	if(strlen($t_buy_in) > 0) {
	//echo "BUY IN";
			$rx_wod .= "Buy in: " . $t_buy_in . ";";
			$inter_wod .= "Buy in: " . $t_buy_in . ";";
			$nov_wod .= "Buy in: " . $t_buy_in . "; ";
	}
	if($t_typeOfWOD == "RFT") {
	//echo "SRFT";
		$rx_wod .= $t_wod_specifics . " rounds for time of:; ";
		$inter_wod .= $t_wod_specifics . " rounds for time of:; ";
		$nov_wod .= $t_wod_specifics . " rounds for time of:; ";
		$t_rft_rounds = $_POST['num_of_rounds'];
		$t_amrap_time = '-';
	}
	elseif($t_typeOfWOD == "AMRAP") {
	//echo "AMRAP";
		$t_amrap_time = $_POST['amrap_time_update'];
		$rx_wod .= $t_amrap_time . " minutes of:; ";
		$inter_wod .= $t_amrap_time . " minutes of:; ";
		$nov_wod .= $t_amrap_time . " minutes of:; ";
		
		$t_rft_rounds = '-';
	}
	elseif($t_typeOfWOD == "TABATA") {
	//echo "TABATA";
		$rx_wod .= $t_wod_specifics . " :20 on :10 off of:; ";
		$inter_wod .= $t_wod_specifics . " :20 on :10 off of:; ";
		$nov_wod .= $t_wod_specifics . " :20 on :10 off of:; ";
	}
}
/*
 * Build the string based on what the user has typed into wod_specifics
 * Currently searching for a -, for rep schemes such as 21-15-9
 * The string need to be built differently to be displayed correctly
 * 
 * If false, build string same as before, if true, build differently
 */
if ($pos === false) {
	//echo " NO DASHES ";
	$first_count = 0;
    foreach( $_POST['movement'] as $cnt => $mvmnt ) 
	{
		$t_movement = $_POST['movement'][$cnt];
		$t_weight = $_POST['weight'][$cnt];
		$t_reps = $_POST['reps'][$cnt];
		
		if(strlen($t_weight) > 0) 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$rx_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1). " ; then :;";
			} else {
				$rx_wod .= $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs, " ;
			}
		} 
		else 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$rx_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1) . " ; then :;";
			} else {
				$rx_wod .= $t_reps . " reps of " . $t_movement . ", " ;
			}
		}
	}
	
	foreach( $_POST['inter_movement'] as $cnt => $mvmnt ) 
	{
		$t_movement = $_POST['inter_movement'][$cnt];
		$t_weight = $_POST['inter_weight'][$cnt];
		$t_reps = $_POST['inter_reps'][$cnt];
		
		if(strlen($t_weight) > 0) 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$inter_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1). " ; then :;";
			} else {
				$inter_wod .= $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs, " ;
			}
		} 
		else 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$inter_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1) . " ; then :;";
			} else {
				$inter_wod .= $t_reps . " reps of " . $t_movement . ", " ;
			}
		}
	}

	foreach( $_POST['nov_movement'] as $cnt => $mvmnt ) 
	{
		$t_movement = $_POST['nov_movement'][$cnt];
		$t_weight = $_POST['nov_weight'][$cnt];
		$t_reps = $_POST['nov_reps'][$cnt];
		
		if(strlen($t_weight) > 0) 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$nov_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1). " ; then :;";
			} else {
				$nov_wod .= $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs, " ;
			}
		} 
		else 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$nov_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1) . " ; then :;";
			} else {
				$nov_wod .= $t_reps . " reps of " . $t_movement . ", " ;
			}
		}
	}
} else {
	/*
	 * If rep scheme is provided, do not display the reps
	 * 
	 */
	 echo " DASHES ";
	foreach( $_POST['movement'] as $cnt => $mvmnt ) 
	{
		$t_movement = $_POST['movement'][$cnt];
		$t_weight = $_POST['weight'][$cnt];
		//$t_reps = $_POST['reps'][$cnt];
		
		if(strlen($t_weight) > 0) 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$rx_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1). " ; then :;";
			} else {
				$rx_wod .= $t_movement . " @ " . $t_weight . "lbs, " ;
			}
		} 
		else 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$rx_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1) . " ; then :;";
			} else {
				$rx_wod .= $t_movement . ", " ;
			}
		}
	}

	foreach( $_POST['inter_movement'] as $cnt => $mvmnt ) 
	{
		$t_movement = $_POST['inter_movement'][$cnt];
		$t_weight = $_POST['inter_weight'][$cnt];
		$t_reps = $_POST['inter_reps'][$cnt];
		
		if(strlen($t_weight) > 0) 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$inter_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1). " ; then :;";
			} else {
				$inter_wod .= $t_movement . " @ " . $t_weight . "lbs, " ;
			}
		} 
		else 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$inter_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1) . " ; then :;";
			} else {
				$inter_wod .= $t_movement . ", " ;
			}
		}
	}

	foreach( $_POST['nov_movement'] as $cnt => $mvmnt ) 
	{
		$t_movement = $_POST['nov_movement'][$cnt];
		$t_weight = $_POST['nov_weight'][$cnt];
		$t_reps = $_POST['nov_reps'][$cnt];
		
		if(strlen($t_weight) > 0) 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$nov_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1). " ; then :;";
			} else {
				$nov_wod .= $t_movement . " @ " . $t_weight . "lbs, " ;
			}
		} 
		else 
		{
			$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
			if($t_to_compare == "r0000") {
				$nov_wod .= " then " . substr($t_movement, strpos($t_movement, "*")+1) . " ; then :;";
			} else {
				$nov_wod .= $t_movement . ", " ;
			}
		}
	}
}

if(strlen($t_cash_out) >0) {
	$rx_wod .= "; Cash out: " . $t_cash_out . "";
	$inter_wod .= "; Cash out: " . $t_cash_out . "";
	$nov_wod .= "; Cash out: " . $t_cash_out . "";
}
//echo $t_num_of_rounds . ", AMRAP: " . $t_amrap_time . ", POST: " .$_POST['amrap_time_update']."\n";
#######
#
# MySql insert
#
# Need to get box ID based on user first
#
#######
$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$query_getBoxID = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserWODs}'";

if ($result = $mysqli->query($query_getBoxID)) {
	$row = $result->fetch_assoc();
	$t_box_id = $row['box_id'];
	$t_wodID = $row['box_id'] . "_" . str_replace("-", "", $t_date);
	$length_of_box_id = strlen($t_box_id);
}
//echo "box id stuff: ".$t_box_id.", ".$t_wodID.", ".$length_of_box_id;

$stmt = $mysqli->prepare("insert into wods values ('{$t_wodID}', 
	'{$t_girl_id}', 
	'{$t_typeOfWOD}', 
	'{$rx_wod}', '{$inter_wod}', '{$nov_wod}', '{$t_date}', '{$t_rft_rounds}', '{$t_amrap_time}')");
$stmt->bind_param( 'sssssssss', $t_wodID, $t_girl_id, $t_typeOfWOD, $rx_wod, $inter_wod, $nov_wod, $t_date, $t_rft_rounds, $t_amrap_time );

if($result = $stmt->execute()) {
	echo "Entered data successfully\n";
	$stmt->close();
} else {
	echo "1 ";
}
$mysqli->close();

function getCustomIDCount($date_to_check, $box_id_check, $box_id_length) {
	$mysqli = new mysqli('127.0.0.1', 'root', 'password!', $database_cboxConn);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	$max_id = "";
	$query_getMaxIDCount = "select MAX(custom_id) AS maxID from custom_wods WHERE SUBSTRING(custom_id, 1, {$box_id_length}) = '{$box_id_check}'";
	echo "Query: " . $query_getMaxIDCount;
	if ($result = $mysqli->query($query_getMaxIDCount)) {
		echo "row: " . $row['maxID'];
		$max_id = $row['maxID'];
		echo "MAX ID: " . $max_id;
	}
	$mysqli->close();
}

?>
