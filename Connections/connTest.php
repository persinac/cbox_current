<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connTest = "192.186.237.9:3306";
$database_connTest = "php_test";
$username_connTest = "phptestuser";
$password_connTest = "password!";
$connTest = mysql_pconnect($hostname_connTest, $username_connTest, $password_connTest) or trigger_error(mysql_error(),E_USER_ERROR); 
?>