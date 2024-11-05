<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
} else {
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH || User List</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script>new WOW().init();</script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
</head> 
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php');?>
        <?php include_once('includes/header.php');?>

        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">User List</h3>
                    
                    <!-- Display status message -->
                    <?php if (isset($_SESSION['status'])) { ?>
                        <div class="alert alert-info">
                            <?php echo $_SESSION['status']; unset($_SESSION['status']); ?>
                        </div>
                    <?php } ?>

                    <div class="table-responsive bs-example widget-shadow" style="max-height: 500px; overflow-y: auto;">
                        <h4>Users:</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Sex</th>
                                    <th>Phone Number</th>
                                    <th>Nationality</th>
                                    <th>City</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

<?php
// Fetch all regular users (role_id = 1)
$ret = mysqli_query($con, "SELECT u.user_id, u.full_name, u.email, u.sex, u.phone_number, u.nationality, u.city, r.role_name, u.status
                           FROM users u
                           LEFT JOIN roles r ON u.role_id = r.role_id
                           WHERE u.role_id = 1");

$cnt = 1;
while ($row = mysqli_fetch_assoc($ret)) {
    $actionText = ($row['status'] == 'active') ? "Deactivate" : "Activate";
    $actionClass = ($row['status'] == 'active') ? "text-danger" : "text-success";
    $actionLink = ($row['status'] == 'active') ? "deactivate" : "activate";
?>

                                <tr>
                                    <th scope="row"><?php echo $cnt; ?></th>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['sex']; ?></td>
                                    <td><?php echo $row['phone_number']; ?></td>
                                    <td><?php echo $row['nationality']; ?></td>
                                    <td><?php echo $row['city']; ?></td>
                                    <td><?php echo $row['role_name'] ? $row['role_name'] : 'N/A'; ?></td>
                                    <td><?php echo ucfirst($row['status']); ?></td>
                                    <td>
                                        <a href="user_action.php?action=<?php echo $actionLink; ?>&user_id=<?php echo $row['user_id']; ?>" 
                                           class="<?php echo $actionClass; ?>"
                                           onclick="return confirm('Are you sure you want to <?php echo strtolower($actionText); ?> this user?');">
                                           <?php echo $actionText; ?>
                                        </a>
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
        
        <?php include_once('includes/footer.php');?>

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
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>
<?php } ?>
