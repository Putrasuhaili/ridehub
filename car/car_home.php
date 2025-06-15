<?php
session_start();
require '../includes/dbh.inc.php';

// Initialize search variables
$searchKeyword = "";
$searchQueryAdded = false;
$resultC = null; // Initialize to null

// Check if a search term is submitted
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchKeyword = trim($_GET['search']);
    $searchTermParam = '%' . $searchKeyword . '%';

    // Prepare SQL query with search conditions for carName, carDesc, rentalPeriod, and carPrice
    $sql = "SELECT * FROM cars WHERE status='AVAILABLE' AND (carName LIKE ? OR carDesc LIKE ? OR rentalPeriod LIKE ? OR CAST(carPrice AS CHAR) LIKE ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $searchTermParam, $searchTermParam, $searchTermParam, $searchTermParam);
        mysqli_stmt_execute($stmt);
        $resultC = mysqli_stmt_get_result($stmt);
        $searchQueryAdded = true;
    } else {
        // Fallback or error handling if statement preparation fails
        $sql = "SELECT * FROM cars WHERE status='AVAILABLE'";
        $resultC = $conn->query($sql) or die($conn->error);
    }
} else {
    // Original query to get all available cars if no search term
    $sql = "SELECT * FROM cars WHERE status='AVAILABLE'";
    $resultC = $conn->query($sql) or die($conn->error);
}

if (isset($_SESSION['userUid'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UMPSA RideHub | Available Cars</title>
  <link rel="icon" href="../img/icon.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
  <link rel="stylesheet" href="https://kit.fontawesome.com/5d2ecb2a4b.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400;1,500&display=swap">
  <style>
    body {
      font-family: 'Cabin', sans-serif;
      background-color: #f4f4f4;
    }

    .navbar-nav li { padding-right: 20px; }
    .nav-link:hover { color: #ADFF2F !important; }
    .card-img-top {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }
    /* Style for the search bar container */
    .search-bar-container {
        padding: 1.5rem 0; /* Add some vertical padding */
        background-color: #f4f4f4; /* Light background for the search bar area */
        border-bottom: 1px solid #dee2e6; /* A subtle border */
        margin-bottom: 20px; /* Space between search bar and cards */
    }
    .search-bar-container .form-control {
        border-radius: 0.5rem;
    }
    .search-bar-container .btn {
        border-radius: 0.5rem;
    }
    footer {
      background: #222;
      color: #fff;
      text-align: center;
      padding: 0.75rem 0;
      margin-top: 2rem;
    }

    .bg-dark {
      background-color: #222 !important;
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
                <li class="nav-item active"><a class="nav-link animate__animated animate__flipInX" href="#">CAR</a></li>
                <li class="nav-item"><a class="nav-link animate__animated animate__flipInX" href="car_user.php#">PROFILE</a></li>
                <li class="nav-item"><a class="nav-link animate__animated animate__flipInX" href="../includes/logoutinc.php">LOG OUT</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- Search Bar Section for Cars -->
<div class="container search-bar-container">
  <form action="" method="GET" class="form-inline justify-content-center">
    <div class="input-group w-75 w-md-50"> <!-- Responsive width -->
      <input class="form-control" type="search" placeholder="Search cars..." aria-label="Search" name="search" value="<?php echo htmlspecialchars($searchKeyword); ?>">
      <div class="input-group-append">
        <button class="btn btn-outline-primary" type="submit">Search</button>
      </div>
    </div>
  </form>
</div>

<div class="container mt-4">
  <div class="row">
    <?php while ($row = $resultC->fetch_assoc()): ?>
      <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card h-100 shadow-sm">
          <img class="card-img-top" src="../<?php echo htmlspecialchars($row['carImagePath']); ?>" alt="Car Image">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($row['carName']); ?></h5>
            <h6 class="card-subtitle text-muted mb-2">By <?php echo htmlspecialchars($row['rentalPeriod']); ?></h6>
            <p class="card-text"><?php echo htmlspecialchars($row['carDesc']); ?></p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-success font-weight-bold">RM<?php echo number_format($row['carPrice'], 2); ?></span>
              <a href="../car/car_rentconfirmation.php?carID=<?php echo $row['carID']; ?>" class="btn btn-danger btn-sm">Rent</a>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<footer>
  <p>&copy; 2025 UMPSA RideHub. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php } else {
  header("Location: car_login.php");
  exit();
} ?>