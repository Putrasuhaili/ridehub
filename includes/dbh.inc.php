<?php
$servername = "sql203.infinityfree.com"; // Hostname from your panel
$dbUsername = "if0_39234805";            // MySQL Username
$dbPassword = "your_vpanel_password";    // Use the password you use to log into InfinityFree
$dbName = "if0_39234805_umpsa_ridehub";  // Full DB name

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
