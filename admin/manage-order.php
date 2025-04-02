<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in as admin
if (!isset($_SESSION['agmsaid'])) {
    echo "<script>alert('Please log in as admin first.');</script>";
    echo "<script>window.location='login.php';</script>";
    exit();
}

// Fetch all orders
$query = "SELECT o.id AS order_id, u.fullname, o.order_date, o.status, SUM(oi.price * oi.quantity) AS total_price 
          FROM orders o 
          JOIN tblusers u ON o.user_id = u.id 
          JOIN order_items oi ON o.id = oi.order_id 
          GROUP BY o.id ";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Error executing query: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | Art Gallery Management System</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
    <style>
        #container {
            color: #000;
        }
    </style>
</head>
<body>

<section id="container" class="">
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/sidebar.php'); ?>

    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-table"></i> Manage Orders</h3>
                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
                        <li><i class="fa fa-table"></i>Manage Orders</li>
                        <li><i class="fa fa-th-list"></i>Manage Orders</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Manage Orders
                        </header>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User</th>
                                        <th>Order Date</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo $row['order_id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                                            <td>Rs. <?php echo number_format($row['total_price'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                                            <td>
                                            <?php if ($row['status'] == 'Pending'): ?>
                                                <a href="confirm_order.php?id=<?php echo $row['order_id']; ?>" class="btn btn-success">Confirm</a>
                                                    || <a href="cancel_order.php?id=<?php echo $row['order_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning">No orders found.</div>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </section>
    </section>
    <?php include_once('includes/footer.php'); ?>
</section>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
