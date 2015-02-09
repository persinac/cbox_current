<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}
mysql_select_db($database_cboxConn, $cboxConn);


$t_user_id = $_SESSION['MM_UserID'];
$t_mvmnt_id = $_POST['mvmnt_id'];
$t_name = $_POST['name'];
$t_weight = $_POST['weight'];
$t_time = $_POST['time'];
$t_date_achieved = $_POST['date'];

$t_string_builder = ""; //nice to have

$t_temp = "-";
$query_insert_wod = "insert into benchmarks values ('{$t_user_id}', '{$t_mvmnt_id}', '{$t_name}', '{$t_weight}', '{$t_time}', '{$t_date_achieved}', '{$t_temp}', '{$t_temp}')";
//echo $query_insert_wod;
$retval = mysql_query( $query_insert_wod, $cboxConn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "1";
mysql_close($cboxConn);

?>