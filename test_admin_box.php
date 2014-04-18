<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
$colname_getUserBox = "-1";
$userBoxID = "";
$_SESSION['MM_UserID'] = "2";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserBox = $_SESSION['MM_UserID'];
  
  $LoginRS__query="select box.box_id from box JOIN athletes AS a ON box.box_id=a.box_id where user_id='{$colname_getUserBox}'"; 
   //echo $LoginRS__query;
  $LoginRS = mysql_query($LoginRS__query, $cboxConn) or die(mysql_error());
  $loginFoundBox = mysql_num_rows($LoginRS);
  //echo $loginFoundBox;
  if ($loginFoundBox) {
    $row = mysql_fetch_row($LoginRS);
	if (PHP_VERSION >= 5.1) {
		session_regenerate_id(true);
	} 
	else {
			session_regenerate_id();
	}
    //declare session variables and assign them
	//echo $row[0];
    $_SESSION['MM_BoxID'] = $row[0];    
}
}

echo $_SESSION['MM_BoxID'];
?>