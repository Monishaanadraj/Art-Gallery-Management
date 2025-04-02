<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is logged in as admin
if (!isset($_SESSION['agmsaid'])) {
    echo "<script>alert('Please log in as admin first.');</script>";
    echo "<script>window.location='login.php';</script>";
    exit();
}

// Check if order ID is provided and is numeric
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid order ID.</div>";
    exit();
}

$order_id = intval($_GET['id']);

// Fetch the current order status
$query = "SELECT status FROM orders WHERE id = $order_id";
$result = mysqli_query($con, $query);

if (!$result) {
    die("<div class='alert alert-danger'>Error fetching order status: " . mysqli_error($con) . "</div>");
}

// Check if the order exists
if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    exit();
}

$order = mysqli_fetch_assoc($result);

// Check if the order is already confirmed
if ($order['status'] == 'Confirmed') {
    echo "<div class='alert alert-info'>This order is already confirmed.</div>";
    echo "<script>setTimeout(() => { window.location.href = 'manage-order.php'; }, 2000);</script>";
    exit();
}

// Update the order status to "Confirmed"
$update_query = "UPDATE orders SET status = 'Confirmed' WHERE id = $order_id";
$update_result = mysqli_query($con, $update_query);

if ($update_result) {
    echo "<div class='alert alert-success'>Order has been successfully confirmed.</div>";
} else {
    echo "<div class='alert alert-danger'>Failed to confirm the order: " . mysqli_error($con) . "</div>";
}

// Redirect back to the manage orders page after a short delay
echo "<script>setTimeout(() => { window.location.href = 'manage-order.php'; }, 2000);</script>";
?>
