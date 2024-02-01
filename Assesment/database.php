<?php

$host = "ysjcs.net";
$dbusername = "gavin.white";
$dbpassword = "F5EKQPXR";
$dbname = "gavinwhite_library";

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

if($conn->connect_error){
    die("Connection failed: ". $conn->connect_error);

}