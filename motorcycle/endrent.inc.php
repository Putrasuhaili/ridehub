<?php
require '../includes/dbh.inc.php';

if(isset($_GET['motorcycleID'])){
    $motorcycleID = $_GET['motorcycleID'];
    
    $conn->query("UPDATE motorcycles SET status='AVAILABLE' WHERE motorcycleID='$motorcycleID'") or die($conn->error());
    
    $conn->query("DELETE FROM rents_motorcycle WHERE motorcycleID='$motorcycleID'") or die($conn->error());
    
    header("Location: ../motorcycle/motorcycle_user.php?rent=ended");
    exit();
}