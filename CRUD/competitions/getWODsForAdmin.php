<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$t_id = $_POST['c_id'];
$is_admin = $_POST['admin'];

$final_html = "";
$unorderedList = '<ul class="tab-links">';
$tab_content = '<div class="tab-content">';
$tabs = "";
$divisions = "";
$retVal = 0; //0 is good to go, anything else - ERROR
$query1 = "select divisions from competition_info where comp_id = $t_id";

if($stmt = $mysqli->prepare($query1)) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		$stmt->bind_result($d);
		
		while ($stmt->fetch()) {
			$divisions = $d;
		}
		$stmt->free_result();
		
	} else {
		$retVal = 1;
	}
} else {
	$retVal = 2;
}

if($retVal == 0) {
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
	$count = 0;
	foreach ($div_array as $k => $v) {
		if(strlen($v) > 0) {
			$query2 = "select division, wodNum, description, type_of_wod from competition_wods ";
			$query2 .= "where comp_id = $t_id and division = '$v' ORDER BY division, wodNum";
			if($stmt = $mysqli->prepare($query2)) {
				$stmt->execute();
				$stmt->store_result();
				$num_of_rows = $stmt->num_rows;
				if($num_of_rows > 0) {
					$stmt->bind_result($d, $wn, $des, $tow);
					
					while ($stmt->fetch()) {
						if($d == "m_rx") {
							$count = 1;
							if($wn == 1) {
								$tab_content .= '<div id="tab1" class="tab active"> <!-- BEGIN TAB 1 -->';
							} 
						} else if($d == "f_rx") {
							$count = 2;
							if($wn == 1) {
								$tab_content .= '<div id="tab2" class="tab"> <!-- BEGIN TAB 2 -->';
							} 
						} else if($d == "m_sc") {
							$count = 3;
							if($wn == 1) {
								$tab_content .= '<div id="tab3" class="tab"> <!-- BEGIN TAB 3 -->';
							} 
						} else if($d == "f_sc") {
							$count = 4;
							if($wn == 1) {
								$tab_content .= '<div id="tab4" class="tab"> <!-- BEGIN TAB 4 -->';
							} 
						} else if($d == "mm_rx") {
							$count = 5;
							if($wn == 1) {
								$tab_content .= '<div id="tab5" class="tab"> <!-- BEGIN TAB 5 -->';
							} 
						} else if($d == "ff_rx") {
							$count = 6;
							if($wn == 1) {
								$tab_content .= '<div id="tab6" class="tab"> <!-- BEGIN TAB '.$count.' -->';
							}
						} else if($d == "mm_sc") {	
							$count = 7;
							if($wn == 1) {
								$tab_content .= '<div id="tab7" class="tab"> <!-- BEGIN TAB '.$count.' -->';
							}
						} else if($d == "ff_sc") {
							$count = 8;
							if($wn == 1) {
								$tab_content .= '<div id="tab8" class="tab"> <!-- BEGIN TAB '.$count.' -->';
							}
						} else if($d == "mf_rx") {
							$count = 9;
							if($wn == 1) {
								$tab_content .= '<div id="tab9" class="tab"> <!-- BEGIN TAB '.$count.' -->';
							}
						} else if($d == "mf_sc") {
							$count = 10;
							if($wn == 1) {
								$tab_content .= '<div id="tab10" class="tab"> <!-- BEGIN TAB '.$count.' -->';
							}
						}
						$tab_content .= '<h4> WOD# '.$wn.'</h4>';
						if($is_admin == 1) {
							
							$tab_content .= '<p>';
							$tab_content .= '<textarea id="'.$d.'_'.$wn.'" class="textarea" rows="5" cols="90">';
							$tab_content .= insertNewlineChar($des);
							$tab_content .= '</textarea>';
							$tab_content .= '</p>';
							$tab_content .= 'Score by: <div id="rad_'.$d.'_'.$wn.'_div">';
							if($tow == 'RFT') {
								$tab_content .= '<input type="radio" id="rad_'.$d.'_'.$wn.'" name="rad_'.$d.'_'.$wn.'" value="1" checked> Time ';
								$tab_content .= '<input type="radio" id="rad_'.$d.'_'.$wn.'" name="rad_'.$d.'_'.$wn.'" value="2"> Reps ';
							} else {
								$tab_content .= '<input type="radio" id="rad_'.$d.'_'.$wn.'" name="rad_'.$d.'_'.$wn.'" value="1">Time  ';
								$tab_content .= '<input type="radio" id="rad_'.$d.'_'.$wn.'" name="rad_'.$d.'_'.$wn.'" value="2" checked>Reps';
							}
							
						} else {
							
							$tab_content .= '<div class="indent_me">'.$des.'</div>';
							$tab_content .= '<b>Score by:</b> <div>';
							if($tow == 'RFT') {
								$tab_content .= ' <p class="indent_me"> Fastest Time </p>';
							} else {
								$tab_content .= ' <p class="indent_me"> Total Rounds and Reps </p>';
							}
						}
						$tab_content .= '</div><p></p>';
					}
					if($is_admin == 1) {
						$tab_content .= '<button type="button" onclick="saveDivisionWODs(\''.$v.'\');">Save WODs For this Division</button>';
					}
					$tab_content .= "</div>";
					$stmt->free_result();
				} else {
					if($v == "m_rx") {
						$tab_content .= '<div id="tab1" class="tab active">';
					} else if($v == "f_rx") {
						$tab_content .= '<div id="tab2" class="tab">';
					} else if($v == "m_sc") {
						$tab_content .= '<div id="tab3" class="tab">';
					} else if($v == "f_sc") {
						$tab_content .= '<div id="tab4" class="tab">';
					} else if($v == "mm_rx") {
						$tab_content .= '<div id="tab5" class="tab">';
					} else if($v == "ff_rx") {
						$tab_content .= '<div id="tab6" class="tab">';
					} else if($v == "mm_sc") {
						$tab_content .= '<div id="tab7" class="tab">';
					} else if($v == "ff_sc") {
						$tab_content .= '<div id="tab8" class="tab">';
					} else if($v == "mf_rx") {
						$tab_content .= '<div id="tab9" class="tab">';
					} else if($v == "mf_sc") {
						$tab_content .= '<div id="tab10" class="tab">';
					}
					$tab_content .= '<button type="button" onclick="saveDivisionWODs()">Save WODs For this Division</button></p></div>';
				}
			} else {
				$retVal = 2;
			}
		}
	}
	$tab_content .= '</div>';
	$final_html .= $tab_content;
	echo $final_html;
} else {
	echo "ERROR: $retVal";
}

$mysqli->close();

function insertNewlineChar($str) {

	$retVal = "";
	$html = array("<p>", "</p>");
	//echo $str . "\n";
	$str = str_replace("<p>", "", $str);
	//echo $str . "\n";
	$retVal = str_replace("</p>", "\n", $str);
	//echo $retVal . "\n";
	return $retVal;
}


?>