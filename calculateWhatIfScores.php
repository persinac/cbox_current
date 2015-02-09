<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUser = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUser = $_SESSION['MM_UserID'];
}

class wod {
	public $wod_id = "";
	public $place = "";
	public $score = "";
}

$division = $_POST['div'];
$region = $_POST['reg'];
$year = $_POST['yr'];
$table = "";
if($year == "12") {
	$table = "cf_open_12_leaderboard";
} else if($year == "13") {
	$table = "cf_open_13_leaderboard";
} else if($year == "14") {
	$table = "cf_open_14_leaderboard";
}

$w = new wod();
$wods = array();
foreach($_POST as $key => $value)
{
	if (substr($key, 9, strlen($key)) == "input") {
		$w->wod_id = substr($key, 0, 3);
		$w->score = $value;
		$w->place = "";
		$wods[] = $w;
		unset($w);
	}
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
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


$whatIfWod = new wod();
$list_of_scores = array();

foreach ($wods as $wa) {
	$range = 15;
	$curr_wod = wodNumToString($wa->wod_id);
	$rs = $mysqli->prepare('SET @scr = ?, @rge = ?, @i_table = ?, @i_division = ?, @i_region = ?, @i_wod = ?') or die('Unable to prepare: ' . $mysqli->error);
	$rs->bind_param('sissss', $wa->score, $range, $table, $division, $region, $curr_wod);
	$rs->execute();
	$rs->free_result();
	//echo "$wa->score, $range, $table, $division, $region, $curr_wod\n";
	if(strpos($wa->score, ":") > -1) {
		if($rs = $mysqli->query("CALL whatIf_RFT(@scr,@rge,@i_table,@i_division,@i_region,@i_wod)")) {
			if($rs->num_rows > 0) {
				while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					//echo "ROW: " . $row['convertedScore'] . ", " . $row['wodPlace']."\n";
					$whatIfWod->wod_id = $wa->wod_id;
					$whatIfWod->place = $row['wodPlace'];
					$whatIfWod->score = $row['convertedScore'];
					$list_of_scores[] = $whatIfWod;
					unset($whatIfWod);
				}
			}
		} else {
			echo "2: " . $mysqli->error;
		}
	} else {
		if($rs = $mysqli->query("CALL whatIf_AMRAP(@scr,@rge,@i_table,@i_division,@i_region,@i_wod)")) 
		{	
			if($rs->num_rows > 0) {
				while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					//echo "ROW: " . $row['score2'] . ", " . $row['wodPlace']."\n";
					$whatIfWod->wod_id = $wa->wod_id;
					$whatIfWod->place = $row['wodPlace'];
					$whatIfWod->score = $row['score2'];
					$list_of_scores[] = $whatIfWod;
					unset($whatIfWod);
				}
			} else {
				echo "1: no rows";
			}
		} else {
			echo "2: " . $mysqli->error;
		}
	}
	//echo "Freeing result - R\n";
	$rs->free_result();
	$mysqli->next_result();
}

$mysqli->close();
findNewPlace($wods, $list_of_scores);
//echo json_encode($list_of_scores);
function wodNumToString($wodNumber) {
	$returnVal = "";
	if(substr($wodNumber, 2, 1) == "1") {
		$returnVal = "wodOne";
	} else if(substr($wodNumber, 2, 1) == "2") {
		$returnVal = "wodTwo";
	} else if(substr($wodNumber, 2, 1) == "3") {
		$returnVal = "wodThree";
	} else if(substr($wodNumber, 2, 1) == "4") {
		$returnVal = "wodFour";
	} else if(substr($wodNumber, 2, 1) == "5") {
		$returnVal = "wodFive";
	}
	return $returnVal;
}

/*
*
* @input: 
*	list of wods entered by user
*	list of scores found 
* 
* @return: list of new wod objects with "what if" places
*/
function findNewPlace($user_list, $score_list) {
	$new_place = new wod();
	$new_place_list = array();
	$curr_wod = "";
	$count = -1;
	$type = "A";
	$ret_str = "";
	$found = false;
	
	foreach($score_list as $i => $score_value) {
		
		if($curr_wod != $score_value->wod_id) {
			$curr_wod = $score_value->wod_id;
			$count++;
			$found = false;
		}
		if($found === false) {
			if($user_list[$count]->wod_id == $curr_wod) {
				if(strpos($user_list[$count]->score, ":") > -1) {
					$t_score = $user_list[$count]->score;
					$t_score = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $t_score);
					sscanf($t_score, "%d:%d:%d", $hours, $minutes, $seconds);
					$t_score = $hours * 3600 + $minutes * 60 + $seconds;
					$type = "R";
				} else {
					$t_score = $user_list[$count]->score;
					$type = "A";
				}
				//echo "$t_score > $score_value->score\n" . intval($t_score). " > " . intval($score_value->score) . ", ". $score_value->place."\n" ;
				if($type === "A") {
					
					if(intval($t_score) > intval($score_value->score)) {
						$ret_str = $score_value->place;
						$found = true;
					} else if(intval($t_score) == intval($score_value->score)) {
						$ret_str = $score_value->place;
						$found = true;
					}
					
				} else if($type === "R") {
					if(intval($t_score) < intval($score_value->score)) {
						$ret_str = $score_value->place;
						$found = true;
					} else if(intval($t_score) == intval($score_value->score)) {
						$ret_str = $score_value->place;
						$found = true;
					}
				}
				if($found === true) {
					//echo "New place for $curr_wod: ".$ret_str . "\n";
					$new_place->wod_id = $curr_wod;
					$new_place->score = "";
					$new_place->place = $ret_str;
					$new_place_list[] = $new_place;
					unset($new_place);
				}
			}
		}
	}
	echo json_encode($new_place_list);
}

function compareScore($whatIfScore, $oldScore, $place, $type) {
	$ret_str = "";
	if($type === "A") {
		if($whatIfScore > $oldScore) {
			$ret_str = $place + 1;
		} else if($whatIfScore == $oldScore) {
			$ret_str = $place;
		}
	} else if($type === "R") {
		
	}
	return $ret_str;
}

?>