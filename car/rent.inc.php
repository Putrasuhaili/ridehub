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
    $carID = $_POST['carID'];
    $acc = $_POST['bankAcc'];
    $payment = floatval($_POST['payment']);

    // Validation
    if (empty($acc)) {
        header("Location: ../car/car_rentconfirmation.php?carID=$carID&error=emptyfields");
        exit();
    } elseif (!preg_match("/^[0-9]+$/", $acc)) {
        header("Location: ../car/car_rentconfirmation.php?carID=$carID&error=invalidAccountNumber");
        exit();
    }

    // Check if user already has a rent
    $stmt = $conn->prepare("SELECT * FROM rents_car WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../car/car_user.php?error=alreadyrenting");
        exit();
    }

    // Insert new rent
    $stmt = $conn->prepare("INSERT INTO rents_car (rentID, user_id, carID, payment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siid", $rentID, $user_id, $carID, $payment);
    if (!$stmt->execute()) {
        header("Location: ../car/car_rentconfirmation.php?carID=$carID&error=insertfail");
        exit();
    }

    // Update car status
    $stmt = $conn->prepare("UPDATE cars SET status = 'RENTED' WHERE carID = ?");
    $stmt->bind_param("i", $carID);
    $stmt->execute();

    header("Location: ../car/car_user.php?rent=success");
    exit();
} else {
    header("Location: ../car/car_rentconfirmation.php");
    exit();
}
?>