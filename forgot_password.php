<?php
error_reporting(0);
session_start();
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    // Get the email, name, new password, and confirm password from the form
    $email = $_POST['email'];
    $name = $_POST['name'];
    $new_password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);

    // Check if the email and name are correct in the database
    $query = "SELECT * FROM tblusers WHERE Email = '$email' AND FullName = '$name'";
    $result = mysqli_query($con, $query);

    // If email and name match a record in the database
    if (mysqli_num_rows($result) > 0) {
        // Check if new password and confirm password match
        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            // // Hash the new password
            // $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_query = "UPDATE tblusers SET Password = '$new_password' WHERE Email = '$email' AND FullName = '$name'";
            if (mysqli_query($con, $update_query)) {
                $success = "Your password has been successfully updated.";
            } else {
                $error = "There was an error updating your password. Please try again.";
            }
        }
    } else {
        $error = "No user found with the provided email and name.";
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Art Gallery Management System | Reset Password</title>
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
            max-width: 500px;
            margin: 50px auto;
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
        #togglePassword i {
            color: #333;
            font-size: 16px;
        }

        #togglePassword:hover i {
            color: #5cb85c;
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
    <script>
        
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

           
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('togglePassword').addEventListener('click', function () {
                const passwordField = document.getElementById('confirm_password');
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
    </script>
</head>
<body>
    <?php include_once('includes/home.php');?>
    <div class="inner_page-banner one-img">
    </div>
    <section class="reset-password py-5">
        <div class="container">
            <div class="login-container">
            <h2>Reset Your Password</h2>

            <!-- Display success or error messages -->
            <?php
            if (isset($error)) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
            if (isset($success)) {
                echo "<div class='alert alert-success'>$success</div>";
            }
            ?>

            <form action="forgot_password.php" method="post">
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Registered Email</label>
                    <span class="fa fa-envelope"></span>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Name Field -->
                <div class="form-group">
                    <label for="name">Registered Name</label>
                    <span class="fa fa-user"></span>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <!-- New Password Field -->
                <div class="form-group">
                    <label for="password">New Password</label>
                    <span class="fa fa-lock"></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                    
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <span class="fa fa-lock"></span>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Reset Password</button>
                <p><a href="login.php">Return to login</a></p>
            </form>
        </div>
        </div>
    </section>

    <?php include_once('includes/footer.php');?>

    <script src="js/jquery-2.2.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
