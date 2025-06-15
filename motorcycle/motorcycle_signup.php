<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up to Rent Motorcycle - UMPSA RideHub</title>
  <link rel="icon" href="img/icon.png">
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
      min-width: 120px;
      margin: 20px 0;
    }

    .form-control {
      border: 3px solid #ccc;
      border-radius: 0 7.5px 0 7.5px;
      border-style: none solid solid none;
    }

    .form-control:hover { border-color: #4caf50; }
    .form-control:focus { border-color: gold; }

    .form-group { margin-bottom: 20px; }

    .register-screen {
      background: url('../img/motorcycle/motorsignin.jpg') no-repeat center center;
      background-size: cover;
      height: 100vh;
      min-height: 700px;
    }

    ::-webkit-scrollbar { width: 7.5px; }
    ::-webkit-scrollbar-track { box-shadow: inset 0 0 5px none; }
    ::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.712); }
    ::-webkit-scrollbar-thumb:hover { background: goldenrod; }
  </style>
</head>
<body class="loginpage">
  <div class="container-fluid bg-dark text-light">
    <div class="row">
      <div class="col-sm-5 col-md-4 login-block animate__animated animate__fadeInLeft">
        <div class="backend-main">

          <!-- Logo -->
          <div class="text-center logo">
            <a href="../index.html"><img src="../img/logohead100.png" alt="UMPSA RideHub"></a>
          </div>

          <h1 class="text-center">SIGN UP TO RENT MOTORCYCLE</h1>

          <!-- Error Messages -->
          <?php
            if (isset($_GET['error'])) {
              switch ($_GET['error']) {
                case "emptyfields":
                  echo '<p class="text-warning">Fill in all fields!</p>';
                  break;
                case "invaliduidmail":
                  echo '<p class="text-warning">Invalid username and e-mail!</p>';
                  break;
                case "invaliduid":
                  echo '<p class="text-warning">Invalid username!</p>';
                  break;
                case "invalidmail":
                  echo '<p class="text-warning">Invalid e-mail!</p>';
                  break;
                case "passwordcheck":
                  echo '<p class="text-warning">Your passwords do not match!</p>';
                  break;
                case "usertaken":
                  echo '<p class="text-warning">Username is already taken!</p>';
                  break;
              }
            }
          ?>

          <!-- Signup Form -->
          <form action="../includes/signupinc.php" method="post" class="needs-validation" novalidate>
            <div class="form-group">
              <input type="text" name="uid" placeholder="Your Username" class="form-control pt-4 pb-4" required>
              <div class="invalid-feedback text-warning">Please insert a username.</div>
            </div>

            <div class="form-group">
              <input type="email" name="mail" placeholder="Your E-mail" class="form-control pt-4 pb-4" required>
              <div class="invalid-feedback text-warning">Please insert an e-mail.</div>
            </div>

            <input type="hidden" name="vehicle" value="motorcycle">

            <div class="form-group">
              <input type="password" name="pwd" placeholder="Password" class="form-control pt-4 pb-4" required>
              <div class="invalid-feedback text-warning">Password between 8–20 characters.</div>
            </div>

            <div class="form-group">
              <input type="password" name="pwd-repeat" placeholder="Repeat password" class="form-control pt-4 pb-4" required>
              <div class="invalid-feedback text-warning">Repeat password for confirmation.</div>
            </div>

            <div class="form-group form-check">
              <input id="agree" type="checkbox" name="terms" class="form-check-input" checked>
              <label for="agree" class="form-check-label"> I agree to the <a href="#" class="text-warning">Terms and Conditions</a>.</label>
            </div>

            <div class="form-group text-center mt-3">
              <button type="submit" class="btn btn-lg btn-success w-75" name="signup-submit">Sign Up</button>
            </div>
          </form>

          <p class="text-center mt-3">Already have an account? <a href="motorcycle_login.php" class="text-warning">Click here</a>.</p>
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
