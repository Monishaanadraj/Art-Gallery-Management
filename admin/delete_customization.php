<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is logged in as admin
if (!isset($_SESSION['agmsaid'])) {
    echo "<script>alert('Please log in as admin first.');</script>";
    echo "<script>window.location='login.php';</script>";
    exit();
}

// Get the customization ID from the URL
if (isset($_GET['id'])) {
    $customization_id = intval($_GET['id']);

    // Update the status of the customization to "Approved"
    $query = "UPDATE tblartcustomizations SET Status = 'Rejected' WHERE ID = ?";
    $stmt = mysqli_prepare($con, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $customization_id);
        $execute_result = mysqli_stmt_execute($stmt);
        
        if ($execute_result) {
            echo "<script>alert('Customization Rejected.');</script>";
            echo "<script>window.location='customized-art.php';</script>";
        } else {
            echo "<script>alert('Error.');</script>";
            echo "<script>window.location='customized-art.php';</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement.');</script>";
        echo "<script>window.location='customized-art.php';</script>";
    }
} else {
    echo "<script>alert('Invalid customization ID.');</script>";
    echo "<script>window.location='customized-art.php';</script>";
}

// Close the database connection
mysqli_close($con);
?>
