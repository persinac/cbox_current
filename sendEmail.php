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

$t_email = $mysqli->real_escape_string($_POST['name']); 
$t_box_name = $mysqli->real_escape_string($_POST['box']); 
$t_whereTo = $mysqli->real_escape_string($_POST['whereTo']); 

$from = "admin@cboxbeta.com"; // sender
$subject = "";
$new_message = "";
// message lines should not exceed 70 characters (PHP rule), so wrap it
if($t_whereTo == "u") {
	if($stmt = $mysqli->prepare('select a.email, l.username from athletes a 
	join box b on b.box_id = a.box_id
	join login l on l.user_id = a.user_id where a.email = ? AND b.box_name = ?')) 
	{
		$stmt->bind_param('ss', $t_email, $t_box_name);
		$stmt->execute();
		$stmt->store_result();
		/* Get the number of rows */
		$num_of_rows = $stmt->num_rows;
		//echo $num_of_rows . "    ";
		if($num_of_rows > 0 && $num_of_rows < 3) {
			$email_to_return = "";
			$stmt->bind_result($e, $p);
			$username_to_return  ="";
			
			while ($stmt->fetch()) {			
				$email_to_return = $e;
				$username_to_return = $p;
			}
			//echo "email: ".$email_to_return.", pw: ".$password_to_return."\n";
			$message = "Here is your username: \n\n" . $username_to_return . "\n\nThanks, CBox Support";
			$subject = "CBOX - Forgot Username";
			//echo "message: " . $message;
			$new_message = $message;
			/* free results */
			$stmt->free_result();
			//echo "0";
			
		}
		else {
			echo "1: " . $mysqli->error;
		}

	} else {
		echo "2: " . $stmt->error;
	}
} else if ($t_whereTo == "p") {
	if($stmt = $mysqli->prepare('select a.email, l.password from athletes a 
	join box b on b.box_id = a.box_id
	join login l on l.user_id = a.user_id where a.email = ? AND b.box_name = ?')) 
	{
		$stmt->bind_param('ss', $t_email, $t_box_name);
		$stmt->execute();
		$stmt->store_result();
		/* Get the number of rows */
		$num_of_rows = $stmt->num_rows;
		//echo $num_of_rows . "    ";
		if($num_of_rows > 0 && $num_of_rows < 3) {
			$email_to_return = "";
			$stmt->bind_result($e, $p);
			$username_to_return  ="";
			
			while ($stmt->fetch()) {			
				$email_to_return = $e;
				$username_to_return = $p;
			}
			//echo "email: ".$email_to_return.", pw: ".$password_to_return."\n";
			$message = "Here is your password: \n\n" . $username_to_return . "\n\nThanks, CBox Support";
			$subject = "CBOX - Forgot Password";
			//echo "message: " . $message;
			$new_message = $message;
			/* free results */
			$stmt->free_result();
			//echo "0";
			
		}
		else {
			echo "1: " . $mysqli->error;
		}

	} else {
		echo "2: " . $stmt->error;
	}
}
//echo "\nNEW: " . $new_message;
/* close connection */
if(@mail($email_to_return,$subject,$new_message,"From: $from\n")) {
	echo "Mail Sent Successfully";
} else {
	echo "Mail Not Sent";
}

$mysqli->close();
?>