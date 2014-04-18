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
#$_SESSION['MM_Username'] = "kellintc";
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}
########
#REMOVE ME
########
#if (!(isset($_SESSION['MM_UserID']))) {
#  $colname_getUserWODs = 1;
#  $_SESSION['MM_Username']= "persinac";
#}
mysql_select_db($database_cboxConn, $cboxConn);

###
# Defualt view is Crossfit->Foundamental benchmarks
###
$box_id = $_POST['dataString'];
if(strlen($box_id) == 1)
{
	$query_getAdminPWOD = "select pwod_id
	, CASE WHEN (type_of_pwod = '') THEN '-'
		ELSE type_of_pwod
		END AS type_of_pwod
	, CASE WHEN (descrip = '') THEN '-'
		ELSE descrip
		END AS descrip
	, CAST(date_of_pwod AS DATE) AS date_of_pwod
	 from post_wod
	WHERE SUBSTRING(post_wod.pwod_id, 1, 1) = '{$box_id}'";
} 
elseif(strlen($box_id) == 2){ echo "DOUBLE";
}
elseif(strlen($box_id) == 3){ echo "TRIPLE";
}
$getAdminPWOD = mysql_query($query_getAdminPWOD, $cboxConn) or die(mysql_error());
$totalRows_getAdminPWOD = mysql_num_rows($getAdminPWOD);
//echo $totalRows_getAdminWODs;
$results = array();

for($i = 0; $i < $totalRows_getAdminPWOD; $i++)
{
	$results[] = mysql_fetch_assoc($getAdminPWOD);
}
echo json_encode($results);	
?>