<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);

if (strlen($_SESSION['user_id']) == 0) {
    header('location:logout.php');
    exit;
}

if (isset($_POST['submit'])) {
    $userid = $_SESSION['user_id'];
    $cpassword = md5($_POST['currentpassword']); // Hash current password
    $newpassword = md5($_POST['newpassword']); // Hash new password

    // Debugging statement to check the session user_id
    // echo "Current User ID: " . $userid;

    // Query to check current password from users table
    $query = mysqli_query($con, "SELECT user_id FROM users WHERE user_id='$userid' AND password='$cpassword'");
    
    // Debugging statement to check SQL execution
    if (!$query) {
        die("Query failed: " . mysqli_error($con));
    }

    $row = mysqli_fetch_array($query);

    if ($row > 0) {
        $ret = mysqli_query($con, "UPDATE users SET password='$newpassword' WHERE user_id='$userid'");
        if ($ret) {
            $msg = "Your password has been successfully changed.";
        } else {
            $msg = "Error updating password: " . mysqli_error($con);
        }
    } else {
        $msg = "Your current password is incorrect.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>MTDH | Change Password</title>
    <script type="application/x-javascript">
        addEventListener("load", function () { setTimeout(hideURLbar, 0); }, false);
        function hideURLbar() { window.scrollTo(0, 1); }
    </script>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
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
    
    <script type="text/javascript">
        function checkpass() {
            if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
                alert('New Password and Confirm Password fields do not match');
                document.changepassword.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>
        <div id="page-wrapper">
            <div class="main-page">
                <div class="forms">
                    <h3 class="title1">Change Password</h3>
                    <div class="form-grids row widget-shadow">
                        <div class="form-title">
                            <h4>Reset Your Password :</h4>
                        </div>
                        <div class="form-body">
                            <form method="post" name="changepassword" onsubmit="return checkpass();" action="">
                                <p style="font-size:16px; color:red" align="center">
                                    <?php if (isset($msg)) { echo $msg; } ?>
                                </p>

                                <?php
                                $userid = $_SESSION['user_id'];
                                $ret = mysqli_query($con, "SELECT * FROM users WHERE user_id='$userid'");
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <div class="form-group">
                                        <label for="currentPassword">Current Password</label>
                                        <input type="password" name="currentpassword" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" name="newpassword" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm Password</label>
                                        <input type="password" name="confirmpassword" class="form-control" required>
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-default">Change</button>
                                </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
        <script src="js/classie.js"></script>
        <script>
            var menuLeft = document.getElementById('cbp-spmenu-s1'),
                showLeftPush = document.getElementById('showLeftPush'),
                body = document.body;

            showLeftPush.onclick = function () {
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
    </div>
</body>
</html>
