<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUser = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUser = $_SESSION['MM_UserID'];
}

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
$city = $_POST['team_box_city'];
$state = $_POST['team_box_state'];
$team_name = $_POST['team_name'];
$team_box = $_POST['team_box'];
$team_id = "";
$athlete_id = "";

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$spots = 0;
if($stmt = $mysqli->prepare("select count(*) from competition_teams
				where competition_id = $comp_id AND team_division = '$division';")) 
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
	/* First find the max team id */
	if($stmt = $mysqli->prepare("select max(cast(team_id as unsigned)) from competition_teams")) 
	{
		$stmt->execute();
		$stmt->store_result();
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			$stmt->bind_result($mtid);

			while ($stmt->fetch()) {
				$team_id = $mtid + 1;
				//echo "team_id: $team_id\n";
			}
			$stmt->free_result();
		}
		else {
			echo "1: No data";
		}
	} else {
		echo "2: " . $mysqli->error;
	}

	/* Then find the max athlete id */
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

	$stmt = $mysqli->prepare("insert into competition_teams values (?, ?, ?, ?)");
	$stmt->bind_param( 'isss', $comp_id,$team_id,$team_name,$division);
	$team_insert = true;
	if($result = $stmt->execute()) {
		echo "Entered Team successfully\n";
		$stmt->close();
	} else {
		echo "4";
		$team_insert = false;
	}

	//$team_insert = true;
	if($team_insert == true) {
		$list_of_athletes = array();
		$a = new athlete();
		$count = 0;
		foreach($_POST as $key => $value)
		{
			if(strpos($key, "full_name")>-1 || 
				strpos($key, "email")>-1 ||
				strpos($key, "gender")>-1) {
				if(strpos($key, "full_name")>-1) {
					//echo "Name: $value ";
					$a->a_comp_id = $comp_id;
					$a->team_id	= $team_id;
					$a->ath_id = intval($athlete_id) + $count;
					$a->box_name = $team_box;
					$a->city = $city;
					$a->state = $state;
					$a->division = $division;
					$a->name = $value;
				} else if(strpos($key, "gender")>-1) {
					//echo "Gender: $value ";
					$a->gender = $value;
				} else if(strpos($key, "email")>-1) {
					//echo "Email: $value ";
					$a->email = $value;
					//echo "add athlete to list, unset current athlete variable. Count: $count ATH_ID: $athlete_id\n";
					$list_of_athletes[] = $a;
					unset($a);
					$count++;
				}
				
			}
		}
	}

	foreach ($list_of_athletes as $ath) {	
		$stmt = $mysqli->prepare("insert into competition_athletes values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param( 'iissssssss', $ath->ath_id, $ath->a_comp_id, $ath->division, $ath->name,$ath->box_name, $ath->state, $ath->city, $ath->team_id, $ath->email, $ath->gender);
		if($result = $stmt->execute()) {
			echo "Entered Individual successfully\n";
			$stmt->close();
		} else {
			echo "2";
		}
	}
} else {
	echo "3"; //Division is full
}
$mysqli->close();
?>