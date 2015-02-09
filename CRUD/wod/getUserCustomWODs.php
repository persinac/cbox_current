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

$user_id = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $user_id = $_SESSION['MM_UserID'];
}

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
 
class wod {
	public $title = ""; //date 
	public $id = 0;
	public $end = "";
	public $start = ""; 
	public $description  = "";
	public $wod_id = "";
	public $date_of_wod = "";
	public $name_of_wod = "";
	public $type_of_wod = "";
	public $score = "";
}

$length_of_user_id = strlen($user_id);

$query_getUserCustomWODs = "select custom_id
, date_of_wod
, CASE WHEN (name_of_wod = '') THEN '-'
	ELSE name_of_wod
	END AS name_of_wod
, type_of_wod
, description
, score
 from custom_wods
WHERE SUBSTRING(custom_id, 1, {$length_of_user_id}) = '{$user_id}'";

if ($result = $mysqli->query($query_getUserCustomWODs)) {
	$wods = array();
	$index = 8;
	$str = 1;
	$cond = 1;
	$count = 1;
	$is_rest_day = 0;
	$prev_date = "";
	while ($row = $result->fetch_assoc()) {
		//first if accounts for multiple wods in a single day
		if($prev_date == $row["date_of_wod"]) {
			$str = $str + $count;
			$cond = $cond + $count;
			$index = $index + $count;
		} else {
			$str = 1;
			$cond = 1;
			$index = 8;
		}
		$w = new wod();
		$w->id = $row["custom_id"];
		if($index < 10) {
			$w->start = $row["date_of_wod"] . "T0".$index.":00:00";
			$w->end = $row["date_of_wod"] . "T0".$index.":59:00";
		} else if ($index > 9 && $index < 13) {
			$w->start = $row["date_of_wod"] . "T".$index.":00:00";
			$w->end = $row["date_of_wod"] . "T".$index.":59:00";
		}
		$w->wod_id = $row["custom_id"];
		$w->date_of_wod = $row["date_of_wod"];
		$w->name_of_wod = $row["name_of_wod"];
		$w->type_of_wod = $row["type_of_wod"];
		$w->description .= "".$row["description"]."  ";
		$w->score = $row['score'];

		$wods[] = $w;
		$index = $index + 1;
		$prev_date = $row["date_of_wod"];
		$count = $count + 1;
	}
	//echo "ISSET: ".$_POST['progress']."\n";
	if(isset($_POST['progress'])) {
		echo customWodsToTable($wods);
	} else {
		echo json_encode($wods);
	}
	$result->free();
}
$mysqli->close();

function customWodsToTable($t_wod_list) {
	$retStr = '';
	foreach($t_wod_list AS $i => $value) {
		$retStr .= '<tr class="cftwod_sec1_data">';
		$retStr .= '<td>'.$value->date_of_wod.'</td>';
		$retStr .= '<td>'.$value->name_of_wod.'</td>';
		$retStr .= '<td>'.$value->type_of_wod.'</td>';
		$retStr .= '<td>'.$value->description.'</td>';
		$retStr .= '<td>'.$value->score.'</td>';
		$retStr .= '</tr>';
	}
	$retStr .= '';
	return $retStr;
}
?>