<?php
session_start();
include('includes/dbconnection.php');

// Check if 'pid' is passed in the URL and sanitize it
if (isset($_GET['pid'])) {
    $pid = intval($_GET['pid']); // Ensures pid is an integer
} else {
    echo "Error: Painting not found.";
    exit();
}

// Fetch painting details
$queryPainting = "SELECT * FROM tblartproduct WHERE ID='$pid'";
$resultPainting = mysqli_query($con, $queryPainting);

if (mysqli_num_rows($resultPainting) > 0) {
    $painting = mysqli_fetch_assoc($resultPainting);
} else {
    echo "Error: Painting not found.";
    exit();
}

// Fetch customization details
$queryCustom = "SELECT * FROM tblartcustomizations WHERE ProductID='$pid'";
$resultCustom = mysqli_query($con, $queryCustom);

if (mysqli_num_rows($resultCustom) > 0) {
    $customization = mysqli_fetch_assoc($resultCustom);
} else {
    echo "Error: No customizations found for this painting.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Customized Artwork</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .customize-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .painting-img {
            max-width: 100%;
            border-radius: 10px;
        }
        .details-container {
            margin:20px;
            padding: 20px;
            border-radius: 10px;
        }
        .details-container h4 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #007bff;
        }
        .details-list {
            list-style-type: none;
            padding: 0;
            font-size: 1.1rem;
        }
        .details-list li {
            margin-bottom: 15px;
            color: #333;
        }
        .details-list li strong {
            color: #007bff;
        }
        .btn-back {
            position:absolute;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-back:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="customize-container">
                    <div class="row">
                        <!-- Left Column: Image -->
                        <div class="col-md-6">
                            <img src="admin/images/<?php echo $painting['Image']; ?>" alt="Your Artwork" class="painting-img mb-4">
                        </div>

                        <!-- Right Column: Customization Details -->
                        <div class="col-md-6">
                            <h4>Customization Highlights</h4>
                            <p>Discover the unique features of your personalized artwork:</p>
                            <ul class="details-list">
                                <li><strong>Frame Style:</strong> <?php echo htmlspecialchars($customization['FrameStyle']); ?></li>
                                <li><strong>Custom Text:</strong> "<?php echo htmlspecialchars($customization['TextOverlay']); ?>"</li>
                                <li><strong>Size:</strong> <?php echo htmlspecialchars($customization['Size']); ?></li>
                            </ul>
                            <p>Your artistic expression is now a reality. This piece is tailored to enhance your space and showcase your individuality.</p>
                            <a href="product.php" class="btn-back mt-4">Back to Gallery</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
