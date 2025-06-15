<?php
    session_start();
    require '../includes/dbh.inc.php';

    if (!isset($_GET['bikeID']) || !isset($_SESSION['userUid'])) {
        header("Location: home.php");
        exit();
    }

    $bikeID = $_GET['bikeID'];
    $username = $_SESSION['userUid'];

    // Get user_id from database using uidUsers
    $sqlUser = "SELECT id FROM users WHERE uidUsers = ?";
    $stmtUser = mysqli_prepare($conn, $sqlUser);
    mysqli_stmt_bind_param($stmtUser, "s", $username);
    mysqli_stmt_execute($stmtUser);
    mysqli_stmt_bind_result($stmtUser, $user_id);
    mysqli_stmt_fetch($stmtUser);
    mysqli_stmt_close($stmtUser);

    // Get selected bike details
    $result = $conn->query("SELECT * FROM bikes WHERE bikeID='$bikeID'") or die($conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent Confirmation</title>
    <link rel="icon" href="img/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400;1,500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cabin', sans-serif;
            background: whitesmoke;
        }
        .bg-dark {
            background-color: #222 !important;
        }
        .nav-link:hover {
            color: #9ACD32 !important;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand animate__animated animate__fadeIn" href="#"><img src="../img/logohead80.png" alt="Logo"> UMPSA RideHub</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link text-light disabled animate__animated animate__flipInX" href="#">Rent Payment Confirmation</a></li>
            </ul>
        </div>
    </div>
</nav>

<?php while($row = $result->fetch_assoc()): ?>
<div class="container col-md-8 bg-white mt-5 mb-5 p-4 animate__animated animate__fadeIn border rounded">
    <form action="rent.inc.php" method="post">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="<?php echo $_SESSION['userUid']; ?>" readonly>

                <input type="hidden" name="username" value="<?php echo $_SESSION['userUid']; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Pay to</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="RideHub Sdn. Bhd." readonly>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Product</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="<?php echo $row['bikeName']; ?>" readonly>
                <input type="hidden" name="bikeID" value="<?php echo $row['bikeID']; ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Rental Period</label>
            <div class="col-sm-9">
                <input type="text" class="form-control text-capitalize" value="<?php echo $row['rentalPeriod']; ?>" readonly>
            </div>
        </div>

        <fieldset class="form-group">
            <div class="row">
                <label class="col-form-label col-sm-3 pt-0">Type of Payment</label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" checked>
                        <label class="form-check-label">Online Bank</label>
                    </div>
                </div>
            </div>
        </fieldset>

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
                <input type="text" class="form-control" name="payment" readonly value="<?php echo number_format($row['bikePrice'], 2); ?>">
            </div>
        </div>

        <div class="form-group text-center mt-4">
            <a href="bicycle_home.php" class="btn btn-outline-primary px-5">Cancel</a>
            <button type="submit" name="rent" class="btn btn-primary px-5">Pay Now</button>
        </div>
    </form>
</div>
<?php endwhile; ?>

<footer class="bg-dark text-light text-center p-2">
    <p class="mb-0">&copy; 2025 - UMPSA RideHub. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
