<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In to Rent Motorcycle - UMPSA RideHub</title>
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
    .bg-dark { background-color: #222 !important; }
    .login-screen {
      background: url('../img/motorcycle/motorlogin.jpg') no-repeat center center;
      background-size: cover;
      height: 100vh;
      min-height: 700px;
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
    .show-pass {
      position: absolute;
      right: 0;
      top: -4px;
      color: gold;
      font-weight: 600;
      cursor: pointer;
    }
    .show-pass i { margin-right: 10px; }
    footer {
      background: #222;
      color: #fff;
      text-align: center;
      padding: 1rem 0;
    }
  </style>
</head>
<body class="loginpage">

  <div class="container-fluid bg-dark text-light">
    <div class="row">
      <!-- Left Panel -->
      <div class="col-sm-5 col-md-4 login-block animate__animated animate__fadeInLeft">
        <div class="backend-main">

          <!-- Logo -->
          <div class="text-center logo">
            <a href="../index.html">
            <img src="../img/logohead100.png" alt="UMPSA RideHub"></a>
          </div>
          <h1 class="text-center">LOG IN TO RENT MOTORCYCLE         </h1>

          <!-- Error / Success Messages -->
          <?php
            if (isset($_GET['error'])) {
              if ($_GET['error'] == "emptyfields") {
                echo '<p class="text-warning text-center">⚠️ Fill in all fields!</p>';
              } elseif ($_GET['error'] == "wrongpwd") {
                echo '<p class="text-warning text-center">⚠️ Wrong Password!</p>';
              } elseif ($_GET['error'] == "nouser") {
                echo '<p class="text-warning text-center">⚠️ No user found!</p>';
              }
            }
            if (isset($_GET['newpwd']) && $_GET['newpwd'] == "passwordupdated") {
              echo '<p class="text-success text-center">✅ Your password has been reset!</p>';
            }
          ?>

          <!-- Login Form -->
          <form action="../includes/logininc.php" method="post" class="needs-validation" novalidate>
            <div class="form-group">
              <label for="username">Username or E-mail</label>
              <input type="text" class="form-control pt-4 pb-4" name="mailuid" id="username"
                     placeholder="Enter username or e-mail"
                     value="<?php if (isset($_COOKIE['muid'])) echo $_COOKIE['muid']; ?>" required>
              <div class="invalid-feedback text-warning">Please insert your username or e-mail.</div>
            </div>

            <div class="form-group position-relative">
              <span class="show-pass"><i class="icon-eye"></i>Show</span>
              <label for="password">Password</label>
              <input type="password" class="form-control pt-4 pb-4 showpassword" name="pwd" id="password"
                     placeholder="Enter password" required>
              <div class="invalid-feedback text-warning">Please insert your password.</div>
            </div>

            <input type="hidden" name="vehicle" value="motorcycle"> <!-- or car, scooter, motorcycle -->

            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="remember_me" name="remember"
                     <?php if (isset($_COOKIE['muid'])) echo 'checked'; ?>>
              <label class="form-check-label" for="remember_me">Keep me remembered</label>
            </div>

            <div class="form-group text-center mt-3">
              <button type="submit" name="login-submit" class="btn btn-success btn-lg w-75">Log In</button>
            </div>

            <div class="text-center">
              <a href="resetpwd.php" class="text-warning">Forgot your password?</a>
            </div>
            <br>
            <p class="text-center">Don't have an account? <a href="..\motorcycle\motorcycle_signup.php" class="text-warning">Sign up here</a>.</p>
          </form>
        </div>
      </div>

      <!-- Right Image -->
      <div class="col-sm-7 col-md-8 d-none d-sm-block login-screen animate__animated animate__fadeInRight"></div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Toggle show/hide password
    $(function () {
      $(".show-pass").on('click', function () {
        const input = $("#password");
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).html(type === 'text'
          ? '<i class="icon-eye-slash"></i>Hide'
          : '<i class="icon-eye"></i>Show');
      });
    });

    // Bootstrap form validation
    (function () {
      'use strict';
      window.addEventListener('load', function () {
        const forms = document.getElementsByClassName('needs-validation');
        Array.prototype.forEach.call(forms, function (form) {
          form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
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
