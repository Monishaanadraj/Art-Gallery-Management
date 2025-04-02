<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Get user id from session (assuming user login is handled)
$userid = $_SESSION['userid'];

if (!isset($userid)) {
    echo "<div class='alert alert-danger'>Please login to add items to your cartpage.</div>";
    exit();
}

// Initialize the cartpage if it's not already set
if (!isset($_SESSION['cartpage'])) {
    $_SESSION['cartpage'] = array();
}

// Add to cartpage
if (isset($_GET['action']) && $_GET['action'] == "add") {
    $pid = intval($_GET['pid']);

    // Check if the product is already in the cartpage
    if (isset($_SESSION['cartpage'][$pid])) {
        $_SESSION['cartpage'][$pid]['quantity']++;
    } else {
        // Add product to cartpage with default quantity 1
        $query = "SELECT * FROM tblartproduct WHERE ID = $pid";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['cartpage'][$pid] = array(
            "title" => $row['Title'],
            "image" => $row['Image'],
            "quantity" => 1,
            "price" => $row['SellingPricing'] // Add price here
        );
    }

    // Redirect back to the product page or show cartpage
    header('location: cartpage.php');
}

// Remove from cartpage
if (isset($_GET['action']) && $_GET['action'] == "remove") {
    $pid = intval($_GET['pid']);
    if (isset($_SESSION['cartpage'][$pid])) {
        unset($_SESSION['cartpage'][$pid]);
    }
    header('location: cartpage.php');
}

// Update cartpage Quantity
if (isset($_POST['update_cartpage'])) {
    foreach ($_POST['qty'] as $pid => $qty) {
        if ($qty == 0) {
            unset($_SESSION['cartpage'][$pid]); // Remove item if quantity is 0
        } else {
            $_SESSION['cartpage'][$pid]['quantity'] = $qty; // Update quantity
        }
    }
    header('location: cartpage.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your cartpage</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/fontawesome-all.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        h2 {
            color: #343a40;
            text-align: center;
            margin-bottom: 20px;
        }
        .card {
            margin: 15px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            height: 200px; /* Fixed height for all images */
            width: 100%; /* Set width to 100% to ensure it fits the card */
            object-fit: 200px; /* Ensure the image covers the entire area */
            border-radius: 10px 10px 0 0; /* Rounded top corners */
        }
        .btn-danger, .btn-success {
            margin-top: 10px;
        }
        .alert {
            margin-top: 20px;
        }
        .checkout-btn {
            margin-top: 20px;
            text-align: center;
            font-size: 1.2em;
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
    <div class="container py-lg-5">
        <h2>Your cartpage</h2>
        <div class="row">
            <?php
            if (!empty($_SESSION['cartpage'])) {
                foreach ($_SESSION['cartpage'] as $pid => $item) {
                    ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="admin/images/<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                <p class="card-text">
                                    Price: Rs.<?php echo number_format($item['price'], 2); ?><br>
                                    Quantity: 
                                    <form method="post" action="cartpage.php" class="d-inline">
                                        <input type="number" name="qty[<?php echo $pid; ?>]" value="<?php echo intval($item['quantity']); ?>" min="1" class="form-control d-inline" style="width: 70px;">
                                        <button type="submit" name="update_cartpage" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </p>
                                <a href="cartpage.php?action=remove&pid=<?php echo $pid; ?>" class="btn btn-danger">Remove</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12'><div class='alert alert-warning'>Your cartpage is empty!</div></div>";
            }
            ?>
        </div>
        <a href="process_payment.php" class="btn btn-success checkout-btn">Proceed to Checkout</a>
    </div>
    <?php include_once('includes/footer.php');?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
