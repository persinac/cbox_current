<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUser = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUser = $_SESSION['MM_UserID'];
}

$wods = array();
$division = $_POST['division'];
$name = $_POST['ath_name'];
$date = $_POST['date'];
$year = $_POST['year'];
$count = 0;

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

for($f = 1; $f < 6; $f++) {
	$wod_id = substr($year, 2, strlen($year)) . $f;
	//echo $wod_id . " " . wodNumToLetter($f) . " ". $_POST[wodNumToLetter($f)] ."\n";
	
	$stmt = $mysqli->prepare("insert into athlete_open_workouts values (?, ?, ?, ?, ?)");
	$stmt->bind_param( 'issss', $colname_getUser,$wod_id,$_POST[wodNumToLetter($f)],$date,$division);

	if($result = $stmt->execute()) {
		echo "1";
		$stmt->close();
	} else {
		echo "2";
	}
}

$mysqli->close();

function wodNumToLetter($num) {
	$value = "";
	if($num == "1") {
		$value = "wodOne";
	} else if($num == "2") {
		$value = "wodTwo";
	} else if($num == "3") {
		$value = "wodThree";
	} else if($num == "4") {
		$value = "wodFour";
	} else if($num == "5") {
		$value = "wodFive";
	}
	return $value;
}

?>