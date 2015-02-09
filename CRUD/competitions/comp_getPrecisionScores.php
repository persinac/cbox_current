<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$retVal = '<table><tr><th>Name</th><th>Start Time</th><th>End Time</th></tr>';
if($stmt = $mysqli->prepare("select ath_name, start_time, end_time from precision_wod")) 
{
	$stmt->execute();
	$stmt->store_result();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($ath, $st, $et);

		while ($stmt->fetch()) {
			$retVal .= "<tr>";
			$retVal .= '<td>'.$ath.'</td>';
			$retVal .= '<td>'.$st.'</td>';
			$retVal .= '<td>'.$et.'</td>';
			$retVal .= '</tr>';
		}
		$retVal .= '</table>';
		/* free results */
		$stmt->free_result();
		//unset($stmt);
		echo $retVal;
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}

$mysqli->close();
?>