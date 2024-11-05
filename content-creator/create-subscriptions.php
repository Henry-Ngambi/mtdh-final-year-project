<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

// Check if the user is logged in
if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
    exit();
} else {

    // Handle Create Operation (Add a new subscription plan)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_plan'])) {
        $plan_name = mysqli_real_escape_string($con, $_POST['plan_name']);
        $price = mysqli_real_escape_string($con, $_POST['price']);

        // Check if the subscription plan already exists
        $check_query = "SELECT * FROM subscription_plan WHERE plan_name='$plan_name'";
        $result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($result) > 0) {
            // Plan already exists
            echo "<script>alert('Subscription plan with this name already exists.');</script>";
        } else {
            // Insert the new subscription plan
            $query = "INSERT INTO subscription_plan (plan_name, price) VALUES ('$plan_name', '$price')";
            if (mysqli_query($con, $query)) {
                echo "<script>alert('Subscription plan created successfully.');</script>";
            } else {
                echo "<script>alert('Error creating subscription plan: " . mysqli_error($con) . "');</script>";
            }
        }
    }

    // Handle Update Operation
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_plan'])) {
        $plan_id = mysqli_real_escape_string($con, $_POST['plan_id']);
        $plan_name = mysqli_real_escape_string($con, $_POST['plan_name']);
        $price = mysqli_real_escape_string($con, $_POST['price']);

        $query = "UPDATE subscription_plan SET plan_name='$plan_name', price='$price', updated_at=CURRENT_TIMESTAMP WHERE plan_id='$plan_id'";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Subscription plan updated successfully.');</script>";
        } else {
            echo "<script>alert('Error updating subscription plan: " . mysqli_error($con) . "');</script>";
        }
    }

    // Handle Delete Operation
    if (isset($_GET['del_plan'])) {
        $plan_id = intval($_GET['del_plan']);
        $query = "DELETE FROM subscription_plan WHERE plan_id='$plan_id'";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Subscription plan deleted successfully.');</script>";
        } else {
            echo "<script>alert('Error deleting subscription plan: " . mysqli_error($con) . "');</script>";
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
    <!-- font-awesome icons -->
    <link href="css/font-awesome.css" rel="stylesheet">
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <!--left-fixed -navigation-->
        <?php include_once('includes/sidebar.php'); ?>
        <!--left-fixed -navigation-->
        <!-- header-starts -->
        <?php include_once('includes/header.php'); ?>
        <!-- //header-ends -->
        <!-- main content start-->
        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Manage Subscription Plans</h3>

                    <!-- Form to create a new subscription plan -->
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Create a New Subscription Plan:</h4>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="plan_name">Plan Name:</label>
                                <select class="form-control" id="plan_name" name="plan_name" required>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="annually">Annually</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Price ($):</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="create_plan">Create Plan</button>
                        </form>
                    </div>

                    <!-- Display all subscription plans with Edit/Delete options -->
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
                                        <!-- Edit button -->
                                        <a href="edit-plan.php?plan_id=<?php echo $row['plan_id']; ?>" class="btn btn-info btn-sm">Edit</a>

                                        <!-- Delete button -->
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
        <!--footer-->
        <?php include_once('includes/footer.php'); ?>
        <!--//footer-->
    </div>

    <!-- Classie -->
    <script src="js/classie.js"></script>
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;

        showLeftPush.onclick = function () {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
        };
    </script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"> </script>
</body>
</html>
<?php } ?>
