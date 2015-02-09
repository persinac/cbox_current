<?php require_once('../../Connections/challenge_cal_Conn.php'); ?>
<?php
session_start();
include('../../CRUD/library/activity_feed_util.php');

$mysqli = new mysqli($hostname_challenge_cal, $username_challenge_cal, $password_challenge_cal, $database_challenge_cal);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$t_username = $_POST['un'];
$t_password = $_POST['pw'];
$correct = 0;
if ($stmt = $mysqli->prepare("SELECT user_id FROM login WHERE username = ? AND password = ?")) {
    $stmt->bind_param("ss", $t_username, $t_password);
    $stmt->execute();
	$stmt->store_result();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		$stmt->bind_result($id);
		$stmt->fetch();
		$correct = $id;
	}
	$stmt->close();
	echo $correct;
	if($correct > 0) {
		$t_uid = $correct;
		$testDB = new ActivityLogConnection();
		$testDB->NewConnection($hostname_challenge_cal, 
			$username_challenge_cal, 
			$password_challenge_cal, 
			$database_challenge_cal);
		$testDB->SetUserFullName($t_uid);
		$testDB->SetUserID($t_uid);
		$testDB->SetLogin("logged on");
		$testDB->SubmitToActivityLog($testDB->GetLogin(), 0);
		$testDB->CloseConnection();
	}
}
$mysqli->close();
?>