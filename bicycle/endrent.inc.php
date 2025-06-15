<?php
require '../includes/dbh.inc.php';

if(isset($_GET['bikeID'])){
    $bikeID = $_GET['bikeID'];
    $conn->query("UPDATE bikes SET status='AVAILABLE' WHERE bikeID='$bikeID'") or die($conn->error());
    
    $conn->query("DELETE FROM rents WHERE bikeID='$bikeID'") or die($conn->error());
    
    header("Location: ../bicycle/bicycle_user.php?rent=ended");
}