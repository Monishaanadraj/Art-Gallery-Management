<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<div class='alert alert-danger'>You need to log in to view your orders.</div>";
    exit();
}

$userid = $_SESSION['userid'];

// Check if 'pid' is passed in the URL and sanitize it
if (isset($_GET['pid'])) {
    $pid = intval($_GET['pid']); // Ensures pid is an integer
} else {
    echo "Error: Painting not found.";
    exit();
}

// Fetch painting details if pid is valid
$query = "SELECT * FROM tblartproduct WHERE ID='$pid'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $painting = mysqli_fetch_assoc($result);
} else {
    echo "Error: Painting not found.";
    exit();
}

// Handle form submission
if (isset($_POST['customize'])) {
    $frameStyle = $_POST['frame_style'];
    $textOverlay = $_POST['text_overlay'];
    $size = $_POST['size'];

    // Insert customization into the database
    $query = "INSERT INTO tblartcustomizations (ProductID, FrameStyle, TextOverlay, Size, UserID) 
              VALUES ('$pid', '$frameStyle', '$textOverlay', '$size','$userid')";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Customization saved successfully!');
         window.location.href = 'main.php';
        </script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($con) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customize Painting</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .customize-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        }
        .customize-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .painting-img {
            max-width: 100%;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }
        .painting-img:hover {
            transform: scale(1.05);
        }
        .btn-custom {
            background-color: #28a745;
            color: #fff;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="customize-container">
                    <h2 class="customize-header">Customize Painting: <?php echo htmlspecialchars($painting['Title']); ?></h2>
                    <div class="text-center mb-4">
                        <img src="admin/images/<?php echo $painting['Image']; ?>" alt="Painting" class="painting-img">
                    </div>

                    <form method="POST" action="">
                        <div class="form-group mb-4">
                            <label for="frame_style" class="font-weight-bold">Select Frame Style:</label>
                            <select name="frame_style" id="frame_style" class="form-control">
                                <option value="Wood">Wood</option>
                                <option value="Metal">Metal</option>
                                <option value="No Frame">No Frame</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="text_overlay" class="font-weight-bold">Custom Text (optional):</label>
                            <input type="text" name="text_overlay" id="text_overlay" class="form-control" placeholder="Enter custom text">
                        </div>

                        <div class="form-group mb-4">
                            <label for="size" class="font-weight-bold">Select Size:</label>
                            <select name="size" id="size" class="form-control">
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Large">Large</option>
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="customize" class="btn btn-custom btn-lg">Save Customization</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
