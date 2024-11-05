<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Check if the user is logged in
if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
    exit();
}

// Initialize message variables
$successMessage = '';
$errorMessage = '';

// Handle Create Operation (Add a new subscription plan)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_plan'])) {
    $plan_name = mysqli_real_escape_string($con, $_POST['plan_name']);
    $price = mysqli_real_escape_string($con, $_POST['price']);

    // Check if the subscription plan already exists
    $check_query = "SELECT * FROM subscription_plan WHERE plan_name='$plan_name'";
    $result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Plan already exists
        $errorMessage = 'Subscription plan with this name already exists.';
    } else {
        // Insert the new subscription plan
        $query = "INSERT INTO subscription_plan (plan_name, price) VALUES ('$plan_name', '$price')";
        if (mysqli_query($con, $query)) {
            $successMessage = 'Subscription plan created successfully.';
        } else {
            $errorMessage = 'Error creating subscription plan: ' . mysqli_error($con);
        }
    }
}

// Handle Delete Operation
if (isset($_GET['del_plan'])) {
    $plan_id = intval($_GET['del_plan']);
    $query = "DELETE FROM subscription_plan WHERE plan_id='$plan_id'";
    if (mysqli_query($con, $query)) {
        $successMessage = 'Subscription plan deleted successfully.';
    } else {
        $errorMessage = 'Error deleting subscription plan: ' . mysqli_error($con);
    }
}

// Fetch all subscription plans
$plans = mysqli_query($con, "SELECT * FROM subscription_plan");
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | Manage Subscription Plans</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <div id="page-wrapper" style="margin-top: 100px;">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Manage Subscription Plans</h3>

                    <?php if ($successMessage): ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>
                    <?php endif; ?>
                    <?php if ($errorMessage): ?>
                        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>

                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Create a New Subscription Plan:</h4>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="plan_name">Plan Name:</label>
                                <input type="text" class="form-control" id="plan_name" name="plan_name" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Price ($):</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="create_plan">Create Plan</button>
                        </form>
                    </div>

                    <div class="table-responsive bs-example widget-shadow">
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
                                while ($row = mysqli_fetch_array($plans)) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo $row['plan_name']; ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><?php echo $row['updated_at']; ?></td>
                                    <td>
                                        <a href="edit-plan.php?plan_id=<?php echo $row['plan_id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                        <a href="manage-plans.php?del_plan=<?php echo $row['plan_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plan?');">Delete</a>
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

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script src="js/bootstrap.js"></script>
</body>
</html>
