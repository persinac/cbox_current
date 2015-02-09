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

if($stmt = $mysqli->prepare('select user_id, box_id, customer_id, subscription_id
						FROM customer_details WHERE user_id = ?')) 
{
	$stmt->bind_param('s', $userID);
	$stmt->execute();
	$stmt->store_result();

	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	//echo $num_of_rows . "    ";
	
	if($num_of_rows > 0) {

		/* Bind the result to variables */
		$stmt->bind_result($u_id, $b_id, $c_id, $s_id);

		while ($stmt->fetch()) {
			//echo " " . $u_id. " " . $b_id . " " . $c_id ." ". $s_id;
			$u->user_id = $u_id;
			$u->box_id = $b_id;
			$u->customer_id = $c_id;
			$u->subscription_id = $s_id;
		}

		/* free results */
		$stmt->free_result();

		echo json_encode($u);
	}
	else {
		echo "1";
	}
}

$mysqli->close();

?>