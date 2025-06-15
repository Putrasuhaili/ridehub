<?php
session_start();
require '../includes/dbh.inc.php';

// Check if user is logged in
if (!isset($_SESSION['userId']) || !isset($_SESSION['userUid'])) {
    header("Location: login.php");
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

// ✅ Corrected car rental query using username
$resultR = $conn->query("
    SELECT * 
    FROM rents_car 
    INNER JOIN cars ON rents_car.carID = cars.carID 
    WHERE rents_car.user_id = $user_id
    ORDER BY rents_car.rent_time DESC 
    LIMIT 1
") or die($conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400;1,500&display=swap">
    <style>
        html,body {
            height: 100%;
            width: 100%;
            font-family: 'Cabin', sans-serif;
            background: whitesmoke;
            color: #222;
        }

        .cursor {
            cursor: pointer;
        }

        .box-shad {
            box-shadow: 2px 2px 12px #aaa;
        }

        .bg-dark {
            background-color: #222 !important;
        }

        .bg-powderblue {
            background-color: #B0E0E6;
        }

        .bg-lavender {
            background-color: #E6E6FA;
        }
        
        .bg-lightsky {
            background-color: #87CEFA;
        }

        .navbar-nav li{
            padding-right: 20px;
        }

        .nav-link:hover {
            color: #ADFF2F !important;
        }

        @media (min-width: 768px) {
            .col-sm-12 {
                width: 100%;
            }
        }

        ::-webkit-scrollbar {
            width: 7.5px;
        }

        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px none; 
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.712);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: goldenrod; 
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
    <a class="navbar-brand" href="../index.html"><img src="../img/logohead80.png" alt="logo"> UMPSA RideHub</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link animate__animated animate__flipInX" href="car_home.php">CAR</a></li>
                <li class="nav-item active"><a class="nav-link animate__animated animate__flipInX" href="#">PROFILE</a></li>
                <li class="nav-item"><a class="nav-link animate__animated animate__flipInX" href="../includes/logoutinc.php">LOG OUT</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-3 mb-3 text-center animate__animated animate__zoomIn">
    <div class="row">
        <div class="col-12">
            <div class="rounded bg-dark box-shad">
                <h2 class="m-0 text-light p-2">
                    <?php 
                        if (isset($_SESSION['userId'])){
                            echo "Welcome " . htmlspecialchars($row['uidUsers']);
                        }
                    ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<?php
function renderAlert($content, $class = 'text-danger') {
    echo '
    <div class="container mt-2 mb-2 text-center animate__animated animate__zoomIn">
        <div class="row">
            <div class="col-12">
                <div class="rounded bg-light box-shad border p-3 ' . $class . '">
                    ' . $content . '
                </div>
            </div>
        </div>
    </div>';
}

if (isset($_GET['error'])) {
    if ($_GET['error'] == "emptyfields") {
        renderAlert('Fill in all fields!');
    } else if ($_GET['error'] == "invalidmail") {
        renderAlert('Invalid e-mail!');
    } else if ($_GET['error'] == "passwordcheck") {
        renderAlert('Your passwords do not match!');
    } else if ($_GET['error'] == "alreadyrenting") {
        renderAlert(
            'You already have an active rental. Please end your current rental before renting another car.',
            'text-warning border-warning'
        );
    }

} else if (isset($_GET['rent']) && $_GET['rent'] == "success") {
    renderAlert('Car rented successfully!', 'text-success border-success');
} else if (isset($_GET['rent']) && $_GET['rent'] == "ended") {
    renderAlert('Your car rental has been successfully ended.', 'text-info border-info');
}
?>

<div class="container mb-5 animate__animated animate__zoomIn">
    <div class="row">
        <div class="col-12">
            <div class="card box-shad">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs nav-fill mb-4" id="myTab" role="tablist">
                                <li class="nav-item bg-lavender rounded-top">
                                    <a class="nav-link active text-dark" id="basicInfo-tab" data-toggle="tab" href="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true">My Account</a>
                                </li>
                                <li class="nav-item bg-powderblue rounded-top">
                                    <a class="nav-link text-dark" id="carInfo-tab" data-toggle="tab" href="#carInfo" role="tab" aria-controls="carInfo" aria-selected="false">My Car</a>
                                </li>
                                <li class="nav-item bg-lightsky rounded-top">
                                    <a class="nav-link text-dark" id="userInfo-tab" data-toggle="tab" href="#userInfo" role="tab" aria-controls="userInfo" aria-selected="false">My Information</a>
                                </li>
                            </ul>
                            <div class="tab-content ml-1" id="myTabContent">
                                <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label class="font-weight-bold">Username</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <?php echo htmlspecialchars($row['uidUsers']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label class="font-weight-bold">Email</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <?php echo htmlspecialchars($row['emailUsers']); ?>
                                        </div>
                                    </div>
                                    <hr>
                            


    <!-- Update Password Section -->
    <div class="row mt-4 mb-3">
        <div class="col-sm-12 col-md-10 offset-md-1">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white font-weight-bold">
                    Update Password
                </div>
                <div class="card-body">
                    <form action="../includes/update.inc.php" method="post">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                        <div class="form-group row mb-3">
                            <label for="pwd" class="col-sm-4 col-form-label font-weight-bold">New Password:</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter New Password" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="repeat-pwd" class="col-sm-4 col-form-label font-weight-bold">Repeat Password:</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="repeat-pwd" name="repeat-pwd" placeholder="Repeat New Password" required>
                            </div>
                        </div>

                        <div class="form-group text-center mt-3">
                            <button type="submit" name="change" class="btn btn-info px-5 font-weight-bold text-white">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

                                <div class="tab-pane fade" id="carInfo" role="tabpanel" aria-labelledby="carInfo-tab">
                                    <?php if ($resultR->num_rows > 0): ?>
                                        <?php while($rowR = $resultR->fetch_assoc()): ?>
                                            <div class="row">
                                                <div class="col-sm-3 col-md-2 col-5">
                                                    <label class="font-weight-bold">Car Image</label>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <img src="../<?php echo htmlspecialchars($rowR['carImagePath']); ?>" alt="Car Image" style="width: 360px; height: 239.5px;" class="img-fluid img-thumbnail">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3 col-md-2 col-5">
                                                    <label class="font-weight-bold">Car Price</label>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    RM<?php echo number_format($rowR['carPrice'], 2); ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3 col-md-2 col-5">
                                                    <label class="font-weight-bold">Rental Period</label>
                                                </div>
                                                <div class="col-md-8 col-6 text-capitalize">
                                                    By <?php echo $rowR['rentalPeriod']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                                <div class="row">
                                                    <div class="col-sm-3 col-md-2 col-5">
                                                        <label class="font-weight-bold">Rented On</label>
                                                    </div>
                                                    <div class="col-md-8 col-6">
                                                        <?php echo $rowR['rent_time']; ?>
                                                    </div>
                                                </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3 col-md-2 col-5">
                                                    <label class="font-weight-bold">Description</label>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <?php echo $rowR['carDesc']; ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group col-md-12">
                                                <p>Car ID: <?php echo $rowR['carID']; ?></p>
                                                <a href="endrent.inc.php?carID=<?php echo $rowR['carID']; ?>" class="btn btn-block btn-danger pr-5 pl-5">End My Rent Now</a>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <p>No current car rental information available.</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="tab-pane fade" id="userInfo" role="tabpanel" aria-labelledby="userInfo-tab">
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label class="font-weight-bold">Full Name</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <?php echo htmlspecialchars($row['custName']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label class="font-weight-bold">Age</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <?php echo htmlspecialchars($row['custAge']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label class="font-weight-bold">Campus</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <?php echo htmlspecialchars($row['custCampus']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label class="font-weight-bold">Address</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <?php echo htmlspecialchars($row['custAddress']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label class="font-weight-bold">Contact No.</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <?php echo htmlspecialchars($row['custPhone']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label class="font-weight-bold">Additional Information</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <?php echo htmlspecialchars($row['custDesc']); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group col-md-12">
                                        <a href="#" class="btn btn-block btn-primary p-2" data-toggle="modal" data-target="#editInfoModal">Edit My Information</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Info Modal -->
<div class="modal fade" id="editInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../includes/update.inc.php">
                    <div class="form-group row">
                        <label for="inputFullname" class="col-sm-3 col-form-label">Full Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['custName']); ?>">
                            <input type="hidden" name="id" value="<?php echo $user_id; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputAge" class="col-sm-3 col-form-label">Age</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" min="1" step="1" max="60" name="age" value="<?php echo htmlspecialchars($row['custAge']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputCampus" class="col-sm-3 col-form-label">Campus</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="campus" value="<?php echo htmlspecialchars($row['custCampus']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputAddress" class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($row['custAddress']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputContact" class="col-sm-3 col-form-label">Contact No.</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($row['custPhone']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Email Address</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="mail" value="<?php echo htmlspecialchars($row['emailUsers']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputAddInfo" class="col-sm-3 col-form-label">Additional Information</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="desc" value="<?php echo htmlspecialchars($row['custDesc']); ?>">
                        </div>
                    </div>

			    <input type="hidden" name="vehicle" value="<?php echo $_SESSION['vehicle'] ?? 'car'; ?>">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success pr-5 pl-5" name="update-info">Update Info</button>
                        <button type="button" class="btn btn-secondary pr-5 pl-5" data-dismiss="modal">Close</button>
                    </div>
                </form>
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
                <h4> You need to return the car in 10 work days or else you will be charged a penalty.</h4>
                <h5> Thank You For Renting With Us!</h5>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <a href="car/endrent.inc.php?carID=<?php echo $row['carID']?>" class="btn btn-danger pr-5 pl-5">End My Rent Now</a>
                <button type="button" class="btn btn-secondary pr-5 pl-5" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div style="height: 100px;"></div>
<footer class="container-fluid bg-dark text-light p-0" style="bottom: 0; position:fixed;">
    <p class="text-center m-0 p-2">&copy; Copyright <?php echo date("Y"); ?> - UMPSA RideHub. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>