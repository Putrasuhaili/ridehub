<?php
if (isset($_POST['signup-submit'])) {

    require 'dbh.inc.php';

    $username = trim($_POST['uid']);
    $email = trim($_POST['mail']);
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];
    $vehicle = $_POST['vehicle'] ?? 'bicycle'; // fallback if not set

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
        header("Location: ../{$vehicle}/{$vehicle}_signup.php?error=emptyfields");
        exit();
    }
    // Validate email and username
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../{$vehicle}/{$vehicle}_signup.php?error=invaliduidmail");
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../{$vehicle}/{$vehicle}_signup.php?error=invalidmail");
        exit();
    }
    elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../{$vehicle}/{$vehicle}_signup.php?error=invaliduid");
        exit();
    }
    elseif ($password !== $passwordRepeat) {
        header("Location: ../{$vehicle}/{$vehicle}_signup.php?error=passwordcheck");
        exit();
    }

    // Check if username or email is already taken
    $sql = "SELECT uidUsers FROM users WHERE uidUsers=? OR emailUsers=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../{$vehicle}/{$vehicle}_signup.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            header("Location: ../{$vehicle}/{$vehicle}_signup.php?error=usertaken");
            exit();
        } else {
            // Insert new user
            $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../{$vehicle}/{$vehicle}_signup.php?error=sqlerror");
                exit();
            } else {
                $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
                mysqli_stmt_execute($stmt);

                header("Location: ../{$vehicle}/{$vehicle}_login.php?signup=success");
                exit();
            }
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    header("Location: ../index.html");
    exit();
}
?>
