<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$c_id = $_POST['comp_id'];
class athlete {
	public $athlete_name = "";
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$t_id = $_POST['team_id'];
$c_id = $_POST['comp_id'];
$ath = new athlete();
if($stmt = $mysqli->prepare("select name from competition_athletes  
	where comp_id = '$c_id' AND team_id = '$t_id'")) 
{
	$stmt->execute();
	$stmt->store_result();
	$list_of_athletes = array();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($na);

		while ($stmt->fetch()) {
			$ath->name = $na;
			//echo "BOUND: ".$wid." ". $sc ."\n ";
			//echo "LIST: ". $list->wod_id. " " .$list->score."\n ";
			
			$list_of_athletes[] = $ath;
			unset($ath);
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		echo json_encode($list_of_athletes);
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}

$mysqli->close();
?>