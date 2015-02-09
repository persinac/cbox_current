<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$c_id = $_POST['comp_id'];
$divisions = $_POST['divisions'];

$final_html = "";
$unorderedList = '';
$tab_content = '';
$tabs = "";

class competitor {
	public $name = "";
	public $division = "";
	public $box = "";
	public $team_name = "";
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$unorderedList = '<ul class="tab-links">';
$tab_content = '<div class="tab-content">';
$div_array = explode(",", $divisions);
foreach ($div_array as $k => $v) {
	//echo "\$div_array[$k] => $v \n";
	if($v == "m_rx") {
		$tabs .= '<li class="active"><a href="#tab1">Male RX</a></li>';
	} else if($v == "f_rx") {
		$tabs .= '<li><a href="#tab2">Female RX</a></li>';
	} else if($v == "m_sc") {
		$tabs .= '<li><a href="#tab3">Male Scaled</a></li>';
	} else if($v == "f_sc") {
		$tabs .= '<li><a href="#tab4">Female Scaled</a></li>';
	} else if($v == "mm_rx") {
		$tabs .= '<li><a href="#tab5">Male/Male RX</a></li>';
	} else if($v == "ff_rx") {
		$tabs .= '<li><a href="#tab6">Female/Female RX</a></li>';
	} else if($v == "mm_sc") {
		$tabs .= '<li><a href="#tab7">Male/Male Scaled</a></li>';
	} else if($v == "ff_sc") {
		$tabs .= '<li><a href="#tab8">Female/Female Scaled</a></li>';
	} else if($v == "mf_rx") {
		$tabs .= '<li><a href="#tab9">Male/Female RX</a></li>';
	} else if($v == "mf_sc") {
		$tabs .= '<li><a href="#tab10">Male/Female Scaled</a></li>';
	}
}
$final_html .= $unorderedList . $tabs . '</ul> <!-- END UL -->';


$query2 = "";
$count = 1;
$key = "i";
foreach ($div_array as $k => $v) {
	if(strlen($v) > 0) {
		if($v == "m_rx") {
			$key = "i";
			$tab_content .= '<div id="tab1" class="tab active"> <!-- BEGIN TAB 1 -->';
		} else if($v == "f_rx") {
			$key = "i";
			$tab_content .= '<div id="tab2" class="tab"> <!-- BEGIN TAB 2 -->';
		} else if($v == "m_sc") {
			$key = "i";
			$tab_content .= '<div id="tab3" class="tab"> <!-- BEGIN TAB 3 -->'; 
		} else if($v == "f_sc") {
			$key = "i";
			$tab_content .= '<div id="tab4" class="tab"> <!-- BEGIN TAB 4 -->';
		} else if($v == "mm_rx") {
			$key = "t";
			$tab_content .= '<div id="tab5" class="tab"> <!-- BEGIN TAB 5 -->';
		} else if($v == "ff_rx") {
			$key = "t";
			$tab_content .= '<div id="tab6" class="tab"> <!-- BEGIN TAB '.$count.' -->';
		} else if($v == "mm_sc") {	
			$key = "t";
			$tab_content .= '<div id="tab7" class="tab"> <!-- BEGIN TAB '.$count.' -->';
		} else if($v == "ff_sc") {
			$key = "t";
			$tab_content .= '<div id="tab8" class="tab"> <!-- BEGIN TAB '.$count.' -->';
		} else if($v == "mf_rx") {
			$key = "t";
			$tab_content .= '<div id="tab9" class="tab"> <!-- BEGIN TAB '.$count.' -->';
		} else if($v == "mf_sc") {
			$key = "t";
			$tab_content .= '<div id="tab10" class="tab"> <!-- BEGIN TAB '.$count.' -->';
		}
		if($key == "i") { 
			$query2 = "select name, box, division from competition_athletes 
					where comp_id = $c_id AND division = '$v'";
		} else {
			$query2 = "select team_id, team_name from competition_teams 
					where competition_id = $c_id AND team_division = '$v'";
		}
		if($stmt = $mysqli->prepare($query2)) {
			$stmt->execute();
			$stmt->store_result();
			$num_of_rows = $stmt->num_rows;

			if($num_of_rows > 0) {
				if($key == "i") {
					$stmt->bind_result($atn, $box, $div);
					$tab_content .= '<table class="competitors2" id="competitors"><tr><th>Name</th><th>Box</th></tr>';	
					while ($stmt->fetch()) {
						$tab_content .= "<tr>";
						$tab_content .="<td>".$atn."</td>";
						$tab_content .="<td>".$box."</td>";
						$tab_content .="</tr>";
					}
				} else {
					$team_box_name = "";
					$team_athletes = "";
					$team_name = "";
					$team_id = 0;
					$stmt->bind_result($tid, $tn);
					$tab_content .= '<table class="competitors2" id="competitors"><tr><th>Team Name</th><th>Athletes</th><th>Box</th></tr>';
					while ($stmt->fetch()) {
						$team_name = $tn;
						$team_id = $tid;
					
						$query3 = "select name, box from competition_athletes where comp_id = $c_id
								AND team_id = $team_id";
						if($stmt3 = $mysqli->prepare($query3)) {
							$stmt3->execute();
							$stmt3->store_result();
							$num_of_rows = $stmt3->num_rows;
							if($num_of_rows > 0) {
								$stmt3->bind_result($atn, $box);
								while ($stmt3->fetch()) {
									$team_box_name = $box;
									$team_athletes .= $atn . ", ";
								}
							}
						}
						$team_athletes = rtrim($team_athletes);
						$team_athletes = substr($team_athletes, 0, strlen($team_athletes)-1);
							
						$tab_content .= "<tr>";
						$tab_content .="<td>".$team_name."</td>";
						$tab_content .="<td>".$team_athletes."</td>";
						$tab_content .="<td>".$team_box_name."</td>";
						$tab_content .="</tr>";
						$team_athletes ="";
					}
				}

				$tab_content .= '</table>';
				$stmt->free_result();
			} else {
				$tab_content .= 'No athletes have registered yet!';
			}
		}
		$tab_content .= "</div> <!-- END TAB -->";
	}
	$count = $count + 1;
}

$tab_content .= '</div> <!-- END TAB CONTENT -->';
$final_html .= $tab_content;
echo $final_html;
/*
$comp = new competitor();
if($key == "i") {
	if($stmt = $mysqli->prepare("select name, box, division from competition_athletes where comp_id = $c_id")) 
	{
		$stmt->execute();
		$stmt->store_result();
		$list_of_competitors = array();
		$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0) {
			$stmt->bind_result($atn, $box, $div);

			while ($stmt->fetch()) {
				$comp->name = $atn;
				$comp->division = $div;
				$comp->box = $box;
				
				//echo "BOUND: ".$wid." ". $sc ."\n ";
				//echo "LIST: ". $list->wod_id. " " .$list->score."\n ";
				
				$list_of_competitors[] = $comp;
				unset($comp);
			}
			$stmt->free_result();
			echo json_encode($list_of_competitors);
		}
		else {
			echo "1: No data";
		}
	} else {
			echo "2: " . $mysqli->error;
	}
} else {
	echo "not yet";
}

if($stmt = $mysqli->prepare("select ca.name, ca.box, ca.division, ct.team_name
	from competition_athletes AS ca 
	join competition_teams AS ct on ca.team_id = ct.team_id 
	where ca.comp_id = '$c_id'")) 
{
	$stmt->execute();
	$stmt->store_result();
	$list_of_competitors = array();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		$stmt->bind_result($atn, $box, $div, $tna);

		while ($stmt->fetch()) {
			$comp->name = $atn;
			$comp->division = $div;
			$comp->box = $box;
			$comp->team_name = $tna;
			
			//echo "BOUND: ".$wid." ". $sc ."\n ";
			//echo "LIST: ". $list->wod_id. " " .$list->score."\n ";
			
			$list_of_competitors[] = $comp;
			unset($comp);
		}
		$stmt->free_result();
		//unset($stmt);
		echo json_encode($list_of_competitors);
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}
*/
$mysqli->close();
?>