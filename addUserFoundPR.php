<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$_SESSION['MM_Username'] = "kellintc";
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}
mysql_select_db($database_cboxConn, $cboxConn);

//echo "<!doctype html>
//<html>
//<head>
//<meta charset=\"utf-8\">
//<title>Untitled Document</title>
//</head>
//<body>";

$t_user_id = $_SESSION['MM_UserID'];
$t_mvmnt_id = $_POST['mvmnt_id'];
$t_name = $_POST['name'];
$t_weight = $_POST['weight'];
$t_time = $_POST['time'];
$t_date_achieved = $_POST['date'];

$t_string_builder = ""; //nice to have

#######
#
# MySql insert
#
# Make sure that wod_id is built
#
#######

//echo "HI ". $t_user_id;
//echo "HI ".$t_mvmnt_id;
//echo "HI ".$t_name;
//echo "HI ".$t_weight;
//echo "HI ".$t_time;
//echo "HI ".$t_date_achieved;

$query_insert_wod = "insert into benchmarks values ('{$t_user_id}', '{$t_mvmnt_id}', '{$t_name}', '{$t_weight}', '{$t_time}', '{$t_date_achieved}')";
//echo $query_insert_wod;
$retval = mysql_query( $query_insert_wod, $cboxConn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "Entered data successfully\n";
mysql_close($cboxConn);

//echo "<body>
//</body>
///</html>";
?>