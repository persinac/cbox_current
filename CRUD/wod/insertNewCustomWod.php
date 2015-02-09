<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
/*
 * CUSTOM_WOD_ID SYNTAX = <userID>_<date>_<maxCustomID>
 * Order of operations:
 *		Get POST variables
 *		Get user first name based on USER_ID
 *      Get custom wod for the day chosen by user
 *			SQL returns IDCOUNT
 *				if IDCOUNT > 0, use that number to generate custom_wod_id
 *				else use 0 for custom_wod_id
 *      Insert values into CUSTOM_WODS
 *
 * RETURN VALUES: 
 * 		1: Success!
 *		2: Error inserting the values into CUSTOM_WODS
 *		3: NAME NOT FOUND NON ERROR - ATHLETE DOES NOT EXIST
 *		4: ERROR w/SQL - NAME NOT FOUND 
 *		5: Error w/SQL - Finding custom WODs
 */
session_start();

$user_id = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $user_id = $_SESSION['MM_UserID'];
}

$t_date = $_POST['date'];
$t_description = $_POST['cus_wod_description'];

if(strlen($_POST['cus_wod_type']) > 0) {
	$t_type_of_wod = $_POST['cus_wod_type'];
} else {
	$t_type_of_wod = "-";
}

if(strlen($_POST['cus_wod_score']) > 0) {
	$t_wod_score = $_POST['cus_wod_score'];
} else {
	$t_wod_score = "-";
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$user_name = "-";
if($stmt = $mysqli->prepare("select first_name from athletes where user_id = $user_id")) 
{
	$stmt->execute();
	$stmt->store_result();
	$list_of_scores = array();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		$stmt->bind_result($r);

		while ($stmt->fetch()) {
			$user_name = $r;
		}
		$stmt->free_result();			
	}
	else {
		echo "3 - NAME NOT FOUND: " . $mysqli->error;
	}
} else {
		echo "4 - SELECT NAME SQL ERROR: " . $mysqli->error;
}


$length_of_user_id = strlen($user_id);
$first_date_uscore = (int)$length_of_user_id + 2;
$second_date_uscore = (int)$length_of_user_id + 3;
//echo "LUID: $length_of_user_id, FDUS: $first_date_uscore, SDUS: $second_date_uscore\nDate: $t_date\n";  
$num_of_custom_wods = 0;
if($stmt = $mysqli->prepare("select count(custom_id) AS idcount
	FROM custom_wods 
	WHERE SUBSTRING(custom_id, 1, $length_of_user_id) = $user_id
	AND SUBSTRING(custom_id, $first_date_uscore, LOCATE('_',SUBSTRING(custom_id, $second_date_uscore))) = '$t_date';")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		$stmt->bind_result($idcount);

		while ($stmt->fetch()) {
			$num_of_custom_wods = $idcount;
			//echo "IDCOUNT: $idcount, NoCW: $num_of_custom_wods\n";
		}
		$stmt->free_result();
	}
	else {
		echo "5: NO CUSTOM WODS TODAY - ERROR";
	}
} else {
		echo "6 - SELECT IDCOUNT SQL ERROR: " . $mysqli->error;
}

$cus_wod_id = $user_id . "_" . $t_date . "_" . $num_of_custom_wods;
$name_of_wod = "";
$rounds = "";
$time = "";

/*
 * Insert: custom_id, user_first_name, name of wod, type of wod,
 *         description of wod, date of wod, rounds (if RFT), time (if AMRAP),
 *		   wod score
 */
$stmt = $mysqli->prepare("insert into custom_wods values (?,?,?,?,?,?,?,?,?)");
$stmt->bind_param( 'sssssssss', $cus_wod_id,$user_name,$name_of_wod,$t_type_of_wod,$t_description,$t_date,$rounds,$time,$t_wod_score);

if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "2 " . $mysqli->error;
}

$mysqli->close();
?>