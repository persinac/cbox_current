<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$arr = json_decode($_POST['items']);
$t_tow = "";
$total = 0;
foreach($arr as $i)
{
	if($i->wod_type == 1) {
		$t_tow = "RFT";
	} else {
		$t_tow = "AMRAP";
	}
	$t_desc = $i->description;
	$found_newline = false;
	$string_builder = "";
	$emergency_exit = 0;
	while(strstr($t_desc, PHP_EOL)) {
		//echo "found newline @ " . strpos($t_desc, PHP_EOL) . "\n";
		$string_builder .= "<p>" . substr($t_desc, 0, strpos($t_desc, PHP_EOL)) . "</p>";
		$t_desc = substr($t_desc, strpos($t_desc, PHP_EOL)+1);
		$found_newline = true;
		$emergency_exit = $emergency_exit + 1;
		if($emergency_exit == 100) {
			echo "Too many new lines, exiting...";
			exit();
		}
	}
	if($found_newline == true) {
		$string_builder .= "<p>" . $t_desc . "</p>";
		
	} else {
		$string_builder = $i->description;
	}
	//echo "Final: $string_builder";
	$stmt = $mysqli->prepare("update competition_wods set description = ?, type_of_wod = ? 
		WHERE comp_id = ? AND division = ? AND wodNum = ?");
	$stmt->bind_param( 'ssisi', $string_builder, $t_tow, $i->cid, $i->division, $i->wod_num);

	if($result = $stmt->execute()) {
		$stmt->close();
	} else {
		$total = $total + 1;
	}
}
echo $total;
$mysqli->close();
?>