<?php
session_start();
include('includes/dbconnection.php');

// If form is submitted
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, md5($_POST['password'])); // Encrypting the password with md5

    // Check if the user exists
    $query = "SELECT * FROM tblusers WHERE Email='$email' AND Password='$password'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Set session variables
        $_SESSION['userid'] = $row['ID'];
        $_SESSION['fullname'] = $row['FullName'];
        
        // Redirect to homepage or dashboard
        // Set user ID in JavaScript
    echo "<script>
            localStorage.setItem('user_id', '" . $row['ID'] . "');
            alert('Login successful!');
            window.location.href = 'main.php';
          </script>";
    } else { 
        echo "<script>alert('Invalid email or password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Art Gallery Management System | Login</title>
    <script>
         addEventListener("load", function () {
         	setTimeout(hideURLbar, 0);
         }, false);
         
         function hideURLbar() {
         	window.scrollTo(0, 1);
         }
      </script>
      <!-- Add JavaScript to confirm if the user ID is set in localStorage -->

    <script>
        function validateForm() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }
 // Toggle password visibility
 document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('togglePassword').addEventListener('click', function () {
                const passwordField = document.getElementById('password');
                const icon = this.querySelector('i');

                // Toggle password visibility
                if (passwordField.type === 'password') {
                    passwordField.type = 'text'; // Show password
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password'; // Hide password
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        // You can retrieve the user_id from localStorage if needed
        let userId = localStorage.getItem('user_id');
        console.log('Logged in User ID from localStorage: ', userId);
    </script>
      <!--//meta tags ends here-->
      <!--booststrap-->
      <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
      <!--//booststrap end-->
      <!-- font-awesome icons -->
      <link href="css/fontawesome-all.min.css" rel="stylesheet" type="text/css" media="all">
      <!-- //font-awesome icons -->
      <!--Shoping cart-->
      <link rel="stylesheet" href="css/shop.css" type="text/css" />
      <!--//Shoping cart-->
      <!--stylesheets-->
      <link href="css/style.css" rel='stylesheet' type='text/css' media="all">
      <!--//stylesheets-->
      <link href="//fonts.googleapis.com/css?family=Sunflower:500,700" rel="stylesheet">
      <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
   
    <style>
        body {
            background-image: url('images/login-bg.jpg');
            background-size: cover;
            background-position: center;
            font-family: 'Open Sans', sans-serif;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            max-width: 400px;
            margin: 100px auto;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            color: #333;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 25px;
            padding-left: 40px;
            height: 45px;
            border: 1px solid #ccc;
            box-shadow: none;
        }

        .form-group {
            position: relative;
        }

        .form-group .fa {
            position: absolute;
            top: 70%;
            left: 10px;
            transform: translateY(-50%);
            color: #333;
        }

        .btn-primary {
            width: 100%;
            background: #5cb85c;
            border: none;
            border-radius: 25px;
            padding: 10px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background: #4cae4c;
        }

        .login-container p {
            text-align: center;
            margin-top: 15px;
        }

        .login-container a {
            color: #5cb85c;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .login-container .login-info {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!--headder-->
    <?php include_once('includes/home.php');?>
      <!-- banner -->
      <div class="inner_page-banner one-img">
      </div>
      <!--//banner -->
    <div class="container">
        <div class="login-container">
            <h2>Login</h2>
            <form method="post" action="login.php" onsubmit="return validateForm();">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <span class="fa fa-envelope"></span>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <span class="fa fa-lock"></span>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <span id="togglePassword" style="position: absolute; top: 70%; right: 40px; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                <!-- <a href="forgot-password.php">forgot password?</a> -->
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p><a href="forgot_password.php">forgot password?</a></p>
        </div>
        
    </div>
    <?php include_once('includes/footer.php');?>

    <script src="js/jquery-2.2.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
