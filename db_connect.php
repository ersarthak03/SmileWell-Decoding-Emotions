<?php
$servername = "localhost"; 
$username = "heroku";
$password = "sarthak@123"; 
$dbname = "smilewell"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
