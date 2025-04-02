<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('You need to log in to perform this action.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

// Check if the art_id is provided
if (!isset($_GET['art_id']) || empty($_GET['art_id'])) {
    echo "<script>alert('Invalid request.');</script>";
    echo "<script>window.location.href='customized.php';</script>";
    exit();
}

$art_id = intval($_GET['art_id']); // Ensure the ID is an integer
$userid = $_SESSION['userid']; // Get the logged-in user's ID

// Check if the art belongs to the logged-in user
$check_query = "SELECT * FROM tblartcustomizations WHERE ID = $art_id AND UserID = $userid";
$check_result = mysqli_query($con, $check_query);

if (!$check_result || mysqli_num_rows($check_result) == 0) {
    echo "<script>alert('Art not found or you do not have permission to delete this item.');</script>";
    echo "<script>window.location.href='customized.php';</script>";
    exit();
}

// Delete the art
$delete_query = "DELETE FROM tblartcustomizations WHERE ID = $art_id AND UserID = $userid";
$delete_result = mysqli_query($con, $delete_query);

if ($delete_result) {
    echo "<script>alert('Art deleted successfully.');</script>";
} else {
    echo "<script>alert('Error deleting art: " . mysqli_error($con) . "');</script>";
}

// Redirect to the customized arts page
echo "<script>window.location.href='customized.php';</script>";
?>
