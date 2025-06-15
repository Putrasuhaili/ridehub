<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up to Rent Scooter - UMPSA RideHub</title>
  <link rel="icon" href="../img/icon.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Cabin&display=swap" rel="stylesheet">

  <style>
    body.loginpage {
      background: #fff;
      margin: 0;
      font-family: 'Cabin', sans-serif;
    }

    .bg-dark {
      background-color: #222 !important;
    }

    .login-block {
      display: table;
      height: 100vh;
    }

    .backend-main {
      display: table-cell;
      vertical-align: middle;
      max-width: 480px;
      margin: auto;
      padding: 0 20px;
    }

    .logo img {
      min-width: 100px;
      max-width: 180px;
    }

    .register-screen {
      background: url('../img/scooter/ktm.jpg') no-repeat center center;
      background-size: cover;
      height: 100vh;
      min-height: 700px;
    }
    .form-control-lg {
        border-radius: 0.5rem;
    }
    .btn-lg {
        border-radius: 0.5rem;
    }
  </style>
</head>
<body class="loginpage">

  <div class="container-fluid">
    <div class="row">
      <!-- Left Register Block -->
      <div class="col-sm-5 col-md-4 bg-dark text-white login-block animate__animated animate__fadeInLeft">
        <div class="backend-main">
          <div class="logo text-center mb-4">
            <a href="../index.html"><img src="../img/logohead100.png" alt="UMPSA RideHub"></a>
          </div>
          <h2 class="backend-title text-center mb-4">Sign Up to Rent Scooter</h2>

          <?php
          if (isset($_GET['error'])) {
            if ($_GET['error'] == "emptyfields") {
              echo '<p class="text-danger text-center">Fill in all fields!</p>';
            } else if ($_GET['error'] == "invaliduidmail") {
              echo '<p class="text-danger text-center">Invalid username and e-mail!</p>';
            } else if ($_GET['error'] == "invaliduid") {
              echo '<p class="text-danger text-center">Invalid username!</p>';
            } else if ($_GET['error'] == "invalidmail") {
              echo '<p class="text-danger text-center">Invalid e-mail!</p>';
            } else if ($_GET['error'] == "passwordcheck") {
              echo '<p class="text-danger text-center">Your passwords do not match!</p>';
            } else if ($_GET['error'] == "usertaken") {
              echo '<p class="text-danger text-center">Username or email is already taken!</p>';
            } else if ($_GET['error'] == "sqlerror") {
                echo '<p class="text-danger text-center">Database error!</p>';
            }
          }
          ?>

          <form action="../includes/signupinc.php" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="vehicle" value="scooter">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control form-control-lg" id="username" name="uid" placeholder="Enter Username" required>
              <div class="invalid-feedback">Please enter a username.</div>
            </div>
            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" class="form-control form-control-lg" id="email" name="mail" placeholder="Enter Email" required>
              <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control form-control-lg" id="password" name="pwd" placeholder="Enter Password" required>
              <div class="invalid-feedback">Please enter a password.</div>
            </div>
            <div class="form-group">
              <label for="repeat-password">Repeat Password</label>
              <input type="password" class="form-control form-control-lg" id="repeat-password" name="pwd-repeat" placeholder="Repeat Password" required>
              <div class="invalid-feedback">Please repeat your password.</div>
            </div>

            <div class="form-group form-check mt-3">
              <input type="checkbox" class="form-check-input" id="agree" required>
              <label for="agree" class="form-check-label"> I agree to the <a href="#" class="text-warning">Terms and Conditions</a>.</label>
              <div class="invalid-feedback">You must agree to the terms and conditions.</div>
            </div>

            <div class="form-group text-center mt-3">
              <button type="submit" class="btn btn-lg btn-success w-75" name="signup-submit">Sign Up</button>
            </div>
          </form>

          <p class="text-center mt-3">Already have an account? <a href="scooter_login.php" class="text-warning">Click here</a>.</p>
        </div>
      </div>

      <!-- Right Side Image -->
      <div class="col-sm-7 col-md-8 d-none d-sm-block register-screen animate__animated animate__fadeInRight"></div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Bootstrap validation
    (function () {
      'use strict';
      window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.forEach.call(forms, function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>

</body>
</html>
