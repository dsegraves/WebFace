<?php
$dbName = "testDB1";
$userName = "jonatans";
$password = "jonatans";
$host = "testdb.cwsnlhwgdhwm.us-east-1.rds.amazonaws.com";

mysql_connect($host, $userName, $password) or die (mysql_error());
//echo("connected to mySQL Database");

//or conection:
//$link = mysqli_connect($host,$userName,$password,$dbName) or die("Error " . mysqli_error($link));

mysql_selectdb($dbName) or die(mysql_error());
echo ("Connected to " . $dbName);
?>