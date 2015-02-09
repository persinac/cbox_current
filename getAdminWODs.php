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

if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

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
/**** OLD *******
 * mysql_select_db($database_cboxConn, $cboxConn);
 ****************/
 
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
	public $color = "";
}

$query_getBoxID = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserWODs}'";

if ($result = $mysqli->query($query_getBoxID)) {
	$row = $result->fetch_assoc();
	$box_id = $row['box_id'];
}

/****************** OLD *****************************
$getBoxID = mysql_query($query_getBoxID, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getBoxID);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getBoxID);

$box_id = $row[0];

//echo "Box id: " . $box_id . "length of box_id: " . strlen($box_id);
*******************************************************/

$length_of_box_id = strlen($box_id);


$query_getAdminWODs = "select wod_id
, date_of_wod
, CASE WHEN (name_of_wod = '') THEN '-'
	ELSE name_of_wod
	END AS name_of_wod
, type_of_wod
, rx_descrip
 from wods
WHERE SUBSTRING(wods.wod_id, 1, {$length_of_box_id}) = '{$box_id}'";
//echo $query_getAdminWODs;
if ($result = $mysqli->query($query_getAdminWODs)) {
	$wods = array();
	$index = 7;
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
			$index = 7;
		}
		$w = new wod();
		$w->id = $row["wod_id"];
		//$w->title = $row["date"];
		if($index < 10) {
			$w->start = $row["date_of_wod"] . "T0".$index.":00:00";
			$w->end = $row["date_of_wod"] . "T0".$index.":59:00";
		} else if ($index > 9 && $index < 13) {
			$w->start = $row["date_of_wod"] . "T".$index.":00:00";
			$w->end = $row["date_of_wod"] . "T".$index.":59:00";
		}
		$w->wod_id = $row["wod_id"];
		$w->date_of_wod = $row["date_of_wod"];
		$w->name_of_wod = $row["name_of_wod"];
		$w->type_of_wod = $row["type_of_wod"];
		$w->description .= "".$row["rx_descrip"]."  ";
		
		$mystring = $w->name_of_wod;
		//echo "MYSTRING: ".$mystring;
		//$result = stristr($mystring, 'g');
		if(strlen(stristr($mystring, 'g')) > 1) {
			$w->title = "Conditioning: Girl";
			$w->color = "pink";
		} else if(strlen(stristr($mystring, 'h')) > 1) {
			$w->title = "Conditioning: Hero";
			$w->color = "yellow";
		} else if($w->type_of_wod == "AMRAP") {
			$w->title = "Conditioning: AMRAP";
			$w->color = "green";
		} else if($w->type_of_wod == "RFT") {
			$w->title = "Conditioning: RFT";
			$w->color = "orange";
		} else if($w->type_of_wod == "MIXED") {
			$w->title = "Conditioning: MIXED";
			$w->color = "blue";
		}

		$wods[] = $w;
		$index = $index + 1;
		$prev_date = $row["date_of_wod"];
		$count = $count + 1;
	}
	echo json_encode($wods);
	/* free result set */
	$result->free();
}
/* close connection */
$mysqli->close();

?>