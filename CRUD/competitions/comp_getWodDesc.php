<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$c_id = $_POST['comp_id'];
$wodNum = $_POST['wodNum'];

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$result = 0;
if($stmt = $mysqli->prepare("select description from competition_wods where comp_id = '$c_id' and wodNum = '$wodNum'")) 
{
	$stmt->execute();
	$stmt->store_result();
	$list_of_competitors = array();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($des);
		
		while ($stmt->fetch()) {
			$result = $des;
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
$retStr = '<br/><br/><b>WOD #'.$wodNum.' Description</b>: <br/><br/>'.$result.'';

echo $retStr;

$mysqli->close();
?>