<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['userid'];

if (isset($_GET['cid'])) {
    $customization_id = $_GET['cid'];

    // Get the details of the customized art
    $query = "SELECT c.ID, c.TextOverlay, c.FrameStyle, c.Size, c.Status, p.Title AS ProductTitle, p.Image AS ProductImage, p.SellingPricing AS Price
              FROM tblartcustomizations c
              JOIN tblartproduct p ON c.ProductID = p.ID
              WHERE c.ID = '$customization_id' AND c.UserID = '$user_id' ";

    $result = mysqli_query($con, $query);

    // Check if the query executed successfully
    if (!$result) {
        // Display the error message if the query fails
        die("Error executing query: " . mysqli_error($con)); // This will show the MySQL error message
    }

    if (mysqli_num_rows($result) > 0) {
        // Fetch the customized art details
        $art = mysqli_fetch_assoc($result);

        // Add the customized art to the cartpage
        $cartpage_item = [
            'id' => $art['ID'],
            'product_title' => $art['ProductTitle'],
            'text_overlay' => $art['TextOverlay'],
            'frame_style' => $art['FrameStyle'],
            'size' => $art['Size'],
            'price' => $art['Price'],
            'image' => $art['ProductImage'],
            'quantity' => 1
        ];

        // Check if the cartpage is already set in session, if not, initialize it
        if (!isset($_SESSION['cartpage'])) {
            $_SESSION['cartpage'] = [];
        }

        // Check if the item already exists in the cartpage
        $exists = false;
        foreach ($_SESSION['cartpage'] as $index => $item) {
            if ($item['id'] == $cartpage_item['id']) {
                $_SESSION['cartpage'][$index]['quantity'] += 1;
                $exists = true;
                break;
            }
        }

        // If the item does not exist in the cartpage, add it
        if (!$exists) {
            $_SESSION['cartpage'][] = $cartpage_item;
        }

        // Redirect to the cartpage page or show a confirmation message
        header('Location: cartpage.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>This customized art is not available for purchase.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Invalid request.</div>";
}
?>
