<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
mysql_select_db($database_cboxConn, $cboxConn);

echo "<!doctype html>
<html>
<head>
<meta charset=\"utf-8\">
<title>Untitled Document</title>
</head>
<body>";
mysql_close($cboxConn);
echo "<body>
</body>
</html>";
?>