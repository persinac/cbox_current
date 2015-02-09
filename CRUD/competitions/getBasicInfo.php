<?php require_once('../../Connections/cboxConn.php'); ?>
<?php

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

class competition {
	public $title = ""; 
	public $date_ = "";
	public $time = "";
	public $location = "";
	public $divisions = ""; 
	public $description  = "";
	public $registration_limits = "";
	public $costs = "";
	public $general = "";
	public $contact = "";
	public $picture_url = "";
}
$competition_info = array();
$comp_id = $_POST['comp_id'];

$data = "";
if($stmt = $mysqli->prepare("SELECT competition_title, date, time, 
		location, divisions, registration_limits, costs, general_info, contact, picture_url
		FROM competition_info where comp_id = $comp_id")) 
{
	$stmt->execute();
	$stmt->store_result();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		$c = new competition();
		/* Bind the result to variables */
		$stmt->bind_result($ct, $dt, $ti, $lo, $di, $reli, $cst, $gi, $cnt, $p);
		
		while ($stmt->fetch()) {
			$c->title = $ct;
			$c->date_ = $dt;
			$c->time = $ti;
			$c->location = $lo;
			$c->divisions = $di;
			$c->registration_limits = $reli;
			$c->costs = $cst;
			$c->general = $gi;
			$c->contact = $cnt;
			$c->picture_url = $p;
			$competition_info[] = $c;
		}

		$stmt->free_result();
		echo json_encode($competition_info);
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}

$mysqli->close();
?>