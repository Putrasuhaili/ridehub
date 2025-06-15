<?php
session_start();
require '../includes/dbh.inc.php';

// Check if user is logged in
if (!isset($_SESSION['userId']) || !isset($_SESSION['userUid'])) {
    header("Location: scooter_login.php");
    exit();
}

$user_id = intval($_SESSION['userId']);
$username = $_SESSION['userUid'];

// Fetch user info
$result = $conn->query("SELECT * FROM users WHERE id=$user_id") or die($conn->error);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $name = $row['custName'];
    $email = $row['emailUsers'];
    $campus = $row['custCampus'];
    $age = $row['custAge'];
    $phone = $row['custPhone'];
    $address = $row['custAddress'];
    $desc = $row['custDesc'];
} else {
    echo "User not found.";
    exit();
}

// Fetch user's current scooter rental
$resultR = $conn->query("
    SELECT r.*, s.scooterName, s.scooterPrice, s.scooterImagePath
    FROM rents_scooter r
    INNER JOIN scooters s ON r.scooterID = s.scooterID
    WHERE r.user_id = $user_id
    ORDER BY r.rent_time DESC
    LIMIT 1
") or die($conn->error);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - UMPSA RideHub</title>
    <link rel="icon" href="../img/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cabin&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cabin', sans-serif;
            background-color: #f4f4f4;
            padding-bottom: 60px; /* Space for fixed footer */
        }
        .profile-container, .rental-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .profile-header h2 {
            font-weight: bold;
            color: #333;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .card-img-top {
            height: 180px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        footer {
            background: #222;
            color: #fff;
            padding: 1rem;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .btn-update {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            border-radius: 0.5rem;
        }
        .btn-update:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-danger {
            border-radius: 0.5rem;
        }
        .btn-secondary {
            border-radius: 0.5rem;
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
            <li class="nav-item active">
                <a class="nav-link" href="scooter_user.php">Profile <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../includes/logoutinc.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4 animate__animated animate__fadeIn">
    <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "emptyfields") {
                echo '<p class="text-danger text-center">Fill in all fields!</p>';
            } else if ($_GET['error'] == "invalidmail") {
                echo '<p class="text-danger text-center">Invalid e-mail!</p>';
            } else if ($_GET['error'] == "passwordcheck") {
                echo '<p class="text-danger text-center">Your passwords do not match!</p>';
            } else if ($_GET['error'] == "alreadyrenting") {
                echo '<div class="alert alert-warning text-center" role="alert">
                        You already have an active rental. Please end your current rental before renting another scooter.
                      </div>';
            }
        } else if (isset($_GET['updated'])) {
            echo '<p class="text-success text-center">Profile updated successfully!</p>';
        } else if (isset($_GET['rent']) && $_GET['rent'] == "success") {
            echo '<div class="alert alert-success text-center" role="alert">
                    Scooter rented successfully!
                  </div>';
        } else if (isset($_GET['rent']) && $_GET['rent'] == "ended") {
            echo '<div class="alert alert-info text-center" role="alert">
                    Your scooter rental has been successfully ended.
                  </div>';
        }
    ?>
    <div class="row">
        <!-- User Profile Section -->
        <div class="col-md-6">
            <div class="profile-container">
                <div class="profile-header text-center mb-4">
                    <h2>User Profile</h2>
                    <p class="text-muted">Manage your personal information.</p>
                </div>
                <form action="../includes/update.inc.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <input type="hidden" name="vehicle" value="scooter">

                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label info-label">Full Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" placeholder="Enter Full Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label info-label">Email:</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="mail" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter Email" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="age" class="col-sm-4 col-form-label info-label">Age:</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($age ?? ''); ?>" placeholder="Enter Age">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="campus" class="col-sm-4 col-form-label info-label">Campus:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="campus" name="campus" value="<?php echo htmlspecialchars($campus ?? ''); ?>" placeholder="Enter Campus">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-sm-4 col-form-label info-label">Address:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter Address"><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-4 col-form-label info-label">Phone:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" placeholder="Enter Phone Number">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="desc" class="col-sm-4 col-form-label info-label">Additional Info:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="Any additional information"><?php echo htmlspecialchars($desc ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button type="submit" name="update-info" class="btn btn-update px-5">Update Profile</button>
                    </div>
                </form>
                <hr>
                <h4 class="text-center mb-3">Change Password</h4>
                <form action="../includes/update.inc.php" method="post">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <input type="hidden" name="vehicle" value="scooter">
                    <div class="form-group row">
                        <label for="pwd" class="col-sm-4 col-form-label info-label">New Password:</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter New Password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="repeat-pwd" class="col-sm-4 col-form-label info-label">Repeat Password:</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="repeat-pwd" name="repeat-pwd" placeholder="Repeat New Password">
                        </div>
                    </div>
                    <div class="form-group text-center mt-4">
                        <button type="submit" name="change" class="btn btn-warning px-5">Change Password</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Rental Section -->
        <div class="col-md-6">
            <div class="rental-container">
                <h2 class="text-center mb-4">My Current Rental (Scooter)</h2>
                <?php if ($resultR->num_rows > 0):
                    $rentRow = $resultR->fetch_assoc();
                ?>
                    <div class="card shadow-sm mb-4">
                        <img class="card-img-top" src="../img/<?php echo htmlspecialchars($rentRow['scooterImagePath']); ?>" alt="Scooter Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($rentRow['scooterName']); ?></h5>
                            <p class="card-text"><strong>Rental ID:</strong> <?php echo htmlspecialchars($rentRow['rentID']); ?></p>
                            <p class="card-text"><strong>Rented On:</strong> <?php echo date("F j, Y, g:i a", strtotime($rentRow['rent_time'])); ?></p>
                            <p class="card-text"><strong>Rental Price:</strong> RM<?php echo number_format($rentRow['scooterPrice'], 2); ?></p>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#endRentModal">End My Rental</button>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        You currently do not have any scooter rented.
                        <br><a href="scooter_home.php" class="alert-link">Explore available scooters!</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- End Rent Modal -->
<div class="modal fade" id="endRentModal" tabindex="-1" role="dialog" aria-labelledby="endRentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-light">
                <h5 class="modal-title" id="endRentModalLabel">End My Rental - Notice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <h4> You need to return the scooter as soon the rental end or else you will be charged a penalty.</h4>
                <h5> Thank You For Renting With Us!</h5>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <a href="endrent.inc.php?scooterID=<?php echo htmlspecialchars($rentRow['scooterID']); ?>" class="btn btn-danger pr-5 pl-5">End My Rent Now</a>
                <button type="button" class="btn btn-secondary pr-5 pl-5" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<footer class="container-fluid bg-dark text-light p-0" style="bottom: 0; position:fixed;">
    <p class="text-center m-0 p-2">&copy; Copyright <?php echo date("Y"); ?> - UMPSA RideHub. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
