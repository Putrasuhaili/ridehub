<?php
if (isset($_POST['rent'])) {
    require '../includes/dbh.inc.php';

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
    $bikeID = $_POST['bikeID'];
    $acc = $_POST['bankAcc'];
    $payment = floatval($_POST['payment']);

    // Validation
    if (empty($acc)) {
        header("Location: ../bicycle/bicycle_rentconfirmation.php?bikeID=$bikeID&error=emptyfields");
        exit();
    } elseif (!preg_match("/^[0-9]+$/", $acc)) {
        header("Location: ../bicycle/bicycle_rentconfirmation.php?bikeID=$bikeID&error=invalidAccountNumber");
        exit();
    }

    // Check if user already has a rent
    $stmt = $conn->prepare("SELECT * FROM rents WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../bicycle/bicycle_user.php?error=alreadyrenting");
        exit();
    }

    // Insert new rent
    $stmt = $conn->prepare("INSERT INTO rents (rentID, user_id, bikeID, payment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siid", $rentID, $user_id, $bikeID, $payment);
    if (!$stmt->execute()) {
        header("Location: ../bicycle/bicycle_rentconfirmation.php?bikeID=$bikeID&error=insertfail");
        exit();
    }

    // Update bike status
    $stmt = $conn->prepare("UPDATE bikes SET status = 'RENTED' WHERE bikeID = ?");
    $stmt->bind_param("i", $bikeID);
    $stmt->execute();

    header("Location: ../bicycle/bicycle_user.php?rent=success");
    exit();
} else {
    header("Location: ../bicycle/bicycle_rentconfirmation.php");
    exit();
}
?>
