<?php require_once('Connections/cboxConn.php'); ?>
<?php 
session_start();


if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}
//
$userID = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $userID = $_SESSION['MM_UserID'];
}


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
	public $street_address = ""; 
	public $city = "";
	public $state = ""; 
	public $zip = ""; 
	public $country = ""; 
	
	public $gender = ""; 
	public $region = ""; 
	public $username = ""; 
	public $password = ""; 
	// region & gender... left 
}

$u = new user();
//echo "New user before credentials: " . $u->first_name . " ". $u->last_name . ", ". $u->email . ", ". $u->box_id . " ";
$query_getuserinfov2 = "select first_name, last_name, 
						email, box_id, street_address, 
						city, state, zip, country, region,
						admin, gender
						FROM athletes 
						WHERE user_id = $userID";
//$query_getuserinfov2 = "select username, password
//			FROM login
//			WHERE user_id = 15; 

//echo "\nMain Query: " .$query_getuserinfov2 ."\n";

if ($result = $mysqli->query($query_getuserinfov2)) {
	while ($row = $result->fetch_assoc()) {
		$u->user_id = $userID;
		$u->first_name = $row['first_name'];
		$u->last_name = $row['last_name'];
		$u->email = $row['email'];
		$u->box_id = $row['box_id'];
		$u->street_address = $row['street_address'];
		$u->city = $row['city'];
		$u->state = $row['state'];
		$u->zip = $row['zip'];
		$u->country = $row['country']; 

		$u->gender = $row['gender']; 
		$u->region = $row['region']; 
	}
	echo json_encode($u);
	
	$result->free();
}
/*
if ($result = $mysqli->query($query_getuserinfov3)) {
	while ($row = $result->fetch_assoc()) {
		$u->username = $row['username'];
		$u->password = $row['password']; 
		
	}
	echo json_encode($u);
	
	$result->free();
}
*/

$mysqli->close();

?>
