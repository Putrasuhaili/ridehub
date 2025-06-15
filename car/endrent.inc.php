<?php
require '../includes/dbh.inc.php';

if(isset($_GET['carID'])){
    $carID = $_GET['carID'];
    $conn->query("UPDATE cars SET status='AVAILABLE' WHERE carID='$carID'") or die($conn->error());
    
    $conn->query("DELETE FROM rents_car WHERE carID='$carID'") or die($conn->error());
    
    header("Location: ../car/car_user.php?rent=ended");
}