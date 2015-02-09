<?php require_once('Connections/cboxConn.php'); ?>
<?php 
session_start();

$my_dir =  __DIR__ . "/Braintree/braintree-php-2.27.2/lib/";
require_once($my_dir . "/Braintree.php");

$userID = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $userID = $_SESSION['MM_UserID'];
}

global $mysqli;
$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql_retVal = "";

Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId('mmrksdmc2rg3zyxs');
Braintree_Configuration::publicKey('g6kfxbnn5cqsk43t');
Braintree_Configuration::privateKey('abc1f9d809865a61f42da2c7906d7419');


if($stmt = $mysqli->prepare("insert into customer_details values (?, ?, ?, ?)")) {
	$result = Braintree_Customer::create(array(
		"firstName" => $_POST["first_name"],
		"lastName" => $_POST["last_name"],
		'company' => $_POST["company"],
		'email' => $_POST["email"],
		'phone' => $_POST["phone"],
		'fax' => $_POST["fax"]
	));
	if ($result->success) {
		$s_id = "-";
		$c_id = $result->customer->id;
		$b_id = $_POST['box_id'];
		//echo "VARS: " .$userID . " ". $b_id ." ". $c_id . " " . $s_id;
		$stmt->bind_param('iiis', $userID, $b_id, $c_id, $s_id); 
		if ($q_result = $stmt->execute()) {
			$sql_retVal = "0";  
			$stmt->close();
		}else{
			$sql_retVal = "1"; 
		}
	
		echo($sql_retVal."S " . $result->customer->id);
		//insert into box_customer table: first_name, last_name, c_id
	} else {
		echo("Validation errors:<br/>");
		foreach (($result->errors->deepAll()) as $error) {
			echo("- " . $error->message . "<br/>");
		}
	}
} else {
	echo "Error!<br/>". $stmt->error;
}

$mysqli->close();

?>