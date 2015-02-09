<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$user_id = $_SESSION['MM_UserID'];

$value = $_POST['data'];
$field = $_POST['value'];

if($field == 1) {
	$query = "update athletes set region = ? where user_id = ?";
} else if($field == 2) {
	$query = "update athletes set cf_exp = ? where user_id = ?";
} else if($field == 3) {
	$query = "update athletes set f_lift = ? where user_id = ?";
} else if($field == 4) {
	$query = "update athletes set f_girl = ? where user_id = ?";
} else if($field == 5) {
	$query = "update athletes set f_hero = ? where user_id = ?";
} else if($field == 6) {
	$query = "update athletes set f_movement = ? where user_id = ?";
}

$stmt = $mysqli->prepare($query);
$stmt->bind_param( 'ss', $value, $user_id);

if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "0";
}

$mysqli->close();
?>