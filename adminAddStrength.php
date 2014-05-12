<?php require_once('Connections/cboxConn.php');  ?>
<?php
session_start(); 

$colname_getUserWODs = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserWODs = $_SESSION['MM_UserID'];
}
mysql_select_db($database_cboxConn, $cboxConn);

$t_strengthID = "";
$t_date = $_POST['date'];
$t_movement = $_POST['strength_mov'];
$t_reps = $_POST['str_reps'];
$t_weight_instr_selector = $_POST['str_instruction_selector'];
$t_str_special = $_POST['special_str'];
$t_weight_instruction =  $_POST['weight_instruction'];

$t_string_builder = "";

//echo $t_date . ", Movement: " . $t_movement .", Reps: ".$t_reps.", Selector: ".$t_weight_instr_selector.", Instruction: ".$t_weight_instruction.", Special: ".$t_str_special."\n";
$t_string_builder .= $t_reps;
if($t_weight_instr_selector == "PER") {
	$t_string_builder .= " @ " . $t_weight_instruction ."% of 1RM";
}
else if($t_weight_instr_selector == "AHAP") {
	$t_string_builder .= " as heavy as possible";
}
else if($t_weight_instr_selector == "ILES") {
	$t_string_builder .= " increase load each set";
	if(strlen($t_weight_instruction) > 0) {
		$t_string_builder .= " by ".$t_weight_instruction . "lbs";
	}
}
else if($t_weight_instr_selector == "LSS") {
	$t_string_builder .= " load stays the same";
}
//echo $t_string_builder ."\n";
#######
#
# MySql insert
#
# Need to get box ID based on user first
#
#######

$query_getAdminWODs = "select box_id
 from athletes
WHERE user_id = '{$colname_getUserWODs}'";

$getAdminWODs = mysql_query($query_getAdminWODs, $cboxConn) or die(mysql_error());
$totalRows_getAdminWODs = mysql_num_rows($getAdminWODs);
####echo $totalRows_getAdminWODs;
$row = mysql_fetch_array($getAdminWODs);

$t_strengthID = $row[0] . "_" . str_replace("-", "", $t_date);
echo "Strength ID: " . $t_strengthID."\n";

$query_insert_strength = "insert into strength values ('{$t_strengthID}', '{$t_date}', '{$t_movement}', '{$t_string_builder}', '{$t_str_special}')";
#echo $query_insert_wod;
$retval = mysql_query( $query_insert_strength, $cboxConn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "\nEntered data successfully\n";
mysql_close($cboxConn);

?>