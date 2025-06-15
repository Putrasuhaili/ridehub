<?php
    session_start();
    require '../includes/dbh.inc.php';

    if (!isset($_GET['scooterID']) || !isset($_SESSION['userUid'])) {
        header("Location: scooter_home.php");
        exit();
    }

    $scooterID = $_GET['scooterID'];
    $username = $_SESSION['userUid'];

    // Get user_id from database using uidUsers
    $sqlUser = "SELECT id FROM users WHERE uidUsers = ?";
    $stmtUser = mysqli_prepare($conn, $sqlUser);
    mysqli_stmt_bind_param($stmtUser, "s", $username);
    mysqli_stmt_execute($stmtUser);
    mysqli_stmt_bind_result($stmtUser, $user_id);
    mysqli_stmt_fetch($stmtUser);
    mysqli_stmt_close($stmtUser);

    // Get selected scooter details
    $result = $conn->query("SELECT * FROM scooters WHERE scooterID='$scooterID'") or die($conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scooter Rent Payment Confirmation</title>
    <link rel="icon" href="../img/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cabin&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cabin', sans-serif;
            background-color: #f4f4f4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1;
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: auto;
        }
        footer {
            background: #222;
            color: #fff;
            padding: 1rem;
            text-align: center;
            margin-top: 2rem;
            width: 100%;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <img src="../img/logohead80.png" width="30" height="30" class="d-inline-block align-top" alt="">
        UMPSA RideHub
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="scooter_home.php">Scooters</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="scooter_user.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../includes/logoutinc.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container d-flex align-items-center justify-content-center">
    <?php while ($row = $result->fetch_assoc()): ?>
    <form action="rent.inc.php" method="post" class="form-container w-100 animate__animated animate__fadeIn">
        <h2 class="text-center mb-4">Confirm Your Scooter Rental</h2>

        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "emptyfields") {
                echo '<p class="text-danger text-center">Please fill in all fields!</p>';
            } else if ($_GET['error'] == "invalidAccountNumber") {
                echo '<p class="text-danger text-center">Invalid bank account number!</p>';
            } else if ($_GET['error'] == "insertfail") {
                echo '<p class="text-danger text-center">Rental failed. Please try again.</p>';
            }
        }
        ?>

        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="scooterID" value="<?php echo htmlspecialchars($scooterID); ?>">
        <!-- The 'vehicle' hidden input is no longer strictly necessary if each rent.inc.php is specific -->
        <!-- <input type="hidden" name="vehicle" value="scooter"> -->

        <fieldset class="form-group border p-3 mb-4 rounded">
            <legend class="w-auto px-2">Scooter Details</legend>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Scooter Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['scooterName']); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                    <textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($row['scooterDesc']); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Rental Period</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['rentalPeriod']); ?>" readonly>
                </div>
            </div>
        </fieldset>

        <fieldset class="form-group border p-3 rounded">
            <legend class="w-auto px-2">Payment Details</legend>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bank Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="bankName" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bank Account No.</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="bankAcc" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Value (RM)</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="payment" readonly value="<?php echo number_format($row['scooterPrice'], 2); ?>">
                </div>
            </div>
        </fieldset>

        <div class="form-group text-center mt-4">
            <a href="scooter_home.php" class="btn btn-outline-primary px-5">Cancel</a>
            <button type="submit" name="rent" class="btn btn-primary px-5">Pay Now</button>
        </div>
    </form>
</div>
<?php endwhile; ?>

<footer class="bg-dark text-light text-center p-2">
    <p class="m-0">&copy; 2025 UMPSA RideHub. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
