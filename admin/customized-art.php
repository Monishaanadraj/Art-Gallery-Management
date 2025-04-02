<?php
session_start();
error_reporting(0); // Enable error reporting for debugging
include('includes/dbconnection.php');

// Check if the user is logged in as admin
if (!isset($_SESSION['agmsaid'])) {
    echo "<script>alert('Please log in as admin first.');</script>";
    echo "<script>window.location='login.php';</script>";
    exit();
}

// Fetch all customizations
$query = "SELECT c.ID, p.Title, c.FrameStyle, c.TextOverlay, c.Size, c.Status, c.CreatedAt, u.FullName AS UserName, u.Email AS UserEmail
          FROM tblartcustomizations c
          JOIN tblartproduct p ON c.ProductID = p.ID
          JOIN tblusers u ON c.UserID = u.ID
          ORDER BY c.CreatedAt ASC";
$result = mysqli_query($con, $query);

// Check if any results are returned
if (!$result) {
    die("Error fetching customizations: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
    <title>Manage Customized Arts | Art Gallery Management System</title>
    <style>
        #container {
            color: #000;
        }
        .panel {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- container section start -->
    <section id="container">
        <!--header start-->
        <?php include_once('includes/header.php'); ?>
        <!--header end-->

        <!--sidebar start-->
        <?php include_once('includes/sidebar.php'); ?>
        <!--sidebar end-->

        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-table"></i> Manage Customized Arts</h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
                            <li><i class="fa fa-table"></i>Manage Customized Arts</li>
                        </ol>
                    </div>
                </div>
                <!-- page start-->
                <div class="row">
                    <div class="col-sm-12">
                        <section class="panel">
                            <header class="panel-heading">
                                Manage Customized Art
                            </header>
                            <div class="table-responsive">
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Painting Title</th>
                                                <th>Frame Style</th>
                                                <th>Custom Text</th>
                                                <th>Size</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>User Name</th>
                                                <th>User Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                <tr>
                                                    <td><?php echo $row['ID']; ?></td>
                                                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['FrameStyle']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['TextOverlay']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['Size']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['CreatedAt']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['UserName']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['UserEmail']); ?></td>
                                                    <td>
                                                        <a href="approve_customization.php?id=<?php echo $row['ID']; ?>" class="btn btn-success">Approve</a> ||
                                                        <a href="delete_customization.php?id=<?php echo $row['ID']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to Reject this customization?');">Reject</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p>No customizations found.</p>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                </div>
                <!-- page end-->
            </section>
        </section>
        <!--main content end-->

        <!-- footer -->
        <?php include_once('includes/footer.php'); ?>
    </section>
    <!-- container section end -->

    <!-- javascripts -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
