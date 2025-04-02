<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userid = $_SESSION['userid'];
    $total_amount = $_POST['total_amount'];
    $payment_method = $_POST['payment_method'];
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $zip = mysqli_real_escape_string($con, $_POST['zip']);

    // Insert order into orders table
    $query = "INSERT INTO orders (user_id, total_amount, payment_method, address, city, state, zip)
              VALUES ('$userid', '$total_amount', '$payment_method', '$address', '$city', '$state', '$zip')";
    
    if (mysqli_query($con, $query)) {
        $order_id = mysqli_insert_id($con);
        
        // Insert order items into order_items table
        foreach ($_SESSION['cart'] as $pid => $item) {
            $quantity = $item['quantity'];
            $price = $item['price'];
            $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price)
                                   VALUES ('$order_id', '$pid', '$quantity', '$price')";
            mysqli_query($con, $insert_item_query);
        }
        
        // Clear the cart
        unset($_SESSION['cart']);
        
        // Redirect to order confirmation page
        echo "<script>
                alert('Your order has been placed successfully!');
                window.location.href = 'order_confirmation.php?order_id=$order_id';
              </script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
