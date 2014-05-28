<?php require_once('Connections/cboxConn.php'); ?>
<?php 
session_start();

/*
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $userID = $_SESSION['MM_UserID'];
}
*/

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

class user {
	public $first_name = ""; 
	public $last_name = "";
	public $email = "";
	public $box_id = "";
	//I'll let you fill in the rest...
}

$u = new user();
//echo "New user before credentials: " . $u->first_name . " ". $u->last_name . ", ". $u->email . ", ". $u->box_id . " ";
$query_getUserInfo = "select first_name, last_name, 
						email, box_id, street_address, 
						city, state, zip, country, region,
						admin, gender
						FROM athletes 
						WHERE user_id = 1";
//echo "\nMain Query: " .$query_getUserInfo ."\n";

if ($result = $mysqli->query($query_getUserInfo)) {
	while ($row = $result->fetch_assoc()) {
		$u->first_name = $row['first_name'];
		$u->last_name = $row['last_name'];
		$u->email = $row['email'];
		$u->box_id = $row['box_id'];
	}
	echo json_encode($u);
	
	$result->free();
}
$mysqli->close();

?>