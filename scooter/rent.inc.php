<?php
session_start();
if (isset($_POST['rent'])) {
    require '../includes/dbh.inc.php'; // Path to your main dbh.inc.php

    // Generate a random rent ID
    function generateRentID($length = 5) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $id = '';
        for ($i = 0; $i < $length; $i++) {
            $id .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $id;
    }

    $rentID = generateRentID();
    $user_id = $_POST['user_id'];
    $scooterID = $_POST['scooterID'];
    $acc = $_POST['bankAcc'];
    $payment = floatval($_POST['payment']);

    // Validation
    if (empty($acc)) {
        header("Location: scooter_rentconfirmation.php?scooterID=$scooterID&error=emptyfields");
        exit();
    } elseif (!preg_match("/^[0-9]+$/", $acc)) {
        header("Location: scooter_rentconfirmation.php?scooterID=$scooterID&error=invalidAccountNumber");
        exit();
    }

    // Check if user already has an active scooter rent
    $stmt_check = $conn->prepare("SELECT * FROM rents_scooter WHERE user_id = ?");
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        header("Location: scooter_user.php?error=alreadyrenting");
        exit();
    }
    $stmt_check->close();

    // Insert new rent
    $stmt_insert = $conn->prepare("INSERT INTO rents_scooter (rentID, user_id, scooterID, payment) VALUES (?, ?, ?, ?)");
    if (!$stmt_insert) {
        header("Location: scooter_rentconfirmation.php?scooterID=$scooterID&error=sqlpreparefail");
        exit();
    }
    $stmt_insert->bind_param("siid", $rentID, $user_id, $scooterID, $payment);
    if (!$stmt_insert->execute()) {
        header("Location: scooter_rentconfirmation.php?scooterID=$scooterID&error=insertfail");
        exit();
    }
    $stmt_insert->close();

    // Update scooter status
    $stmt_update_status = $conn->prepare("UPDATE scooters SET status = 'RENTED' WHERE scooterID = ?");
    if (!$stmt_update_status) {
        header("Location: scooter_rentconfirmation.php?scooterID=$scooterID&error=sqlupdatepreparefail");
        exit();
    }
    $stmt_update_status->bind_param("i", $scooterID);
    if (!$stmt_update_status->execute()) {
        header("Location: scooter_rentconfirmation.php?scooterID=$scooterID&error=statusupdatefail");
        exit();
    }
    $stmt_update_status->close();

    // Success redirect
    header("Location: scooter_user.php?rent=success");
    exit();

} else {
    header("Location: scooter_home.php"); // Redirect to home if accessed directly
    exit();
}
