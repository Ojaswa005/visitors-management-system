<?php
$server="localhost:3306";
$username="root";
$password="";
$databasename="vms_db";

$conn = mysqli_connect($server, $username, $password);
//$conn = mysqli_connect("localhost", "root", "your_password");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$abc=mysqli_select_db($conn,$databasename);

if(!$abc)
{
	die("disconnect");
}
else
{
	//die ("successfull");
}
?>