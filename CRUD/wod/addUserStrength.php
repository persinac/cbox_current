<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();

$colname_getUserStrength = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserStrength = $_SESSION['MM_UserID'];
}

$t_user_id = $_SESSION['MM_UserID'];
$t_strength_mvmnt = $_POST['str_in_mvmnt'];

$t_string_builder = "$t_strength_mvmnt: "; //nice to have
$pos = strpos($mystring, $findme);
foreach($_POST as $key => $value)
{
	
	if(strpos($key, "Reps") > -1) {
		$t_string_builder .= "$value @ ";
	} else if(strpos($key, "Weight_") > -1) {
		$t_string_builder .= "$value ";
	} else if(strpos($key, "user") > -1) {
		$t_string_builder .= "$value, ";
	}
}
echo $t_string_builder;
#######
#
# MySql insert
#
# Make sure that wod_id is built
#
#######

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$t_first_name = "";
$query_getBoxID = "select first_name, user_id,box_id
 from athletes
WHERE user_id = '{$colname_getUserStrength}'";

if ($result = $mysqli->query($query_getBoxID)) {
	$row = $result->fetch_assoc();
	$t_user_id = $row['user_id'];
	
	$length_of_user_id = strlen($t_user_id);
}
$t_temp_date = str_replace("-", "",$_POST['date']);
echo "$t_temp_date, $t_user_id, $length_of_user_id";
$t_wodID = $t_user_id . "_" . $t_temp_date;
$max_id = -1;
if($t_user_id < 10) {
	$t_id_loc = 12;
} else if($t_user_id < 100) {
	$t_id_loc = 13;
} else if($t_user_id < 1000) {
	$t_id_loc = 14;
} else if($t_user_id < 10000) {
	$t_id_loc = 15;
} else if($t_user_id < 100000) {
	$t_id_loc = 16;
}
$uscore_loc = (int)$length_of_user_id + 1;
$first_date_uscore = (int)$length_of_user_id + 2;
$second_date_uscore = (int)$length_of_user_id + 3;
$query_getMaxIDCount = "select SUBSTRING(str_id, $t_id_loc) AS maxID from athlete_strength WHERE SUBSTRING(str_id, 1, $length_of_user_id) = '$t_user_id' AND LOCATE('_',str_id) = $uscore_loc 
AND SUBSTRING(str_id, $first_date_uscore, LOCATE('_',SUBSTRING(str_id, $second_date_uscore))) = '$t_temp_date'";
echo "Query: " . $query_getMaxIDCount;
if ($result = $mysqli->query($query_getMaxIDCount)) {
	$row = $result->fetch_assoc();
	echo "ROWS:".sizeof($row);
	if(sizeof($row) > 0) {
		$max_id = $row['maxID'];
	}
}
echo "\nMAX ID: " . $max_id;

if($max_id > -1) {
	$custom_wod_id = $max_id + 1;
	echo "\nUse max_id+1 as the insert ID...Wod ID: " . $t_wodID . " Custom ID num: ".$custom_wod_id;
	$t_wodID .= "_" . $custom_wod_id;
	echo "\nNew custom ID...Wod ID: " . $t_wodID; 
} else {
	echo "\nCreate new ID...Wod ID: " . $t_wodID . " Custom ID num: 0" ;
	$t_wodID .= "_0";
	echo "\nNew custom ID...Wod ID: " . $t_wodID; 
}

$stmt = $mysqli->prepare("insert into athlete_strength values (?, 
		?, 
		?)");
$stmt->bind_param( 'sss', $t_user_id, $t_wodID, $t_string_builder);

if($result = $stmt->execute()) {
	echo "Entered custom wod successfully\n";
	$stmt->close();
} else {
	echo "1 ";
}





$mysqli->close();
/*
$query_getBoxID = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserStrength}'";

$getBoxID = mysql_query($query_getBoxID, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getBoxID);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getBoxID);

$t_wodID = $row[0] . "_".str_replace("-", "", $t_wod_id);


$query_insert_wod = "insert into athlete_strength values ('{$t_user_id}', '{$t_strength_id}', '{$t_strength_val}')";
//echo $query_insert_wod;
$retval = mysql_query( $query_insert_wod, $cboxConn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "Entered data successfully\n";
mysql_close($cboxConn);
*/
?>