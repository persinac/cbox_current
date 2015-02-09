<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUser = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUser = $_SESSION['MM_UserID'];
}

/*
I need the following data passed in to this:
	wod_id
	year
	division
	region
	score
*/
class wodPlace {
	public $wodNum = "";
	public $place = "";
}

$division = $_POST['division'];
$region = $_POST['region'];
$year = $_POST['year'];
$wod_id = $_POST['wod_id'];

$table = "";

if($year == "12") {
	$table = "cf_open_12_leaderboard";
} else if($year == "13") {
	$table = "cf_open_13_leaderboard";
} else if($year == "14") {
	$table = "cf_open_14_leaderboard";
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

if(trim($region) == "-") {
	if($stmt = $mysqli->prepare("select region from athletes where user_id = $colname_getUser")) 
	{
		$stmt->execute();
		$stmt->store_result();
		$list_of_scores = array();
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			$stmt->bind_result($r);

			while ($stmt->fetch()) {
				$region = $r;
			}
			$stmt->free_result();			
		}
		else {
			echo "3: " . $mysqli->error;
		}
	} else {
			echo "4: " . $mysqli->error;
	}
}

$list = new wodPlace();
$list_of_scores = array();
for($f = 1; $f < 6; $f++) {
	$wod = wodNumToLetter($f);
	if($f === 1) {
		$score = $_POST[$f];
	} else if($f === 2) {
		$score = $_POST[$f];
	} else if($f === 3) {
		$score = $_POST[$f];
	} else if($f === 4) {
		$score = $_POST[$f];
	} else {
		$score = $_POST[$f];
	}
	//echo " WOD: $wod SCORE: $score DIVISION: $division REGION: $region TABLE: $table\n";
	if($stmt = $mysqli->prepare("select DISTINCT min(substring_index($wod,'(',1)) AS wodPlace
			from $table 
			where substring_index(substring_index($wod,')',1),'(',-1) = '$score'
			and division = '$division' 
			and region = '$region'
			and place NOT LIKE '%--%'
			ORDER BY cast(substring_index($wod,'(',1) AS unsigned)")) 
	{
		$stmt->execute();
		$stmt->store_result();
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			$stmt->bind_result($n);

			while ($stmt->fetch()) {
				$list->wodNum = $year . $f;
				$list->place = $n;
				$list_of_scores[] = $list;
				unset($list);
			}
			$stmt->free_result();			
		}
		else {
			echo "1: " . $mysqli->error;
		}
	} else {
			echo "2: " . $mysqli->error;
	}
}

echo json_encode($list_of_scores);
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