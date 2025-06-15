<?php 
require 'dbh.inc.php';

// Get vehicle type from session or default to bicycle
$vehicle = $_SESSION['vehicle'] ?? 'bicycle';

if (isset($_POST['change'])) {
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['repeat-pwd'];

    if (empty($passwordRepeat) || empty($password)) {
        header("Location: ../{$vehicle}/{$vehicle}_user.php?error=emptyfields");
        exit();
    } else if ($password !== $passwordRepeat) {
        header("Location: ../{$vehicle}/{$vehicle}_user.php?error=passwordcheck&mail=" . $email);
        exit();
    } else {
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET pwdUsers='$hashedPwd' WHERE emailUsers='$email'") or die($conn->error);
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../loginoption.html?updated");
        exit();
    }
}

if (isset($_POST['update-info'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $email = $_POST['mail'];
    $age = intval($_POST['age']);
    $campus = $_POST['campus'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $desc = $_POST['desc'];
    $vehicle = $_POST['vehicle'] ?? $_SESSION['vehicle'] ?? 'bicycle'; // Get vehicle from POST or session

    $stmt = $conn->prepare("UPDATE users SET emailUsers=?, custName=?, custAge=?, custCampus=?, custAddress=?, custPhone=?, custDesc=? WHERE id=?");
    $stmt->bind_param("ssissssi", $email, $name, $age, $campus, $address, $phone, $desc, $id);
    $stmt->execute();

    // Refresh session data
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        session_start();
        $_SESSION['userUid'] = $row['uidUsers'];
        $_SESSION['userEmail'] = $row['emailUsers'];
        $_SESSION['userName'] = $row['custName'];
        $_SESSION['userAge'] = $row['custAge'];
        $_SESSION['userCampus'] = $row['custCampus'];
        $_SESSION['userPhone'] = $row['custPhone'];
        $_SESSION['userAddress'] = $row['custAddress'];
        $_SESSION['userDesc'] = $row['custDesc'];
        $_SESSION['vehicle'] = $vehicle; // Update session with vehicle type
    }

    // Redirect based on vehicle type
    switch($vehicle) {
        case 'car':
            header("Location: ../car/car_user.php?updated");
            break;
        case 'motorcycle':
            header("Location: ../motorcycle/motorcycle_user.php?updated");
            break;
        case 'scooter':
            header("Location: ../scooter/scooter_user.php?updated");
            break;
        case 'bicycle':
        default:
            header("Location: ../bicycle/bicycle_user.php?updated");
            break;
    }
    exit();
}