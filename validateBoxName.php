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
 
$t_box_name = $mysqli->real_escape_string($_POST['name']); 

if($stmt = $mysqli->prepare('select * from box where box_name = ?')) 
{
	
	$stmt->bind_param('s', $t_box_name);
	$stmt->execute();
	$stmt->store_result();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	//echo $num_of_rows . "    ";
	if($num_of_rows > 0 && $num_of_rows < 2) {
	
		/* free results */
		$stmt->free_result();
		echo "0";
		
	}
	else {
		echo "1: " . $mysqli->error;
	}

}
/* close connection */
$mysqli->close();
?>