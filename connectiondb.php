<?php
//connect to a specific database (dbitgroupbjan)

$server =  "localhost";
$serveruseraccount = "root";
$serveruserpassword = "";

//variable for the database
$db = "artgallery";

//establish connection to database
$connect = mysqli_connect($server, $serveruseraccount, $serveruserpassword, $db);

//check if connection is successful
if (!$connect)
{
    die("Connection failed: " . mysqli_connect_error());
}

?>