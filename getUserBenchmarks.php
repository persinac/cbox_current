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


class benchmark {
	public $date_achieved = "";
	public $weight = "";
	public $timea = "";
	public $reps = "";
	public $wod_type = "";
}

$list = new benchmark();

if($stmt = $mysqli->prepare('select date_achieved, weight, time, reps, wod_type 
from benchmarks 
where mvmnt_id = ? AND user_id = ?
order by date_achieved desc')) 
{
	
	$stmt->bind_param('si', $_POST['core_type_selector'],$userID);
	$stmt->execute();
	$stmt->store_result();
	$list_of_benchmarks = array();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	//echo $num_of_rows . "    ";
	if($num_of_rows > 0) {

		/* Bind the result to variables */
		$stmt->bind_result($d, $we, $t, $r, $wt);

		while ($stmt->fetch()) {			
			
			$list->date_achieved = $d;
			$list->weight = $we;
			$list->time_a = $t;
			$list->reps = $r;
			$list->wod_type = $wt;
			
			/*
			echo "BOUND: ". $d. " ". $we. " " .$t. " ".$r. " ".$wt;
			echo "LIST: ". $list->date_achieved. " ". $list->weight. " " .$list->time_a. " ".$list->reps. " ".$list->wod_type;
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