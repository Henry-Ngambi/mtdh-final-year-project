<?php
session_start();
include '../includes/dbconnection.php';  

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize message variable
$msg = ""; 

// Handle form submission for password change
if (isset($_POST['submit'])) {
    $userid = $_SESSION['user_id'];
    $cpassword = md5($_POST['currentpassword']); // Hash current password
    $newpassword = md5($_POST['newpassword']); // Hash new password

    // Query to check current password from users table
    $query = mysqli_query($con, "SELECT user_id FROM users WHERE user_id='$userid' AND password='$cpassword'");

    // Check if the query execution is successful
    if (!$query) {
        die("Query failed: " . mysqli_error($con));
    }

    $row = mysqli_fetch_array($query);

    if ($row > 0) {
        // Update password if current password is correct
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

// Include header and sidebar at the beginning
include('header.php'); 
include('sidebar.php'); 
?>

<div class="container">
    <div class="form-body">
        <h2 class="text-center">Change Password</h2>
        <form method="post" name="changepassword" onsubmit="return checkpass();" action="">
            <p style="font-size:16px; color:red" align="center"><?php if (isset($msg)) { echo $msg; } ?></p>

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
            <button type="submit" name="submit" class="btn btn-primary">Change</button>
        </form>
    </div>
</div>

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

<?php include 'footer.php'; ?>
</body>
</html>
