<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$c_id = $_POST['comp_id'];

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$result = 0;
if($stmt = $mysqli->prepare("select max(wodNum) from competition_wods where comp_id = '$c_id'")) 
{
	$stmt->execute();
	$stmt->store_result();
	$list_of_competitors = array();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($num);
		
		while ($stmt->fetch()) {
			$result = $num;
		}

		/* free results */
		$stmt->free_result();
		//unset($stmt);
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}
$retStr = '<b>WOD</b>: <select id="wod_selector"><option value="none"> - </option>';
for($i = 0; $i<$result;$i++) {
	$t_num = $i+1;
	$retStr .= '<option value="wod_'.$t_num.'"> WOD '.$t_num.' </option>';
}
$retStr .= '<option value="overall"> Overall </option>';
$retStr .= '</select><p id="wod_desc"></p><div id="wod_score_results"></div>';

echo $retStr;

$mysqli->close();
?>