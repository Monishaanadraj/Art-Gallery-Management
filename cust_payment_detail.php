<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['userid'];

// Query to fetch all orders for the logged-in user
$query = "SELECT co.ID, co.UserID, co.ProductTitle, co.Quantity, co.Price, co.TotalPrice, co.DeliveryAddress, 
          co.PaymentMethod, co.PaymentDetails, co.OrderDate
          FROM customizeorders co
          WHERE co.UserID = '$user_id'";

// Execute the query
$result = mysqli_query($con, $query);

// Check if the query executed successfully
if (!$result) {
    die("Error fetching orders: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
     <!--//meta tags ends here-->
      <!--booststrap-->
      <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
      <!--//booststrap end-->
      <!-- font-awesome icons -->
      <link href="css/fontawesome-all.min.css" rel="stylesheet" type="text/css" media="all">
      <!-- //font-awesome icons -->
      <!--Shoping cart-->
      <link rel="stylesheet" href="css/shop.css" type="text/css" />
      <!--//Shoping cart-->
      <!--stylesheets-->
      <link href="css/style.css" rel='stylesheet' type='text/css' media="all">
      <!--//stylesheets-->
      <link href="//fonts.googleapis.com/css?family=Sunflower:500,700" rel="stylesheet">
      <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
   
    <style>
        body {
            background-color: #f4f7f6;
        }
        .container {
            margin-top: 30px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }
        .table th {
            background-color: #007bff;
            color: white;
        } 
        .table tr:hover {
            background-color: #f1f1f1;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-color: #ffeeba;
        }
        .order-card {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .order-card h3 {
            color: #28a745;
        }
        .order-card .details {
            font-size: 14px;
            margin-top: 10px;
            color: #555;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
      <!--headder-->
  <?php include_once('includes/header.php');?>
      <!-- banner -->
      <div class="inner_page-banner one-img">
      </div>
      <!--//banner -->
<div class="container">
    <h2 class="text-center mb-4">Customized Arts Orders</h2>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6">
                    <div class="order-card">
                        <h3>Order ID: <?php echo $order['ID']; ?></h3>
                        <div class="details">
                            <p><strong>Product Title:</strong> <?php echo htmlspecialchars($order['ProductTitle']); ?><br></p>
                            <p><strong>Quantity:</strong> <?php echo $order['Quantity']; ?><br><p>
                            <p><strong>Price:</strong> ₹<?php echo number_format($order['Price'], 2); ?><br><p>
                            <p><strong>Total Price:</strong> ₹<?php echo number_format($order['TotalPrice'], 2); ?><br><p>
                            <p><strong>Delivery Address:</strong><br> <?php echo nl2br(htmlspecialchars($order['DeliveryAddress'])); ?><br><p>
                            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['PaymentMethod']); ?><br><p>
                            <p><strong>Payment Details:</strong> <?php echo htmlspecialchars($order['PaymentDetails']); ?><br><p>
                            <p><strong>Order Date:</strong> <?php echo date("d-m-Y H:i:s", strtotime($order['OrderDate'])); ?><p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">You have no orders yet.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php include_once('includes/footer.php');?>
</body>
</html>
