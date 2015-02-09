<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
$t_comp_id = $_POST['c_id'];
$t_update_id = $_POST['id'];
$t_text = $_POST['text'];

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$query_begin = "update competition_info set ";
$query_end = " = ? where comp_id = ?";
$final_query = "";

if($t_update_id == 0) {
	$final_query = $query_begin . "competition_title" . $query_end;
} else if($t_update_id == 1) {
	$final_query = $query_begin . "location" . $query_end;	
} else if($t_update_id == 2) {
	$final_query = $query_begin . "time" . $query_end;	
} else if($t_update_id == 3) {
	$final_query = $query_begin . "date" . $query_end;	
} else if($t_update_id == 4) {
	$final_query = $query_begin . "divisions" . $query_end;	
} else if($t_update_id == 5) {
	$final_query = $query_begin . "registration_limits" . $query_end;	
} else if($t_update_id == 6) {
	$final_query = $query_begin . "costs" . $query_end;	
} else if($t_update_id == 7) {
	$final_query = $query_begin . "general_info" . $query_end;	
} else if($t_update_id == 8) {
	$final_query = $query_begin . "contact" . $query_end;	
}

$stmt = $mysqli->prepare($final_query);
$stmt->bind_param( 'ss',  $t_text, $t_comp_id);

if($result = $stmt->execute()) {
	echo "1";
	$stmt->close();
} else {
	echo "0";
}	
$mysqli->close();
?>