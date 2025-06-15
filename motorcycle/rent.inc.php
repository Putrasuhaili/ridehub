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
    $motorcycleID = $_POST['motorcycleID']; // assuming input name is still 'motorcycleID', you can rename it if needed
    $acc = $_POST['bankAcc'];
    $payment = floatval($_POST['payment']);

    // Validation
    if (empty($acc)) {
        header("Location: ../motorcycle/motorcycle_rentconfirmation.php?motorcycleID=$motorcycleID&error=emptyfields");
        exit();
    } elseif (!preg_match("/^[0-9]+$/", $acc)) {
        header("Location: ../motorcycle/motorcycle_rentconfirmation.php?motorcycleID=$motorcycleID&error=invalidAccountNumber");
        exit();
    }

    // Check if user already has a rent
    $stmt = $conn->prepare("SELECT * FROM rents_motorcycle WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../motorcycle/motorcycle_user.php?error=alreadyrenting");
        exit();
    }

    // Insert new rent
    $stmt = $conn->prepare("INSERT INTO rents_motorcycle (rentID, user_id, motorcycleID, payment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siid", $rentID, $user_id, $motorcycleID, $payment);
    if (!$stmt->execute()) {
        header("Location: ../motorcycle/motorcycle_rentconfirmation.php?motorcycleID=$motorcycleID&error=insertfail");
        exit();
    }

    // Update motorcycle status
    $stmt = $conn->prepare("UPDATE motorcycles SET status = 'RENTED' WHERE motorcycleID = ?");
    $stmt->bind_param("i", $motorcycleID);
    $stmt->execute();

    header("Location: ../motorcycle/motorcycle_user.php?rent=success");
    exit();
} else {
    header("Location: ../motorcycle/motorcycle_rentconfirmation.php");
    exit();
}
?>
