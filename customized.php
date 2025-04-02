<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');



// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<div class='alert alert-danger'>You need to log in to view your customized arts.</div>";
    exit();
}

$userid = $_SESSION['userid'];


// Fetch customized arts for the logged-in user with the product image
$customized_arts_query = "SELECT c.ID, c.TextOverlay, c.FrameStyle, c.Size, c.Status, c.CreatedAt, p.Title AS ProductTitle, p.Image AS ProductImage
    FROM tblartcustomizations c
    JOIN tblartproduct p ON c.ProductID = p.ID
    WHERE c.UserID = '$userid'"; // Ensure the UserID is correctly filtered

$customized_arts_result = mysqli_query($con, $customized_arts_query);

if (!$customized_arts_result) {
    die("Error executing query: " . mysqli_error($con)); // Error handling
}

// Check if there are any customized arts
if (mysqli_num_rows($customized_arts_result) == 0) {
    echo "<div class='alert alert-warning'>No customized arts found for your account.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Customized Arts</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/fontawesome-all.min.css" rel="stylesheet" type="text/css" media="all">
    <link rel="stylesheet" href="css/shop.css" type="text/css" />
    <link href="css/style.css" rel='stylesheet' type='text/css' media="all">
    <link href="//fonts.googleapis.com/css?family=Sunflower:500,700" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 30px;
            margin-bottom: 30px;
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
        .art-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8f9fa;
        }
        .art-item img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 5px;
            margin-right: 15px;
        }
        .art-details {
            flex: 1;
        }
        .btn-group {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
<!--header-->
<?php include_once('includes/header.php'); ?>
<div class="inner_page-banner one-img"></div>
<div class="container">
    <h2>Your Customized Arts</h2>
    <?php while ($art = mysqli_fetch_assoc($customized_arts_result)) { ?>
        <div class="art-item">
            <img src="admin/images/<?php echo htmlspecialchars($art['ProductImage']); ?>" alt="<?php echo htmlspecialchars($art['ProductTitle']); ?>" class="img-thumbnail" />
            <div class="art-details">
                <h5><?php echo htmlspecialchars($art['ProductTitle']); ?></h5>
                <p><strong>Custom Text: </strong><?php echo htmlspecialchars($art['TextOverlay']); ?></p>
                <p><strong>Frame Style: </strong><?php echo htmlspecialchars($art['FrameStyle']); ?></p>
                <p><strong>Size: </strong><?php echo htmlspecialchars($art['Size']); ?></p>
                <p><strong>Status: </strong><?php echo htmlspecialchars($art['Status']); ?></p>
            </div>
            <div class="btn-group">
                <?php if ($art['Status'] == 'Pending') { ?>
                    <a href="delete_art.php?art_id=<?php echo $art['ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this art?');">Cancel</a>
                <?php } elseif ($art['Status'] == 'Approved') { ?>
                    <a href="add-to-cart.php?cid=<?php echo $art['ID']; ?>" class="btn btn-success btn-sm">Add to Cart</a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <a href="main.php" class="btn btn-success mt-3">Back to Home</a>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<?php include_once('includes/footer.php');?>
</body>
</html>
