<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUser = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUser = $_SESSION['MM_UserID'];
}

global $div; 
global $year;
global $reg;
$t_wod = $_POST['open_wod_selector'];
global $table; 
global $wod; 

$div = $_POST['open_division_selector'];
$year = $_POST['open_year_selector'];
$reg = $_POST['open_region_selector'];
 $table = "";
 $wod = "";


class athlete {
	public $name = "";
	public $place_overall = "";
	public $score = "";
	public $cScore = "";
	public $region = "";
	public $division = "";
}

if($year == "11") {
	$table = "cf_open_11_leaderboard";
} else if($year == "12") {
	$table = "cf_open_12_leaderboard";
} else if($year == "13") {
	$table = "cf_open_13_leaderboard";
} else if($year == "14") {
	$table = "cf_open_14_leaderboard";
}
//echo "Wod number: ".substr($t_wod,3,1);
if(substr($t_wod,3,1) == "1") {
	$wod = "wodOne";
} else if(substr($t_wod,3,1) == "2") {
	$wod = "wodTwo";
} else if(substr($t_wod,3,1) == "3") {
	$wod = "wodThree";
} else if(substr($t_wod,3,1) == "4") {
	$wod = "wodFour";
} else if(substr($t_wod,3,1) == "5") {
	$wod = "wodFive";
}
$t_wod = substr($t_wod,0,2) . "" . substr($t_wod,3,1);
$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$list = new athlete();
if($wod == "wodFive" && $year == "14") { 
	if($stmt = $mysqli->prepare("SELECT athlete, place, region, division, $wod,
		case when length(substring_index(substring_index($wod,')',1),'(',-1)) > 5 THEN time_to_sec(substring_index(substring_index($wod,')',1),'(',-1))
			when length(substring_index(substring_index($wod,')',1),'(',-1)) < 6 THEN time_to_sec(CONCAT('00:',substring_index(substring_index($wod,')',1),'(',-1)))
			when length(substring_index(substring_index($wod,')',1),'(',-1)) < 4 THEN time_to_sec(CONCAT('00:00',substring_index(substring_index($wod,')',1),'(',-1))) 
			when length(substring_index(substring_index($wod,')',1),'(',-1)) < 3 THEN time_to_sec(CONCAT('00:00:',substring_index(substring_index($wod,')',1),'(',-1))) END as convertedScore
		FROM $table
		WHERE region = '$reg'
		AND division = '$div'
		AND athlete != '-'
		AND place NOT LIKE '%--%'
		ORDER BY convertedScore")) 
	{

		$stmt->execute();
		$stmt->store_result();
		$list_of_scores = array();
		/* Get the number of rows */
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			/* Bind the result to variables */
			$stmt->bind_result($n, $pl, $re, $di, $sc, $conScore);

			while ($stmt->fetch()) {			
				$list->score = $sc;
				$list->name = $n;
				$list->place_overall = $pl;
				$list->region = $re;
				$list->division = $di;
				$list->cScore = $conScore;
				/*
				echo "BOUND: ".$n." ". $pl. " " .$re. " ".$di. " ". $sc ."\n ";
				echo "LIST: ". $list->name. " " .$list->place_overall. " ".$list->region. " " .$list->division. " ".$list->score."\n ";
				*/
				$list_of_scores[] = $list;
				unset($list);
			}

			/* free results */
			$stmt->free_result();
			//unset($stmt);
			
		}
		else {
			echo "1: " . $mysqli->error;
		}
	} else {
			echo "2: " . $mysqli->error;
	}
}
else {
	if($stmt = $mysqli->prepare("SELECT athlete, place, region, division, $wod FROM $table
		WHERE region = '$reg'
		AND division = '$div'
		AND place NOT LIKE '%--%'
		ORDER BY CAST(SUBSTRING_INDEX($wod,'(',1) as UNSIGNED) ASC")) 
	{
		//echo "Selector: ".$_POST['core_type_selector']. "\n";
		///echo "$wod,$table,$reg,$div";
		//$stmt->bind_param( 'ssss', $wod,$reg,$div);
		$stmt->execute();
		$stmt->store_result();
		$list_of_scores = array();
		/* Get the number of rows */
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			/* Bind the result to variables */
			$stmt->bind_result($n, $pl, $re, $di, $sc);

			while ($stmt->fetch()) {			
				$list->score = $sc;
				$list->name = $n;
				$list->place_overall = $pl;
				$list->region = $re;
				$list->division = $di;
				$list->cScore = "";
				/*
				echo "BOUND: ".$n." ". $pl. " " .$re. " ".$di. " ". $sc ."\n ";
				echo "LIST: ". $list->name. " " .$list->place_overall. " ".$list->region. " " .$list->division. " ".$list->score."\n ";
				*/
				$list_of_scores[] = $list;
				unset($list);
			}

			/* free results */
			$stmt->free_result();
			//unset($stmt);
			
		}
		else {
			echo "1: " . $mysqli->error;
		}
	} else {
			echo "2: " . $mysqli->error;
	}
}

/** WOD **//*
if($stmt = $mysqli->prepare("SELECT $wod FROM $table
		WHERE region = '$reg'
		AND division = '$div'
		AND place NOT LIKE '%--%'")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {

		$stmt->bind_result($avg);
		$average = 0;
		while ($stmt->fetch()) {			
			$average = $avg;
		}


		$stmt->free_result();
		//unset($stmt);
		$list_of_scores[] = array('name'=>'average', 'value' => $average);
	}
	else {
		echo "3: " . $mysqli->error;
	}
} else {
		echo "4: " . $mysqli->error;
}
*/
/** Average **/
if($stmt = $mysqli->prepare("SELECT AVG(substring_index(substring_index($wod,')',1),'(',-1)) as average FROM $table
		WHERE region = '$reg'
		AND division = '$div'
		AND place NOT LIKE '%--%'")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($avg);
		$average = 0;
		while ($stmt->fetch()) {			
			$average = $avg;
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		$list_of_scores[] = array('name'=>'average', 'value' => $average);
	}
	else {
		echo "3: " . $mysqli->error;
	}
} else {
		echo "4: " . $mysqli->error;
}

/** Mid Range **/
if($stmt = $mysqli->prepare("SELECT (MAX( cast(substring_index( substring_index( $wod, ')' , 1 ) ,'(' ,-1 ) AS UNSIGNED) ) + MIN( cast(substring_index( substring_index( $wod, ')' , 1 ) ,'(' ,-1 ) AS UNSIGNED) ) ) /2 as MidRange
FROM $table
WHERE region = '$reg'
		AND division = '$div'
		AND place NOT LIKE '%--%';")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($mr);
		$average = 0;
		while ($stmt->fetch()) {			
			$midrange = $mr;
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		$list_of_scores[] = array('name'=>'midrange', 'value' => $midrange);
	}
	else {
		echo "3: " . $mysqli->error;
	}
} else {
		echo "4: " . $mysqli->error;
}

/** RANGE **/
if($stmt = $mysqli->prepare("SELECT (MAX( cast(substring_index( substring_index( $wod, ')' , 1 ) ,'(' ,-1 ) AS UNSIGNED) ) - MIN( cast(substring_index( substring_index( $wod, ')' , 1 ) ,'(' ,-1 ) AS UNSIGNED) ) ) as ScoreRange
FROM $table
WHERE region = '$reg'
		AND division = '$div'
		AND place NOT LIKE '%--%';")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($r);
		$average = 0;
		while ($stmt->fetch()) {			
			$range = $r;
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		$list_of_scores[] = array('name'=>'range', 'value' => $range);
	}
	else {
		echo "5: " . $mysqli->error;
	}
} else {
		echo "6: " . $mysqli->error;
}

/** STANDARD DEVIATION **/
if($stmt = $mysqli->prepare("SELECT STD( substring_index( substring_index( $wod, ')' , 1 ) ,'(' ,-1 ) ) as StDeviation
FROM $table
WHERE region = '$reg'
		AND division = '$div'
		AND place NOT LIKE '%--%';")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($std);
		$average = 0;
		while ($stmt->fetch()) {			
			$stdev = $std;
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		$list_of_scores[] = array('name'=>'stdev', 'value' => $stdev);

	}
	else {
		echo "7: " . $mysqli->error;
	}
} else {
		echo "8: " . $mysqli->error;
}

/** STANDARD DEVIATION **/
if($stmt = $mysqli->prepare("SELECT (STD( substring_index( substring_index( $wod, ')' , 1 ) ,'(' ,-1 ) ) *  STD( substring_index( substring_index( $wod, ')' , 1 ) ,'(' ,-1 ) ))as Variance
FROM $table
WHERE region = '$reg'
		AND division = '$div'
		AND place NOT LIKE '%--%';")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($va);
		$average = 0;
		while ($stmt->fetch()) {			
			$vari = $va;
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		$list_of_scores[] = array('name'=>'vari', 'value' => $vari);
		
		//echo json_encode($list_of_scores);
	}
	else {
		echo "9: " . $mysqli->error;
	}
} else {
		echo "10: " . $mysqli->error;
}

if($stmt = $mysqli->prepare("select description from cf_open_workouts
		WHERE division = '$div'
		AND id = '$t_wod';")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($w);
		$average = 0;
		while ($stmt->fetch()) {			
			$d_wod = $w;
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
		$list_of_scores[] = array('name'=>'open_wod_desc', 'value' => $d_wod);
		
		echo json_encode($list_of_scores);
	}
	else {
		echo "11: No rows found! Values used: $t_wod     $div";
	}
} else {
		echo "12: " . $mysqli->error;
}

$mysqli->close();
?>