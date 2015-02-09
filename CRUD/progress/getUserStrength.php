<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$user_id = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $user_id = $_SESSION['MM_UserID'];
}

class strength {
	public $date = "";
	public $values = "";

}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$length_of_user_id = strlen($user_id);
$first_date_uscore = (int)$length_of_user_id + 2;
$second_date_uscore = (int)$length_of_user_id + 3;

if($stmt = $mysqli->prepare("select SUBSTRING(str_id, $first_date_uscore, LOCATE('_',SUBSTRING(str_id, $second_date_uscore))) AS date,
	athlete_strength.values AS weight
	FROM athlete_strength 
	WHERE athlete_id = $user_id")) 
{
	$stmt->execute();
	$stmt->store_result();
	$ts = new strength();
	$list_of_scores = array();
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		$stmt->bind_result($da, $we);

		while ($stmt->fetch()) {
			$ts->date = $da;
			$ts->weight = $we;
			$list_of_scores[] = $ts;
			unset($ts);
		}
		$stmt->free_result();
	}
	else {
		echo "1: No data";
	}
	$retStr = '';
	
	foreach($list_of_scores AS $i => $value) {
		$retStr .= '<tr class="cftwod_sec1_data">';
		$retStr .= '<td>'.$value->date.'</td>';
		$retStr .= '<td>'.$value->weight.'</td>';
		$retStr .= '</tr>';
	}
	$retStr .= '';
}

echo $retStr;

$mysqli->close();
?>