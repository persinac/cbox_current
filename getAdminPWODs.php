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
/************** OLD **************************
mysql_select_db($database_cboxConn, $cboxConn);
**********************************************/

class postwod {
	public $title = ""; //date 
	public $id = 0;
	public $end = "";
	public $start = ""; 
	public $description  = "";
	public $pwod_id = "";
	public $date_of_pwod = "";
	public $type_of_pwod = "";
	public $color = "";
}

$query_getBoxID = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserWODs}'";

if ($result = $mysqli->query($query_getBoxID)) {
	$row = $result->fetch_assoc();
	$box_id = $row['box_id'];
}

$length_of_box_id = strlen($box_id);

$query_getAdminPWOD = "select pwod_id
, CASE WHEN (type_of_pwod = '') THEN '-'
	ELSE type_of_pwod
	END AS type_of_pwod
, CASE WHEN (descrip = '') THEN '-'
	ELSE descrip
	END AS description
, CAST(date_of_pwod AS DATE) AS date_of_pwod
 from post_wod
WHERE SUBSTRING(pwod_id, 1, {$length_of_box_id}) = '{$box_id}'";

if ($result = $mysqli->query($query_getAdminPWOD)) {
	$post_wods = array();
	$index = 9;
	$str = 1;
	$cond = 1;
	$count = 1;
	$is_rest_day = 0;
	$prev_date = "";
	while ($row = $result->fetch_assoc()) {
		//first if accounts for multiple wods in a single day
		if($row["description"] != "-") 
		{
			if($prev_date == $row["date_of_wod"]) {
				$str = $str + $count;
				$cond = $cond + $count;
				$index = $index + $count;
			} else {
				$str = 1;
				$cond = 1;
				$index = 9;
			}
			$w = new postwod();
			$w->id = $row["pwod_id"];
			//$w->title = $row["date"];
			if($index < 10) {
				$w->start = $row["date_of_pwod"] . "T0".$index.":00:00";
				$w->end = $row["date_of_pwod"] . "T0".$index.":59:00";
			} else if ($index > 9 && $index < 13) {
				$w->start = $row["date_of_pwod"] . "T".$index.":00:00";
				$w->end = $row["date_of_pwod"] . "T".$index.":59:00";
			}
			$w->pwod_id = $row["pwod_id"];
			$w->date_of_pwod = $row["date_of_pwod"];
			$w->type_of_pwod = $row["type_of_pwod"];
			$w->description .= "".$row["description"]."  ";
			$w->title = "Post WOD: ".$row["type_of_pwod"];
			
			//echo "Strength object current values: ";
			//echo $w->id . ", " . $w->str_id .", ". $w->date_of_strength .", ". $w->movement .", ". $w->description;
			/******* Color code based on movement - add later **************
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
			}
			***************************************************/
			$post_wods[] = $w;
			$index = $index + 1;
			$prev_date = $row["date_of_pwod"];
			$count = $count + 1;
		}
	}
echo json_encode($post_wods);
	/* free result set */
	$result->free();
}
/* close connection */
$mysqli->close();


/***************** OLD *************************
$getAdminPWOD = mysql_query($query_getAdminPWOD, $cboxConn) or die(mysql_error());
$totalRows_getAdminPWOD = mysql_num_rows($getAdminPWOD);
//echo $totalRows_getAdminWODs;
$results = array();

for($i = 0; $i < $totalRows_getAdminPWOD; $i++)
{
	$results[] = mysql_fetch_assoc($getAdminPWOD);
}
echo json_encode($results);	
******************************************/
?>