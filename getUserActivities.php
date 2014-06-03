<?php session_start(); ?>
<?php
require_once('Connections/wellnessConn.php');
/*
echo "Starting...";
echo ", Username: " . $_SESSION['MM_Username'];
echo ", UserID: " . $_SESSION['MM_UserID'];
*/
$colname_getUser = "-1";

if (isset($_SESSION['MM_UserID'])) {
  $colname_getUser = $_SESSION['MM_UserID'];
}

//echo $colname_getUser;

mysql_select_db($database_wellConn, $wellConn);

###
# Defualt view 
###

$query_getUserActivities = "select DATE_FORMAT(date_of_activity, '%m-%d-%Y') AS 'DateofActivity', activity, duration_of_activity, activity_id from work_activity_log
where user_id = '{$colname_getUser}' ORDER BY DateofActivity DESC";
$getUserActivities = mysql_query($query_getUserActivities, $wellConn) or die(mysql_error());
$totalRows_getUserActivities = mysql_num_rows($getUserActivities);
//echo $totalRows_getUserFirstName;
//echo $colname_getUser;
$results = array();

for($i = 0; $i < $totalRows_getUserActivities; $i++)
{
	$results[] = mysql_fetch_assoc($getUserActivities);
}
echo json_encode($results);	

?>