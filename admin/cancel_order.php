<?php
session_start();
include('includes/dbconnection.php');

// Check if order ID is set
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Update the order status to 'Cancelled'
    $query = "UPDATE orders SET status='Cancelled' WHERE id='$order_id' AND status='Pending'";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo "<script>alert('Order has been cancelled successfully.');</script>";
    } else {
        echo "<script>alert('Failed to cancel the order. Please try again.');</script>";
    }
    echo "<script>window.location.href='manage-order.php';</script>";
} else {
    echo "<script>alert('Invalid request.');</script>";
    echo "<script>window.location.href='manage-order.php';</script>";
}
?>
