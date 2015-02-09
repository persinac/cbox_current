<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
} 
} #end function

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $userID = $_SESSION['MM_UserID'];
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


class core_benchmark {
	public $name = "";
	public $score = "";
	public $user_id = "";
}

$list = new core_benchmark();
//echo $_POST['gender']."\n";
$gender_string = "";
if($_POST['gender'] == "M") {
	$gender_string = " AND a.gender = 'M' ";
} else if($_POST['gender'] == "F") {
	$gender_string = " AND a.gender = 'F' ";
}


if($stmt = $mysqli->prepare("SELECT max(weight) score, mvmnt_id, b.user_id, a.athlete_name
	FROM benchmarks b
	JOIN (select user_id, CONCAT(first_name, ' ', last_name) athlete_name, gender
		from athletes) a ON b.user_id = a.user_id
	WHERE mvmnt_id = ?
	{$gender_string}
	GROUP BY mvmnt_id, b.user_id 
	ORDER BY score DESC")) 
{
	//echo "Selector: ".$_POST['core_type_selector']. "\n";
	$stmt->bind_param('s', $_POST['core_type_selector']);
	$stmt->execute();
	$stmt->store_result();
	$list_of_benchmarks = array();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	//echo $num_of_rows . "    ";
	if($num_of_rows > 0) {

		/* Bind the result to variables */
		$stmt->bind_result($sc, $mvmt, $uid, $n);

		while ($stmt->fetch()) {			
			$list->score = $sc;
			$list->name = $n;
			$list->user_id = $uid;
			
			/*
			echo "BOUND: ".$mvmt." ". $we. " " .$n. " ".$uid. "\n ";
			echo "LIST: ". $list->weight. " " .$list->name. " ".$list->user_id. "\n ";
			*/
			$list_of_benchmarks[] = $list;
			unset($list);
		}

		/* free results */
		$stmt->free_result();

		echo json_encode($list_of_benchmarks);
	}
	else {
		echo "1: " . $mysqli->error;
	}
}
$mysqli->close();
?>