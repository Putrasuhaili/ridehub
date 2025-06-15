<?php
$servername = "localhost:3307";
$dbUsername = "root";
$dbPassword = ""; // default is empty for XAMPP
$dbName = "umpsa_ridehub";

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
