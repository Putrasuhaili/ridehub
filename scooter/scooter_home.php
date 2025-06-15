<?php
session_start();
require '../includes/dbh.inc.php';

// Initialize search variables
$searchKeyword = "";
$searchQueryAdded = false;

// Check if a search term is submitted
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchKeyword = trim($_GET['search']);
    $searchTermParam = '%' . $searchKeyword . '%';
    // Use prepared statement for security and to prevent SQL injection
    $sql = "SELECT * FROM scooters WHERE status='AVAILABLE' AND (scooterName LIKE ? OR scooterPrice LIKE ? OR rentalPeriod LIKE ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $searchTermParam, $searchTermParam, $searchTermParam);
    mysqli_stmt_execute($stmt);
    $resultS = mysqli_stmt_get_result($stmt);
    $searchQueryAdded = true; // Flag to indicate search query was used
} else {
    // Original query to get all available scooters if no search term
    $sql = "SELECT * FROM scooters WHERE status='AVAILABLE'";
    $resultS = $conn->query($sql) or die($conn->error);
}

if (isset($_SESSION['userUid'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UMPSA RideHub | Available Scooters</title>
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
    .nav-link:hover { color: #f8f9fa !important; }
    

    footer {
      background: #222;
      color: #fff;
      padding: 1rem;
      text-align: center;
      margin-top: 2rem;
    }

    .card {
      border: none;
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card-img-top {
      height: 180px;
      object-fit: cover;
    }
    .card-body {
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
    }
    .card-title {
      font-weight: bold;
      color: #333;
    }
    .card-text {
      flex-grow: 1;
      color: #555;
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
            <li class="nav-item active"><a class="nav-link" href="scooter_home.php">Scooters <span class="sr-only">(current)</span></a></li>
            <li class="nav-item"><a class="nav-link" href="scooter_user.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="../includes/logoutinc.php">Logout</a></li>
        </ul>
    </div>
</nav>

<form action="" method="GET" class="form-inline my-4 justify-content-center">
    <input class="form-control mr-sm-2" type="search" placeholder="Search scooters..." aria-label="Search" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
</form>

<div class="container mt-4">
  <div class="row">
    <?php while ($row = $resultS->fetch_assoc()): ?>
      <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card h-100 shadow-sm">
          <img class="card-img-top" src="../img/<?php echo htmlspecialchars($row['scooterImagePath']); ?>" alt="Scooter Image">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($row['scooterName']); ?></h5>
            <h6 class="card-subtitle text-muted mb-2">By <?php echo htmlspecialchars($row['rentalPeriod']); ?></h6>
            <p class="card-text"><?php echo htmlspecialchars($row['scooterDesc']); ?></p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-success font-weight-bold">RM<?php echo number_format($row['scooterPrice'], 2); ?></span>
              <a href="scooter_rentconfirmation.php?scooterID=<?php echo $row['scooterID']; ?>" class="btn btn-danger btn-sm">Rent</a>
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

<?php
} else {
    header("Location: scooter_login.php");
    exit();
}
?>
