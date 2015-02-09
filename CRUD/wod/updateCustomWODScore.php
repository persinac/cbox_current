<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
/*
 * CUSTOM_WOD_ID SYNTAX = <userID>_<date>_<maxCustomID>
 * Order of operations:
 *		Get POST variables
 *		Update Score based on custom_wod_id
 */
session_start();

$t_id = $_POST['cus_wod_id'];
$t_score = $_POST['score'];

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

echo "t_id: $t_id, score: $t_score\n";

/*
 * UPDATE: custom_id, wod score
 */
$stmt = $mysqli->prepare("UPDATE custom_wods SET score = ? WHERE custom_id = ?;");
$stmt->bind_param( 'ss', $t_score, $t_id);

if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "2 " . $mysqli->error;
}

$mysqli->close();
?>