<?php
session_start();
include('includes/dbconnection.php');

// Retrieve contact information
$ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
$contactInfo = mysqli_fetch_array($ret);
?>

<div class="header-bar">
    <div class="info-top-grid">
        <div class="info-contact-agile">
            <ul>
                <li>
                    <span class="fas fa-phone-volume"></span>
                    <p><?php echo $contactInfo['MobileNumber']; ?></p>
                </li>
                <li>
                    <span class="fas fa-envelope"></span>
                    <p><?php echo $contactInfo['Email']; ?></p>
                </li>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <div class="hedder-up row">
            <div class="col-lg-3 col-md-3 logo-head">
                <h1><a class="navbar-brand" href="index.php">Art Gallery</a></h1>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Art Type
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                        $artTypes = mysqli_query($con, "SELECT * FROM tblarttype");
                        while ($row = mysqli_fetch_array($artTypes)) {
                        ?>
                            <a class="nav-link" href="product.php?cid=<?php echo $row['ID']; ?>&&artname=<?php echo $row['ArtType']; ?>"><?php echo $row['ArtType']; ?></a>
                        <?php } ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="cart.php" class="nav-link">Cart</a>
                </li>
                <li class="nav-item">
                    <a href="myorder.php" class="nav-link">My Orders</a>
                </li>
                <li class="nav-item">
                    <a href="customized.php" class="nav-link">Customized Arts</a>
                </li>
                <li class="nav-item">
                    <a href="cust_payment_detail.php" class="nav-link">Customized Arts Payment</a>
                </li>

                <!-- User Login Button -->
                <li class="dropdown" style="position: absolute; right: 50px;">
                    <?php
                    $ret = mysqli_query($con, "SELECT FullName FROM tblusers WHERE ID='$ID'");
                    $row = mysqli_fetch_array($ret);
                    $name = $row['FullName'];
                    ?>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#" style="display: flex; align-items: center; padding: 10px; border-radius: 8px; text-decoration: none;">
                        <span class="profile-ava" style="margin-right: 0;">
                            <img alt="" src="admin/images/av1.jpg" width="40" height="30" style="border-radius: 20%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        </span>
                        <span class="username" style="color:#000; font-weight: bold; font-size: 16px; padding:5px">User</span>
                        <b class="caret" style="margin-left:0; color:#000;"></b>
                    </a>
                    <ul class="dropdown-menu extended logout" style="right: 50px; background-color: #fff; border-radius: 8px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); margin-top: 5px; min-width: 180px;">
                        <div class="log-arrow-up"></div>
                        <li style="padding: 8px;">
                            <a href="logout.php" style="color: #000; text-decoration: none; display: flex; align-items: center;">
                                <i class="icon_key_alt" style="margin-right: 8px; font-size: 18px;"></i> Log Out
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="register-form">
                    <form action="login.php" method="post"> <!-- Update action to your login processing file -->
                        <div class="fields-grid">
                            <div class="styled-input">
                                <input type="email" placeholder="Your Email" name="email" required="">
                            </div>
                            <div class="styled-input">
                                <input type="password" placeholder="Password" name="password" required="">
                            </div>
                            <button type="submit" class="btn subscrib-btnn">Login</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- //Login Modal -->
