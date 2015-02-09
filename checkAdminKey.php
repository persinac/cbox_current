<?php require_once('Connections/cboxConn.php'); ?>
<?php

$box_id = $_POST['box_id'];
$key = $_POST['key'];
$t_admin_id = "";
$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
if($stmt = $mysqli->prepare("SELECT admin FROM athletes WHERE box_id = $box_id")) 
{
	$stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		echo "5 - An administrator already exists for this box";
	} else {
		if($stmt = $mysqli->prepare("SELECT admin_id FROM box WHERE box_id = $box_id")) 
		{
			$stmt->execute();
			$stmt->store_result();
			$num_of_rows = $stmt->num_rows;
			if($num_of_rows > 0) {
				$stmt->bind_result($aid);

				while ($stmt->fetch()) {
					$t_admin_id = $aid;
				}
				$stmt->free_result();
			}
			else {
				echo "3: No data";
			}
		} else {
				echo "4: " . $mysqli->error;
		}
		//echo "DB: $t_admin_id INPUT: $key";

		if(strcmp($t_admin_id, $key) == 0) {
			echo "1";
		} else {
			echo "2";
		} 
	}
}

$mysqli->close();
?>