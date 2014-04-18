<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_cboxConn = "127.0.0.1";
$database_cboxConn = "cbox";
$username_cboxConn = "root";
$password_cboxConn = "digiOceanMysql";
$cboxConn = mysql_pconnect($hostname_cboxConn, $username_cboxConn, $password_cboxConn) or trigger_error(mysql_error(),E_USER_ERROR); 
?>