<?php require_once('../../Connections/challenge_cal_Conn.php'); ?>
<?php
session_start();
include('../../CRUD/library/activity_feed_util.php');
$mysqli = new mysqli($hostname_challenge_cal, $username_challenge_cal, $password_challenge_cal, $database_challenge_cal);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$user_id = $_POST['id'];
//$user_id = $_SESSION["MM_user_id"];
$value = $_POST['data'];
$field = $_POST['field'];
echo "$user_id, $value, $field";
$act_field = "";
$show = -1;
if($field == 1) {
	$query = "update user_info set email = ? where user_id = ?";
	$act_field = "email";
	$show = 0;
} else if($field == 2) {
	$query = "update user_info set city = ? where user_id = ?";
	$act_field = "location";
	$show = 0;
} else if($field == 3) {
	$query = "update user_info set state = ? where user_id = ?";
	$act_field = "location";
	$show = 0;
} else if($field == 4) {
	$query = "update user_pub_info set bio = ? where user_id = ?";
	$act_field = "bio";
	$show = 0;
} else if($field == 5) {
	$query = "update user_pub_info set fav_lift = ? where user_id = ?";
	$act_field = "favorite lift";
	$show = 1;
} else if($field == 6) {
	$query = "update user_pub_info set fav_exercise = ? where user_id = ?";
	$act_field = "favorite exercise";
	$show = 1;
}
//echo $query;

$stmt = $mysqli->prepare($query);
$stmt->bind_param( 'si', $value, $user_id);

if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
	$testDB = new ActivityLogConnection();
	$testDB->NewConnection($hostname_challenge_cal, 
		$username_challenge_cal, 
		$password_challenge_cal, 
		$database_challenge_cal);
	$testDB->SetUserFullName($user_id);
	$testDB->SetUserID($user_id);
	$testDB->SetUpdateProfile("updated their " . $act_field . "");
	$testDB->SubmitToActivityLog($testDB->GetUpdateProfile(), $show);
	$testDB->CloseConnection();
} else {
	echo "0";
}

$mysqli->close();
?>