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
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
 
$t_custom_id = $_POST['date']; 
//echo "Custom id: ".$t_custom_id;
class wod {
	public $id = 0;
	public $description  = "";
	public $score = "";
	public $date_of_wod = "";
	public $name_of_wod = "";
	public $type_of_wod = "";
}

$query_getCustomWOD = "select CASE WHEN (name_of_wod = '') THEN '-'
	ELSE name_of_wod
	END AS name_of_wod
, type_of_wod
, description
, score
, date_of_wod
 from custom_wods
WHERE custom_id = '{$t_custom_id}'";
//echo "\nQuery: ".$query_getCustomWOD;
if ($result = $mysqli->query($query_getCustomWOD)) {
	$wods = array();
	$index = 7;
	$str = 1;
	$cond = 1;
	$count = 1;
	$is_rest_day = 0;
	$prev_date = "";
	while ($row = $result->fetch_assoc()) {
		$w = new wod();
		$w->id = $row["custom_id"];
		$w->score = $row["score"];
		$w->date_of_wod = $row["date_of_wod"];
		$w->name_of_wod = $row["name_of_wod"];
		$w->type_of_wod = $row["type_of_wod"];
		$w->description .= "".$row["description"]."  ";

		$wods[] = $w;
	}
	echo json_encode($wods);
	/* free result set */
	$result->free();
}
/* close connection */
$mysqli->close();
?>