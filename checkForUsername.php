<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
mysql_select_db($database_cboxConn, $cboxConn);

$t_username = mysql_real_escape_string($_POST['username']);
$check_query = mysql_query("SELECT username FROM login WHERE username = '{$t_username}'"); // Check the database
$total_rows = mysql_num_rows($check_query);
echo $total_rows; // echo the num or rows 0 or greater than 0
mysql_close($cboxConn);

?>