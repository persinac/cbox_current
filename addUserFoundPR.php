<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$_SESSION['MM_Username'] = "kellintc";
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$user_id = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $user_id = $_SESSION['MM_UserID'];
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}


$t_user_id = $user_id;
$t_mvmnt_id = $_POST['mvmnt_id'];
$t_name = $_POST['name'];
$t_weight = $_POST['weight'];
$t_time = $_POST['time'];
$t_date_achieved = $_POST['date'];
$t_rex = $_POST['reps'];
$t_wod_type = $_POST['wod_type'];

$t_string_builder = ""; //nice to have

/*
*
* MySql insert
*
*	VALUES: user_id, mvmnt_id, name (opt), weight, time, date, reps, wod_type
*
* @return:
*	1 - SUCCESS
*	2 - ERROR
*/
$stmt = $mysqli->prepare("insert into benchmarks values (?, ?, ?, ?, ?, ?, ?, ?)");


$stmt->bind_param( 'ississss', $t_user_id, $t_mvmnt_id, $t_name, $t_weight, $t_time, $t_date_achieved, $t_rex, $t_wod_type);

if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "2";
}

$mysqli->close();
?>