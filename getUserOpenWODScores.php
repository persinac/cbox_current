<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUser = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUser = $_SESSION['MM_UserID'];
}

$year = $_POST['yr'];
class wod_score {
	public $wod_id = "";
	public $score = "";
}
$list = new wod_score();


$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}


if($stmt = $mysqli->prepare("SELECT cf_wod_id, 
		CASE WHEN (type_of_wod like 'R%') THEN min(score)
			WHEN (type_of_wod like 'A%') THEN max(score)
		END AS score
		FROM athlete_open_workouts aow
		JOIN cf_open_workouts cow ON aow.cf_wod_id = cow.id 
		WHERE LEFT(cow.id,2) = '$year'
		AND user_id = '$colname_getUser'
		GROUP BY cf_wod_id")) 
{
	$stmt->execute();
	$stmt->store_result();
	$list_of_scores = array();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($wid, $sc);

		while ($stmt->fetch()) {
			$list->wod_id = $wid;
			$list->score = $sc;
			//$list->cScore = $conScore;
			
			//echo "BOUND: ".$wid." ". $sc ."\n ";
			//echo "LIST: ". $list->wod_id. " " .$list->score."\n ";
			
			$list_of_scores[] = $list;
			unset($list);
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		echo json_encode($list_of_scores);
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}

$mysqli->close();
?>