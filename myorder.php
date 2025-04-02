<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<div class='alert alert-danger'>You need to log in to view your orders.</div>";
    exit();
}

$userid = $_SESSION['userid'];

// Fetch ordered items for the logged-in user
$order_items_query = "SELECT oi.*, o.id AS order_id, p.Title AS product_name FROM order_items oi
                      JOIN orders o ON oi.order_id = o.id
                      JOIN tblartproduct p ON oi.product_id = p.ID 
                      WHERE o.user_id = $userid";
$order_items_result = mysqli_query($con, $order_items_query);

if (!$order_items_result) {
    die("Error executing query: " . mysqli_error($con));
}

// Check if there are ordered items
if (mysqli_num_rows($order_items_result) == 0) {
    echo "<div class='alert alert-warning'>No items found for your orders.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Ordered Items</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <script>
         addEventListener("load", function () {
         	setTimeout(hideURLbar, 0);
         }, false);
         
         function hideURLbar() {
         	window.scrollTo(0, 1);
         }
      </script>
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
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            margin-bottom: 52px;
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
    <h2>Your Ordered Items</h2>
    <ul class="list-group">
        <?php while ($item = mysqli_fetch_assoc($order_items_result)) { ?>
            <li class="list-group-item">
                <span><?php echo htmlspecialchars($item['product_name']); ?> - Quantity: <?php echo $item['quantity']; ?></span>
                <span>Rs. <?php echo number_format($item['price'], 2); ?></span>
                <span><a href="order_confirmation.php?order_id=<?php echo $item['order_id']; ?>" class="btn btn-info btn-sm">View Order</a></span>
            </li>
        <?php } ?>
    </ul>
    <a href="main.php" class="btn btn-success mt-3">Back to Home</a>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<?php include_once('includes/footer.php');?>
</body>
</html>
