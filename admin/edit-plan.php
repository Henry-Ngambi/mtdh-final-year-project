<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
    exit;
}

// Initialize message variable
$msg = '';

// Handle form submission for editing a subscription
if (isset($_POST['submit'])) {
    $plan_id = $_GET['plan_id']; // Get plan_id from the query string
    $plan_name = mysqli_real_escape_string($con, $_POST['plan_name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);

    // Update the subscription plan in the database
    $query = mysqli_query($con, "UPDATE subscription_plan SET plan_name='$plan_name', description='$description', price='$price', updated_at=NOW() WHERE plan_id='$plan_id'");

    if ($query) {
        $msg = "Subscription plan updated successfully!";
    } else {
        $msg = "Error updating subscription plan: " . mysqli_error($con);
    }
}

// Fetch the current subscription plan details
$plan_id = $_GET['plan_id'];
$planQuery = mysqli_query($con, "SELECT * FROM subscription_plan WHERE plan_id='$plan_id'");
$plan = mysqli_fetch_array($planQuery);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Edit Subscription Plan</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/custom.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/wow.min.js"></script>
    <script>new WOW().init();</script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <style>
        .header-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .footer {
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <!-- Main content start -->
        <div id="page-wrapper">
            <div class="main-page">
                <div class="forms">
                    <div class="header-buttons">
                        <h3 class="title1">Edit Subscription Plan</h3>
                        <a href="create-subscriptions.php" class="btn btn-primary">Go Back</a>
                    </div>

                    <?php if (!empty($msg)) { echo "<p style='color: green;'>$msg</p>"; } ?>

                    <form method="post" action="">
                        <input type="hidden" name="plan_id" value="<?php echo $plan['plan_id']; ?>">
                        <div class="form-group">
                            <label for="plan_name">Plan Name:</label>
                            <select name="plan_name" id="plan_name" class="form-control" required>
                                <option value="Weekly" <?php echo ($plan['plan_name'] == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
                                <option value="Monthly" <?php echo ($plan['plan_name'] == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                                <option value="Annually" <?php echo ($plan['plan_name'] == 'Annually') ? 'selected' : ''; ?>>Annually</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required><?php echo $plan['description']; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price (in USD):</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?php echo $plan['price']; ?>" required>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Update Plan</button>
                    </form>
                </div>
            </div>
        </div>
        <!--footer-->
        <footer class="footer">
            <div class="container">
                <p class="text-muted">© 2024 Malawi Traditional Dances Hub. All rights reserved.</p>
            </div>
        </footer>
        <!--//footer-->
    </div>

    <script src="js/classie.js"></script>
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;

        showLeftPush.onclick = function() {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
            disableOther('showLeftPush');
        };

        function disableOther(button) {
            if (button !== 'showLeftPush') {
                classie.toggle(showLeftPush, 'disabled');
            }
        }
    </script>
    <script src="js/bootstrap.js"></script>
</body>
</html>
