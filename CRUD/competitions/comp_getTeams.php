<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$c_id = $_POST['comp_id'];
class team {
	public $team_name = "";
	public $num_of_mems = 0;
	public $team_id = "";
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$comp = new team();
if($stmt = $mysqli->prepare("select ct.team_name, ct.team_id, count(ca.name) 
	from competition_athletes AS ca 
	join competition_teams AS ct on ca.team_id = ct.team_id 
	where ca.comp_id = '$c_id'
	group by ct.team_name")) 
{
	$stmt->execute();
	$stmt->store_result();
	$list_of_teams = array();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($tna, $tid, $ct);

		while ($stmt->fetch()) {
			$comp->team_name = $tna;
			$comp->num_of_mems = $ct;
			$comp->team_id = $tid;
			//echo "BOUND: ".$wid." ". $sc ."\n ";
			//echo "LIST: ". $list->wod_id. " " .$list->score."\n ";
			
			$list_of_teams[] = $comp;
			unset($comp);
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		echo json_encode($list_of_teams);
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}

$mysqli->close();
?>