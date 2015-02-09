<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
$comp_id = $_POST['comp_id'];
$team_id = $_POST['team_id'];

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$stmt = $mysqli->prepare("delete from competition_team_wod_composition where comp_id = ? AND team_id = ? ;");
$stmt->bind_param( 'is', $comp_id, $team_id);		
if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "2";
	die('Unable to execute: ' . $mysqli->error);
}

$count = 0;
$wodCount = 0;
$ath_comp = "";
foreach($_POST as $key => $value) {	
	$index = strpos($value,"ath_");
	if($index > -1) {
		if($count % 2 == 0) {
			$wodCount++;
			$ath_comp = "";
		}
		$ath_comp .= substr($value,strpos($value,"_")+1) . " ";
		
		if($count % 2 == 1) {
			//echo "WOD #$wodCount Team Composition\n";
			//echo $ath_comp . "\n";
			/* COMP_ID, TEAM_ID, WODNUM, ATH_COMPOSITION */
			$stmt = $mysqli->prepare("insert into competition_team_wod_composition values (?, ?, ?, ?)");
			$stmt->bind_param( 'isss', $comp_id, $team_id, $wodCount, $ath_comp);		
			if($result = $stmt->execute()) {
				echo "1";
				$stmt->close();
			} else {
				echo "2";
			}
		}
		$count++;
	}
}
$wodCount++;
$ath_comp = "ALL";
$stmt = $mysqli->prepare("insert into competition_team_wod_composition values (?, ?, ?, ?)");
$stmt->bind_param( 'isss', $comp_id, $team_id, $wodCount, $ath_comp);	
if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "2";
}

$mysqli->close();
?>