<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

class athlete {
	public $ath_id = "";
	public $a_comp_id = "";
	public $division = "";
	public $name = "";
	public $box_name = "";
	public $state = "";
	public $city ="";
	public $a_team_id = "";
	public $email = "";
	public $gender = "";
}

$comp_id = $_POST['comp_id'];
$division = $_POST['division'];
$city = $_POST['city'];
$state = $_POST['state'];
$name = $_POST['full_name'];
$box = $_POST['box'];
$team_id = "-";
$email = $_POST['email'];
$gender = "";
$athlete_id = "";

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$spots = 0;
if($stmt = $mysqli->prepare("select count(*) from competition_athletes
				where comp_id = $comp_id AND division = '$division'; ")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		$stmt->bind_result($ct);
		
		while ($stmt->fetch()) {
			$spots = $ct;
		}
		$stmt->free_result();
	}
	else {
		echo "1: No data";
	}
} else {
	echo "2: " . $mysqli->error;
}

if($spots < 10) {
	/* Find the max athlete id */
	if($stmt = $mysqli->prepare("select max(athlete_id) from competition_athletes")) 
	{
		$stmt->execute();
		$stmt->store_result();
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			$stmt->bind_result($maid);

			while ($stmt->fetch()) {
				$athlete_id = $maid + 1;
			}
			$stmt->free_result();
		}
		else {
			echo "1: No data";
		}
	} else {
		echo "2: " . $mysqli->error;
	}

	if(strpos($_POST['division'], "m") > -1) {
		$gender = "M";
	} else {
		$gender = "F";
	}
	//echo "$athlete_id, $comp_id, $division, $name, $box, $state, $city, $team_id, $email, $gender";

	$stmt = $mysqli->prepare("insert into competition_athletes values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param( 'iissssssss', $athlete_id, $comp_id, 
		$division, $name, $box, $state, $city, 
		$team_id, $email, $gender);
	if($result = $stmt->execute()) {
		echo "Entered Athlete successfully\n";
		$stmt->close();
	} else {
		echo "2";
	}
} else {
	echo "3"; //Division is full
}

$mysqli->close();
?>
