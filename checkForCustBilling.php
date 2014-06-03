<?php require_once('Connections/cboxConn.php'); ?>
<?php 
session_start();

$userID = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $userID = $_SESSION['MM_UserID'];
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

class billing_info {
	public $user_id = ""; 
	public $box_id = "";
	public $customer_id = "";
	public $subscription_id = "";
}

$u = new billing_info();
//echo "New user before credentials: " . $u->first_name . " ". $u->last_name . ", ". $u->email . ", ". $u->box_id . " ";
$query_checkCustDetail = "select user_id, box_id, 
						customer_id, 
						subscription_id
						FROM customer_detail 
						WHERE user_id = $userID";

//echo "\nMain Query: " .$query_getuserinfov2 ."\n";

if ($result = $mysqli->query($query_checkCustDetail)) {
	while ($row = $result->fetch_assoc()) {
		$u->user_id = $row['user_id'];
		$u->box_id = $row['box_id'];
		$u->customer_id = $row['customer_id'];
		$u->subscription_id = $row['subscription_id'];
	}
	echo json_encode($u);
	
	$result->free();
} else {
	echo "1";
}

$mysqli->close();

?>