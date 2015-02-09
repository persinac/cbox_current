<?php require_once('../../Connections/challenge_cal_Conn.php'); ?>
<?php
session_start();
include('../../CRUD/library/activity_feed_util.php');

$mysqli = new mysqli($hostname_challenge_cal, $username_challenge_cal, $password_challenge_cal, $database_challenge_cal);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$t_username = $_POST['username'];
$t_password = $_POST['password'];
echo "HELLO $t_username";
?>