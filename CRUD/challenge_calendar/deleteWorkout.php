<?php require_once('../../Connections/challenge_cal_Conn.php'); ?>
<?php
session_start();
include('../../CRUD/library/activity_feed_util.php');


$user_id = $_POST['uid'];
$t_date = $_POST['c_date'];
//$user_id = $_SESSION["MM_user_id"];


$mysqli = new mysqli($hostname_challenge_cal, $username_challenge_cal, $password_challenge_cal, $database_challenge_cal);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}


$stmt = $mysqli->prepare("delete from workouts WHERE user_id = ? AND date = ?");
$stmt->bind_param( 'is', $user_id, $t_date);

if($result = $stmt->execute()) {
	echo "Success\n";
	$stmt->close();
	$testDB = new ActivityLogConnection();
	$testDB->NewConnection($hostname_challenge_cal, $username_challenge_cal, $password_challenge_cal, $database_challenge_cal);
	$testDB->SetUserFullName($user_id);
	$testDB->SetUserID($user_id);
	$testDB->SetDelete("deleted their workout for $t_date");
	$testDB->SubmitToActivityLog($testDB->GetDelete(), 1);
	$testDB->CloseConnection();
} else {
	echo "1 ";
}

$mysqli->close();

?>