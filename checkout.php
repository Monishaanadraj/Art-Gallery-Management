<?php
session_start();
include('includes/dbconnection.php');

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='alert alert-danger'>Your cart is empty!</div>";
    exit();
}

// Calculate the total amount
$userid = $_SESSION['userid'];
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/fontawesome-all.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #343a40;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .payment-option {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: background 0.3s;
            cursor: pointer;
        }
        .payment-option:hover {
            background-color: #f8f9fa;
        }
        .btn-success {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        .total-amount {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
            text-align: right;
        }
        .hidden {
            display: none;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Checkout</h2>
    <form action="process_order.php" method="POST" id="checkoutForm">
        <div class="total-amount">Total Amount: Rs. <?php echo number_format($total_amount, 2); ?></div>
        <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">

        <h3>Delivery Address</h3>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" class="form-control" required>
        <span id="address-error" class="error"></span><br>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" class="form-control" required>
        <span id="city-error" class="error"></span><br>

        <label for="state">State:</label>
        <input type="text" id="state" name="state" class="form-control" required>
        <span id="state-error" class="error"></span><br>

        <label for="zip">Zip Code:</label>
        <input type="text" id="zip" name="zip" class="form-control" required>
        <span id="zip-error" class="error"></span><br>

        <h3>Payment Method</h3>
        <div class="payment-option">
            <label>
                <input type="radio" name="payment_method" value="card" required onclick="togglePaymentDetails('card')"> Credit/Debit Card
            </label>
        </div>
        <div class="payment-option">
            <label>
                <input type="radio" name="payment_method" value="upi" required onclick="togglePaymentDetails('upi')"> UPI
            </label>
        </div>
        <div class="payment-option">
            <label>
                <input type="radio" name="payment_method" value="cod" required onclick="togglePaymentDetails('cod')"> Cash on Delivery
            </label>
        </div>
        <span id="payment-error" class="error"></span><br>

        <!-- Card Details -->
        <div id="card-details" class="hidden">
            <h4>Card Details</h4>
            <!-- Card Number -->
<label for="card_number">Card Number:</label>
<input type="text" id="card_number" name="card_number" class="form-control" maxlength="19" placeholder="#### #### #### ####" oninput="formatCardNumber(this)">
<span id="card-number-error" class="error"></span><br>

<!-- Expiry Date -->
<label for="card_expiry">Expiry Date:</label>
<input type="text" id="card_expiry" name="card_expiry" class="form-control" maxlength="5" placeholder="MM/YY" oninput="formatExpiryDate(this)">
<span id="card-expiry-error" class="error"></span><br>

<script>
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


            <label for="card_cvv">CVV:</label>
            <input type="password" id="card_cvv" name="card_cvv" class="form-control">
            <span id="card-cvv-error" class="error"></span><br>
        </div>

        <!-- UPI Details -->
        <div id="upi-details" class="hidden">
            <h4>UPI Details</h4>
            <label for="upi_id">UPI ID:</label>
            <input type="text" id="upi_id" name="upi_id" class="form-control">
            <span id="upi-error" class="error"></span><br>
        </div>

        <button type="submit" class="btn btn-success">Place Order</button>
    </form>
</div>

<script>
    function togglePaymentDetails(method) {
        document.getElementById('card-details').classList.add('hidden');
        document.getElementById('upi-details').classList.add('hidden');

        if (method === 'card') {
            document.getElementById('card-details').classList.remove('hidden');
        } else if (method === 'upi') {
            document.getElementById('upi-details').classList.remove('hidden');
        }
    }

    document.getElementById('checkoutForm').addEventListener('submit', function(event) {
        let isValid = true;

        // Validate Address
        const address = document.getElementById('address').value.trim();
        if (address.length < 5) {
            document.getElementById('address-error').innerText = "Address must be at least 5 characters.";
            isValid = false;
        } else {
            document.getElementById('address-error').innerText = "";
        }

        // Validate City
        const city = document.getElementById('city').value.trim();
        if (!/^[a-zA-Z\s]+$/.test(city)) {
            document.getElementById('city-error').innerText = "City must contain only letters and spaces.";
            isValid = false;
        } else {
            document.getElementById('city-error').innerText = "";
        }

        // Validate State
        const state = document.getElementById('state').value.trim();
        if (!/^[a-zA-Z\s]+$/.test(state)) {
            document.getElementById('state-error').innerText = "State must contain only letters and spaces.";
            isValid = false;
        } else {
            document.getElementById('state-error').innerText = "";
        }

        // Validate Zip Code
        const zip = document.getElementById('zip').value.trim();
        if (!/^\d{6}$/.test(zip)) {
            document.getElementById('zip-error').innerText = "Zip code must be exactly 6 digits.";
            isValid = false;
        } else {
            document.getElementById('zip-error').innerText = "";
        }

        // Validate Payment Method
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            document.getElementById('payment-error').innerText = "Please select a payment method.";
            isValid = false;
        } else {
            document.getElementById('payment-error').innerText = "";
        }

        // Validate Card Details if Card is selected
        if (paymentMethod && paymentMethod.value === 'card') {
            // const cardNumber = document.getElementById('card_number').value.trim();
            // const cardExpiry = document.getElementById('card_expiry').value.trim();
            const cardCVV = document.getElementById('card_cvv').value.trim();

            // if (!/^\d{19}$/.test(cardNumber)) {
            //     document.getElementById('card-number-error').innerText = "Card number must be 19 digits.";
            //     isValid = false;
            // } else {
            //     document.getElementById('card-number-error').innerText = "";
            // }

            // if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(cardExpiry)) {
            //     document.getElementById('card-expiry-error').innerText = "Expiry date must be in MM/YY format.";
            //     isValid = false;
            // } else {
            //     document.getElementById('card-expiry-error').innerText = "";
            // }

            if (!/^\d{3}$/.test(cardCVV)) {
                document.getElementById('card-cvv-error').innerText = "CVV must be 3 digits.";
                isValid = false;
            } else {
                document.getElementById('card-cvv-error').innerText = "";
            }
        }

        // Validate UPI Details if UPI is selected
        if (paymentMethod && paymentMethod.value === 'upi') {
            const upiId = document.getElementById('upi_id').value.trim();
            if (!/^[\w\.-]+@[\w\.-]+$/.test(upiId)) {
                document.getElementById('upi-error').innerText = "UPI ID format is invalid.";
                isValid = false;
            } else {
                document.getElementById('upi-error').innerText = "";
            }
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>
