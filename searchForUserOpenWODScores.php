<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUser = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUser = $_SESSION['MM_UserID'];
}

$year = $_POST['yr'];
class results {
	public $athlete = "";
	public $place = "";
	public $region = "";
	public $division = "";
	public $wodOne = "";
	public $wodTwo = "";
	public $wodThree = "";
	public $wodFour = "";
	public $wodFive = "";
}
$list = new results();

if($year == "2011") {
	$table = "cf_open_11_leaderboard";
} else if($year == "2012") {
	$table = "cf_open_12_leaderboard";
} else if($year == "2013") {
	$table = "cf_open_13_leaderboard";
} else if($year == "2014") {
	$table = "cf_open_14_leaderboard";
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$name = "";
$foundName = true;
if($stmt = $mysqli->prepare("SELECT first_name, last_name FROM athletes WHERE user_id = '$colname_getUser'")) 
{
	$stmt->execute();
	$stmt->store_result();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($fn, $ln);

		while ($stmt->fetch()) {
			$name = $fn . " " . $ln;
		}

		/* free results */
		$stmt->free_result();
	}
	else {
		echo "1: No name under that User ID found";
		$foundName = false;
	}
} else {
		echo "2: " . $mysqli->error;
}

if($foundName === true) {
	//echo "$table  $name\n";
	if($stmt = $mysqli->prepare("select athlete,
								place,
								region,
								division,
								wodOne,
								wodTwo,
								wodThree,
								wodFour,
								wodFive from $table where athlete = '$name' limit 25")) 
	{
		$stmt->execute();
		$stmt->store_result();
		$list_of_results = array();
		/* Get the number of rows */
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			/* Bind the result to variables */
			$stmt->bind_result($n, $pl, $r, $di, $won, $wtw, $wth, $wfo, $wfi);

			while ($stmt->fetch()) {
				$list->athlete = $n;
				$list->place = $pl;
				$list->region = $r;
				$list->division = $di;
				$list->wodOne = $won;
				$list->wodTwo = $wtw;
				$list->wodThree = $wth;
				$list->wodFour = $wfo;
				$list->wodFive = $wfi;
				
				/*
				echo "BOUND: $n, $pl, $r, $di, $won, $wtw, $wth, $wfo, $wfi\n ";
				echo "LIST: $list->athlete = $n   
				$list->place = $pl    
				$list->region = $r    
				$list->division = $di    
				$list->wodOne = $won   
				$list->wodTwo = $wtw   
				$list->wodThree = $wth   
				$list->wodFour = $wfo   
				$list->wodFive\n ";
				*/
				$list_of_results[] = $list;
				unset($list);
			}

			/* free results */
			$stmt->free_result();
			echo json_encode($list_of_results);
		}
		else {
			echo "3: No data! Please go to PROGRESS page and input WOD scores for the appropriate year.";
		}
	} else {
			echo "4: " . $mysqli->error;
	}
}

$mysqli->close();
?>