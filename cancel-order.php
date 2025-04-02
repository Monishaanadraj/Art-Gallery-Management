<?php
session_start();
include('includes/dbconnection.php');

// Check if order_id is set in the URL and is an integer
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "<script>alert('Invalid order ID.'); window.location.href='manage_orders.php';</script>";
    exit();
}

$order_id = intval($_GET['order_id']);

// Check if the order is pending before canceling
$query = "SELECT status FROM orders WHERE id = $order_id";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result);
    
    if ($order['status'] == 'Pending') {
        // Update the order status to 'Cancelled'
        $update_query = "UPDATE orders SET status='Cancelled' WHERE id = $order_id";
        $update_result = mysqli_query($con, $update_query);

        if ($update_result) {
            echo "<script>alert('Order has been cancelled successfully.'); window.location.href='order_confirmation.php?order_id=$order_id';</script>";
        } else {
            echo "<script>alert('Failed to cancel the order. Please try again.'); window.location.href='order_confirmation.php?order_id=$order_id';</script>";
        }
    } else {
        echo "<script>alert('This order has already been confirmed and cannot be cancelled.'); window.location.href='order_confirmation.php?order_id=$order_id';</script>";
    }
} else {
    echo "<script>alert('Order not found.'); window.location.href='manage_orders.php';</script>";
}
?>
