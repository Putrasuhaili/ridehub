<?php
require '../includes/dbh.inc.php'; // Path to your main dbh.inc.php

if(isset($_GET['scooterID'])){
    $scooterID = intval($_GET['scooterID']);

    // Start a transaction to ensure both updates are successful or neither
    mysqli_begin_transaction($conn);

    try {
        // Update scooter status to AVAILABLE
        $stmt_update_status = $conn->prepare("UPDATE scooters SET status='AVAILABLE' WHERE scooterID=?");
        if (!$stmt_update_status) {
            throw new Exception("Failed to prepare status update statement: " . $conn->error);
        }
        $stmt_update_status->bind_param("i", $scooterID);
        if (!$stmt_update_status->execute()) {
            throw new Exception("Failed to execute status update: " . $stmt_update_status->error);
        }
        $stmt_update_status->close();

        // Delete the rental record
        $stmt_delete_rent = $conn->prepare("DELETE FROM rents_scooter WHERE scooterID=?");
        if (!$stmt_delete_rent) {
            throw new Exception("Failed to prepare rent delete statement: " . $conn->error);
        }
        $stmt_delete_rent->bind_param("i", $scooterID);
        if (!$stmt_delete_rent->execute()) {
            throw new Exception("Failed to execute rent delete: " . $stmt_delete_rent->error);
        }
        $stmt_delete_rent->close();

        // Commit transaction
        mysqli_commit($conn);

        header("Location: scooter_user.php?rent=ended");
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        error_log("Scooter End Rent Error: " . $e->getMessage()); // Log error for debugging
        header("Location: scooter_user.php?error=endrentfail&msg=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: scooter_home.php"); // Redirect to home if accessed directly
    exit();
}
