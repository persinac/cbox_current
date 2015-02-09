<?php require_once('Connections/cboxConn.php'); ?>
<?php require_once('Connections/challenge_cal_Conn.php'); ?>
<?php
session_start();

include('CRUD/library/ChallengeCalendarUtility.php');
mysql_select_db($database_cboxConn, $cboxConn);

$t_firstname = mysql_real_escape_string($_POST['first_name']);
$t_lastname = mysql_real_escape_string($_POST['last_name']);
$t_email = mysql_real_escape_string($_POST['email']);
$t_boxID = mysql_real_escape_string($_POST['box_id']);
$t_streetAddress = mysql_real_escape_string($_POST['street_address']);
$t_city = mysql_real_escape_string($_POST['city']);
$t_state= mysql_real_escape_string($_POST['state']);
$t_zipCode= mysql_real_escape_string($_POST['zip_code']);
$t_country= mysql_real_escape_string($_POST['country']);
$t_region= mysql_real_escape_string($_POST['region']);
$t_gender = mysql_real_escape_string($_POST['gender']);

$t_username = mysql_real_escape_string($_POST['username']);
$t_password = mysql_real_escape_string($_POST['password']);
$t_admin = mysql_real_escape_string($_POST['isAdmin']);
//echo "ADMIN: $t_admin\nPOST: ".$_POST['isAdmin']."\n";
#######
#
# MySql insert
#
# Need to get box ID based on user first
#
#######

$query_selectMaxUID = "select max(user_id) from athletes";
$selectMax = mysql_query($query_selectMaxUID, $cboxConn) or die(mysql_error());
$row = mysql_fetch_array($selectMax);
$result= $row[0]+1;
$t_user_id=$result;
$t_curr_date = date("Y-m-d");
$query_registerNewUser = "insert into athletes values ('{$t_user_id}',
 '{$t_firstname}', '{$t_lastname}', '{$t_email}', '{$t_boxID}', 
 '{$t_streetAddress}', '{$t_city}', '{$t_state}', '{$t_country}', 
 '{$t_zipCode}', '{$t_region}', '{$t_admin}', '{$t_gender}',
 '../../images/profiles/$t_boxID/default.jpg', '-', '-', '-','-','-','{$t_curr_date}')";

$opts = Array(
	"un"=>$t_username,
	"pw"=>$t_password,
	"first"=>$t_firstname,
	"last"=>$t_lastname,
	"email"=>$t_email,
	"gen"=>$t_gender,
	"city"=>$t_city,
	"state"=>$t_state
);
 
$retval = mysql_query($query_registerNewUser, $cboxConn );
if(! $retval )
{
	die('Could not enter data: ' . mysql_error());
}
else
{
	$testDB = new ChallengeCalendarUtility();
	$testDB->NewConnection($hostname_challenge_cal, $username_challenge_cal, $password_challenge_cal, $database_challenge_cal);
	$testDB->SetChallengeUserID($testDB->GetMaxChallengeUserID());
	$chall_id = $testDB->GetChallengeUserID();
	//$testDB->CloseConnection();
	$query_registerNewUser = "insert into login values ('{$t_user_id}', '{$t_username}', '{$t_password}', '{$t_admin}', '{$chall_id}')";
	$retval = mysql_query($query_registerNewUser, $cboxConn );
	if(! $retval )
	{
	 	die('Could not enter data: ' . mysql_error());
	} else { 
		echo '1';
		$testDB->InsertNewUser($t_user_id, $opts);
		$testDB->CloseConnection();
		//insert girl benchmark values
		for($i = 1; $i < 22; $i++) {
			if($i < 10) {
				if($i == 3) {
					$query_insertGirls = "insert into benchmarks values ('{$t_user_id}', 'grl_0$i','','0','','2000-01-01','0','a')";
				} else {
					$query_insertGirls = "insert into benchmarks values ('{$t_user_id}', 'grl_0$i','','0','99:99','2000-01-01','','r')";
				}
			} else {
				if($i == 14 || $i == 19 || $i == 20) {
					$query_insertGirls = "insert into benchmarks values ('{$t_user_id}', 'grl_$i','','0','','2000-01-01','0','a')";
				} else {
					$query_insertGirls = "insert into benchmarks values ('{$t_user_id}', 'grl_$i','','0','99:99','2000-01-01','','r')";
				}
			}
			$retval = mysql_query($query_insertGirls, $cboxConn );
			if(! $retval )
			{
				die('Could not enter GIRL data: ' . mysql_error());
			} else { 
				echo '1';
			}
		}
		$queryInsertHeroes = "CALL insertintohero($t_user_id)";
		$retval = mysql_query($queryInsertHeroes, $cboxConn );
		if(! $retval )
		{
			die('Could not enter HERO data: ' . mysql_error());
		} else { 
			echo '1';
		}
	}
}

mysql_close($cboxConn);
?>