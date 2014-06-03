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

class user2 {
	
	public $username = ""; 
	public $password = ""; 
	// region & gender... left 
}

$u = new user2();
//echo "New user before credentials: " . $u->first_name . " ". $u->last_name . ", ". $u->email . ", ". $u->box_id . " ";
$query_getuserinfov3 = "select	username, password
						FROM login 
						WHERE user_id = $userID";

//echo "\nMain Query: " .$query_getuserinfov3 ."\n";

if ($result = $mysqli->query($query_getuserinfov3)) {
	while ($row = $result->fetch_assoc()) {
		$u->username = $row['username'];
		$u->password = $row['password']; 		
	}
	echo json_encode($u);
	
	$result->free();
}


$mysqli->close();

?>