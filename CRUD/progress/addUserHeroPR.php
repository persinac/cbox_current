<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$user_id = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $user_id = $_SESSION['MM_UserID'];
}


$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$stmt = $mysqli->prepare("insert into cf_heroes values (?, ?, ?, ?, ?)");
$stmt->bind_param( 'issss', $user_id,$_POST['id'],$_POST['date'],$_POST['level'],$_POST['score']);

if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "2 - " . $mysqli->error;
}

$mysqli->close();
?>