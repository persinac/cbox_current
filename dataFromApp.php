<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
$str = $_POST['wod_num'] . ", " .$_POST['athlete_number'] . ", " .$_POST['total_work_time'] . ", ";
$str .= $_POST['start_time'] . ", " . $_POST['end_time'];
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

fwrite($myfile, $str);
fclose($myfile);

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$att_num = "";
$wod_num = "";

$index = strpos($_POST['wod_num'], "_") + 1;
$att_num = substr($_POST['wod_num'], $index, 1);

$index = strpos($_POST['wod_num'], "*") + 1;
$wod_num = substr($_POST['wod_num'], $index);

$ath_name = $_POST['athlete_name'];
$stmt = $mysqli->prepare("insert into precision_wod values (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param( 'ssssssss', $_POST['wod_num'], $_POST['athlete_number'], $ath_name,
	$_POST['start_time'], $_POST['end_time'], $_POST['total_work_time'], $att_num, $wod_num);		
if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "2";
}

?>