<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$selector = "";
if($stmt = $mysqli->prepare("SELECT box_name, box_id
		FROM box")) 
{
	$stmt->execute();
	$stmt->store_result();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($bn, $bid);
		$selector = "<select id=\"box_select\"><option value=\"-\"></option>";
		while ($stmt->fetch()) {
			$selector .= "<option value=\"".$bid."\">".$bn."</option>";
		}
		$selector .= "</select>";
		/* free results */
		$stmt->free_result();
		echo $selector;
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}

$mysqli->close();
?>