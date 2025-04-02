<?php
session_start();
include('includes/dbconnection.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

// Check if the cart is empty
if (!isset($_SESSION['cartpage']) || count($_SESSION['cartpage']) === 0) {
    echo "<div class='alert alert-warning'>Your cart is empty. <a href='main.php'>Continue Shopping</a></div>";
    exit();
}

$user_id = $_SESSION['userid'];
$cart_items = $_SESSION['cartpage'];
$total_price = 0;

// Calculate total price
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Initialize variables
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $delivery_address = $_POST['delivery_address'];
    $payment_details = '';

    // Collect payment details based on the selected method
    if ($payment_method === 'Card') {
        // Combine card details into a single string to store in payment_details column
        $payment_details = "Card Number: " . $_POST['payment_details'] . ", Expiry: " . $_POST['card_expiry'] . ", CVV: " . $_POST['card_cvv'];
    } elseif ($payment_method === 'UPI') {
        // Store UPI details as payment details
        $payment_details = $_POST['payment_details']; // UPI ID
    } elseif ($payment_method === 'Cash on Delivery') {
        // For Cash on Delivery, leave payment details blank
        $payment_details = "Cash on Delivery";
    }

    // Validate input
    if (empty($delivery_address)) {
        $error = "Please provide a delivery address.";
    } elseif (empty($payment_method)) {
        $error = "Please select a payment method.";
    } elseif (($payment_method === 'Card' || $payment_method === 'UPI') && empty($payment_details)) {
        $error = "Please provide payment details.";
    } else {
        // Save order details to the database
        foreach ($cart_items as $item) {
            $query = "INSERT INTO customizeorders (UserID, ProductTitle, Quantity, Price, TotalPrice, DeliveryAddress, OrderDate, PaymentMethod, PaymentDetails)
                      VALUES ('$user_id', '{$item['product_title']}', '{$item['quantity']}', '{$item['price']}', '$total_price', '$delivery_address', NOW(),'$payment_method', '$payment_details')";

            if (!mysqli_query($con, $query)) {
                die("Error saving order: " . mysqli_error($con));
            }
        }

        // Clear the cart after successful order
        unset($_SESSION['cartpage']);
        $success = "Your payment was successful, and the order has been placed! <a href='main.php'>Continue Shopping</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .hidden {
            display: none;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">Payment Processing</h2>
    
    <!-- Display error or success messages -->
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>
    <?php if (!empty($success)) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } else { ?>
        <h4>Total Price: ₹<?php echo number_format($total_price, 2); ?></h4>
        
        <!-- Payment form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="delivery_address">Delivery Address:</label>
                <textarea id="delivery_address" name="delivery_address" class="form-control" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="payment_method">Select Payment Method:</label>
                <select id="payment_method" name="payment_method" class="form-control" onchange="togglePaymentDetails(this.value)" required>
                    <option value="">Select</option>
                    <option value="Card">Card</option>
                    <option value="UPI">UPI</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                </select>
            </div>

            <!-- Card Details -->
            <div id="card-details" class="hidden">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="payment_details" class="form-control" maxlength="19" placeholder="#### #### #### ####" oninput="formatCardNumber(this)">
                <span id="card-number-error" class="error"></span><br>

                <label for="card_expiry">Expiry Date:</label>
                <input type="text" id="card_expiry" name="card_expiry" class="form-control" maxlength="5" placeholder="MM/YY" oninput="formatExpiryDate(this)">
                <span id="card-expiry-error" class="error"></span><br>

                <label for="card_cvv">CVV:</label>
                <input type="password" id="card_cvv" name="card_cvv" class="form-control">
                <span id="card-cvv-error" class="error"></span><br>
            </div>

            <!-- UPI Details -->
            <div id="upi-details" class="hidden">
                <h4>UPI Details</h4>
                <label for="upi_id">UPI ID:</label>
                <input type="text" id="upi_id" name="payment_details" class="form-control">
                <span id="upi-error" class="error"></span><br>
            </div>

            <button type="submit" class="btn btn-success btn-block">Confirm Order</button>
        </form>
    <?php } ?>
    <a href="cartpage.php" class="btn btn-secondary mt-3">Back to Cart</a>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
    function togglePaymentDetails(method) {
        document.getElementById('card-details').classList.add('hidden');
        document.getElementById('upi-details').classList.add('hidden');

        if (method === 'Card') {
            document.getElementById('card-details').classList.remove('hidden');
        } else if (method === 'UPI') {
            document.getElementById('upi-details').classList.remove('hidden');
        }
    }

    // Format card number input (grouping in sets of 4 digits)
    function formatCardNumber(input) {
        let value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
        value = value.substring(0, 16); // Limit to 16 digits
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        input.value = formattedValue; // Set the formatted value
    }

    // Format expiry date input (MM/YY)
    function formatExpiryDate(input) {
        let value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
        value = value.substring(0, 4); // Limit to 4 digits
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        input.value = value; // Set the formatted value
    }
</script>
</body>
</html>
