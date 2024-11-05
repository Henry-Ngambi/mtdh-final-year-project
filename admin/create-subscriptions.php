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

// Handle form submission for creating a new subscription
if (isset($_POST['submit'])) {
    $plan_name = mysqli_real_escape_string($con, $_POST['plan_name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);

    // Check if the plan already exists
    $checkQuery = mysqli_query($con, "SELECT * FROM subscription_plan WHERE plan_name='$plan_name'");
    if (mysqli_num_rows($checkQuery) > 0) {
        $msg = "This subscription plan already exists!";
    } else {
        // Calculate duration based on plan name
        switch ($plan_name) {
            case 'Weekly':
                $duration = 7;
                break;
            case 'Monthly':
                $duration = 30;
                break;
            case 'Annually':
                $duration = 365;
                break;
            default:
                $duration = 0;
                break;
        }

        // Insert the new subscription plan into the database
        $query = mysqli_query($con, "INSERT INTO subscription_plan (plan_name, description, price, duration) VALUES ('$plan_name', '$description', '$price', '$duration')");

        if ($query) {
            $msg = "Subscription plan created successfully!";
        } else {
            $msg = "Error creating subscription plan: " . mysqli_error($con);
        }
    }
}

// Fetch all subscription plans
$plansQuery = mysqli_query($con, "SELECT * FROM subscription_plan");
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | Create Subscription Plan</title>
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
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .modal-backdrop.show {
            opacity: 0.5;
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
                    <h3 class="title1">Create Subscription Plan</h3>

                    <?php if (!empty($msg)) { echo "<p style='color: green;'>$msg</p>"; } ?>

                    <form method="post" action="">
                        <div class="form-group">
                            <label for="plan_name">Plan Name:</label>
                            <select name="plan_name" id="plan_name" class="form-control" required>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Annually">Annually</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price (in USD):</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Create Plan</button>
                    </form>

                    <!-- Display all subscription plans with Edit/Delete options -->
                    <div class="table-responsive bs-example widget-shadow" style="margin-top: 20px;">
                        <h4>All Subscription Plans:</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Plan Name</th>
                                    <th>Price ($)</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($plansQuery)) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo $row['plan_name']; ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><?php echo $row['updated_at']; ?></td>
                                    <td>
                                        <!-- Edit button -->
                                        <a href="edit-plan.php?plan_id=<?php echo $row['plan_id']; ?>" class="btn btn-info btn-sm">Edit</a>

                                        <!-- Delete button -->
                                        <a href="delete-plan.php?del_plan=<?php echo $row['plan_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plan?');">Delete</a>
                                    </td>
                                </tr>
                                <?php
                                    $cnt++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
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
