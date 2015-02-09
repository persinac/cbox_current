<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$t_lim = $_POST['limits'];
$t_cid = $_POST['id'];
$spots = 0;
$final_string = "";
$t_string = "";
$arr = explode(",", $t_lim);
foreach($arr as $item) {
	if(strlen($item) > 0) {
		$div = substr($item,strpos($item, "_")+1);
		
		$t_string .= "<p class=\"indent_me\">";
		if($div == "m_rx") {
			$t_string .= "Male RX - "; 
		} else if($div == "f_rx") {
			$t_string .= "Female RX - "; 
		} else if($div == "m_sc") {
			$t_string .= "Male Scaled - "; 
		} else if($div == "f_sc") {
			$t_string .= "Female Scaled - "; 
		} else if($div == "mm_rx") {
			$t_string .= "Team Male RX - "; 
		} else if($div == "ff_rx") {
			$t_string .= "Team Female RX - "; 
		} else if($div == "mm_sc") {
			$t_string .= "Team Male Scaled - "; 
		} else if($div == "ff_sc") {
			$t_string .= "Team Female Scaled - "; 
		} else if($div == "mf_rx") {
			$t_string .= "Team Male/Female RX - "; 
		} else if($div == "mf_sc") {
			$t_string .= "Team Male/Female Scaled - "; 
		}
		
		if(strpos($div,"_") == 1) {
			$query = "select count(*) from competition_athletes
				where comp_id = $t_cid AND division = '$div'";
		} else {
			$query = "select count(*) from competition_teams
				where competition_id = $t_cid AND team_division = '$div';";
		}
		
		$spots = substr($item,0,strpos($item, "_"));
		if($stmt = $mysqli->prepare($query)) 
		{
			$stmt->execute();
			$stmt->store_result();
			$num_of_rows = $stmt->num_rows;
			if($num_of_rows > 0) {
				$stmt->bind_result($ct);
				
				while ($stmt->fetch()) {
					$spots = $spots - $ct;
				}
				
				$stmt->free_result();
				$t_string .= $spots . " open spots</p>";
			}
			else {
				echo "1: No data";
			}
		} else {
			echo "2: " . $mysqli->error;
		}
	}
}
echo $t_string;
$mysqli->close();
?>