<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_challenge_cal = "127.0.0.1";
$database_challenge_cal = "challenge_calendar";
$username_challenge_cal = "root";
$password_challenge_cal = "password!";
$challConn = mysql_pconnect($hostname_challenge_cal, $username_challenge_cal, $password_challenge_cal) or trigger_error(mysql_error(),E_USER_ERROR); 
?>