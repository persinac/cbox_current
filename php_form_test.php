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
$t_is_custom = $_POST['custom_wod'];
$t_mixed = $_POST['mixed_column'];

$t_string_builder = "";
$rx_wod = "";
$inter_wod = "";
$nov_wod = "";

$mystring = $t_wod_specifics;
$findme   = '-';
$pos = strpos($mystring, $findme);

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

if($t_is_custom == "1") {
	$t_first_name = "";
	$query_getBoxID = "select first_name, user_id,box_id
	 from athletes
	WHERE user_id = '{$colname_getUserWODs}'";

	if ($result = $mysqli->query($query_getBoxID)) {
		$row = $result->fetch_assoc();
		$t_user_id = $row['user_id'];
		$t_wodID = $row['user_id'] . "_" . str_replace("-", "", $t_date);
		$t_first_name = $row['first_name'];
		$length_of_user_id = strlen($t_user_id);
	}
	$t_temp_date = str_replace("-", "",$_POST['date']);
	$max_id = "";
	if($t_user_id < 10) {
		$t_id_loc = 12;
	} else if($t_user_id < 100) {
		$t_id_loc = 13;
	} else if($t_user_id < 1000) {
		$t_id_loc = 14;
	} else if($t_user_id < 10000) {
		$t_id_loc = 15;
	} else if($t_user_id < 100000) {
		$t_id_loc = 16;
	}
	$uscore_loc = (int)$length_of_user_id + 1;
	$first_date_uscore = (int)$length_of_user_id + 2;
	$second_date_uscore = (int)$length_of_user_id + 3;
	$query_getMaxIDCount = "select SUBSTRING(custom_id, $t_id_loc) AS maxID from custom_wods WHERE SUBSTRING(custom_id, 1, $length_of_user_id) = '$t_user_id' AND LOCATE('_',custom_id) = $uscore_loc 
	AND SUBSTRING(custom_id, $first_date_uscore, LOCATE('_',SUBSTRING(custom_id, $second_date_uscore))) = '$t_temp_date'";
	echo "Query: " . $query_getMaxIDCount;
	if ($result = $mysqli->query($query_getMaxIDCount)) {
		$row = $result->fetch_assoc();
		$max_id = $row['maxID'];
		echo "MAX ID: " . $max_id;
	}
	$description = "";
	if(strlen($t_buy_in) > 0) {
			$description .= "<p> Buy in: " . $t_buy_in . "</p>";
	}
	if($t_typeOfWOD == "RFT") {
		$description .= "<p>".$t_wod_specifics . " rounds for time of:</p> ";
	}
	elseif($t_typeOfWOD == "AMRAP") {
		$description .= "<p>".$t_amrap_time . " minutes of:</p> ";
	}
	elseif($t_typeOfWOD == "TABATA") {
		$description .= "<p>".$t_wod_specifics . " :20 on :10 off of:</p> ";
	}
	
	if ($pos === false) {
		//echo " NO DASHES ";
		$first_count = 0;
		foreach( $_POST['movement'] as $cnt => $mvmnt ) 
		{
			$t_movement = $_POST['movement'][$cnt];
			$t_weight = $_POST['weight'][$first_count];
			$t_reps = $_POST['reps'][$first_count];
			if(strlen($t_weight) > 0) 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$description .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$description .= '<p class="movement_for_wod">' . $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs </p> " ;
					$first_count++;
				}
			} 
			else 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$description .= '<p class="new_part_for_wod"> -  Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$description .= '<p class="movement_for_wod">' . $t_reps . " reps of " . $t_movement . " </p> " ;
					$first_count++;
				}
			}
		}
	} else {
		/*
		 * If rep scheme is provided, do not display the reps
		 * 
		 */
		 //echo " DASHES ";
		 $first_count = 0;
		foreach( $_POST['movement'] as $cnt => $mvmnt ) 
		{
			$t_movement = $_POST['movement'][$cnt];
			$t_weight = $_POST['weight'][$first_count];
			//$t_reps = $_POST['reps'][$cnt];
			
			if(strlen($t_weight) > 0) 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$description .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$description .= '<p class="movement_for_wod"> '. $t_movement . " @ " . $t_weight . "lbs </p> " ;
					$first_count++;
				}
			} 
			else 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$description .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$description .= '<p class="movement_for_wod">' . $t_movement . "</p> " ;
					$first_count++;
				}
			}
		}
	}

	if(strlen($t_cash_out) >0) {
		$description .= "<p> Cash out: " . $t_cash_out . "</p>";
	}

	if(strlen($t_special) > 0) {
		//echo "SPECIAL";
		$description .= "<p> Special instructions: " . $t_special . " </p> ";
	}

	if(strlen($t_penalty) > 0) {
		//echo "PENALTY";
		$description .= "<p class=\"penalty_for_wod\"> Penalty: " . $t_penalty . "</p>";
	}
	echo "\n".$description;
	
	if($max_id > -1) {
		$custom_wod_id = $max_id + 1;
		echo "\nUse max_id+1 as the insert ID...Wod ID: " . $t_wodID . " Custom ID num: ".$custom_wod_id;
		$t_wodID .= "_" . $custom_wod_id;
		echo "\nNew custom ID...Wod ID: " . $t_wodID; 
	} else {
		echo "\nCreate new ID...Wod ID: " . $t_wodID . " Custom ID num: 0" ;
		$t_wodID .= "_0";
		echo "\nNew custom ID...Wod ID: " . $t_wodID; 
	}
	echo "\n\n DESCRIPTION: $description \n\n";
	#######
	#
	# Custom WOD insert
	#
	#######
	$t_name_of_wod = "";
	$t_score = "";
	$stmt = $mysqli->prepare("insert into custom_wods values ('{$t_wodID}', 
		'{$t_first_name}', 
		'{$t_name_of_wod}', 
		'{$t_typeOfWOD}', '{$description}', '{$t_date}', '{$t_rft_rounds}', '{$t_amrap_time}', '{$t_score}')");
	$stmt->bind_param( 'ssssssssss', $t_wodID, $t_first_name, $t_name_of_wod, $t_typeOfWOD, $description, $t_date, $t_rft_rounds, $t_amrap_time, $t_score );

	if($result = $stmt->execute()) {
		echo "Entered custom wod successfully\n";
		$stmt->close();
	} else {
		echo "1 ";
	}
	$mysqli->close();
} 
else {

	if($t_typeOfWOD == "GIRLS") {
		$t_typeOfWOD = $t_girl_wod;	
	}


	if(!(empty($t_typeOfWOD))) {
		if(strlen($t_buy_in) > 0) {
		//echo "BUY IN";
				$rx_wod .= "<p> Buy in: " . $t_buy_in . "</p>";
				$inter_wod .= "<p> Buy in: " . $t_buy_in . "</p>";
				$nov_wod .= "<p> Buy in: " . $t_buy_in . "</p> ";
		}
		if($t_typeOfWOD == "RFT") {
		//echo "SRFT";
			$rx_wod .= "<p>".$t_wod_specifics . " rounds for time of:</p> ";
			$inter_wod .= "<p>".$t_wod_specifics . " rounds for time of:</p> ";
			$nov_wod .= "<p>".$t_wod_specifics . " rounds for time of:</p> ";
			$t_rft_rounds = $_POST['num_of_rounds'];
			$t_amrap_time = '-';
		}
		elseif($t_typeOfWOD == "AMRAP") {
		//echo "AMRAP";
			$t_amrap_time = $_POST['amrap_time_update'];
			$rx_wod .= "<p>".$t_amrap_time . " minutes of:</p> ";
			$inter_wod .= "<p>".$t_amrap_time . " minutes of:</p> ";
			$nov_wod .= "<p>".$t_amrap_time . " minutes of:</p> ";
			
			$t_rft_rounds = '-';
		}
		elseif($t_typeOfWOD == "TABATA") {
		//echo "TABATA";
			$rx_wod .= "<p>".$t_wod_specifics . " :20 on :10 off of:</p> ";
			$inter_wod .= "<p>".$t_wod_specifics . " :20 on :10 off of:</p> ";
			$nov_wod .= "<p>".$t_wod_specifics . " :20 on :10 off of:</p> ";
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
			$t_weight = $_POST['weight'][$first_count];
			$t_reps = $_POST['reps'][$first_count];
			if(strlen($t_weight) > 0) 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$rx_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$rx_wod .= '<p class="movement_for_wod">' . $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs </p> " ;
					$first_count++;
				}
			} 
			else 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$rx_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$rx_wod .= '<p class="movement_for_wod">' . $t_reps . " reps of " . $t_movement . " </p> " ;
					$first_count++;
				}
			}
		}
		$second_count = 0;
		foreach( $_POST['inter_movement'] as $cnt => $mvmnt ) 
		{
			$t_movement = $_POST['inter_movement'][$cnt];
			$t_weight = $_POST['inter_weight'][$second_count];
			$t_reps = $_POST['inter_reps'][$second_count];
			
			if(strlen($t_weight) > 0) 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$inter_wod .= '<p class="new_part> - Then '. substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$inter_wod .= '<p class="movement_for_wod">' . $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs </p> " ;
					$second_count++;
				}
			} 
			else 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$inter_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$inter_wod .= '<p class="movement_for_wod">' . $t_reps . " reps of " . $t_movement . " </p> " ;
					$second_count++;
				}
			}
		}
		$third_count = 0;
		foreach( $_POST['nov_movement'] as $cnt => $mvmnt ) 
		{
			$t_movement = $_POST['nov_movement'][$cnt];
			$t_weight = $_POST['nov_weight'][$third_count];
			$t_reps = $_POST['nov_reps'][$third_count];
			
			if(strlen($t_weight) > 0) 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$nov_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$nov_wod .= '<p class="movement_for_wod">' . $t_reps . " reps of " . $t_movement . " @ " . $t_weight . "lbs </p> " ;
					$third_count++;
				}
			} 
			else 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$nov_wod .= 'p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$nov_wod .= '<p class="movement_for_wod">' . $t_reps . " reps of " . $t_movement . " </p> " ;
					$third_count++;
				}
			}
		}
	} else {
		/*
		 * If rep scheme is provided, do not display the reps
		 * 
		 */
		 //echo " DASHES ";
		 $first_count = 0;
		foreach( $_POST['movement'] as $cnt => $mvmnt ) 
		{
			$t_movement = $_POST['movement'][$cnt];
			$t_weight = $_POST['weight'][$first_count];
			//$t_reps = $_POST['reps'][$cnt];
			
			if(strlen($t_weight) > 0) 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$rx_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$rx_wod .= '<p class="movement_for_wod"> '. $t_movement . " @ " . $t_weight . "lbs </p> " ;
					$first_count++;
				}
			} 
			else 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$rx_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$rx_wod .= '<p class="movement_for_wod">' . $t_movement . "</p> " ;
					$first_count++;
				}
			}
		}
		$second_count = 0;
		foreach( $_POST['inter_movement'] as $cnt => $mvmnt ) 
		{
			$t_movement = $_POST['inter_movement'][$cnt];
			$t_weight = $_POST['inter_weight'][$second_count];
			//$t_reps = $_POST['inter_reps'][$cnt];
			
			if(strlen($t_weight) > 0) 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$inter_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$inter_wod .= '<p class="movement_for_wod"> ' . $t_movement . " @ " . $t_weight . "lbs </p> " ;
					$second_count++;
				}
			} 
			else 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$inter_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$inter_wod .=  '<p class="movement_for_wod">' . $t_movement . "</p> " ;
					$second_count++;
				}
			}
		}
		$third_count = 0;
		foreach( $_POST['nov_movement'] as $cnt => $mvmnt ) 
		{
			$t_movement = $_POST['nov_movement'][$cnt];
			$t_weight = $_POST['nov_weight'][$third_count];
			//$t_reps = $_POST['nov_reps'][$cnt];
			
			if(strlen($t_weight) > 0) 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$nov_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$nov_wod .= '<p class="movement_for_wod"> ' . $t_movement . " @ " . $t_weight . "lbs </p> " ;
					$third_count++;
				}
			} 
			else 
			{
				$t_to_compare = substr($t_movement, 0, strpos($t_movement, "*"));
				if($t_to_compare == "r0000") {
					$nov_wod .= '<p class="new_part_for_wod"> - Then ' . substr($t_movement, strpos($t_movement, "*")+1) . " - </p>";
				} else {
					$nov_wod .= '<p class="movement_for_wod">' . $t_movement . "</p> " ;
					$third_count++;
				}
			}
		}
	}

	if(strlen($t_cash_out) >0) {
		$rx_wod .= "<p> Cash out: " . $t_cash_out . "</p>";
		$inter_wod .= "<p> Cash out: " . $t_cash_out . "</p>";
		$nov_wod .= "<p> Cash out: " . $t_cash_out . "</p>";
	}

	if(strlen($t_special) > 0) {
		//echo "SPECIAL";
		$rx_wod .= "<p> Special instructions: " . $t_special . " </p> ";
		$inter_wod .= "<p> Special instructions; " . $t_special . " </p> ";
		$nov_wod .= "<p> Special instructions: " . $t_special . " </p> ";
	}

	if(strlen($t_penalty) > 0) {
		//echo "PENALTY";
		$rx_wod .= "<p class=\"penalty_for_wod\"> Penalty: " . $t_penalty . "</p>";
		$inter_wod .= "<p class=\"penalty_for_wod\"> Penalty: " . $t_penalty . "</p> ";
		$nov_wod .= "<p class=\"penalty_for_wod\"> Penalty: " . $t_penalty . "</p> ";
	}
	
	#######
	#
	# MySql insert
	#
	# Need to get box ID based on user first
	#
	#######
	/*
	$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	} */

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

	if(strlen($t_mixed) > 0) {
		$t_typeOfWOD = "MIXED";
		$t_rft_rounds = "-";
		$t_amrap_time = "-";
	}

	$stmt = $mysqli->prepare("insert into wods values ('{$t_wodID}', 
		'{$t_girl_id}', 
		'{$t_typeOfWOD}', 
		'{$rx_wod}', '{$inter_wod}', '{$nov_wod}', '{$t_date}', '{$t_rft_rounds}', '{$t_amrap_time}', '{$t_mixed}')");
	$stmt->bind_param( 'ssssssssss', $t_wodID, $t_girl_id, $t_typeOfWOD, $rx_wod, $inter_wod, $nov_wod, $t_date, $t_rft_rounds, $t_amrap_time, $t_mixed );

	if($result = $stmt->execute()) {
		echo "Entered data successfully\n";
		$stmt->close();
	} else {
		echo "1 ";
	}
	$mysqli->close();

}
?>