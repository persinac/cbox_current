<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$comp_id = $_POST['comp_id'];
$wodNum = $_POST['wod_num'];

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$stmt = $mysqli->prepare("delete from competition_team_scores where competition_id = ? AND wodNum = ? ;");
$stmt->bind_param( 'is', $comp_id, $wodNum);		
if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "2";
	die('Unable to execute: ' . $mysqli->error);
}

$tid = "";
$tsc = "";
$tpo = "";
$count = 1;
foreach($_POST as $key => $value) {	
	//echo "$key : $value\n";
	
	if (strpos($key,"team") > -1) {
		if(strpos($key,"_id_") > -1) {
			$tid = $value;
			//echo "ID: var: $tid, val: $value, COUNT: $count\n";
		}
		if(strpos($key,"_score_") > -1) {
			$tsc = $value;
			//echo "SCORE: var: $tsc, val: $value, COUNT: $count\n";
		}
		if(strpos($key,"_points_") > -1) {
			$tpo = $value;
			//echo "POINTS: var: $tpo, val: $value, COUNT: $count\n";
		}
		if($count == 3) {
			$stmt = $mysqli->prepare("insert into competition_team_scores values (?, ?, ?, ?, ?)");
			$stmt->bind_param( 'issss', $comp_id, $tid, $wodNum, $tsc, $tpo);		
			if($result = $stmt->execute()) {
				echo "1";
				$stmt->close();
			} else {
				echo "2";
			}
			$count = 0;
		}
		$count++;
	}
}

$mysqli->close();
?>