<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($adminCode, $UserName)
{ 
  // For security, start by assuming the visitor is NOT authorized. 
  $value = 9; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) 
  { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login.  
    // Or, you may restrict access to only certain users based on their username. 
    if ($adminCode == 0) { 
      $valid = 0; 
    } 
    else if ($adminCode == 1) { 
      $valid = 1; 
    } 
  } 
  return $valid; 
}

$MM_restrictGoTo = "Error401UnauthorizedAccess.php";
$MM_AdminPage = "info.php";
$MM_UserPage = "User_Home_Page.php";
if ((isset($_SESSION['MM_Username'])))
{  
	$returnVal =  isAuthorized($_SESSION['MM_Admin'],$_SESSION['MM_Username']);
   	if ($id == "0") {$link = $MM_UserPage;} //Default Blank 
	if ($id == "1") {$link = $MM_AdminPage;} // COMMENT 
	if ($id == "9") {$link = $MM_restrictGoTo;} // COMMENT 
	header("Location: $link"); // Jump to the link
  exit;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>