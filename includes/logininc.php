<?php
session_start();

if (isset($_POST['login-submit'])) {

    require 'dbh.inc.php';

    $mailuid = trim($_POST['mailuid']);
    $password = $_POST['pwd'];
    $vehicle = $_POST['vehicle'] ?? 'bicycle'; // fallback if not provided

    // Validate empty fields
    if (empty($mailuid) || empty($password)) {
        header("Location: ../{$vehicle}/{$vehicle}_login.php?error=emptyfields");
        exit();
    }

    // Prepare SQL
    $sql = "SELECT * FROM users WHERE uidUsers=? OR emailUsers=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../{$vehicle}/{$vehicle}_login.php?error=sqlerror");
        exit();
    }

    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check user found
    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['pwdUsers'])) {
            // Success: start session
            $_SESSION['userId'] = $row['id'];
            $_SESSION['userUid'] = $row['uidUsers'];
            $_SESSION['vehicle'] = $vehicle;

            // Redirect to correct home page
            switch ($vehicle) {
                case 'car':
                    header("Location: ../car/car_home.php");
                    break;
                case 'motorcycle':
                    header("Location: ../motorcycle/motorcycle_home.php");
                    break;
                case 'scooter':
                    header("Location: ../scooter/scooter_home.php");
                    break;
                case 'bicycle':
                default:
                    header("Location: ../bicycle/bicycle_home.php");
                    break;
            }
            exit();

        } else {
            header("Location: ../{$vehicle}/{$vehicle}_login.php?error=wrongpwd");
            exit();
        }
    } else {
        header("Location: ../{$vehicle}/{$vehicle}_login.php?error=nouser");
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    // Direct access without submit
    header("Location: ../index.html");
    exit();
}
