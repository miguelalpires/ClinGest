<?php

$host = "localhost";		//database location
$user = "root"; 		//database username
$pass = "miguel"; 			//database password
$db_name = "test"; 		//database name

//database connection
$link = mysql_connect($host, $user, $pass);
mysql_select_db($db_name);

//sets encoding to utf8
$sqlstr = mysql_query("SELECT * FROM `teste` WHERE 1");

while ($row = mysql_fetch_array($sqlstr))
{
	print_r($row);
}
?>