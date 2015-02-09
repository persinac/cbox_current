<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$admin_key = $_POST['key'];

$c_id = "";
if($stmt = $mysqli->prepare("SELECT comp_id, admin_key FROM competitions where admin_key = '$admin_key'")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($cid, $ad_key);
		while ($stmt->fetch()) {
			$c_id = $cid;
		}
		/* free results */
		$stmt->free_result();
		echo $c_id;
	}
	else {
		echo "0";
	}
} else {
		echo "0";
}

$mysqli->close();
?>