<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$c_id = $_POST['comp_id'];
$t_wod_num = $_POST['wodNum'];

class team_scores {
	public $team_name = "";
	public $wod_comp = "";
	public $wod_score = "";
	public $wod_points = "";
	public $overall = "";
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
if(trim($t_wod_num) == 'all') {
	if($stmt = $mysqli->prepare("select ct.team_name, sum(comp_score) AS overall
		from competition_team_scores cts 
		join competition_teams ct on cts.team_id = ct.team_id
		WHERE cts.competition_id = $c_id
		GROUP BY ct.team_name
		ORDER BY overall DESC")) 
	{
		$stmt->execute();
		$stmt->store_result();
		$ts = new team_scores();
		$list_of_scores = array();
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			$stmt->bind_result($tna, $o);

			while ($stmt->fetch()) {
				$ts->team_name = $tna;
				$ts->overall = $o;
				$list_of_scores[] = $ts;
				unset($ts);
			}
			$stmt->free_result();
		}
		else {
			echo "1: No data";
		}
		$retStr = '<table class="competitors"><tr><th>Team</th><th>Overall</th></tr>';
		foreach($list_of_scores AS $i => $value) {
			$retStr .= '<tr>';
			$retStr .= '<td>'.$value->team_name.'</td>';
			$retStr .= '<td>'.$value->overall.'</td>';
			$retStr .= '</tr>';
		}
		$retStr .= '</table>';
	}
} else {
	$rs = $mysqli->prepare('SET @wn = ?, @cid = ?') or die('Unable to prepare: ' . $mysqli->error);
	$rs->bind_param('si', $t_wod_num, $c_id);
	$rs->execute();
	$rs->free_result();
	$ts = new team_scores();
	$list_of_scores = array();
	if($rs = $mysqli->query("CALL comp_getScores(@wn, @cid)")) {
		if($rs->num_rows > 0) {
			while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {
					$ts->team_name = $row['team_name'];
					$ts->wod_score = $row['wod_score'];
					$ts->wod_points = $row['wod_points'];
					$ts->wod_comp = $row['ath_composition'];
					$list_of_scores[] = $ts;
					unset($ts);
			}
		}
	}
	$retStr = '<table class="competitors"><tr><th>Team</th><th>Athletes</th><th>Score</th><th>Points</th></tr>';
	foreach($list_of_scores AS $i => $value) {
		$retStr .= '<tr>';
		$retStr .= '<td>'.$value->team_name.'</td>';
		$retStr .= '<td>'.$value->wod_comp.'</td>';
		$retStr .= '<td>'.$value->wod_score.'</td>';
		$retStr .= '<td>'.$value->wod_points.'</td>';
		$retStr .= '</tr>';
	}
	$retStr .= '</table>';
}

echo $retStr;

$mysqli->close();
?>