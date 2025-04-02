<?php
include('includes/dbconnection.php');

// Check if order_id is set in the URL and is an integer
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "<div class='alert alert-danger'>Invalid order ID.</div>";
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$query = "SELECT o.*, u.fullname FROM orders o
          JOIN tblusers u ON o.user_id = u.id WHERE o.id = $order_id";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Error executing query: " . mysqli_error($con));
}

$order = mysqli_fetch_assoc($result);

// Check if order exists
if (!$order) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    exit();
}

// Fetch ordered items
$order_items_query = "SELECT oi.*, p.Title AS product_name FROM order_items oi
                      JOIN tblartproduct p ON oi.product_id = p.ID WHERE oi.order_id = $order_id";
$order_items_result = mysqli_query($con, $order_items_query);

if (!$order_items_result) {
    die("Error executing query: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
        }
        h5 {
            color: #555;
        }
        .order-details {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .order-details h6 {
            margin: 5px 0;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .list-group-item:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .list-group-item:nth-child(even) {
            background-color: #ffffff;
        }
        .btn {
            margin-top: 20px;
            width: 100%;
            background-color: #28a745;
            color: white;
        }
        .btn:hover {
            background-color: #218838;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: white;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($order['status'] == 'Confirmed'): ?>
        <!-- Display message for confirmed order -->
        <h2>Thank You for Your Order!</h2>
        <h5>Your order details are shown below:</h5>
        <div class="order-details">
            <h6>Order ID: <strong><?php echo $order['id']; ?></strong></h6>
            <h6>Total Amount: <strong>Rs. <?php echo number_format($order['total_amount'], 2); ?></strong></h6>
            <h6>Payment Method: <strong><?php echo ucfirst($order['payment_method']); ?></strong></h6>
            <h6>Status: <strong><?php echo ucfirst($order['status']); ?></strong></h6>
            <h6>Date of Ordered: <strong><?php echo ucfirst($order['order_date']); ?></strong></h6>
            <h6>Delivery Address:</h6>
            <p><?php echo htmlspecialchars($order['address']) . ", " . htmlspecialchars($order['city']) . ", " . htmlspecialchars($order['state']) . ", " . htmlspecialchars($order['zip']); ?></p>
        </div>

        <h3>Ordered Items</h3>
        <ul class="list-group">
            <?php while ($item = mysqli_fetch_assoc($order_items_result)) { ?>
                <li class="list-group-item">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> - Quantity: <?php echo $item['quantity']; ?></span>
                    <span>Rs. <?php echo number_format($item['price'], 2); ?></span>
                </li>
            <?php } ?>
        </ul>

    <?php elseif ($order['status'] == 'Cancelled'): ?>
        <!-- Display message for canceled order -->
        <h2>Your Order is Canceled</h2>
        <h5>The details of your canceled order are shown below:</h5>
        <div class="order-details">
            <h6>Order ID: <strong><?php echo $order['id']; ?></strong></h6>
            <h6>Total Amount: <strong>Rs. <?php echo number_format($order['total_amount'], 2); ?></strong></h6>
            <h6>Payment Method: <strong><?php echo ucfirst($order['payment_method']); ?></strong></h6>
            <h6>Status: <strong>Canceled</strong></h6>
            <h6>Date of Ordered: <strong><?php echo ucfirst($order['order_date']); ?></strong></h6>
            <h6>Delivery Address:</h6>
            <p><?php echo htmlspecialchars($order['address']) . ", " . htmlspecialchars($order['city']) . ", " . htmlspecialchars($order['state']) . ", " . htmlspecialchars($order['zip']); ?></p>
        </div>

        <h3>Canceled Items</h3>
        <ul class="list-group">
            <?php while ($item = mysqli_fetch_assoc($order_items_result)) { ?>
                <li class="list-group-item">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> - Quantity: <?php echo $item['quantity']; ?></span>
                    <span>Rs. <?php echo number_format($item['price'], 2); ?></span>
                </li>
            <?php } ?>
        </ul>

    <?php else: ?>
        <!-- Display message for other order statuses, if needed -->
        <h2>Order Status: <?php echo ucfirst($order['status']); ?></h2>
        <div class="order-details">
            <h6>Order ID: <strong><?php echo $order['id']; ?></strong></h6>
            <h6>Total Amount: <strong>Rs. <?php echo number_format($order['total_amount'], 2); ?></strong></h6>
            <h6>Payment Method: <strong><?php echo ucfirst($order['payment_method']); ?></strong></h6>
            <h6>Status: <strong><?php echo ucfirst($order['status']); ?></strong></h6>
            <h6>Date of Ordered: <strong><?php echo ucfirst($order['order_date']); ?></strong></h6>
            <h6>Delivery Address:</h6>
            <p><?php echo htmlspecialchars($order['address']) . ", " . htmlspecialchars($order['city']) . ", " . htmlspecialchars($order['state']) . ", " . htmlspecialchars($order['zip']); ?></p>
        </div>

        <h3>Ordered Items</h3>
        <ul class="list-group">
            <?php while ($item = mysqli_fetch_assoc($order_items_result)) { ?>
                <li class="list-group-item">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> - Quantity: <?php echo $item['quantity']; ?></span>
                    <span>Rs. <?php echo number_format($item['price'], 2); ?></span>
                </li>
            <?php } ?>
        </ul>
    <?php endif; ?>
    <?php if ($order['status'] == 'Pending'): ?>
            <!-- Cancel Button (only for pending orders) -->
            <a href="cancel-order.php?order_id=<?php echo $order_id; ?>" class="btn cancel-btn" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel Order</a>
        <?php endif; ?>

    <a href="product.php" class="btn">Continue Shopping</a>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
