<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In to Rent Scooter - UMPSA RideHub</title>
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
    .bg-dark { background-color: #222 !important; }
    .login-screen {
      background: url('../img/scooter/harley.jpg') no-repeat center center;
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
      min-width: 100px;
      max-width: 180px;
    }
    .btn-link {
      color: #007bff;
      text-decoration: none;
      background-color: transparent;
      border: none;
      padding: 0;
      cursor: pointer;
    }
    .btn-link:hover {
      text-decoration: underline;
    }
    .backend-title {
      font-size: 24px;
      font-weight: bold;
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
      <!-- Left Login Block -->
      <div class="col-sm-5 col-md-4 bg-dark text-white login-block animate__animated animate__fadeInLeft">
        <div class="backend-main">
          <div class="logo text-center mb-4">
            <a href="../index.html"><img src="../img/logohead100.png" alt="UMPSA RideHub"></a>
          </div>
          <h2 class="backend-title text-center mb-4">Log In to Rent Scooter</h2>

          <?php
            if (isset($_GET['error'])) {
              if ($_GET['error'] == "emptyfields") {
                echo '<p class="text-danger text-center">Fill in all fields!</p>';
              } else if ($_GET['error'] == "wrongpwd") {
                echo '<p class="text-danger text-center">Wrong password!</p>';
              } else if ($_GET['error'] == "nouser") {
                echo '<p class="text-danger text-center">User not found!</p>';
              } else if ($_GET['error'] == "sqlerror") {
                echo '<p class="text-danger text-center">Database error!</p>';
              }
            } else if (isset($_GET['signup']) && $_GET['signup'] == "success") {
              echo '<p class="text-success text-center">Signup successful! Please log in.</p>';
            }
          ?>

          <form action="../includes/logininc.php" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="vehicle" value="scooter">
            <div class="form-group">
              <label for="username">Username/Email</label>
              <input type="text" class="form-control form-control-lg" id="username" name="mailuid" placeholder="Enter Username or Email" required>
              <div class="invalid-feedback">Please enter your username or email.</div>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control form-control-lg" id="password" name="pwd" placeholder="Enter Password" required>
              <div class="invalid-feedback">Please enter your password.</div>
            </div>
            <div class="form-group text-right">
              <a href="#" class="btn-link text-warning">Forgot password?</a>
            </div>
            <div class="form-group mt-4">
              <button type="submit" class="btn btn-primary btn-lg w-100" name="login-submit">Log In</button>
            </div>
            <p class="text-center mt-3">Don't have an account? <a href="scooter_signup.php" class="text-warning">Sign up here</a>.</p>
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
    // Toggle show/hide password (if needed, currently not implemented in CSS/icon)
    // $(function () {
    //   $(".show-pass").on('click', function () {
    //     const input = $("#password");
    //     const type = input.attr('type') === 'password' ? 'text' : 'password';
    //     input.attr('type', type);
    //     $(this).html(type === 'text'
    //       ? '<i class="icon-eye-slash"></i>Hide'
    //       : '<i class="icon-eye"></i>Show');
    //   });
    // });

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
