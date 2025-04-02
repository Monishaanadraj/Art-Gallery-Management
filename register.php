<?php
session_start();
include('includes/dbconnection.php');

// If form is submitted
if (isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, md5($_POST['password'])); // Encrypting the password with md5

    // Check if the user already exists
    $check_query = "SELECT * FROM tblusers WHERE Email='$email'";
    $check_result = mysqli_query($con, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        // Insert new user into the database
        $query = "INSERT INTO tblusers (FullName, Email, Password) VALUES ('$fullname', '$email', '$password')";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Art Gallery Management System | Register</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/fontawesome-all.min.css" rel="stylesheet" type="text/css" media="all">
    <style>
        body {
            background-image: url('images/register-bg.jpg'); /* Replace with a suitable image */
            background-size: cover;
            background-position: center;
            font-family: 'Poppins', sans-serif;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            max-width: 450px;
            margin: 100px auto;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.5);
        }

        .register-container h2 {
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

        #togglePassword i {
            color: #333;
            font-size: 16px;
        }

        #togglePassword:hover i {
            color: #5cb85c;
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

        .register-container p {
            text-align: center;
            margin-top: 15px;
        }

        .register-container a {
            color: #5cb85c;
            text-decoration: none;
        }

        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // Validate form inputs
        function validateForm() {
            const fullname = document.getElementById('fullname').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            const nameRegex = /^[a-zA-Z ]+$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordRegex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{6,}$/;

            if (!nameRegex.test(fullname)) {
                alert("Full Name must contain only letters and spaces.");
                return false;
            }

            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            if (!passwordRegex.test(password)) {
                alert("Password must be at least 6 characters long, with at least one number, one uppercase letter, and one special character.");
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
    </script>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h2>Register</h2>
            <form method="post" action="register.php" onsubmit="return validateForm();">
                <div class="form-group">
                    <label for="fullname">Full Name:</label>
                    <span class="fa fa-user"></span>
                    <input type="text" id="fullname" name="fullname" class="form-control" required>
                </div>
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
                <button type="submit" name="register" class="btn btn-primary">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
